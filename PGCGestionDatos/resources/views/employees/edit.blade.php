@extends('layouts.cafe')

@section('title', 'Editar Empleado')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Editar Empleado - {{ $employee->full_name }}</h3>
                    <div>
                        <a href="{{ route('employees.show', $employee) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> Ver
                        </a>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('employees.update', $employee) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Información Personal -->
                            <div class="col-md-6">
                                <h5 class="mb-3 text-primary">Información Personal</h5>
                                
                                <div class="mb-3">
                                    <label for="employee_code" class="form-label">Código Empleado</label>
                                    <input type="text" class="form-control @error('employee_code') is-invalid @enderror" 
                                           id="employee_code" name="employee_code" 
                                           value="{{ old('employee_code', $employee->employee_code) }}" required>
                                    @error('employee_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Nombres</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                           id="first_name" name="first_name" 
                                           value="{{ old('first_name', $employee->first_name) }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                           id="last_name" name="last_name" 
                                           value="{{ old('last_name', $employee->last_name) }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="document_type" class="form-label">Tipo Documento</label>
                                    <select class="form-select @error('document_type') is-invalid @enderror" 
                                            id="document_type" name="document_type" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="CC" {{ old('document_type', $employee->document_type) == 'CC' ? 'selected' : '' }}>Cédula de Ciudadanía</option>
                                        <option value="TI" {{ old('document_type', $employee->document_type) == 'TI' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                                        <option value="CE" {{ old('document_type', $employee->document_type) == 'CE' ? 'selected' : '' }}>Cédula de Extranjería</option>
                                        <option value="PP" {{ old('document_type', $employee->document_type) == 'PP' ? 'selected' : '' }}>Pasaporte</option>
                                    </select>
                                    @error('document_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="document_number" class="form-label">Número Documento</label>
                                    <input type="text" class="form-control @error('document_number') is-invalid @enderror" 
                                           id="document_number" name="document_number" 
                                           value="{{ old('document_number', $employee->document_number) }}" required>
                                    @error('document_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">Fecha Nacimiento</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" 
                                           value="{{ old('birth_date', $employee->birth_date?->format('Y-m-d')) }}" required>
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Género</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('gender', $employee->gender) == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('gender', $employee->gender) == 'F' ? 'selected' : '' }}>Femenino</option>
                                        <option value="O" {{ old('gender', $employee->gender) == 'O' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Información Laboral -->
                            <div class="col-md-6">
                                <h5 class="mb-3 text-success">Información Laboral</h5>
                                
                                <div class="mb-3">
                                    <label for="position" class="form-label">Cargo</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                           id="position" name="position" 
                                           value="{{ old('position', $employee->position) }}" required>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="department" class="form-label">Departamento</label>
                                    <select class="form-select @error('department') is-invalid @enderror" 
                                            id="department" name="department" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="Administración" {{ old('department', $employee->department) == 'Administración' ? 'selected' : '' }}>Administración</option>
                                        <option value="Ventas" {{ old('department', $employee->department) == 'Ventas' ? 'selected' : '' }}>Ventas</option>
                                        <option value="Operaciones" {{ old('department', $employee->department) == 'Operaciones' ? 'selected' : '' }}>Operaciones</option>
                                        <option value="Cocina" {{ old('department', $employee->department) == 'Cocina' ? 'selected' : '' }}>Cocina</option>
                                        <option value="Servicio" {{ old('department', $employee->department) == 'Servicio' ? 'selected' : '' }}>Servicio</option>
                                        <option value="Limpieza" {{ old('department', $employee->department) == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                                        <option value="Contabilidad" {{ old('department', $employee->department) == 'Contabilidad' ? 'selected' : '' }}>Contabilidad</option>
                                    </select>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="hire_date" class="form-label">Fecha Ingreso</label>
                                    <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                           id="hire_date" name="hire_date" 
                                           value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" required>
                                    @error('hire_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="base_salary" class="form-label">Salario Base (COP)</label>
                                    <input type="number" class="form-control @error('base_salary') is-invalid @enderror" 
                                           id="base_salary" name="base_salary" 
                                           value="{{ old('base_salary', $employee->base_salary) }}" min="0" step="1000" required>
                                    @error('base_salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="transportation_allowance" class="form-label">Auxilio de Transporte (COP)</label>
                                    <input type="number" class="form-control @error('transportation_allowance') is-invalid @enderror" 
                                           id="transportation_allowance" name="transportation_allowance" 
                                           value="{{ old('transportation_allowance', $employee->transportation_allowance) }}" min="0" step="1000">
                                    @error('transportation_allowance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Estado</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $employee->status) == 'active' ? 'selected' : '' }}>Activo</option>
                                        <option value="inactive" {{ old('status', $employee->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                        <option value="suspended" {{ old('status', $employee->status) == 'suspended' ? 'selected' : '' }}>Suspendido</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3 text-info">Información de Contacto</h5>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', $employee->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" 
                                           value="{{ old('phone', $employee->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="emergency_contact" class="form-label">Contacto Emergencia</label>
                                    <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" 
                                           id="emergency_contact" name="emergency_contact" 
                                           value="{{ old('emergency_contact', $employee->emergency_contact) }}">
                                    @error('emergency_contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Dirección</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="2">{{ old('address', $employee->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-save"></i> Actualizar Empleado
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection