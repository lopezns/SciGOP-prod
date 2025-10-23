@extends('layouts.cafe')

@section('title', 'DIAN - Declaraciones Tributarias')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Declaraciones Tributarias</h2>
            <p class="text-gray-600 mt-1">Gestión y seguimiento de declaraciones de renta, IVA y demás obligaciones fiscales</p>
        </div>
        <a href="{{ route('dian.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Volver al Dashboard DIAN
        </a>
    </div>

    <!-- Current Period Information -->
    <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 flex items-center">
                <span class="text-2xl mr-2">📅</span>
                Información del Período Actual ({{ \Carbon\Carbon::create($currentYear ?? date('Y'), $currentMonth ?? date('n'))->format('F Y') }})
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl shadow-sm p-6 border border-blue-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                            <span class="text-blue-600 text-2xl">🧾</span>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-600">Retención en la Fuente</h3>
                            <p class="text-xl font-bold text-blue-600">${{ number_format($periodData['income_tax'] ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">COP</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-green-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                            <span class="text-green-600 text-2xl">👥</span>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-600">Aportes Empleados</h3>
                            <p class="text-xl font-bold text-green-600">${{ number_format($periodData['employee_contributions'] ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">COP</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-6 border border-yellow-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                            <span class="text-yellow-600 text-2xl">🏢</span>
                        </div>
                        <div>
                            <h3 class="text-sm text-gray-600">Aportes Patronales</h3>
                            <p class="text-xl font-bold text-yellow-600">${{ number_format($periodData['employer_contributions'] ?? 0, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500">COP</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Declaration Forms -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt me-2"></i>
                        Formularios de Declaración
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Formulario</th>
                                    <th>Fecha Límite</th>
                                    <th>Estado</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($declarations as $declaration)
                                <tr>
                                    <td>
                                        <strong>{{ $declaration['form'] }}</strong>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ $declaration['deadline'] }}
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = match($declaration['status']) {
                                                'Pendiente' => 'bg-warning',
                                                'Vencida' => 'bg-danger',
                                                'Próximo' => 'bg-info',
                                                'Completada' => 'bg-success',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $declaration['status'] }}</span>
                                    </td>
                                    <td>{{ $declaration['description'] }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" title="Ver Detalle">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" title="Generar">
                                                <i class="fas fa-file-export"></i>
                                            </button>
                                            @if($declaration['status'] !== 'Completada')
                                            <button class="btn btn-sm btn-outline-warning" title="Recordatorio">
                                                <i class="fas fa-bell"></i>
                                            </button>
                                            @endif
                                        </div>
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

    <!-- Tax Calendar -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        Calendario Tributario 2024
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Declaración de Renta Personas Jurídicas</strong>
                                <br><small class="text-muted">Formulario 350</small>
                            </div>
                            <span class="badge bg-warning">Oct 31, 2024</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Información Exógena</strong>
                                <br><small class="text-muted">Formato 1001, 1003, etc.</small>
                            </div>
                            <span class="badge bg-info">Mar 31, 2025</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Declaración Bimestral IVA</strong>
                                <br><small class="text-muted">Grandes contribuyentes</small>
                            </div>
                            <span class="badge bg-success">Mensual</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Retención en la Fuente</strong>
                                <br><small class="text-muted">Formulario 350</small>
                            </div>
                            <span class="badge bg-primary">Mensual</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información Importante
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Recordatorio:</strong> Las declaraciones presentadas después de la fecha límite están sujetas a sanciones e intereses moratorios.
                    </div>
                    
                    <h6>Documentos Requeridos:</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Estados financieros auditados</li>
                        <li><i class="fas fa-check text-success me-2"></i>Información de terceros (Exógena)</li>
                        <li><i class="fas fa-check text-success me-2"></i>Certificados de ingresos y retenciones</li>
                        <li><i class="fas fa-check text-success me-2"></i>Soportes de deducciones</li>
                    </ul>
                    
                    <h6 class="mt-3">Enlaces Útiles:</h6>
                    <div class="d-grid gap-2">
                        <a href="https://www.dian.gov.co" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>Portal DIAN
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-download me-2"></i>Formularios PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection