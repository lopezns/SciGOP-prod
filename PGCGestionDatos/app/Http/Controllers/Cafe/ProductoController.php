<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::query();
        
        // Búsqueda
        if ($request->has('search') && $request->search) {
            $query->buscar($request->search);
        }
        
        // Filtro por stock
        if ($request->has('stock_filter')) {
            if ($request->stock_filter === 'bajo') {
                $query->where('stock', '<=', 10);
            } elseif ($request->stock_filter === 'sin_stock') {
                $query->where('stock', '=', 0);
            } elseif ($request->stock_filter === 'con_stock') {
                $query->where('stock', '>', 0);
            }
        }
        
        $productos = $query->orderBy('nombre')->paginate(15);
        
        return view('cafe.productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cafe.productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:productos,codigo',
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0'
        ]);
        
        try {
            Producto::create([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'precio_compra' => $request->precio_compra ?? 0,
                'precio_venta' => $request->precio_venta,
                'stock' => $request->stock ?? 0
            ]);
            
            return redirect()->route('cafe.productos.index')
                           ->with('success', 'Producto creado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al crear el producto: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return view('cafe.productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('cafe.productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:50|unique:productos,codigo,' . $producto->id,
            'precio_compra' => 'nullable|numeric|min:0',
            'precio_venta' => 'required|numeric|min:0',
            'stock' => 'nullable|integer|min:0'
        ]);
        
        try {
            $producto->update([
                'nombre' => $request->nombre,
                'codigo' => $request->codigo,
                'precio_compra' => $request->precio_compra ?? 0,
                'precio_venta' => $request->precio_venta,
                'stock' => $request->stock ?? 0
            ]);
            
            return redirect()->route('cafe.productos.index')
                           ->with('success', 'Producto actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al actualizar el producto: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        try {
            // Verificar si el producto tiene movimientos de inventario
            $tieneMovimientos = DB::table('movimientos_inventario')
                                 ->where('producto_id', $producto->id)
                                 ->exists();
            
            if ($tieneMovimientos) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar el producto porque tiene movimientos de inventario asociados.');
            }
            
            $producto->delete();
            
            return redirect()->route('cafe.productos.index')
                           ->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al eliminar el producto: ' . $e->getMessage());
        }
    }
    
    /**
     * Búsqueda AJAX para el sistema de ventas
     */
    public function search(Request $request)
    {
        $term = $request->get('term');
        
        $productos = Producto::conStock()
                           ->buscar($term)
                           ->limit(10)
                           ->get(['id', 'nombre', 'codigo', 'precio_venta', 'stock']);
        
        return response()->json($productos);
    }
}
