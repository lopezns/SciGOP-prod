<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DianController extends Controller
{
    /**
     * DIAN Dashboard
     */
    public function index(): View
    {
        $stats = [
            'total_employees' => Employee::where('status', 'active')->count(),
            'current_month_payrolls' => Payroll::whereMonth('period_start', now()->month)
                                               ->whereYear('period_start', now()->year)->count(),
            'total_income_tax' => Payroll::whereYear('period_start', now()->year)
                                         ->sum('income_tax_withholding'),
            'total_contributions' => Payroll::whereYear('period_start', now()->year)
                                            ->sum('total_employer_contributions'),
        ];
        
        return view('dian.index', compact('stats'));
    }
    
    /**
     * Declaraciones - Tax Declarations
     */
    public function declaraciones(): View
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Calculate current period data
        $periodData = [
            'income_tax' => Payroll::whereYear('period_start', $currentYear)
                                   ->whereMonth('period_start', $currentMonth)
                                   ->sum('income_tax_withholding'),
            'employee_contributions' => Payroll::whereYear('period_start', $currentYear)
                                               ->whereMonth('period_start', $currentMonth)
                                               ->selectRaw('SUM(health_contribution + pension_contribution) as total')
                                               ->value('total') ?? 0,
            'employer_contributions' => Payroll::whereYear('period_start', $currentYear)
                                               ->whereMonth('period_start', $currentMonth)
                                               ->sum('total_employer_contributions'),
        ];
        
        // DIAN declaration deadlines and forms
        $declarations = [
            [
                'form' => 'Formulario 350 - Declaración de Renta',
                'deadline' => 'Octubre 2024',
                'status' => 'Pendiente',
                'description' => 'Declaración anual de renta y complementarios para personas jurídicas',
            ],
            [
                'form' => 'Formulario 210 - Declaración de Renta Personas Naturales',
                'deadline' => 'Agosto 2024',
                'status' => 'Vencida',
                'description' => 'Declaración de renta para personas naturales obligadas a declarar',
            ],
            [
                'form' => 'Formato 1001 - Información de Terceros',
                'deadline' => 'Marzo 2025',
                'status' => 'Próximo',
                'description' => 'Información exigida sobre operaciones con terceros',
            ],
            [
                'form' => 'Formato 1003 - Empleados, Socios y Trabajadores Independientes',
                'deadline' => 'Marzo 2025',
                'status' => 'Próximo',
                'description' => 'Información de pagos a empleados, socios y trabajadores independientes',
            ],
        ];
        
        return view('dian.declaraciones', compact('periodData', 'declarations', 'currentYear', 'currentMonth'));
    }
    
    /**
     * Retenciones - Tax Withholdings
     */
    public function retenciones(): View
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Calculate withholdings data
        $withholdings = [
            'income_tax' => Payroll::whereYear('period_start', $currentYear)
                                   ->sum('income_tax_withholding'),
            'monthly_income_tax' => Payroll::whereYear('period_start', $currentYear)
                                           ->whereMonth('period_start', $currentMonth)
                                           ->sum('income_tax_withholding'),
            'employees_with_withholding' => Payroll::whereYear('period_start', $currentYear)
                                                   ->where('income_tax_withholding', '>', 0)
                                                   ->distinct('employee_id')
                                                   ->count(),
        ];
        
        // Recent withholding transactions
        $recentWithholdings = Payroll::with('employee')
            ->where('income_tax_withholding', '>', 0)
            ->latest()
            ->take(10)
            ->get();
            
        // Withholding rates and information
        $retentionRates = [
            ['concept' => 'Salarios', 'rate' => 'Tabla Art. 383 E.T.', 'uvt_base' => '95 UVT'],
            ['concept' => 'Honorarios', 'rate' => '11%', 'uvt_base' => '4 UVT'],
            ['concept' => 'Comisiones', 'rate' => '6%', 'uvt_base' => '4 UVT'],
            ['concept' => 'Servicios', 'rate' => '6%', 'uvt_base' => '4 UVT'],
        ];
        
        return view('dian.retenciones', compact('withholdings', 'recentWithholdings', 'retentionRates', 'currentYear'));
    }
    
    /**
     * Certificación - Certifications
     */
    public function certificacion(): View
    {
        $employees = Employee::where('status', 'active')->get();
        
        // Mock employee certificate data - in a real implementation, this would be calculated
        $employeeCertificates = Payroll::select(
                'employee_id',
                DB::raw('SUM(total_income) as total_income'),
                DB::raw('SUM(income_tax_withholding) as total_withholdings'),
                DB::raw('SUM(health_contribution) as total_health'),
                DB::raw('SUM(pension_contribution) as total_pension'),
                DB::raw('COUNT(*) as payrolls_count')
            )
            ->with('employee')
            ->whereYear('period_start', date('Y'))
            ->groupBy('employee_id')
            ->get()
            ->map(function ($item) {
                $item->employee = Employee::find($item->employee_id);
                return $item;
            });
        
        // Annual statistics
        $annualStats = [
            'total_income' => Payroll::whereYear('period_start', date('Y'))->sum('total_income'),
            'total_withholdings' => Payroll::whereYear('period_start', date('Y'))->sum('income_tax_withholding'),
            'total_health' => Payroll::whereYear('period_start', date('Y'))->sum('health_contribution'),
            'total_pension' => Payroll::whereYear('period_start', date('Y'))->sum('pension_contribution'),
        ];
        
        // Historical data for different years
        $historicalData = [];
        for ($year = date('Y'); $year >= 2020; $year--) {
            $yearData = Payroll::whereYear('period_start', $year)
                ->selectRaw('COUNT(DISTINCT employee_id) as employees_count, SUM(total_income) as total_income, SUM(income_tax_withholding) as total_withholdings')
                ->first();
            
            if ($yearData->employees_count > 0) {
                $historicalData[$year] = [
                    'employees_count' => $yearData->employees_count,
                    'total_income' => $yearData->total_income ?? 0,
                    'total_withholdings' => $yearData->total_withholdings ?? 0,
                ];
            }
        }
        
        return view('dian.certificacion', compact('employees', 'employeeCertificates', 'annualStats', 'historicalData'));
    }
    
    /**
     * Aportes Parafiscales - Social Contributions
     */
    public function aportes(): View
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Current month contributions
        $currentMonth = [
            'sena' => Payroll::whereYear('period_start', $currentYear)
                             ->whereMonth('period_start', date('n'))
                             ->sum('employer_sena'),
            'icbf' => Payroll::whereYear('period_start', $currentYear)
                             ->whereMonth('period_start', date('n'))
                             ->sum('employer_icbf'),
            'compensacion' => Payroll::whereYear('period_start', $currentYear)
                                     ->whereMonth('period_start', date('n'))
                                     ->sum('employer_compensation'),
        ];
        
        // Calculation base
        $calculationBase = [
            'total_payroll' => Payroll::whereYear('period_start', $currentYear)
                                     ->whereMonth('period_start', date('n'))
                                     ->sum('base_salary'),
            'active_employees' => Employee::where('status', 'active')->count(),
            'average_salary' => Employee::where('status', 'active')->avg('base_salary') ?? 0,
        ];
        
        // Payment calendar (mock data for next few months)
        $paymentCalendar = [
            'Octubre 2024' => [
                'liquidation' => '31 Oct 2024',
                'presentation' => '15 Nov 2024',
                'payment' => '15 Nov 2024'
            ],
            'Noviembre 2024' => [
                'liquidation' => '30 Nov 2024',
                'presentation' => '16 Dic 2024',
                'payment' => '16 Dic 2024'
            ],
            'Diciembre 2024' => [
                'liquidation' => '31 Dic 2024',
                'presentation' => '15 Ene 2025',
                'payment' => '15 Ene 2025'
            ]
        ];
        
        // Historical contributions (mock data)
        $historicalContributions = collect();
        for ($i = 3; $i >= 1; $i--) {
            $month = now()->subMonths($i);
            $payrollSum = Payroll::whereYear('period_start', $month->year)
                                ->whereMonth('period_start', $month->month)
                                ->sum('base_salary');
            
            if ($payrollSum > 0) {
                $historicalContributions->push((object) [
                    'period' => $month->format('M Y'),
                    'calculation_base' => $payrollSum,
                    'sena_amount' => $payrollSum * 0.02,
                    'icbf_amount' => $payrollSum * 0.03,
                    'compensation_amount' => $payrollSum * 0.04,
                    'total_amount' => $payrollSum * 0.09,
                    'status' => $i == 1 ? 'pending' : 'paid',
                    'status_color' => $i == 1 ? 'yellow' : 'green',
                    'status_text' => $i == 1 ? 'Pendiente' : 'Pagado',
                    'id' => $i
                ]);
            }
        }
        
        return view('dian.aportes', compact(
            'currentMonth',
            'calculationBase', 
            'paymentCalendar', 
            'historicalContributions'
        ));
    }
}
