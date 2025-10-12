<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Cliente::query();
        
        // Búsqueda
        if ($request->has('search') && $request->search) {
            $query->buscar($request->search);
        }
        
        $clientes = $query->orderBy('nombre')->paginate(15);
        
        return view('cafe.clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cafe.clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_documento' => 'required|in:cedula,nit,pasaporte',
            'numero_documento' => 'required|string|max:20|unique:clientes,numero_documento',
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255'
        ]);
        
        try {
            Cliente::create([
                'nombre' => $request->nombre,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email
            ]);
            
            return redirect()->route('cafe.clientes.index')
                           ->with('success', 'Cliente registrado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al registrar el cliente: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load('facturasVenta');
        return view('cafe.clientes.show', compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('cafe.clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_documento' => 'required|in:cedula,nit,pasaporte',
            'numero_documento' => 'required|string|max:20|unique:clientes,numero_documento,' . $cliente->id,
            'direccion' => 'nullable|string',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255'
        ]);
        
        try {
            $cliente->update([
                'nombre' => $request->nombre,
                'tipo_documento' => $request->tipo_documento,
                'numero_documento' => $request->numero_documento,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'email' => $request->email
            ]);
            
            return redirect()->route('cafe.clientes.index')
                           ->with('success', 'Cliente actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al actualizar el cliente: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        try {
            // Verificar si el cliente tiene facturas asociadas
            if ($cliente->facturasVenta()->count() > 0) {
                return redirect()->back()
                               ->with('error', 'No se puede eliminar el cliente porque tiene facturas asociadas.');
            }
            
            $cliente->delete();
            
            return redirect()->route('cafe.clientes.index')
                           ->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al eliminar el cliente: ' . $e->getMessage());
        }
    }
}
