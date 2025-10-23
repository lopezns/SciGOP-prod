@extends('layouts.cafe')

@section('title', 'Nómina - ' . $payroll->payroll_code)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <!-- Información de la Nómina -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>{{ $payroll->payroll_code }} - {{ $payroll->employee->full_name }}</h3>
                    <div>
                        <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Información General -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Información General</h5>
                            
                            <div class="mb-3">
                                <strong>Código Nómina:</strong> {{ $payroll->payroll_code }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Período:</strong> {{ $payroll->period_start->format('d/m/Y') }} - {{ $payroll->period_end->format('d/m/Y') }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Fecha de Pago:</strong> {{ $payroll->payment_date->format('d/m/Y') }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Tipo:</strong> {{ ucfirst($payroll->payroll_type) }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Estado:</strong>
                                <span class="badge bg-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'approved' ? 'warning' : ($payroll->status === 'calculated' ? 'secondary' : 'danger')) }}">
                                    @switch($payroll->status)
                                        @case('paid') Pagado @break
                                        @case('approved') Aprobado @break
                                        @case('calculated') Calculado @break
                                        @case('cancelled') Cancelado @break
                                        @default {{ ucfirst($payroll->status) }}
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="text-success mb-3">Tiempo Trabajado</h5>
                            
                            <div class="mb-3">
                                <strong>Días Trabajados:</strong> {{ $payroll->days_worked }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Horas Trabajadas:</strong> {{ $payroll->hours_worked }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Horas Extra:</strong> {{ $payroll->overtime_hours }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Salario Base:</strong> {{ $payroll->formatted_base_salary }}
                            </div>
                            
                            @if($payroll->overtime_pay > 0)
                            <div class="mb-3">
                                <strong>Pago Horas Extra:</strong> {{ $payroll->formatted_overtime_pay }}
                            </div>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <!-- Ingresos -->
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-info mb-3">Ingresos</h5>
                            
                            <div class="mb-2">
                                <strong>Salario Base:</strong> 
                                <span class="float-end">{{ $payroll->formatted_base_salary }}</span>
                            </div>
                            
                            @if($payroll->overtime_pay > 0)
                            <div class="mb-2">
                                <strong>Horas Extra:</strong> 
                                <span class="float-end">{{ $payroll->formatted_overtime_pay }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->bonuses > 0)
                            <div class="mb-2">
                                <strong>Bonificaciones:</strong> 
                                <span class="float-end">{{ $payroll->formatted_bonuses }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->commissions > 0)
                            <div class="mb-2">
                                <strong>Comisiones:</strong> 
                                <span class="float-end">{{ $payroll->formatted_commissions }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->transportation_allowance > 0)
                            <div class="mb-2">
                                <strong>Auxilio de Transporte:</strong> 
                                <span class="float-end">{{ $payroll->formatted_transportation_allowance }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->food_allowance > 0)
                            <div class="mb-2">
                                <strong>Auxilio de Alimentación:</strong> 
                                <span class="float-end">{{ $payroll->formatted_food_allowance }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->other_income > 0)
                            <div class="mb-2">
                                <strong>Otros Ingresos:</strong> 
                                <span class="float-end">{{ $payroll->formatted_other_income }}</span>
                            </div>
                            @endif
                            
                            <hr>
                            <div class="mb-3">
                                <strong class="text-success">Total Ingresos:</strong> 
                                <span class="float-end text-success"><strong>{{ $payroll->formatted_total_income }}</strong></span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="text-warning mb-3">Deducciones</h5>
                            
                            @if($payroll->health_contribution > 0)
                            <div class="mb-2">
                                <strong>Salud (4%):</strong> 
                                <span class="float-end">{{ $payroll->formatted_health_contribution }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->pension_contribution > 0)
                            <div class="mb-2">
                                <strong>Pensión (4%):</strong> 
                                <span class="float-end">{{ $payroll->formatted_pension_contribution }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->solidarity_fund > 0)
                            <div class="mb-2">
                                <strong>Fondo de Solidaridad (1%):</strong> 
                                <span class="float-end">{{ $payroll->formatted_solidarity_fund }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->unemployment_fund > 0)
                            <div class="mb-2">
                                <strong>Fondo de Cesantías:</strong> 
                                <span class="float-end">{{ $payroll->formatted_unemployment_fund }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->income_tax_withholding > 0)
                            <div class="mb-2">
                                <strong>Retención en la Fuente:</strong> 
                                <span class="float-end">{{ $payroll->formatted_income_tax_withholding }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->other_withholdings > 0)
                            <div class="mb-2">
                                <strong>Otras Retenciones:</strong> 
                                <span class="float-end">{{ $payroll->formatted_other_withholdings }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->loan_deductions > 0)
                            <div class="mb-2">
                                <strong>Préstamos:</strong> 
                                <span class="float-end">{{ $payroll->formatted_loan_deductions }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->other_deductions > 0)
                            <div class="mb-2">
                                <strong>Otras Deducciones:</strong> 
                                <span class="float-end">{{ $payroll->formatted_other_deductions }}</span>
                            </div>
                            @endif
                            
                            <hr>
                            <div class="mb-3">
                                <strong class="text-warning">Total Deducciones:</strong> 
                                <span class="float-end text-warning"><strong>{{ $payroll->formatted_total_deductions }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Salario Neto a Pagar</h5>
                                    <h4 class="mb-0 text-success">{{ $payroll->formatted_net_salary }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($payroll->notes)
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-secondary mb-3">Notas</h5>
                            <div class="alert alert-light">
                                {{ $payroll->notes }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Aportes Patronales -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Aportes Patronales</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($payroll->employer_health > 0)
                            <div class="mb-2">
                                <strong>Salud Empleador (8.5%):</strong> 
                                <span class="float-end">{{ $payroll->formatted_employer_health }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->employer_pension > 0)
                            <div class="mb-2">
                                <strong>Pensión Empleador (12%):</strong> 
                                <span class="float-end">{{ $payroll->formatted_employer_pension }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->employer_arl > 0)
                            <div class="mb-2">
                                <strong>ARL:</strong> 
                                <span class="float-end">{{ $payroll->formatted_employer_arl }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            @if($payroll->employer_sena > 0)
                            <div class="mb-2">
                                <strong>SENA (2%):</strong> 
                                <span class="float-end">{{ $payroll->formatted_employer_sena }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->employer_icbf > 0)
                            <div class="mb-2">
                                <strong>ICBF (3%):</strong> 
                                <span class="float-end">{{ $payroll->formatted_employer_icbf }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->employer_compensation > 0)
                            <div class="mb-2">
                                <strong>Caja de Compensación (4%):</strong> 
                                <span class="float-end">{{ $payroll->formatted_employer_compensation }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <hr>
                    <div class="mb-2">
                        <strong class="text-primary">Total Aportes Patronales:</strong> 
                        <span class="float-end text-primary"><strong>{{ $payroll->formatted_total_employer_contributions }}</strong></span>
                    </div>
                </div>
            </div>

            <!-- Provisiones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Provisiones</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($payroll->vacation_provision > 0)
                            <div class="mb-2">
                                <strong>Vacaciones:</strong> 
                                <span class="float-end">{{ $payroll->formatted_vacation_provision }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->bonus_provision > 0)
                            <div class="mb-2">
                                <strong>Prima:</strong> 
                                <span class="float-end">{{ $payroll->formatted_bonus_provision }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            @if($payroll->severance_provision > 0)
                            <div class="mb-2">
                                <strong>Cesantías:</strong> 
                                <span class="float-end">{{ $payroll->formatted_severance_provision }}</span>
                            </div>
                            @endif
                            
                            @if($payroll->severance_interest > 0)
                            <div class="mb-2">
                                <strong>Intereses sobre Cesantías:</strong> 
                                <span class="float-end">{{ $payroll->formatted_severance_interest }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Costo Total -->
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-primary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Costo Total de la Nómina</h5>
                            <h4 class="mb-0 text-primary">{{ $payroll->formatted_total_cost }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar con acciones -->
        <div class="col-md-4">
            <!-- Acciones -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Acciones</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-warning btn-sm w-100 mb-2">
                        <i class="fas fa-edit"></i> Editar Nómina
                    </a>
                    
                    @if($payroll->status === 'calculated')
                    <form action="{{ route('payrolls.update', $payroll) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success btn-sm w-100">
                            <i class="fas fa-check"></i> Aprobar Nómina
                        </button>
                    </form>
                    @endif
                    
                    @if($payroll->status === 'approved')
                    <form action="{{ route('payrolls.update', $payroll) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="paid">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-money-bill-wave"></i> Marcar como Pagada
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('reports.payroll.pdf', ['payroll_id' => $payroll->id]) }}" target="_blank" class="btn btn-info btn-sm w-100 mb-2">
                        <i class="fas fa-file-pdf"></i> Generar PDF
                    </a>
                    
                    <hr>
                    
                    @if($payroll->status !== 'paid')
                    <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" 
                          onsubmit="return confirm('¿Está seguro de eliminar esta nómina? Esta acción no se puede deshacer.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-trash"></i> Eliminar Nómina
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Información del Empleado -->
            <div class="card">
                <div class="card-header">
                    <h5>Información del Empleado</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Nombre:</strong><br>
                        <a href="{{ route('employees.show', $payroll->employee) }}">
                            {{ $payroll->employee->full_name }}
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Código:</strong><br>
                        {{ $payroll->employee->employee_code }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Cargo:</strong><br>
                        {{ $payroll->employee->position }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Departamento:</strong><br>
                        {{ $payroll->employee->department }}
                    </div>
                    
                    <a href="{{ route('employees.show', $payroll->employee) }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="fas fa-user"></i> Ver Empleado
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection