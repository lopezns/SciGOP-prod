<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Proveedor::activos();
        
        // Búsqueda
        if ($request->has('search') && $request->search) {
            $query->buscar($request->search);
        }
        
        $proveedores = $query->orderBy('nombre')->paginate(15);
        
        // Estadísticas
        $stats = [
            'total_proveedores' => Proveedor::count(),
            'proveedores_activos' => Proveedor::where('activo', true)->count(),
            'compras_mes_actual' => \App\Models\Compra::whereMonth('fecha', now()->month)
                                                    ->whereYear('fecha', now()->year)
                                                    ->count(),
            'total_gastado_mes' => \App\Models\Compra::whereMonth('fecha', now()->month)
                                                    ->whereYear('fecha', now()->year)
                                                    ->sum('total')
        ];
        
        return view('cafe.proveedores.index', compact('proveedores', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cafe.proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_documento' => 'required|in:nit,cedula,rut',
            'numero_documento' => 'required|string|max:20|unique:proveedores,numero_documento',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contacto' => 'nullable|string|max:255'
        ]);
        
        try {
            Proveedor::create([
                'nombre' => $request->nombre,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'contacto' => $request->contacto,
                'activo' => true
            ]);
            
            return redirect()->route('cafe.proveedores.index')
                           ->with('success', 'Proveedor registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al registrar el proveedor: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        $proveedor->load('compras');
        return view('cafe.proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        return view('cafe.proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_documento' => 'required|in:nit,cedula,rut',
            'numero_documento' => 'required|string|max:20|unique:proveedores,numero_documento,' . $proveedor->id,
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'contacto' => 'nullable|string|max:255',
            'activo' => 'boolean'
        ]);
        
        try {
            $proveedor->update([
                'nombre' => $request->nombre,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email,
                'contacto' => $request->contacto,
                'activo' => $request->boolean('activo', true)
            ]);
            
            return redirect()->route('cafe.proveedores.index')
                           ->with('success', 'Proveedor actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al actualizar el proveedor: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        try {
            // Verificar si el proveedor tiene compras asociadas
            if ($proveedor->compras()->count() > 0) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se puede eliminar el proveedor porque tiene compras asociadas.'
                    ]);
                }
                return redirect()->back()
                               ->with('error', 'No se puede eliminar el proveedor porque tiene compras asociadas.');
            }
            
            $proveedor->delete();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Proveedor eliminado exitosamente.'
                ]);
            }
            
            return redirect()->route('cafe.proveedores.index')
                           ->with('success', 'Proveedor eliminado exitosamente.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al eliminar el proveedor: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()
                           ->with('error', 'Error al eliminar el proveedor: ' . $e->getMessage());
        }
    }
}
