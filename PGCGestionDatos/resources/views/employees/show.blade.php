@extends('layouts.cafe')

@section('title', 'Empleado - ' . $employee->full_name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <!-- Información del Empleado -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ $employee->full_name }}</h3>
                    <div>
                        <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Información Personal -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Información Personal</h5>
                            
                            <div class="mb-3">
                                <strong>Código:</strong> {{ $employee->employee_code }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Documento:</strong> {{ $employee->document_type }} {{ $employee->document_number }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Fecha de Nacimiento:</strong> {{ $employee->birth_date ? $employee->birth_date->format('d/m/Y') : 'No registrada' }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Género:</strong> 
                                @switch($employee->gender)
                                    @case('M') Masculino @break
                                    @case('F') Femenino @break
                                    @case('O') Otro @break
                                    @default No especificado
                                @endswitch
                            </div>
                            
                            @if($employee->age)
                            <div class="mb-3">
                                <strong>Edad:</strong> {{ $employee->age }} años
                            </div>
                            @endif
                        </div>

                        <!-- Información Laboral -->
                        <div class="col-md-6">
                            <h5 class="text-success mb-3">Información Laboral</h5>
                            
                            <div class="mb-3">
                                <strong>Cargo:</strong> {{ $employee->position }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Departamento:</strong> {{ $employee->department }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Fecha de Ingreso:</strong> {{ $employee->hire_date ? $employee->hire_date->format('d/m/Y') : 'No registrada' }}
                            </div>
                            
                            @if($employee->years_worked)
                            <div class="mb-3">
                                <strong>Años Trabajados:</strong> {{ $employee->years_worked }}
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong>Salario Base:</strong> {{ $employee->formatted_base_salary }}
                            </div>
                            
                            @if($employee->transportation_allowance > 0)
                            <div class="mb-3">
                                <strong>Auxilio de Transporte:</strong> {{ $employee->formatted_transportation_allowance }}
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <strong>Estado:</strong>
                                <span class="badge bg-{{ $employee->status === 'active' ? 'success' : ($employee->status === 'inactive' ? 'secondary' : 'warning') }}">
                                    @switch($employee->status)
                                        @case('active') Activo @break
                                        @case('inactive') Inactivo @break
                                        @case('suspended') Suspendido @break
                                        @default {{ ucfirst($employee->status) }}
                                    @endswitch
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($employee->email || $employee->phone || $employee->address || $employee->emergency_contact)
                    <hr>
                    
                    <!-- Información de Contacto -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-info mb-3">Información de Contacto</h5>
                        </div>
                        
                        @if($employee->email)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Email:</strong> 
                                <a href="mailto:{{ $employee->email }}">{{ $employee->email }}</a>
                            </div>
                        </div>
                        @endif
                        
                        @if($employee->phone)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Teléfono:</strong> 
                                <a href="tel:{{ $employee->phone }}">{{ $employee->phone }}</a>
                            </div>
                        </div>
                        @endif
                        
                        @if($employee->emergency_contact)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Contacto de Emergencia:</strong> {{ $employee->emergency_contact }}
                            </div>
                        </div>
                        @endif
                        
                        @if($employee->address)
                        <div class="col-12">
                            <div class="mb-3">
                                <strong>Dirección:</strong> {{ $employee->address }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar con acciones y estadísticas -->
        <div class="col-md-4">
            <!-- Acciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Acciones</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('payrolls.create', ['employee_id' => $employee->id]) }}" class="btn btn-dark btn-sm w-100 mb-2">
                        <i class="fas fa-plus"></i> Nueva Nómina
                    </a>
                    <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning btn-sm w-100 mb-2">
                        <i class="fas fa-edit"></i> Editar Empleado
                    </a>
                    <a href="{{ route('reports.payroll.pdf', ['employee_id' => $employee->id]) }}" target="_blank" class="btn btn-info btn-sm w-100 mb-2">
                        <i class="fas fa-file-pdf"></i> Reporte Nómina
                    </a>
                    
                    <hr>
                    
                    <form action="{{ route('employees.destroy', $employee) }}" method="POST" 
                          onsubmit="return confirm('¿Está seguro de eliminar este empleado? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-trash"></i> Eliminar Empleado
                        </button>
                    </form>
                </div>
            </div>

            <!-- Estadísticas de Nómina -->
            <div class="card">
                <div class="card-header">
                    <h5>Estadísticas de Nómina</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Total Nóminas:</strong> 
                        <span class="badge bg-primary">{{ $employee->payrolls()->count() }}</span>
                    </div>
                    
                    @if($lastPayroll = $employee->payrolls()->latest()->first())
                    <div class="mb-3">
                        <strong>Última Nómina:</strong><br>
                        <small class="text-muted">{{ $lastPayroll->period_start->format('M Y') }}</small><br>
                        <strong>{{ $lastPayroll->formatted_net_salary }}</strong>
                    </div>
                    @endif
                    
                    @if($totalPaid = $employee->payrolls()->where('status', 'paid')->sum('net_salary'))
                    <div class="mb-3">
                        <strong>Total Pagado:</strong><br>
                        <strong class="text-success">{{ 'COP ' . number_format($totalPaid, 0, ',', '.') }}</strong>
                    </div>
                    @endif
                    
                    <div class="mb-3">
                        <strong>Estado Actual:</strong><br>
                        <span class="badge bg-{{ $employee->status === 'active' ? 'success' : ($employee->status === 'inactive' ? 'secondary' : 'warning') }}">
                            @switch($employee->status)
                                @case('active') Activo @break
                                @case('inactive') Inactivo @break
                                @case('suspended') Suspendido @break
                                @default {{ ucfirst($employee->status) }}
                            @endswitch
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Nóminas -->
    @if($employee->payrolls()->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Historial de Nóminas</h5>
                    <a href="{{ route('payrolls.index', ['employee' => $employee->id]) }}" class="btn btn-primary btn-sm">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Período</th>
                                    <th>Código</th>
                                    <th>Salario Neto</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employee->payrolls()->latest()->take(5)->get() as $payroll)
                                <tr>
                                    <td>{{ $payroll->period_start->format('M Y') }}</td>
                                    <td>{{ $payroll->payroll_code }}</td>
                                    <td>{{ $payroll->formatted_net_salary }}</td>
                                    <td>
                                        <span class="badge bg-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'approved' ? 'warning' : 'secondary') }}">
                                            @switch($payroll->status)
                                                @case('paid') Pagado @break
                                                @case('approved') Aprobado @break
                                                @case('calculated') Calculado @break
                                                @default {{ ucfirst($payroll->status) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection