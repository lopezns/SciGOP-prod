@extends('layouts.cafe')

@section('title', 'Nóminas')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Gestión de Nóminas</h3>
                    <div>
                        <a href="{{ route('payroll.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                        <a href="{{ route('payrolls.create') }}" class="btn btn-dark">
                            <i class="fas fa-plus"></i> Nueva Nómina
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Estado:</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Todos</option>
                                <option value="calculated">Calculada</option>
                                <option value="approved">Aprobada</option>
                                <option value="paid">Pagada</option>
                                <option value="cancelled">Cancelada</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="employeeFilter" class="form-label">Empleado:</label>
                            <select class="form-select" id="employeeFilter">
                                <option value="">Todos los empleados</option>
                                @foreach($employees ?? [] as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="periodFilter" class="form-label">Período:</label>
                            <input type="month" class="form-control" id="periodFilter">
                        </div>
                        <div class="col-md-3">
                            <label for="searchFilter" class="form-label">Buscar:</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Código o empleado...">
                        </div>
                    </div>

                    <!-- Tabla de Nóminas -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Empleado</th>
                                    <th>Período</th>
                                    <th>Tipo</th>
                                    <th>Salario Neto</th>
                                    <th>Estado</th>
                                    <th>Fecha Pago</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($payrolls as $payroll)
                                <tr>
                                    <td>
                                        <strong>{{ $payroll->payroll_code }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial bg-secondary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                                                {{ substr($payroll->employee->first_name, 0, 1) }}{{ substr($payroll->employee->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $payroll->employee->full_name }}</div>
                                                <small class="text-muted">{{ $payroll->employee->employee_code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $payroll->period_start->format('M Y') }}</div>
                                        <small class="text-muted">{{ $payroll->period_start->format('d/m') }} - {{ $payroll->period_end->format('d/m') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($payroll->payroll_type) }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ $payroll->formatted_net_salary }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $payroll->status === 'paid' ? 'success' : ($payroll->status === 'approved' ? 'warning' : ($payroll->status === 'calculated' ? 'secondary' : 'danger')) }}">
                                            @switch($payroll->status)
                                                @case('paid') Pagado @break
                                                @case('approved') Aprobado @break
                                                @case('calculated') Calculado @break
                                                @case('cancelled') Cancelado @break
                                                @default {{ ucfirst($payroll->status) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>{{ $payroll->payment_date->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('payrolls.show', $payroll) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($payroll->status !== 'paid')
                                            <a href="{{ route('payrolls.edit', $payroll) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            <a href="{{ route('reports.payroll.pdf', ['payroll_id' => $payroll->id]) }}" target="_blank" class="btn btn-sm btn-outline-info" title="PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                            @if($payroll->status === 'calculated')
                                            <form action="{{ route('payrolls.update', $payroll) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-outline-success" title="Aprobar" 
                                                        onclick="return confirm('¿Aprobar esta nómina?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            @endif
                                            @if($payroll->status === 'approved')
                                            <form action="{{ route('payrolls.update', $payroll) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="paid">
                                                <button type="submit" class="btn btn-sm btn-outline-primary" title="Marcar como Pagada" 
                                                        onclick="return confirm('¿Marcar como pagada?')">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                                            <h5>No hay nóminas registradas</h5>
                                            <p>Comience creando la primera nómina para sus empleados.</p>
                                            <a href="{{ route('payrolls.create') }}" class="btn btn-dark">
                                                <i class="fas fa-plus"></i> Nueva Nómina
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($payrolls->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $payrolls->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-invoice-dollar fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $totalPayrolls ?? 0 }}</h5>
                            <small>Total Nóminas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $paidPayrolls ?? 0 }}</h5>
                            <small>Nóminas Pagadas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-hourglass-half fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $pendingPayrolls ?? 0 }}</h5>
                            <small>Pendientes</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-dollar-sign fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $totalAmount ?? 'N/A' }}</h5>
                            <small>Total Pagado</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones masivas -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Acciones Rápidas</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('reports.payroll.pdf') }}" target="_blank" class="btn btn-outline-dark">
                            <i class="fas fa-file-pdf"></i> Reporte General
                        </a>
                        <a href="{{ route('reports.payroll.pdf', ['status' => 'calculated']) }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-calculator"></i> Nóminas Calculadas
                        </a>
                        <a href="{{ route('reports.payroll.pdf', ['status' => 'approved']) }}" target="_blank" class="btn btn-outline-warning">
                            <i class="fas fa-check"></i> Nóminas Aprobadas
                        </a>
                        <a href="{{ route('reports.payroll.pdf', ['status' => 'paid']) }}" target="_blank" class="btn btn-outline-success">
                            <i class="fas fa-money-bill-wave"></i> Nóminas Pagadas
                        </a>
                        <a href="{{ route('reports.dian.certificate.pdf') }}" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-certificate"></i> Certificado DIAN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filtros en tiempo real
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('employeeFilter').addEventListener('change', filterTable);
document.getElementById('periodFilter').addEventListener('change', filterTable);
document.getElementById('searchFilter').addEventListener('keyup', filterTable);

function filterTable() {
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const employeeFilter = document.getElementById('employeeFilter').value;
    const periodFilter = document.getElementById('periodFilter').value;
    const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 8) return; // Skip empty state row

        const status = cells[5].textContent.toLowerCase();
        const employee = cells[1].textContent.toLowerCase();
        const period = cells[2].textContent.toLowerCase();
        const searchText = (cells[0].textContent + ' ' + cells[1].textContent).toLowerCase();

        const matchesStatus = !statusFilter || status.includes(statusFilter);
        const matchesEmployee = !employeeFilter || employee.includes(employeeFilter.toLowerCase());
        const matchesPeriod = !periodFilter || period.includes(periodFilter);
        const matchesSearch = !searchFilter || searchText.includes(searchFilter);

        row.style.display = matchesStatus && matchesEmployee && matchesPeriod && matchesSearch ? '' : 'none';
    });
}
</script>
@endsection