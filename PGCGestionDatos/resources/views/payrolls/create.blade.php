@extends('layouts.cafe')

@section('title', 'Nueva Nómina')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Nueva Nómina</h3>
                    <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('payrolls.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Información General -->
                            <div class="col-md-4">
                                <h5 class="mb-3 text-primary">Información General</h5>
                                
                                <div class="mb-3">
                                    <label for="employee_id" class="form-label">Empleado</label>
                                    <select class="form-select @error('employee_id') is-invalid @enderror" 
                                            id="employee_id" name="employee_id" required>
                                        <option value="">Seleccionar empleado...</option>
                                        @foreach(App\Models\Employee::where('status', 'active')->orderBy('first_name')->get() as $employee)
                                            <option value="{{ $employee->id }}" 
                                                    {{ old('employee_id', request('employee_id')) == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }} ({{ $employee->employee_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="payroll_code" class="form-label">Código Nómina</label>
                                    <input type="text" class="form-control @error('payroll_code') is-invalid @enderror" 
                                           id="payroll_code" name="payroll_code" 
                                           value="{{ old('payroll_code') }}" required
                                           placeholder="NOM-{{ date('Y-m') }}-XXX">
                                    @error('payroll_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="period_start" class="form-label">Inicio Período</label>
                                    <input type="date" class="form-control @error('period_start') is-invalid @enderror" 
                                           id="period_start" name="period_start" 
                                           value="{{ old('period_start', now()->startOfMonth()->format('Y-m-d')) }}" required>
                                    @error('period_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="period_end" class="form-label">Fin Período</label>
                                    <input type="date" class="form-control @error('period_end') is-invalid @enderror" 
                                           id="period_end" name="period_end" 
                                           value="{{ old('period_end', now()->endOfMonth()->format('Y-m-d')) }}" required>
                                    @error('period_end')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Fecha de Pago</label>
                                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                           id="payment_date" name="payment_date" 
                                           value="{{ old('payment_date', now()->addDays(5)->format('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="payroll_type" class="form-label">Tipo Nómina</label>
                                    <select class="form-select @error('payroll_type') is-invalid @enderror" 
                                            id="payroll_type" name="payroll_type" required>
                                        <option value="mensual" {{ old('payroll_type', 'mensual') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                                        <option value="quincenal" {{ old('payroll_type') == 'quincenal' ? 'selected' : '' }}>Quincenal</option>
                                        <option value="semanal" {{ old('payroll_type') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                        <option value="diario" {{ old('payroll_type') == 'diario' ? 'selected' : '' }}>Diario</option>
                                    </select>
                                    @error('payroll_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tiempo Trabajado -->
                            <div class="col-md-4">
                                <h5 class="mb-3 text-success">Tiempo Trabajado</h5>
                                
                                <div class="mb-3">
                                    <label for="days_worked" class="form-label">Días Trabajados</label>
                                    <input type="number" class="form-control @error('days_worked') is-invalid @enderror" 
                                           id="days_worked" name="days_worked" 
                                           value="{{ old('days_worked', 30) }}" min="1" max="31" required>
                                    @error('days_worked')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="hours_worked" class="form-label">Horas Trabajadas</label>
                                    <input type="number" class="form-control @error('hours_worked') is-invalid @enderror" 
                                           id="hours_worked" name="hours_worked" 
                                           value="{{ old('hours_worked', 240) }}" min="1" step="0.5" required>
                                    @error('hours_worked')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="overtime_hours" class="form-label">Horas Extra</label>
                                    <input type="number" class="form-control @error('overtime_hours') is-invalid @enderror" 
                                           id="overtime_hours" name="overtime_hours" 
                                           value="{{ old('overtime_hours', 0) }}" min="0" step="0.5">
                                    @error('overtime_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="base_salary" class="form-label">Salario Base (COP)</label>
                                    <input type="number" class="form-control @error('base_salary') is-invalid @enderror" 
                                           id="base_salary" name="base_salary" 
                                           value="{{ old('base_salary') }}" min="0" step="1000" required>
                                    @error('base_salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="overtime_pay" class="form-label">Pago Horas Extra (COP)</label>
                                    <input type="number" class="form-control @error('overtime_pay') is-invalid @enderror" 
                                           id="overtime_pay" name="overtime_pay" 
                                           value="{{ old('overtime_pay', 0) }}" min="0" step="1000">
                                    @error('overtime_pay')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Ingresos Adicionales -->
                            <div class="col-md-4">
                                <h5 class="mb-3 text-info">Ingresos Adicionales</h5>
                                
                                <div class="mb-3">
                                    <label for="bonuses" class="form-label">Bonificaciones (COP)</label>
                                    <input type="number" class="form-control @error('bonuses') is-invalid @enderror" 
                                           id="bonuses" name="bonuses" 
                                           value="{{ old('bonuses', 0) }}" min="0" step="1000">
                                    @error('bonuses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="commissions" class="form-label">Comisiones (COP)</label>
                                    <input type="number" class="form-control @error('commissions') is-invalid @enderror" 
                                           id="commissions" name="commissions" 
                                           value="{{ old('commissions', 0) }}" min="0" step="1000">
                                    @error('commissions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="transportation_allowance" class="form-label">Auxilio Transporte (COP)</label>
                                    <input type="number" class="form-control @error('transportation_allowance') is-invalid @enderror" 
                                           id="transportation_allowance" name="transportation_allowance" 
                                           value="{{ old('transportation_allowance', 162000) }}" min="0" step="1000">
                                    @error('transportation_allowance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="food_allowance" class="form-label">Auxilio Alimentación (COP)</label>
                                    <input type="number" class="form-control @error('food_allowance') is-invalid @enderror" 
                                           id="food_allowance" name="food_allowance" 
                                           value="{{ old('food_allowance', 0) }}" min="0" step="1000">
                                    @error('food_allowance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="other_income" class="form-label">Otros Ingresos (COP)</label>
                                    <input type="number" class="form-control @error('other_income') is-invalid @enderror" 
                                           id="other_income" name="other_income" 
                                           value="{{ old('other_income', 0) }}" min="0" step="1000">
                                    @error('other_income')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Deducciones -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3 text-warning">Deducciones Adicionales</h5>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="other_withholdings" class="form-label">Otras Retenciones (COP)</label>
                                    <input type="number" class="form-control @error('other_withholdings') is-invalid @enderror" 
                                           id="other_withholdings" name="other_withholdings" 
                                           value="{{ old('other_withholdings', 0) }}" min="0" step="1000">
                                    @error('other_withholdings')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="loan_deductions" class="form-label">Desc. Préstamos (COP)</label>
                                    <input type="number" class="form-control @error('loan_deductions') is-invalid @enderror" 
                                           id="loan_deductions" name="loan_deductions" 
                                           value="{{ old('loan_deductions', 0) }}" min="0" step="1000">
                                    @error('loan_deductions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="other_deductions" class="form-label">Otras Deducciones (COP)</label>
                                    <input type="number" class="form-control @error('other_deductions') is-invalid @enderror" 
                                           id="other_deductions" name="other_deductions" 
                                           value="{{ old('other_deductions', 0) }}" min="0" step="1000">
                                    @error('other_deductions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="calculated" {{ old('status', 'calculated') == 'calculated' ? 'selected' : '' }}>Calculada</option>
                                        <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Aprobada</option>
                                        <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Pagada</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Notas -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notas</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Nota:</strong> Los cálculos de aportes de salud, pensión, ARL, parafiscales y provisiones se realizarán automáticamente según la normativa DIAN vigente.
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('payrolls.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-calculator"></i> Calcular y Guardar Nómina
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-rellenar campos basado en empleado seleccionado
document.getElementById('employee_id').addEventListener('change', function() {
    const employeeId = this.value;
    if (!employeeId) return;
    
    // Aquí podrías hacer una llamada AJAX para obtener datos del empleado
    // Por ahora solo generamos un código de nómina automático
    const today = new Date();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();
    const empCode = this.options[this.selectedIndex].text.match(/\((\w+)\)/);
    const employeeCode = empCode ? empCode[1] : '000';
    
    const payrollCode = `NOM-${year}-${month}-${employeeCode}`;
    document.getElementById('payroll_code').value = payrollCode;
});

// Calcular horas trabajadas automáticamente
document.getElementById('days_worked').addEventListener('input', function() {
    const daysWorked = parseInt(this.value) || 0;
    const hoursPerDay = 8;
    document.getElementById('hours_worked').value = daysWorked * hoursPerDay;
});
</script>
@endsection