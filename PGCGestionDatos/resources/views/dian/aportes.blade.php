@extends('layouts.cafe')

@section('title', 'DIAN - Aportes Parafiscales')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Aportes Parafiscales</h2>
            <p class="text-gray-600 mt-1">SENA, ICBF y Cajas de Compensación Familiar</p>
        </div>
        <a href="{{ route('dian.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Volver al Dashboard DIAN
        </a>
    </div>

    <!-- Resumen Mensual Actual -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-blue-600 text-2xl">🏭</span>
                </div>
                <div>
                    <h3 class="text-sm text-gray-600">SENA (2%)</h3>
                    <p class="text-2xl font-bold text-blue-600">${{ number_format($currentMonth['sena'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">{{ date('M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-green-600 text-2xl">👨‍👩‍👧‍👦</span>
                </div>
                <div>
                    <h3 class="text-sm text-gray-600">ICBF (3%)</h3>
                    <p class="text-2xl font-bold text-green-600">${{ number_format($currentMonth['icbf'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">{{ date('M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-purple-600 text-2xl">🏛️</span>
                </div>
                <div>
                    <h3 class="text-sm text-gray-600">Caja Compensación (4%)</h3>
                    <p class="text-2xl font-bold text-purple-600">${{ number_format($currentMonth['compensacion'] ?? 0, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">{{ date('M Y') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                    <span class="text-orange-600 text-2xl">💰</span>
                </div>
                <div>
                    <h3 class="text-sm text-gray-600">Total Aportes</h3>
                    <p class="text-2xl font-bold text-orange-600">${{ number_format(($currentMonth['sena'] ?? 0) + ($currentMonth['icbf'] ?? 0) + ($currentMonth['compensacion'] ?? 0), 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">9% sobre nómina</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalle de Cálculos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Base de Cálculo -->
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Base de Cálculo - {{ date('M Y') }}</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Nómina Base:</span>
                        <span class="font-medium">${{ number_format($calculationBase['total_payroll'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Empleados Activos:</span>
                        <span class="font-medium">{{ $calculationBase['active_employees'] ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Salario Promedio:</span>
                        <span class="font-medium">${{ number_format($calculationBase['average_salary'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <hr>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">Tarifas Vigentes</h4>
                        <div class="text-sm space-y-1">
                            <div class="flex justify-between">
                                <span>SENA:</span>
                                <span class="font-medium">2.0%</span>
                            </div>
                            <div class="flex justify-between">
                                <span>ICBF:</span>
                                <span class="font-medium">3.0%</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Caja de Compensación:</span>
                                <span class="font-medium">4.0%</span>
                            </div>
                            <div class="flex justify-between font-medium border-t pt-1 mt-1">
                                <span>Total:</span>
                                <span>9.0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fechas de Pago -->
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Calendario de Pagos</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($paymentCalendar as $month => $dates)
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h4 class="font-medium text-gray-900">{{ $month }}</h4>
                        <div class="text-sm text-gray-600 space-y-1">
                            <div class="flex justify-between">
                                <span>Liquidación:</span>
                                <span>{{ $dates['liquidation'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Presentación:</span>
                                <span class="font-medium text-red-600">{{ $dates['presentation'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Pago:</span>
                                <span class="font-medium text-green-600">{{ $dates['payment'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <span class="text-yellow-600 mr-2">⚠️</span>
                        <div class="text-sm">
                            <p class="font-medium text-yellow-800">Recordatorio:</p>
                            <p class="text-yellow-700">Los aportes parafiscales deben pagarse antes del día 15 del mes siguiente al periodo liquidado.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Aportes -->
    <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Histórico de Aportes Parafiscales</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Período</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Cálculo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SENA (2%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ICBF (3%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Caja (4%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($historicalContributions as $contribution)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $contribution->period }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($contribution->calculation_base, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($contribution->sena_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($contribution->icbf_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ${{ number_format($contribution->compensation_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ${{ number_format($contribution->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $contribution->status_color }}-100 text-{{ $contribution->status_color }}-800">
                                {{ $contribution->status_text }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            <button onclick="generateContributionReport('{{ $contribution->period }}')" class="text-indigo-600 hover:text-indigo-900">
                                📄 Reporte
                            </button>
                            @if($contribution->status === 'pending')
                            <button onclick="markAsPaid('{{ $contribution->id }}')" class="text-green-600 hover:text-green-900">
                                ✅ Marcar Pagado
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            <div class="py-8">
                                <span class="text-6xl">💼</span>
                                <h3 class="mt-4 text-lg font-medium text-gray-900">No hay aportes registrados</h3>
                                <p class="text-gray-600">Los aportes se calculan automáticamente cuando hay nóminas procesadas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Entidades y Contactos -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-medium text-blue-900">SENA</h3>
                <p class="text-sm text-blue-700">Servicio Nacional de Aprendizaje</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">Tarifa:</span>
                        <span class="font-medium">2% sobre nómina</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Código Entidad:</span>
                        <span class="font-medium">00013</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Sitio Web:</span>
                        <a href="https://www.sena.edu.co" target="_blank" class="text-blue-600 hover:underline">www.sena.edu.co</a>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Teléfono:</span>
                        <span class="font-medium">57(1) 5461500</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <h3 class="text-lg font-medium text-green-900">ICBF</h3>
                <p class="text-sm text-green-700">Instituto Colombiano de Bienestar Familiar</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">Tarifa:</span>
                        <span class="font-medium">3% sobre nómina</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Código Entidad:</span>
                        <span class="font-medium">00011</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Sitio Web:</span>
                        <a href="https://www.icbf.gov.co" target="_blank" class="text-green-600 hover:underline">www.icbf.gov.co</a>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Teléfono:</span>
                        <span class="font-medium">57(1) 4377630</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-50">
                <h3 class="text-lg font-medium text-purple-900">Caja de Compensación</h3>
                <p class="text-sm text-purple-700">Compensar</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div>
                        <span class="text-sm text-gray-600">Tarifa:</span>
                        <span class="font-medium">4% sobre nómina</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Código Entidad:</span>
                        <span class="font-medium">00022</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Sitio Web:</span>
                        <a href="https://www.compensar.com" target="_blank" class="text-purple-600 hover:underline">www.compensar.com</a>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Teléfono:</span>
                        <span class="font-medium">57(1) 5945555</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Acción Rápida</h3>
        </div>
        <div class="p-6">
            <div class="flex justify-center space-x-4">
                <button onclick="exportToPDF()" class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-lg text-center font-semibold flex items-center space-x-2">
                    <span>📄</span>
                    <span>Exportar a PDF</span>
                </button>
                <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-8 py-3 rounded-lg text-center font-semibold flex items-center space-x-2">
                    <span>🖨️</span>
                    <span>Imprimir</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function generateContributionReport(period) {
    alert(`Generando reporte de aportes para ${period}...`);
    // Aquí se implementaría la generación del reporte
}

function markAsPaid(contributionId) {
    if (confirm('¿Marcar este aporte como pagado?')) {
        alert(`Marcando aporte ${contributionId} como pagado...`);
        // Aquí se implementaría la actualización del estado
        location.reload();
    }
}

function generateCurrentMonthReport() {
    const url = `{{ route('reports.payroll.pdf') }}?month={{ date('Y-m') }}`;
    window.open(url, '_blank');
}

function generateAnnualReport() {
    const url = `{{ route('reports.payroll.pdf') }}?year={{ date('Y') }}`;
    window.open(url, '_blank');
}

function calculateNextMonth() {
    alert('Calculando proyección para el próximo mes...\n\nFuncionalidad en desarrollo.');
}

function exportToPDF() {
    // Generate PDF with current data
    window.print();
}
</script>
@endsection