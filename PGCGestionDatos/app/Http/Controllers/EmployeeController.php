<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $employees = Employee::with(['payrolls' => function($query) {
            $query->latest()->take(1);
        }])->orderBy('first_name')->paginate(15);
        
        // Estadísticas para las tarjetas
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $totalDepartments = Employee::distinct('department')->count('department');
        $averageSalary = Employee::where('status', 'active')->avg('base_salary');
        $averageSalary = $averageSalary ? 'COP ' . number_format($averageSalary, 0, ',', '.') : 'N/A';
        
        return view('employees.index', compact(
            'employees',
            'totalEmployees',
            'activeEmployees',
            'totalDepartments',
            'averageSalary'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => 'required|unique:employees',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_type' => 'required|in:CC,TI,CE,PP',
            'document_number' => 'required|unique:employees',
            'email' => 'nullable|email|unique:employees',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'birth_date' => 'required|date',
            'hire_date' => 'required|date',
            'position' => 'required|string',
            'department' => 'required|string',
            'base_salary' => 'required|numeric|min:0',
            'transportation_allowance' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'gender' => 'required|in:M,F,O',
            'emergency_contact' => 'nullable|string',
        ]);

        Employee::create($validated);

        return redirect()->route('employees.index')
                        ->with('success', 'Empleado creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee): View
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee): View
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'employee_code' => 'required|unique:employees,employee_code,' . $employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'document_type' => 'required|in:CC,TI,CE,PP',
            'document_number' => 'required|unique:employees,document_number,' . $employee->id,
            'email' => 'nullable|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'birth_date' => 'required|date',
            'hire_date' => 'required|date',
            'position' => 'required|string',
            'department' => 'required|string',
            'base_salary' => 'required|numeric|min:0',
            'transportation_allowance' => 'nullable|numeric|min:0',
            'status' => 'required|in:active,inactive,suspended',
            'gender' => 'required|in:M,F,O',
            'emergency_contact' => 'nullable|string',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
                        ->with('success', 'Empleado actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->update(['status' => 'inactive']);
        
        return redirect()->route('employees.index')
                        ->with('success', 'Empleado desactivado exitosamente.');
    }
}
