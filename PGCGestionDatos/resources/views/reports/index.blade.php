@extends('layouts.cafe')

@section('title', 'Reportes')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Reportes</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshReports()">
                            <i class="bi bi-arrow-clockwise"></i> Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Reporte de Nómina -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-people-fill text-primary"></i> Reporte de Nómina
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Generar reporte detallado de nómina con cálculos DIAN, aportes patronales y deducciones.</p>
                    
                    <form action="{{ route('reports.payroll') }}" method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="payroll_start_date" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="payroll_start_date" name="start_date" 
                                       value="{{ date('Y-m-01') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="payroll_end_date" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="payroll_end_date" name="end_date" 
                                       value="{{ date('Y-m-t') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">Empleado (Opcional)</label>
                                <select class="form-select" id="employee_id" name="employee_id">
                                    <option value="">Todos los empleados</option>
                                    <!-- Se llenarán dinámicamente -->
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Departamento (Opcional)</label>
                                <select class="form-select" id="department" name="department">
                                    <option value="">Todos los departamentos</option>
                                    <!-- Se llenarán dinámicamente -->
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-file-earmark-pdf"></i> Generar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte de Ventas -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-cart-fill text-success"></i> Reporte de Ventas
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Análisis completo de ventas con productos más vendidos y tendencias.</p>
                    
                    <form action="{{ route('reports.sales') }}" method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sales_start_date" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control" id="sales_start_date" name="start_date" 
                                       value="{{ date('Y-m-01') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sales_end_date" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control" id="sales_end_date" name="end_date" 
                                       value="{{ date('Y-m-t') }}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-file-earmark-pdf"></i> Generar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reporte de Inventario -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-boxes text-warning"></i> Reporte de Inventario
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Estado actual del inventario con valorización y alertas de stock.</p>
                    
                    <form action="{{ route('reports.inventory') }}" method="POST" target="_blank">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-file-earmark-pdf"></i> Generar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Certificado DIAN -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-file-earmark-text text-info"></i> Certificado DIAN
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Certificado de ingresos y retenciones para empleados (DIAN).</p>
                    
                    <form action="{{ route('reports.dian.income') }}" method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="dian_employee_id" class="form-label">Empleado</label>
                                <select class="form-select" id="dian_employee_id" name="employee_id" required>
                                    <option value="">Seleccionar empleado</option>
                                    <!-- Se llenarán dinámicamente -->
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dian_year" class="form-label">Año</label>
                                <select class="form-select" id="dian_year" name="year" required>
                                    @for($year = date('Y'); $year >= 2020; $year--)
                                        <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-info">
                            <i class="bi bi-file-earmark-pdf"></i> Generar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Nómina Actual -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up"></i> Resumen Nómina Mes Actual
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row" id="payroll-summary">
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="text-center">
                                <h4 class="text-primary mb-1" id="total-payrolls">--</h4>
                                <small class="text-muted">Nóminas</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="text-center">
                                <h4 class="text-success mb-1" id="total-income">$ --</h4>
                                <small class="text-muted">Ingresos Totales</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="text-center">
                                <h4 class="text-danger mb-1" id="total-deductions">$ --</h4>
                                <small class="text-muted">Deducciones</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="text-center">
                                <h4 class="text-info mb-1" id="total-net-salary">$ --</h4>
                                <small class="text-muted">Salario Neto</small>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4 mb-3">
                            <div class="text-center">
                                <h4 class="text-warning mb-1" id="total-cost">$ --</h4>
                                <small class="text-muted">Costo Total</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadEmployeesData();
    loadPayrollSummary();
});

function loadEmployeesData() {
    fetch('{{ route("reports.data") }}?type=employees')
        .then(response => response.json())
        .then(data => {
            // Llenar select de empleados
            const employeeSelects = ['employee_id', 'dian_employee_id'];
            employeeSelects.forEach(selectId => {
                const select = document.getElementById(selectId);
                if (select) {
                    data.employees.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = `${employee.first_name} ${employee.last_name} - ${employee.document_number}`;
                        select.appendChild(option);
                    });
                }
            });

            // Llenar select de departamentos
            const departmentSelect = document.getElementById('department');
            if (departmentSelect && data.departments) {
                data.departments.forEach(dept => {
                    const option = document.createElement('option');
                    option.value = dept;
                    option.textContent = dept;
                    departmentSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading employees data:', error);
        });
}

function loadPayrollSummary() {
    fetch('{{ route("reports.data") }}?type=payroll_summary')
        .then(response => response.json())
        .then(data => {
            const summary = data.summary;
            if (summary) {
                document.getElementById('total-payrolls').textContent = summary.total_payrolls || 0;
                document.getElementById('total-income').textContent = formatCurrency(summary.total_income || 0);
                document.getElementById('total-deductions').textContent = formatCurrency(summary.total_deductions || 0);
                document.getElementById('total-net-salary').textContent = formatCurrency(summary.total_net_salary || 0);
                document.getElementById('total-cost').textContent = formatCurrency(summary.total_cost || 0);
            }
        })
        .catch(error => {
            console.error('Error loading payroll summary:', error);
        });
}

function formatCurrency(amount) {
    return '$ ' + new Intl.NumberFormat('es-CO').format(amount);
}

function refreshReports() {
    loadPayrollSummary();
}
</script>
@endpush
@endsection