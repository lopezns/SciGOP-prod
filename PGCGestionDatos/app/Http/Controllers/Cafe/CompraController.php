<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Compra::with(['proveedor', 'usuario']);
        
        // Filtros
        if ($request->has('proveedor_id') && $request->proveedor_id) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
        
        if ($request->has('estado') && $request->estado) {
            $query->where('estado', $request->estado);
        }
        
        if ($request->has('fecha_inicio') && $request->fecha_inicio) {
            $query->whereDate('fecha', '>=', $request->fecha_inicio);
        }
        
        if ($request->has('fecha_fin') && $request->fecha_fin) {
            $query->whereDate('fecha', '<=', $request->fecha_fin);
        }
        
        $compras = $query->orderBy('fecha', 'desc')->paginate(15);
        $proveedores = Proveedor::activos()->orderBy('nombre')->get();
        
        // Estadísticas
        $totalCompras = Compra::where('estado', 'recibida')->sum('total');
        $comprasMes = Compra::whereMonth('fecha', now()->month)
                           ->whereYear('fecha', now()->year)
                           ->where('estado', 'recibida')
                           ->sum('total');
        $pendientes = Compra::where('estado', 'pendiente')->count();
        
        return view('cafe.compras.index', compact(
            'compras',
            'proveedores',
            'totalCompras',
            'comprasMes',
            'pendientes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedor::activos()->orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        
        return view('cafe.compras.create', compact('proveedores', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'numero_factura' => 'required|string|max:50',
            'fecha' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            // Calcular total
            $total = 0;
            foreach ($request->productos as $item) {
                $total += $item['cantidad'] * $item['precio_unitario'];
            }
            
            // Crear compra
            $compra = Compra::create([
                'proveedor_id' => $request->proveedor_id,
                'numero_factura' => $request->numero_factura,
                'fecha' => $request->fecha,
                'total' => $total,
                'estado' => 'pendiente',
                'observaciones' => $request->observaciones,
                'usuario_id' => session('user.id') ?? null
            ]);
            
            // Crear detalles
            foreach ($request->productos as $item) {
                DetalleCompra::create([
                    'compra_id' => $compra->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_unitario'],
                    'subtotal' => $item['cantidad'] * $item['precio_unitario']
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('cafe.compras.index')
                           ->with('success', 'Compra registrada exitosamente.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error al registrar la compra: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $compra->load(['proveedor', 'detalles.producto', 'usuario']);
        return view('cafe.compras.show', compact('compra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra)
    {
        if ($compra->estado === 'recibida') {
            return redirect()->back()
                           ->with('error', 'No se puede editar una compra que ya fue recibida.');
        }
        
        $compra->load('detalles.producto');
        $proveedores = Proveedor::activos()->orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();
        
        return view('cafe.compras.edit', compact('compra', 'proveedores', 'productos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        if ($compra->estado === 'recibida') {
            return redirect()->back()
                           ->with('error', 'No se puede editar una compra que ya fue recibida.');
        }
        
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'numero_factura' => 'required|string|max:50',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string'
        ]);
        
        try {
            $compra->update([
                'proveedor_id' => $request->proveedor_id,
                'numero_factura' => $request->numero_factura,
                'fecha' => $request->fecha,
                'observaciones' => $request->observaciones
            ]);
            
            return redirect()->route('cafe.compras.index')
                           ->with('success', 'Compra actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al actualizar la compra: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        if ($compra->estado === 'recibida') {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar una compra que ya fue recibida.'
                ]);
            }
            return redirect()->back()
                           ->with('error', 'No se puede eliminar una compra que ya fue recibida.');
        }
        
        try {
            $compra->delete();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Compra eliminada exitosamente.'
                ]);
            }
            
            return redirect()->route('cafe.compras.index')
                           ->with('success', 'Compra eliminada exitosamente.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar la compra: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()
                           ->with('error', 'Error al eliminar la compra: ' . $e->getMessage());
        }
    }
    
    /**
     * Recibir compra y actualizar inventario
     */
    public function recibir(Compra $compra)
    {
        if ($compra->estado === 'recibida') {
            return redirect()->back()
                           ->with('error', 'Esta compra ya fue recibida.');
        }
        
        try {
            DB::beginTransaction();
            
            // Actualizar stock de productos y registrar movimientos
            foreach ($compra->detalles as $detalle) {
                $producto = $detalle->producto;
                $stockAnterior = $producto->stock;
                
                // Actualizar stock
                $producto->stock += $detalle->cantidad;
                $producto->save();
                
                // Registrar movimiento de inventario
                MovimientoInventario::create([
                    'producto_id' => $detalle->producto_id,
                    'tipo' => 'entrada',
                    'cantidad' => $detalle->cantidad,
                    'stock_anterior' => $stockAnterior,
                    'stock_nuevo' => $producto->stock,
                    'motivo' => 'Compra',
                    'observaciones' => "Compra: {$compra->numero_factura}",
                    'usuario_id' => session('user.id') ?? null
                ]);
            }
            
            // Marcar compra como recibida
            $compra->update(['estado' => 'recibida']);
            
            DB::commit();
            
            return redirect()->back()
                           ->with('success', 'Compra recibida exitosamente. Inventario actualizado.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error al recibir la compra: ' . $e->getMessage());
        }
    }
}
