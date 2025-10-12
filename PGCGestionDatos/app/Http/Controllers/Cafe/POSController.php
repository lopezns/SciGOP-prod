<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\FacturaVenta;
use App\Models\DetalleFacturaVenta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    /**
     * Mostrar el punto de venta
     */
    public function index()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::conStock()->orderBy('nombre')->get();
        
        return view('cafe.pos.index', compact('clientes', 'productos'));
    }
    
    /**
     * Buscar productos para POS
     */
    public function searchProducts(Request $request)
    {
        $term = $request->get('term', '');
        
        $productos = Producto::conStock()
                           ->where(function($query) use ($term) {
                               $query->where('nombre', 'like', "%{$term}%")
                                     ->orWhere('codigo', 'like', "%{$term}%");
                           })
                           ->limit(10)
                           ->get(['id', 'nombre', 'codigo', 'precio_venta', 'stock']);
        
        return response()->json($productos);
    }
    
    /**
     * Procesar venta desde POS
     */
    public function procesarVenta(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'cliente_nombre' => 'nullable|string|max:255',
            'cliente_documento' => 'nullable|string|max:20',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Crear cliente si es necesario
            $cliente_id = $request->cliente_id;
            if (!$cliente_id && $request->cliente_nombre) {
                $cliente = Cliente::create([
                    'nombre' => $request->cliente_nombre,
                    'tipo_documento' => 'cedula',
                    'numero_documento' => $request->cliente_documento ?? 'N/A-' . time(),
                    'direccion' => null,
                    'telefono' => null,
                    'email' => null
                ]);
                $cliente_id = $cliente->id;
            }
            
            // Si no hay cliente, usar cliente genérico
            if (!$cliente_id) {
                $clienteGenerico = Cliente::firstOrCreate(
                    ['numero_documento' => 'GENERICO-001'],
                    [
                        'nombre' => 'Cliente General',
                        'tipo_documento' => 'cedula',
                        'direccion' => null,
                        'telefono' => null,
                        'email' => null
                    ]
                );
                $cliente_id = $clienteGenerico->id;
            }
            
            // Generar número de factura
            $ultimaFactura = FacturaVenta::orderBy('id', 'desc')->first();
            $numeroFactura = 'POS' . str_pad(($ultimaFactura ? $ultimaFactura->id + 1 : 1), 6, '0', STR_PAD_LEFT);
            
            // Crear factura
            $factura = FacturaVenta::create([
                'cliente_id' => $cliente_id,
                'numero_factura' => $numeroFactura,
                'fecha' => now()->toDateString(),
                'total' => $request->total,
                'estado' => 'pagada'
            ]);
            
            // Crear detalles y actualizar inventario
            foreach ($request->items as $item) {
                $producto = Producto::find($item['producto_id']);
                
                // Verificar stock
                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}. Stock disponible: {$producto->stock}");
                }
                
                // Crear detalle
                DetalleFacturaVenta::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'subtotal' => $item['cantidad'] * $item['precio']
                ]);
                
                // Actualizar stock
                $stockAnterior = $producto->stock;
                $producto->stock -= $item['cantidad'];
                $producto->save();
                
                // Registrar movimiento
                MovimientoInventario::create([
                    'producto_id' => $item['producto_id'],
                    'tipo' => 'salida',
                    'cantidad' => $item['cantidad'],
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $producto->stock,
                    'motivo' => 'Venta POS',
                    'observaciones' => "Factura: {$numeroFactura} - Método: {$request->metodo_pago}",
                    'usuario_id' => session('user.id') ?? null
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'factura_id' => $factura->id,
                'numero_factura' => $numeroFactura,
                'total' => $request->total
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage()
            ], 422);
        }
    }
    
    /**
     * Mostrar factura para impresión
     */
    public function mostrarFactura($id)
    {
        $factura = FacturaVenta::with(['cliente', 'detalles.producto'])->findOrFail($id);
        return view('cafe.pos.factura', compact('factura'));
    }
}
