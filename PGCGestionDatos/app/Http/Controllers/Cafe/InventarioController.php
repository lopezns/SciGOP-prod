<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventarioController extends Controller
{
    /**
     * Mostrar el dashboard de inventario
     */
    public function index(Request $request)
    {
        $query = Producto::query();
        
        // Filtros
        if ($request->has('search') && $request->search) {
            $query->buscar($request->search);
        }
        
        if ($request->has('stock_filter')) {
            switch ($request->stock_filter) {
                case 'bajo':
                    $query->where('stock', '<=', 10);
                    break;
                case 'sin_stock':
                    $query->where('stock', '=', 0);
                    break;
                case 'con_stock':
                    $query->where('stock', '>', 0);
                    break;
            }
        }
        
        $productos = $query->orderBy('nombre')->paginate(15);
        
        // Estadísticas
        $totalProductos = Producto::count();
        $sinStock = Producto::where('stock', '=', 0)->count();
        $stockBajo = Producto::where('stock', '<=', 10)->where('stock', '>', 0)->count();
        $valorInventario = Producto::selectRaw('SUM(stock * precio_compra) as total')->first()->total ?? 0;
        
        return view('cafe.inventario.index', compact(
            'productos',
            'totalProductos', 
            'sinStock', 
            'stockBajo',
            'valorInventario'
        ));
    }
    
    /**
     * Mostrar historial de movimientos
     */
    public function movimientos(Request $request)
    {
        $query = MovimientoInventario::with(['producto', 'usuario']);
        
        // Filtros
        if ($request->has('producto_id') && $request->producto_id) {
            $query->where('producto_id', $request->producto_id);
        }
        
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->has('fecha_inicio') && $request->fecha_inicio) {
            $query->whereDate('created_at', '>=', $request->fecha_inicio);
        }
        
        if ($request->has('fecha_fin') && $request->fecha_fin) {
            $query->whereDate('created_at', '<=', $request->fecha_fin);
        }
        
        $movimientos = $query->orderBy('created_at', 'desc')->paginate(15);
        $productos = Producto::orderBy('nombre')->get();
        
        return view('cafe.inventario.movimientos', compact('movimientos', 'productos'));
    }
    
    /**
     * Mostrar formulario de ajuste de inventario
     */
    public function ajustar()
    {
        $productos = Producto::orderBy('nombre')->get();
        return view('cafe.inventario.ajustar', compact('productos'));
    }
    
    /**
     * Procesar ajuste de inventario
     */
    public function procesarAjuste(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'tipo' => 'required|in:entrada,salida,ajuste',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'required|string|max:255',
            'observaciones' => 'nullable|string'
        ]);
        
        try {
            DB::beginTransaction();
            
            $producto = Producto::find($request->producto_id);
            $stockAnterior = $producto->stock;
            
            // Calcular nuevo stock
            switch ($request->tipo) {
                case 'entrada':
                    $stockNuevo = $stockAnterior + $request->cantidad;
                    break;
                case 'salida':
                    if ($stockAnterior < $request->cantidad) {
                        throw new \Exception('No hay suficiente stock para realizar la salida.');
                    }
                    $stockNuevo = $stockAnterior - $request->cantidad;
                    break;
                case 'ajuste':
                    $stockNuevo = $request->cantidad;
                    break;
            }
            
            // Actualizar stock del producto
            $producto->stock = $stockNuevo;
            $producto->save();
            
            // Registrar movimiento
            MovimientoInventario::create([
                'producto_id' => $request->producto_id,
                'tipo' => $request->tipo,
                'cantidad' => $request->tipo === 'ajuste' ? abs($stockNuevo - $stockAnterior) : $request->cantidad,
                'stock_anterior' => $stockAnterior,
                'stock_nuevo' => $stockNuevo,
                'motivo' => $request->motivo,
                'observaciones' => $request->observaciones,
                'usuario_id' => session('user.id') ?? null
            ]);
            
            DB::commit();
            
            return redirect()->route('cafe.inventario.index')
                           ->with('success', 'Ajuste de inventario realizado exitosamente.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error al procesar el ajuste: ' . $e->getMessage())
                           ->withInput();
        }
    }
    
    /**
     * Mostrar reporte de inventario
     */
    public function reporte()
    {
        $productos = Producto::with('movimientosInventario')
                           ->orderBy('nombre')
                           ->get();
        
        $resumen = [
            'total_productos' => $productos->count(),
            'valor_total' => $productos->sum(function($p) { return $p->stock * $p->precio_compra; }),
            'productos_sin_stock' => $productos->where('stock', 0)->count(),
            'productos_stock_bajo' => $productos->whereBetween('stock', [1, 10])->count()
        ];
        
        return view('cafe.inventario.reporte', compact('productos', 'resumen'));
    }
}
