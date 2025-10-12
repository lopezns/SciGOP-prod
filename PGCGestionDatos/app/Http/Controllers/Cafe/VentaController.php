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

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FacturaVenta::with(['cliente', 'detalles.producto']);
        
        // Filtros
        if ($request->has('fecha_inicio') && $request->fecha_inicio) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->has('fecha_fin') && $request->fecha_fin) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->has('search') && $request->search) {
            $query->whereHas('cliente', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('numero_documento', 'like', '%' . $request->search . '%');
            })->orWhere('numero_factura', 'like', '%' . $request->search . '%');
        }
        
        $ventas = $query->orderBy('fecha', 'desc')->paginate(15);
        
        // Estadísticas
        $totalVentas = FacturaVenta::where('estado', 'pagada')->sum('total');
        $ventasHoy = FacturaVenta::whereDate('fecha', today())
                               ->where('estado', 'pagada')
                               ->sum('total');
        $cantidadVentasHoy = FacturaVenta::whereDate('fecha', today())->count();
        
        return view('cafe.ventas.index', compact(
            'ventas', 
            'totalVentas', 
            'ventasHoy', 
            'cantidadVentasHoy'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::conStock()->orderBy('nombre')->get();
        
        return view('cafe.ventas.create', compact('clientes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'fecha' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Generar número de factura
            $ultimaFactura = FacturaVenta::orderBy('id', 'desc')->first();
            $numeroFactura = 'F' . str_pad(($ultimaFactura ? $ultimaFactura->id + 1 : 1), 6, '0', STR_PAD_LEFT);
            
            // Calcular total
            $total = 0;
            foreach ($request->productos as $item) {
                $total += $item['cantidad'] * $item['precio_unitario'];
            }
            
            // Crear factura
            $factura = FacturaVenta::create([
                'cliente_id' => $request->cliente_id,
                'numero_factura' => $numeroFactura,
                'fecha' => $request->fecha,
                'total' => $total,
                'estado' => 'pagada' // Por defecto las ventas del café son pagadas
            ]);
            
            // Crear detalles y actualizar inventario
            foreach ($request->productos as $item) {
                $producto = Producto::find($item['producto_id']);
                
                // Verificar stock disponible
                if ($producto->stock < $item['cantidad']) {
                    throw new \Exception("Stock insuficiente para {$producto->nombre}. Stock disponible: {$producto->stock}");
                }
                
                // Crear detalle
                DetalleFacturaVenta::create([
                    'factura_id' => $factura->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $item['cantidad'] * $item['precio_unitario']
                ]);
                
                // Actualizar stock del producto
                $stockAnterior = $producto->stock;
                $producto->stock -= $item['cantidad'];
                $producto->save();
                
                // Registrar movimiento de inventario
                MovimientoInventario::create([
                    'producto_id' => $item['producto_id'],
                    'tipo' => 'salida',
                    'cantidad' => $item['cantidad'],
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $producto->stock,
                    'motivo' => 'Venta',
                    'observaciones' => "Factura: {$numeroFactura}",
                    'usuario_id' => session('user.id') ?? null
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('cafe.ventas.index')
                           ->with('success', 'Venta registrada exitosamente.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error al registrar la venta: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('cafe.ventas.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('cafe.ventas.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return redirect()->route('cafe.ventas.index')
                        ->with('success', 'Venta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return redirect()->route('cafe.ventas.index')
                        ->with('success', 'Venta eliminada exitosamente.');
    }
    
    /**
     * Display a listing of facturas/invoices.
     */
    public function facturas(Request $request)
    {
        $query = FacturaVenta::with('cliente');
        
        // Búsqueda
        if ($request->has('search') && $request->search) {
            $query->whereHas('cliente', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->search . '%')
                  ->orWhere('numero_documento', 'like', '%' . $request->search . '%');
            })->orWhere('numero_factura', 'like', '%' . $request->search . '%');
        }
        
        // Filtro por estado
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        $facturas = $query->orderBy('fecha', 'desc')->paginate(15);
        
        // Estadísticas
        $totalFacturas = FacturaVenta::count();
        $ventasMes = FacturaVenta::whereMonth('fecha', now()->month)
                                ->whereYear('fecha', now()->year)
                                ->where('estado', 'pagada')
                                ->sum('total');
        $pendientes = FacturaVenta::where('estado', 'pendiente')->count();
        $canceladas = FacturaVenta::where('estado', 'anulada')->count();
        
        return view('cafe.facturas.index', compact(
            'facturas',
            'totalFacturas',
            'ventasMes',
            'pendientes',
            'canceladas'
        ));
    }
    
    /**
     * Display the specified factura.
     */
    public function showFactura($id)
    {
        $factura = FacturaVenta::with('cliente')->findOrFail($id);
        return view('cafe.facturas.show', compact('factura'));
    }
}
