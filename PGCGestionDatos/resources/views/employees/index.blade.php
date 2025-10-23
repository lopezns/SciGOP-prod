@extends('layouts.cafe')

@section('title', 'Empleados')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Gestión de Empleados</h3>
                    <div>
                        <a href="{{ route('payroll.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-chart-pie"></i> Dashboard
                        </a>
                        <a href="{{ route('employees.create') }}" class="btn btn-dark">
                            <i class="fas fa-plus"></i> Nuevo Empleado
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="statusFilter" class="form-label">Estado:</label>
                            <select class="form-select" id="statusFilter">
                                <option value="">Todos</option>
                                <option value="active">Activo</option>
                                <option value="inactive">Inactivo</option>
                                <option value="suspended">Suspendido</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="departmentFilter" class="form-label">Departamento:</label>
                            <select class="form-select" id="departmentFilter">
                                <option value="">Todos</option>
                                <option value="Administración">Administración</option>
                                <option value="Ventas">Ventas</option>
                                <option value="Operaciones">Operaciones</option>
                                <option value="Cocina">Cocina</option>
                                <option value="Servicio">Servicio</option>
                                <option value="Limpieza">Limpieza</option>
                                <option value="Contabilidad">Contabilidad</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="searchFilter" class="form-label">Buscar:</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Nombre o código...">
                        </div>
                    </div>

                    <!-- Tabla de Empleados -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Código</th>
                                    <th>Empleado</th>
                                    <th>Documento</th>
                                    <th>Cargo</th>
                                    <th>Departamento</th>
                                    <th>Salario Base</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                <tr>
                                    <td>
                                        <strong>{{ $employee->employee_code }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-initial bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $employee->full_name }}</div>
                                                @if($employee->email)
                                                <small class="text-muted">{{ $employee->email }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $employee->document_type }} {{ $employee->document_number }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $employee->department }}</span>
                                    </td>
                                    <td>{{ $employee->formatted_base_salary }}</td>
                                    <td>
                                        <span class="badge bg-{{ $employee->status === 'active' ? 'success' : ($employee->status === 'inactive' ? 'secondary' : 'warning') }}">
                                            @switch($employee->status)
                                                @case('active') Activo @break
                                                @case('inactive') Inactivo @break
                                                @case('suspended') Suspendido @break
                                                @default {{ ucfirst($employee->status) }}
                                            @endswitch
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-outline-primary" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('payrolls.create', ['employee_id' => $employee->id]) }}" class="btn btn-sm btn-outline-success" title="Nueva Nómina">
                                                <i class="fas fa-plus"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <h5>No hay empleados registrados</h5>
                                            <p>Comience agregando su primer empleado al sistema.</p>
                                            <a href="{{ route('employees.create') }}" class="btn btn-dark">
                                                <i class="fas fa-plus"></i> Agregar Empleado
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($employees->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $employees->links() }}
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
                        <i class="fas fa-users fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $totalEmployees ?? 0 }}</h5>
                            <small>Total Empleados</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-check fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $activeEmployees ?? 0 }}</h5>
                            <small>Empleados Activos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-building fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $totalDepartments ?? 0 }}</h5>
                            <small>Departamentos</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-dollar-sign fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">{{ $averageSalary ?? 'N/A' }}</h5>
                            <small>Salario Promedio</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Filtros en tiempo real
document.getElementById('statusFilter').addEventListener('change', filterTable);
document.getElementById('departmentFilter').addEventListener('change', filterTable);
document.getElementById('searchFilter').addEventListener('keyup', filterTable);

function filterTable() {
    const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
    const departmentFilter = document.getElementById('departmentFilter').value.toLowerCase();
    const searchFilter = document.getElementById('searchFilter').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 8) return; // Skip empty state row

        const status = cells[6].textContent.toLowerCase();
        const department = cells[4].textContent.toLowerCase();
        const searchText = (cells[0].textContent + ' ' + cells[1].textContent).toLowerCase();

        const matchesStatus = !statusFilter || status.includes(statusFilter);
        const matchesDepartment = !departmentFilter || department.includes(departmentFilter);
        const matchesSearch = !searchFilter || searchText.includes(searchFilter);

        row.style.display = matchesStatus && matchesDepartment && matchesSearch ? '' : 'none';
    });
}
</script>
@endsection