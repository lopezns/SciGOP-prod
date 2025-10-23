<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\FacturaVenta;
use App\Models\Producto;
use App\Models\MovimientoInventario;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Mostrar página principal de reportes
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Generar reporte de nómina en PDF
     */
    public function payrollReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'employee_id' => 'nullable|exists:employees,id',
            'department' => 'nullable|string',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        // Query base para nóminas
        $query = Payroll::with(['employee'])
            ->whereBetween('period_start', [$startDate, $endDate]);
            
        // Filtros opcionales
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->department) {
            $query->whereHas('employee', function($q) use ($request) {
                $q->where('department', $request->department);
            });
        }
        
        $payrolls = $query->orderBy('period_start', 'desc')->get();
        
        // Calcular resúmenes
        $summary = [
            'total_employees' => $payrolls->groupBy('employee_id')->count(),
            'total_income' => $payrolls->sum('total_income'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_net_salary' => $payrolls->sum('net_salary'),
            'total_employer_contributions' => $payrolls->sum('total_employer_contributions'),
            'total_cost' => $payrolls->sum('total_cost'),
        ];
        
        // Resumen por departamento
        $departmentSummary = $payrolls->groupBy('employee.department')
            ->map(function ($payrollsByDept) {
                return [
                    'employees_count' => $payrollsByDept->groupBy('employee_id')->count(),
                    'total_income' => $payrollsByDept->sum('total_income'),
                    'total_deductions' => $payrollsByDept->sum('total_deductions'),
                    'total_net_salary' => $payrollsByDept->sum('net_salary'),
                    'total_cost' => $payrollsByDept->sum('total_cost'),
                ];
            });

        $data = [
            'title' => 'Reporte de Nómina',
            'period' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'generated_at' => Carbon::now()->format('d/m/Y H:i:s'),
            'payrolls' => $payrolls,
            'summary' => $summary,
            'department_summary' => $departmentSummary,
            'filters' => [
                'employee_id' => $request->employee_id,
                'department' => $request->department,
            ]
        ];

        $pdf = Pdf::loadView('reports.payroll-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download('reporte-nomina-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.pdf');
    }

    /**
     * Generar reporte de ventas en PDF
     */
    public function salesReport(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        
        $sales = FacturaVenta::with(['cliente', 'detalles.producto'])
            ->whereBetween('fecha', [$startDate, $endDate])
            ->orderBy('fecha', 'desc')
            ->get();
        
        $summary = [
            'total_sales' => $sales->count(),
            'total_amount' => $sales->sum('total'),
            'paid_sales' => $sales->where('estado', 'pagada')->count(),
            'pending_sales' => $sales->where('estado', 'pendiente')->count(),
            'average_sale' => $sales->count() > 0 ? $sales->sum('total') / $sales->count() : 0,
        ];
        
        // Ventas por día
        $dailySales = $sales->groupBy(function($sale) {
                return $sale->fecha->format('Y-m-d');
            })
            ->map(function ($salesByDay) {
                return [
                    'count' => $salesByDay->count(),
                    'total' => $salesByDay->sum('total'),
                ];
            });

        // Productos más vendidos
        $topProducts = DB::table('detalle_factura_venta')
            ->join('facturas_venta', 'detalle_factura_venta.factura_id', '=', 'facturas_venta.id')
            ->join('productos', 'detalle_factura_venta.producto_id', '=', 'productos.id')
            ->whereBetween('facturas_venta.fecha', [$startDate, $endDate])
            ->select(
                'productos.nombre',
                DB::raw('SUM(detalle_factura_venta.cantidad) as total_quantity'),
                DB::raw('SUM(detalle_factura_venta.subtotal) as total_revenue')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        $data = [
            'title' => 'Reporte de Ventas',
            'period' => $startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y'),
            'generated_at' => Carbon::now()->format('d/m/Y H:i:s'),
            'sales' => $sales,
            'summary' => $summary,
            'daily_sales' => $dailySales,
            'top_products' => $topProducts,
        ];

        $pdf = Pdf::loadView('reports.sales-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download('reporte-ventas-' . $startDate->format('Y-m-d') . '-' . $endDate->format('Y-m-d') . '.pdf');
    }

    /**
     * Generar reporte de inventario en PDF
     */
    public function inventoryReport(Request $request)
    {
        $products = Producto::with('movimientos')
            ->orderBy('nombre')
            ->get();
        
        // Calcular stock y valores
        $inventoryData = $products->map(function ($product) {
            $totalEntradas = $product->movimientos
                ->where('tipo_movimiento', 'entrada')
                ->sum('cantidad');
                
            $totalSalidas = $product->movimientos
                ->where('tipo_movimiento', 'salida')
                ->sum('cantidad');
            
            $stockActual = $totalEntradas - $totalSalidas;
            $valorInventario = $stockActual * $product->precio_venta;
            
            return [
                'id' => $product->id,
                'codigo' => $product->codigo ?? 'N/A',
                'nombre' => $product->nombre,
                'categoria' => $product->categoria ?? 'Sin categoría',
                'stock_actual' => $stockActual,
                'precio_costo' => $product->precio_compra ?? 0,
                'precio_venta' => $product->precio_venta,
                'valor_costo' => $stockActual * ($product->precio_compra ?? 0),
                'valor_venta' => $valorInventario,
                'estado' => $stockActual <= 0 ? 'Sin Stock' : ($stockActual <= 5 ? 'Stock Bajo' : 'Stock Normal'),
            ];
        });
        
        $summary = [
            'total_products' => $inventoryData->count(),
            'products_in_stock' => $inventoryData->where('stock_actual', '>', 0)->count(),
            'products_out_of_stock' => $inventoryData->where('stock_actual', '<=', 0)->count(),
            'products_low_stock' => $inventoryData->where('stock_actual', '>', 0)->where('stock_actual', '<=', 5)->count(),
            'total_inventory_cost' => $inventoryData->sum('valor_costo'),
            'total_inventory_value' => $inventoryData->sum('valor_venta'),
        ];

        $data = [
            'title' => 'Reporte de Inventario',
            'generated_at' => Carbon::now()->format('d/m/Y H:i:s'),
            'inventory' => $inventoryData,
            'summary' => $summary,
        ];

        $pdf = Pdf::loadView('reports.inventory-pdf', $data)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download('reporte-inventario-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Reporte DIAN - Certificado de ingresos y retenciones
     */
    public function dianIncomeReport(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'year' => 'required|integer|min:2020|max:' . date('Y'),
        ]);

        $employee = Employee::findOrFail($request->employee_id);
        $year = $request->year;
        
        // Obtener todas las nóminas del empleado en el año
        $payrolls = Payroll::where('employee_id', $employee->id)
            ->whereYear('period_start', $year)
            ->orderBy('period_start')
            ->get();
        
        // Calcular totales anuales
        $annualTotals = [
            'total_income' => $payrolls->sum('total_income'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_health' => $payrolls->sum('health_contribution'),
            'total_pension' => $payrolls->sum('pension_contribution'),
            'total_solidarity_fund' => $payrolls->sum('solidarity_fund'),
            'total_tax_withholding' => $payrolls->sum('income_tax_withholding'),
            'net_income' => $payrolls->sum('net_salary'),
        ];
        
        // Información empresa (esto debería venir de configuración)
        $company = [
            'name' => 'PGC Gestión de Datos',
            'nit' => '900123456-1',
            'address' => 'Calle 123 # 45-67',
            'city' => 'Bogotá D.C.',
            'phone' => '(601) 123-4567',
        ];

        $data = [
            'title' => 'Certificado de Ingresos y Retenciones',
            'year' => $year,
            'generated_at' => Carbon::now()->format('d/m/Y H:i:s'),
            'employee' => $employee,
            'company' => $company,
            'payrolls' => $payrolls,
            'annual_totals' => $annualTotals,
            'certificate_number' => 'CERT-' . $year . '-' . str_pad($employee->id, 4, '0', STR_PAD_LEFT),
        ];

        $pdf = Pdf::loadView('reports.dian-income-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);

        return $pdf->download('certificado-ingresos-' . $employee->document_number . '-' . $year . '.pdf');
    }

    /**
     * Obtener datos para vista previa de reportes
     */
    public function getReportData(Request $request)
    {
        $type = $request->get('type');
        
        switch ($type) {
            case 'employees':
                return response()->json([
                    'employees' => Employee::active()->select('id', 'first_name', 'last_name', 'document_number')->get(),
                    'departments' => Employee::active()->distinct()->pluck('department')->filter()->values(),
                ]);
                
            case 'payroll_summary':
                $summary = Payroll::currentMonth()
                    ->selectRaw('
                        COUNT(*) as total_payrolls,
                        SUM(total_income) as total_income,
                        SUM(total_deductions) as total_deductions,
                        SUM(net_salary) as total_net_salary,
                        SUM(total_cost) as total_cost
                    ')
                    ->first();
                    
                return response()->json(['summary' => $summary]);
                
            default:
                return response()->json(['error' => 'Tipo de reporte no válido'], 400);
        }
    }

    /**
     * Generate payroll PDF report (GET version)
     */
    public function payrollPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $employeeId = $request->input('employee_id');
        $payrollId = $request->input('payroll_id');
        $status = $request->input('status');
        
        $query = Payroll::with('employee');
        
        if ($payrollId) {
            $query->where('id', $payrollId);
        } else {
            $query->whereBetween('period_start', [$startDate, $endDate]);
            
            if ($employeeId) {
                $query->where('employee_id', $employeeId);
            }
            
            if ($status) {
                $query->where('status', $status);
            }
        }
        
        $payrolls = $query->orderBy('period_start', 'desc')->get();
        
        // Calculate totals
        $summary = [
            'total_employees' => $payrolls->groupBy('employee_id')->count(),
            'total_income' => $payrolls->sum('total_income'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_net_salary' => $payrolls->sum('net_salary'),
            'total_employer_contributions' => $payrolls->sum('total_employer_contributions'),
            'total_cost' => $payrolls->sum('total_cost'),
        ];
        
        $data = [
            'title' => 'Reporte de Nómina',
            'period' => Carbon::parse($startDate)->format('d/m/Y') . ' - ' . Carbon::parse($endDate)->format('d/m/Y'),
            'generated_at' => Carbon::now()->format('d/m/Y H:i:s'),
            'payrolls' => $payrolls,
            'summary' => $summary,
        ];
        
        $pdf = Pdf::loadView('reports.payroll-pdf', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);
        
        return $pdf->download('reporte-nomina-' . date('Y-m-d') . '.pdf');
    }
    
    /**
     * Generate DIAN certificate PDF
     */
    public function dianCertificatePDF(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $employeeId = $request->input('employee_id');
        
        if ($employeeId) {
            // Certificado individual
            $employee = Employee::findOrFail($employeeId);
            $payrolls = Payroll::where('employee_id', $employeeId)
                ->whereYear('period_start', $year)
                ->where('status', 'paid')
                ->orderBy('period_start')
                ->get();
        } else {
            // Certificado general
            $employee = null;
            $payrolls = Payroll::with('employee')
                ->whereYear('period_start', $year)
                ->where('status', 'paid')
                ->orderBy('period_start')
                ->get();
        }
        
        // Calculate totals for DIAN certificate
        $annualTotals = [
            'total_income' => $payrolls->sum('total_income'),
            'total_deductions' => $payrolls->sum('total_deductions'),
            'total_health' => $payrolls->sum('health_contribution'),
            'total_pension' => $payrolls->sum('pension_contribution'),
            'total_solidarity_fund' => $payrolls->sum('solidarity_fund'),
            'total_tax_withholding' => $payrolls->sum('income_tax_withholding'),
            'net_income' => $payrolls->sum('net_salary'),
            'total_employer_contributions' => $payrolls->sum('total_employer_contributions'),
        ];
        
        // Company information
        $company = [
            'name' => 'PGC Gestión de Datos',
            'nit' => '900123456-1',
            'address' => 'Calle 123 # 45-67',
            'city' => 'Bogotá D.C.',
            'phone' => '(601) 123-4567',
        ];
        
        $data = [
            'title' => 'Certificado de Ingresos y Retenciones DIAN',
            'year' => $year,
            'generated_at' => Carbon::now()->format('d/m/Y H:i:s'),
            'employee' => $employee,
            'company' => $company,
            'payrolls' => $payrolls,
            'annual_totals' => $annualTotals,
            'certificate_number' => 'CERT-' . $year . '-' . ($employee ? str_pad($employee->id, 4, '0', STR_PAD_LEFT) : 'GENERAL'),
        ];
        
        $pdf = Pdf::loadView('reports.dian-certificate', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
                'isHtml5ParserEnabled' => true,
            ]);
        
        $filename = 'certificado-dian-' . $year . ($employee ? '-' . $employee->document_number : '') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Formatear número con separadores de miles (COP)
     */
    private function formatCurrency($amount)
    {
        return number_format($amount, 0, ',', '.');
    }
}
