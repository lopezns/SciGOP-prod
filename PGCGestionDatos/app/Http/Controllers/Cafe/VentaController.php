<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\FacturaVenta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cafe.ventas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cafe.ventas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('cafe.ventas.index')
                        ->with('success', 'Venta registrada exitosamente.');
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
