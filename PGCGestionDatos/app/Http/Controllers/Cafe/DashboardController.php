<?php

namespace App\Http\Controllers\Cafe;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\FacturaVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Métricas del mes actual
        $mesActual = now();
        
        // Ventas del mes
        $ventasMes = FacturaVenta::whereYear('fecha', $mesActual->year)
                                ->whereMonth('fecha', $mesActual->month)
                                ->sum('total');
        
        // Ventas del día
        $ventasHoy = FacturaVenta::whereDate('fecha', $mesActual->toDateString())
                               ->sum('total');
        
        // Número de facturas del mes
        $facturasMes = FacturaVenta::whereYear('fecha', $mesActual->year)
                                  ->whereMonth('fecha', $mesActual->month)
                                  ->count();
        
        // Productos con stock bajo
        $productosStockBajo = Producto::where('stock', '<=', 10)
                                    ->where('stock', '>', 0)
                                    ->count();
        
        // Productos sin stock
        $productosSinStock = Producto::where('stock', '=', 0)->count();
        
        // Total de productos
        $totalProductos = Producto::count();
        
        // Total de clientes
        $totalClientes = Cliente::count();
        
        // Productos más vendidos (simulado por ahora)
        $productosPopulares = Producto::orderBy('stock', 'desc')
                                    ->limit(5)
                                    ->get(['nombre', 'stock', 'precio_venta']);
        
        // Ventas de los últimos 7 días
        $ventasUltimosDias = [];
        for ($i = 6; $i >= 0; $i--) {
            $fecha = now()->copy()->subDays($i);
            $total = FacturaVenta::whereDate('fecha', $fecha->toDateString())
                               ->where('estado', 'pagada')
                               ->sum('total');
            $ventasUltimosDias[] = [
                'fecha' => $fecha->format('d/m'),
                'total' => $total ?: 0
            ];
        }
        
        return view('cafe.dashboard', compact(
            'ventasMes',
            'ventasHoy', 
            'facturasMes',
            'productosStockBajo',
            'productosSinStock',
            'totalProductos',
            'totalClientes',
            'productosPopulares',
            'ventasUltimosDias'
        ));
    }
    
    public function reportes()
    {
        // Reportes básicos
        $reportes = [
            'ventas_mensuales' => $this->getVentasMensuales(),
            'productos_mas_vendidos' => $this->getProductosMasVendidos(),
            'clientes_frecuentes' => $this->getClientesFrecuentes(),
        ];
        
        return view('cafe.reportes.index', compact('reportes'));
    }
    
    private function getVentasMensuales()
    {
        $ventas = [];
        for ($i = 11; $i >= 0; $i--) {
            $fecha = now()->subMonths($i);
            $ventas[] = [
                'mes' => $fecha->format('M Y'),
                'total' => FacturaVenta::whereYear('fecha', $fecha->year)
                                     ->whereMonth('fecha', $fecha->month)
                                     ->sum('total') ?? 0
            ];
        }
        return $ventas;
    }
    
    private function getProductosMasVendidos()
    {
        // Simulación básica - en un sistema real tendríamos tabla de detalles de factura
        return Producto::orderBy('stock', 'desc')
                      ->limit(10)
                      ->get(['nombre', 'precio_venta', 'stock']);
    }
    
    private function getClientesFrecuentes()
    {
        return Cliente::withCount('facturasVenta')
                     ->orderBy('facturas_venta_count', 'desc')
                     ->limit(10)
                     ->get();
    }
}
