@extends('layouts.cafe')

@section('title', 'Dashboard Nómina')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <span class="text-2xl">👥</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Empleados Activos</p>
                    <p class="text-2xl font-bold text-gray-900" id="active-employees">--</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <span class="text-2xl">💰</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Nómina Mes</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-payroll">$ --</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <span class="text-2xl">📊</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Nóminas Procesadas</p>
                    <p class="text-2xl font-bold text-gray-900" id="processed-payrolls">--</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <span class="text-2xl">⏰</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pendientes</p>
                    <p class="text-2xl font-bold text-gray-900" id="pending-payrolls">--</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('payroll.empleados.create') }}" class="flex items-center justify-center p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                    <span class="text-2xl mr-3">👤</span>
                    <span class="font-medium text-blue-800">Nuevo Empleado</span>
                </a>
                
                <a href="{{ route('payroll.nominas.create') }}" class="flex items-center justify-center p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors duration-200">
                    <span class="text-2xl mr-3">💼</span>
                    <span class="font-medium text-green-800">Nueva Nómina</span>
                </a>
                
                <a href="{{ route('reports.index') }}" class="flex items-center justify-center p-4 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors duration-200">
                    <span class="text-2xl mr-3">📊</span>
                    <span class="font-medium text-purple-800">Reportes</span>
                </a>
                
                <button onclick="generateQuickPayrollReport()" class="flex items-center justify-center p-4 bg-orange-50 border border-orange-200 rounded-lg hover:bg-orange-100 transition-colors duration-200">
                    <span class="text-2xl mr-3">📄</span>
                    <span class="font-medium text-orange-800">PDF Rápido</span>
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información DIAN</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">SMLV 2024:</span>
                    <span class="font-medium text-gray-900">$ 1.300.000</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">Auxilio Transporte:</span>
                    <span class="font-medium text-gray-900">$ 140.606</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm text-gray-600">UVT 2024:</span>
                    <span class="font-medium text-gray-900">$ 42.412</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-gray-600">Límite Retención:</span>
                    <span class="font-medium text-gray-900">$ 4.915.000</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Actividad Reciente</h3>
            <a href="{{ route('payroll.nominas.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Ver todas</a>
        </div>
        
        <div class="space-y-4" id="recent-activity">
            <div class="text-center text-gray-500 py-8">
                <span class="text-4xl">📋</span>
                <p class="mt-2">Cargando actividad reciente...</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

function loadDashboardData() {
    // Simular carga de datos (en implementación real vendría de API)
    setTimeout(() => {
        document.getElementById('active-employees').textContent = '12';
        document.getElementById('total-payroll').textContent = '$ 15.600.000';
        document.getElementById('processed-payrolls').textContent = '8';
        document.getElementById('pending-payrolls').textContent = '4';
        
        // Cargar actividad reciente
        const recentActivity = document.getElementById('recent-activity');
        recentActivity.innerHTML = `
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 text-sm">✓</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Nómina calculada - Juan Pérez</p>
                        <p class="text-xs text-gray-500">Hace 2 horas</p>
                    </div>
                </div>
                <span class="text-sm text-gray-600">$ 1.300.000</span>
            </div>
            
            <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-b-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 text-sm">+</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Nuevo empleado agregado - María González</p>
                        <p class="text-xs text-gray-500">Hace 1 día</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center justify-between py-3">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 text-sm">📊</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Reporte generado - Nómina Octubre 2024</p>
                        <p class="text-xs text-gray-500">Hace 2 días</p>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

function generateQuickPayrollReport() {
    const startDate = new Date();
    startDate.setDate(1);
    const endDate = new Date();
    endDate.setMonth(endDate.getMonth() + 1, 0);
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("reports.payroll") }}';
    form.target = '_blank';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const startDateInput = document.createElement('input');
    startDateInput.type = 'hidden';
    startDateInput.name = 'start_date';
    startDateInput.value = startDate.toISOString().split('T')[0];
    
    const endDateInput = document.createElement('input');
    endDateInput.type = 'hidden';
    endDateInput.name = 'end_date';
    endDateInput.value = endDate.toISOString().split('T')[0];
    
    form.appendChild(csrfToken);
    form.appendChild(startDateInput);
    form.appendChild(endDateInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endpush
@endsection
