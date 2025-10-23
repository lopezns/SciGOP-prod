@extends('layouts.cafe')

@section('title', 'DIAN - Sistema de Información Tributaria')

@section('content')
<div class="container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Sistema de Información Tributaria DIAN</h2>
                            <p class="mb-0">Gestión integral de obligaciones fiscales y parafiscales para cumplimiento tributario</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <i class="fas fa-file-invoice-dollar fa-4x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">{{ $stats['total_employees'] }}</h4>
                            <small>Empleados Activos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-invoice fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">{{ $stats['current_month_payrolls'] }}</h4>
                            <small>Nóminas del Mes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-receipt fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">COP {{ number_format($stats['total_income_tax'], 0, ',', '.') }}</h4>
                            <small>Retención en la Fuente</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hand-holding-usd fa-2x me-3"></i>
                        <div>
                            <h4 class="mb-0">COP {{ number_format($stats['total_contributions'], 0, ',', '.') }}</h4>
                            <small>Aportes Parafiscales</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DIAN Modules -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Declaraciones Tributarias
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Gestión de declaraciones de renta, IVA, retenciones en la fuente y otros formularios oficiales de la DIAN.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Formulario 350 - Renta Jurídicas</li>
                        <li><i class="fas fa-check text-success me-2"></i>Formulario 210 - Renta Naturales</li>
                        <li><i class="fas fa-check text-success me-2"></i>Información Exógena</li>
                        <li><i class="fas fa-check text-success me-2"></i>Seguimiento de Plazos</li>
                    </ul>
                    <a href="{{ route('dian.declaraciones') }}" class="btn btn-dark w-100">
                        <i class="fas fa-arrow-right me-2"></i>Gestionar Declaraciones
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Retenciones en la Fuente
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Control y seguimiento de retenciones en la fuente aplicadas a empleados, proveedores y terceros.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Retención por Salarios</li>
                        <li><i class="fas fa-check text-success me-2"></i>Retención por Honorarios</li>
                        <li><i class="fas fa-check text-success me-2"></i>Cálculo Automático</li>
                        <li><i class="fas fa-check text-success me-2"></i>Reportes Detallados</li>
                    </ul>
                    <a href="{{ route('dian.retenciones') }}" class="btn btn-dark w-100">
                        <i class="fas fa-arrow-right me-2"></i>Ver Retenciones
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-certificate me-2"></i>
                        Certificaciones
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Generación automática de certificados de ingresos, retenciones y demás documentos tributarios requeridos.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Certificados de Ingresos</li>
                        <li><i class="fas fa-check text-success me-2"></i>Certificados de Retenciones</li>
                        <li><i class="fas fa-check text-success me-2"></i>Generación Masiva</li>
                        <li><i class="fas fa-check text-success me-2"></i>Formato PDF</li>
                    </ul>
                    <a href="{{ route('dian.certificacion') }}" class="btn btn-dark w-100">
                        <i class="fas fa-arrow-right me-2"></i>Generar Certificados
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-hand-holding-usd me-2"></i>
                        Aportes Parafiscales
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Gestión integral de aportes al SENA, ICBF, Cajas de Compensación y ARL con cálculos automáticos.</p>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>SENA (2%)</li>
                        <li><i class="fas fa-check text-success me-2"></i>ICBF (3%)</li>
                        <li><i class="fas fa-check text-success me-2"></i>Caja de Compensación (4%)</li>
                        <li><i class="fas fa-check text-success me-2"></i>ARL (Variable)</li>
                    </ul>
                    <a href="{{ route('dian.aportes') }}" class="btn btn-dark w-100">
                        <i class="fas fa-arrow-right me-2"></i>Gestionar Aportes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('reports.dian.certificate.pdf') }}" target="_blank" class="btn btn-outline-dark w-100 mb-2">
                                <i class="fas fa-file-pdf me-2"></i>Certificado DIAN
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('reports.payroll.pdf') }}" target="_blank" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-file-invoice me-2"></i>Reporte Nómina
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('employees.index') }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-users me-2"></i>Ver Empleados
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('payrolls.index') }}" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-calculator me-2"></i>Ver Nóminas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection