@extends('layouts.cafe')

@section('title', 'Nóminas')

@section('content')
<style>
.filtros-card {
    background-color: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    border: 2px solid #fcd34d;
    margin-bottom: 1.5rem;
}

.filtros-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1.5rem;
}

.filtros-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: #78350f;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-limpiar-header {
    font-size: 0.875rem;
    color: #b45309;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    cursor: pointer;
    background: none;
    border: none;
    text-decoration: none;
}

.btn-limpiar-header:hover {
    color: #78350f;
}

.filtros-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.filtro-group {
    display: flex;
    flex-direction: column;
}

.filtro-label {
    display: block;
    font-size: 0.875rem;
    font-weight: 500;
    color: #78350f;
    margin-bottom: 0.5rem;
}

.filtro-input, .filtro-select {
    width: 100%;
    padding: 0.625rem 1rem;
    border: 2px solid #fcd34d;
    border-radius: 0.5rem;
    background-color: white;
    color: #111827;
    font-size: 0.875rem;
}

.filtro-input:focus, .filtro-select:focus {
    outline: none;
    border-color: #f59e0b;
    box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
}

.search-wrapper {
    position: relative;
}

.search-input {
    padding-left: 2.5rem;
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 0.75rem;
    width: 1.25rem;
    height: 1.25rem;
    color: #9ca3af;
    pointer-events: none;
}

.filtros-actions {
    grid-column: span 4;
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1rem;
}

.btn-limpiar {
    padding: 0.625rem 1.5rem;
    border: 2px solid #fcd34d;
    color: #78350f;
    border-radius: 0.5rem;
    background-color: white;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s;
}

.btn-limpiar:hover {
    background-color: #fef3c7;
}

.btn-aplicar {
    padding: 0.625rem 1.5rem;
    background-color: #b45309;
    color: white;
    border-radius: 0.5rem;
    font-weight: 500;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    border: none;
    font-size: 0.875rem;
    transition: all 0.2s;
}

.btn-aplicar:hover {
    background-color: #92400e;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

@media (max-width: 1024px) {
    .filtros-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .filtros-actions {
        grid-column: span 2;
    }
}

@media (max-width: 640px) {
    .filtros-grid {
        grid-template-columns: 1fr;
    }
    .filtros-actions {
        grid-column: span 1;
        flex-direction: column;
    }
    .btn-limpiar, .btn-aplicar {
        width: 100%;
        justify-content: center;
    }
}
</style>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-amber-900">Gestión de Nóminas</h2>
            <p class="text-amber-700 mt-1 text-sm">Administra y consulta todas las nóminas del sistema</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('payroll.dashboard') }}" class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-2.5 rounded-lg flex items-center space-x-2 transition-all shadow-sm hover:shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('payroll.nominas.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg flex items-center space-x-2 transition-all shadow-sm hover:shadow-md font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>Nueva Nómina</span>
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filtros-card">
        <div class="filtros-header">
            <h3 class="filtros-title">
                <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filtros de Búsqueda
            </h3>
            <button onclick="resetFilters()" class="btn-limpiar-header">
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Limpiar</span>
            </button>
        </div>
        
        <form method="GET" action="{{ route('payroll.nominas.index') }}" class="filtros-grid">
            <!-- Estado -->
            <div class="filtro-group">
                <label class="filtro-label">Estado</label>
                <select name="status" class="filtro-select">
                    <option value="">Todos</option>
                    <option value="draft">Borrador</option>
                    <option value="calculated">Calculada</option>
                    <option value="approved">Aprobada</option>
                    <option value="paid">Pagada</option>
                </select>
            </div>
            
            <!-- Empleado -->
            <div class="filtro-group">
                <label class="filtro-label">Empleado</label>
                <select name="employee_id" class="filtro-select">
                    <option value="">Todos los empleados</option>
                    @if(isset($employees))
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            
            <!-- Período -->
            <div class="filtro-group">
                <label class="filtro-label">Período</label>
                <input type="month" name="period" class="filtro-input">
            </div>
            
            <!-- Búsqueda -->
            <div class="filtro-group">
                <label class="filtro-label">Búsqueda</label>
                <div class="search-wrapper">
                    <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" placeholder="Código o empleado..." class="filtro-input search-input">
                </div>
            </div>
            
            <!-- Botones -->
            <div class="filtros-actions">
                <button type="button" onclick="resetFilters()" class="btn-limpiar">
                    Limpiar Filtros
                </button>
                <button type="submit" class="btn-aplicar">
                    <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    <span>Aplicar Filtros</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Payrolls Table -->
    <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lista de Nóminas</h3>
        </div>

        @if($payrolls->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($payrolls as $payroll)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $payroll->payroll_code }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payroll->employee->full_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payroll->period_description ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst($payroll->payroll_type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ $payroll->formatted_net_salary ?? '0' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $payroll->status_color ?? 'gray' }}-100 text-{{ $payroll->status_color ?? 'gray' }}-800">
                                {{ $payroll->status_text ?? 'Desconocido' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <a href="{{ route('payroll.nominas.show', $payroll) }}" 
                               class="text-blue-600 hover:text-blue-900">Ver</a>
                            @if($payroll->status !== 'paid')
                            <a href="{{ route('payroll.nominas.edit', $payroll) }}" 
                               class="text-indigo-600 hover:text-indigo-900">Editar</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $payrolls->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-500">
                <span class="text-6xl">💼</span>
                <p class="mt-4 text-lg">No hay nóminas registradas</p>
                <p class="text-sm">Comienza creando tu primera nómina</p>
                <a href="{{ route('payroll.nominas.create') }}" 
                   class="mt-4 inline-block bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Crear Primera Nómina
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function resetFilters() {
    // Reset all form inputs
    document.querySelector('select[name="status"]').value = '';
    document.querySelector('select[name="employee_id"]').value = '';
    document.querySelector('input[name="period"]').value = '';
    document.querySelector('input[name="search"]').value = '';
    
    // Reload page without query params
    window.location.href = '{{ route('payroll.nominas.index') }}';
}
</script>
@endsection
