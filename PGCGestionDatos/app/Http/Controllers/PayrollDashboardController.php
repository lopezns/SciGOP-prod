<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payroll;
use Illuminate\Http\Request;

class PayrollDashboardController extends Controller
{
    /**
     * Display the payroll dashboard.
     */
    public function index()
    {
        // Estadísticas generales
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $totalPayrolls = Payroll::count();
        
        // Estadísticas de nóminas
        $pendingPayrolls = Payroll::whereIn('status', ['calculated', 'approved'])->count();
        $paidPayrolls = Payroll::where('status', 'paid')->count();
        $totalPaidAmount = Payroll::where('status', 'paid')->sum('net_salary');
        
        // Nóminas recientes
        $recentPayrolls = Payroll::with('employee')
            ->latest()
            ->take(10)
            ->get();
        
        // Empleados recién agregados
        $recentEmployees = Employee::latest()
            ->take(5)
            ->get();
        
        // Estadísticas por departamento
        $departmentStats = Employee::selectRaw('department, COUNT(*) as total, AVG(base_salary) as avg_salary')
            ->where('status', 'active')
            ->groupBy('department')
            ->get();
        
        // Distribución por estado de nóminas
        $payrollStatusStats = Payroll::selectRaw('status, COUNT(*) as count, SUM(net_salary) as total')
            ->groupBy('status')
            ->get();
        
        // Nóminas del mes actual
        $currentMonthPayrolls = Payroll::whereYear('period_start', now()->year)
            ->whereMonth('period_start', now()->month)
            ->count();
        
        $currentMonthAmount = Payroll::whereYear('period_start', now()->year)
            ->whereMonth('period_start', now()->month)
            ->sum('net_salary');

        return view('payroll.dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'totalPayrolls',
            'pendingPayrolls',
            'paidPayrolls',
            'totalPaidAmount',
            'recentPayrolls',
            'recentEmployees',
            'departmentStats',
            'payrollStatusStats',
            'currentMonthPayrolls',
            'currentMonthAmount'
        ));
    }
}