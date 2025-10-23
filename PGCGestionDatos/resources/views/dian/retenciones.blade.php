@extends('layouts.cafe')

@section('title', 'DIAN - Retenciones en la Fuente')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-amber-900">Retenciones en la Fuente</h2>
            <p class="text-amber-800 mt-1">Control y gestión de retenciones aplicadas a empleados y terceros</p>
        </div>
        <a href="{{ route('dian.index') }}" class="bg-amber-700 hover:bg-amber-800 text-white px-4 py-2 rounded-lg">
            ← Volver al Dashboard DIAN
        </a>
    </div>

    <!-- Monthly Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-blue-600 text-2xl">👥</span>
                </div>
                <div>
                    <h3 class="text-sm text-amber-800">Empleados con Retención</h3>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['total_employees'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-green-600 text-2xl">💰</span>
                </div>
                <div>
                    <h3 class="text-sm text-amber-800">Total Retenido (Mes)</h3>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($stats['total_withholdings'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-amber-700">COP</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-purple-600 text-2xl">📊</span>
                </div>
                <div>
                    <h3 class="text-sm text-amber-800">Tasa Promedio</h3>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['avg_rate'] ?? 0, 2) }}%</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-red-600 text-2xl">⏰</span>
                </div>
                <div>
                    <h3 class="text-sm text-amber-800">Pagos Pendientes</h3>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['pending_payments'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Withholding Rates Table -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-amber-900 text-white">
                <h3 class="text-lg font-medium flex items-center">
                    <span class="text-2xl mr-2">🧮</span>
                    Tarifas de Retención Vigentes
                </h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Concepto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tarifa</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">UVT</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($retentionRates ?? [] as $rate)
                            <tr>
                                <td class="px-4 py-3 font-medium text-amber-900">{{ $rate['concept'] ?? 'N/A' }}</td>
                                <td class="px-4 py-3"><span class="bg-amber-200 text-amber-900 px-2 py-1 rounded text-xs">{{ $rate['code'] ?? 'N/A' }}</span></td>
                                <td class="px-4 py-3 text-amber-900">{{ $rate['rate'] ?? '0' }}%</td>
                                <td class="px-4 py-3 text-amber-900">{{ $rate['uvt_threshold'] ?? 'N/A' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    <span class="text-2xl">📋</span>
                                    <p class="mt-2">No hay tarifas de retención configuradas</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-medium text-blue-900 flex items-center">
                    <span class="text-2xl mr-2">💰</span>
                    Información UVT 2024
                </h3>
            </div>
            <div class="p-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-900 mb-2 flex items-center">
                        <span class="mr-2">🪙</span>
                        Valor UVT 2024
                    </h4>
                    <p class="text-2xl font-bold text-blue-600 mb-2">COP 47,065</p>
                    <p class="text-sm text-blue-700">Resolución 000013 del 09 de Febrero de 2024</p>
                </div>
                
                <h4 class="font-semibold text-gray-900 mb-3">Rangos de Retención por Salarios:</h4>
                <div class="space-y-2">
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded">
                        <span>0 - 95 UVT</span>
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-medium">0%</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded">
                        <span>95 - 150 UVT</span>
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-sm font-medium">19%</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded">
                        <span>150 - 360 UVT</span>
                        <span class="bg-orange-100 text-orange-800 px-2 py-1 rounded text-sm font-medium">28%</span>
                    </div>
                    <div class="flex justify-between items-center p-3 border border-gray-200 rounded">
                        <span>Más de 360 UVT</span>
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm font-medium">33%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
function viewCertificate(employeeId) {
    // Redirect to certificate view
    window.location.href = `{{ route('dian.certificacion') }}?employee=${employeeId}`;
}

function generatePDF(employeeId) {
    // Generate PDF for specific employee
    window.open(`{{ route('reports.payroll.pdf') }}?employee=${employeeId}`, '_blank');
}

function exportToExcel() {
    // Export current withholdings to Excel
    alert('Función de exportación a Excel en desarrollo');
}

function calculateWithholdings() {
    alert('Calculadora de retenciones en desarrollo');
}

function generateFormats() {
    alert('Generador de formatos en desarrollo');
}

function uploadThirdParties() {
    alert('Carga de terceros en desarrollo');
}

function validatePayments() {
    alert('Validador de pagos en desarrollo');
}
</script>

@endsection