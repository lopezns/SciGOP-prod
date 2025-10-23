<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $payrolls = Payroll::with('employee')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);
                          
        // Estadísticas para las tarjetas
        $totalPayrolls = Payroll::count();
        $paidPayrolls = Payroll::where('status', 'paid')->count();
        $pendingPayrolls = Payroll::whereIn('status', ['calculated', 'approved'])->count();
        $totalAmount = Payroll::where('status', 'paid')->sum('net_salary');
        $totalAmount = $totalAmount ? 'COP ' . number_format($totalAmount, 0, ',', '.') : 'N/A';
        
        // Empleados para filtros
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        return view('payrolls.index', compact(
            'payrolls',
            'totalPayrolls',
            'paidPayrolls',
            'pendingPayrolls',
            'totalAmount',
            'employees'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('payrolls.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_code' => 'required|unique:payrolls',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'payment_date' => 'required|date',
            'payroll_type' => 'required|in:mensual,quincenal,semanal,diario',
            'days_worked' => 'required|integer|min:1|max:31',
            'hours_worked' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'base_salary' => 'required|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'commissions' => 'nullable|numeric|min:0',
            'transportation_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'other_income' => 'nullable|numeric|min:0',
            'other_withholdings' => 'nullable|numeric|min:0',
            'loan_deductions' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'status' => 'required|in:calculated,approved,paid,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payroll = Payroll::create($validated);
        
        // Calculate payroll automatically
        try {
            $payroll->calculatePayroll();
        } catch (\Exception $e) {
            // Continue even if calculation fails
        }

        return redirect()->route('payrolls.show', $payroll)
                        ->with('success', 'Nómina creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payroll $payroll): View
    {
        $payroll->load('employee');
        return view('payrolls.show', compact('payroll'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payroll $payroll): View
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        return view('payrolls.edit', compact('payroll', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payroll $payroll): RedirectResponse
    {
        // If only updating status (quick status change)
        if ($request->has('status') && count($request->all()) <= 2) { // status + _token
            $request->validate([
                'status' => 'required|in:calculated,approved,paid,cancelled'
            ]);
            
            $payroll->update(['status' => $request->status]);
            
            $statusNames = [
                'calculated' => 'calculada',
                'approved' => 'aprobada', 
                'paid' => 'pagada',
                'cancelled' => 'cancelada'
            ];
            
            return redirect()->back()
                            ->with('success', 'Nómina marcada como ' . $statusNames[$request->status] . ' exitosamente.');
        }
        
        // Full update
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_code' => 'required|unique:payrolls,payroll_code,' . $payroll->id,
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'payment_date' => 'required|date',
            'payroll_type' => 'required|in:mensual,quincenal,semanal,diario',
            'days_worked' => 'required|integer|min:1|max:31',
            'hours_worked' => 'nullable|numeric|min:0',
            'overtime_hours' => 'nullable|numeric|min:0',
            'base_salary' => 'required|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonuses' => 'nullable|numeric|min:0',
            'commissions' => 'nullable|numeric|min:0',
            'transportation_allowance' => 'nullable|numeric|min:0',
            'food_allowance' => 'nullable|numeric|min:0',
            'other_income' => 'nullable|numeric|min:0',
            'other_withholdings' => 'nullable|numeric|min:0',
            'loan_deductions' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'status' => 'required|in:calculated,approved,paid,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $payroll->update($validated);
        
        // Recalculate if data changed
        if ($payroll->status === 'calculated') {
            try {
                $payroll->calculatePayroll();
            } catch (\Exception $e) {
                // Continue even if calculation fails
            }
        }

        return redirect()->route('payrolls.show', $payroll)
                        ->with('success', 'Nómina actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payroll $payroll): RedirectResponse
    {
        if ($payroll->status === 'paid') {
            return redirect()->back()
                           ->with('error', 'No se puede eliminar una nómina ya pagada.');
        }

        $payroll->delete();
        
        return redirect()->route('payrolls.index')
                        ->with('success', 'Nómina eliminada exitosamente.');
    }

    /**
     * Calculate payroll
     */
    public function calculate(Payroll $payroll): RedirectResponse
    {
        try {
            $payroll->calculatePayroll();
            return redirect()->back()
                           ->with('success', 'Nómina calculada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Error al calcular la nómina: ' . $e->getMessage());
        }
    }

    /**
     * Approve payroll
     */
    public function approve(Payroll $payroll): RedirectResponse
    {
        if ($payroll->status !== 'calculated') {
            return redirect()->back()
                           ->with('error', 'Solo se pueden aprobar nóminas calculadas.');
        }

        $payroll->update(['status' => 'approved']);
        
        return redirect()->back()
                        ->with('success', 'Nómina aprobada exitosamente.');
    }

    /**
     * Mark payroll as paid
     */
    public function pay(Payroll $payroll): RedirectResponse
    {
        if ($payroll->status !== 'approved') {
            return redirect()->back()
                           ->with('error', 'Solo se pueden pagar nóminas aprobadas.');
        }

        $payroll->update(['status' => 'paid']);
        
        return redirect()->back()
                        ->with('success', 'Nómina marcada como pagada exitosamente.');
    }
}
