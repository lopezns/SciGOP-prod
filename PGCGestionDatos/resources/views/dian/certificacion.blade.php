@extends('layouts.cafe')

@section('title', 'DIAN - Certificación')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Certificación DIAN</h2>
            <p class="text-gray-600 mt-1">Certificados de ingresos y retenciones para empleados</p>
        </div>
        <a href="{{ route('dian.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Volver al Dashboard DIAN
        </a>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                    <span class="text-blue-600 text-2xl">📄</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900">Certificados Individuales</h3>
                    <p class="text-gray-600 text-sm mt-1">Generar por empleado</p>
                </div>
            </div>
            <div class="mt-4">
                <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg" onclick="openIndividualCertificate()">
                    Generar Individual
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                    <span class="text-green-600 text-2xl">📋</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900">Certificados Masivos</h3>
                    <p class="text-gray-600 text-sm mt-1">Todos los empleados</p>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('reports.dian.certificate.pdf', ['year' => date('Y')]) }}" target="_blank" 
                   class="block w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-center">
                    Generar Masivo
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-start">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                    <span class="text-purple-600 text-2xl">📊</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-semibold text-gray-900">Resumen Anual</h3>
                    <p class="text-gray-600 text-sm mt-1">Consolidado {{ date('Y') }}</p>
                </div>
            </div>
            <div class="mt-4">
                <button class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg" onclick="showAnnualSummary()">
                    Ver Resumen
                </button>
            </div>
        </div>
    </div>

    <!-- Certificados por Empleado -->
    <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Certificados por Empleado - {{ date('Y') }}</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @forelse($employeeCertificates as $cert)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-gray-600 font-medium">
                                    {{ substr($cert->employee->first_name, 0, 1) }}{{ substr($cert->employee->last_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $cert->employee->full_name }}</h4>
                                <p class="text-sm text-gray-600">{{ $cert->employee->document_type }}: {{ $cert->employee->document_number }}</p>
                            </div>
                        </div>
                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                            {{ $cert->payrolls_count }} nóminas
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                        <div>
                            <span class="text-gray-600">Total Ingresos:</span>
                            <p class="font-medium text-green-600">${{ number_format($cert->total_income, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Total Retenciones:</span>
                            <p class="font-medium text-red-600">${{ number_format($cert->total_withholdings, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Salud:</span>
                            <p class="font-medium">${{ number_format($cert->total_health, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Pensión:</span>
                            <p class="font-medium">${{ number_format($cert->total_pension, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    
                    <div class="flex space-x-2">
                        <a href="{{ route('reports.dian.certificate.pdf', ['employee_id' => $cert->employee->id, 'year' => date('Y')]) }}" 
                           target="_blank"
                           class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-center text-sm">
                            📄 Descargar PDF
                        </a>
                        <button onclick="previewCertificate({{ $cert->employee->id }})"
                                class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm">
                            👁️ Vista Previa
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-2 text-center py-8">
                    <span class="text-6xl">📄</span>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No hay certificados disponibles</h3>
                    <p class="text-gray-600">Los certificados se generan automáticamente cuando hay nóminas pagadas.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Estadísticas Anuales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-blue-600">💰</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Ingresos {{ date('Y') }}</p>
                    <p class="text-xl font-bold text-blue-600">${{ number_format($annualStats['total_income'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-red-600">📉</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Retenciones</p>
                    <p class="text-xl font-bold text-red-600">${{ number_format($annualStats['total_withholdings'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-green-600">⚕️</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Aportes Salud</p>
                    <p class="text-xl font-bold text-green-600">${{ number_format($annualStats['total_health'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-amber-200">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <span class="text-purple-600">🏦</span>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Aportes Pensión</p>
                    <p class="text-xl font-bold text-purple-600">${{ number_format($annualStats['total_pension'] ?? 0, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Histórico de Certificados -->
    <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Histórico de Certificados</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Año</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empleados</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Ingresos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Retenciones</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($historicalData as $year => $data)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $year }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $data['employees_count'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($data['total_income'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($data['total_withholdings'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('reports.dian.certificate.pdf', ['year' => $year]) }}" target="_blank"
                               class="text-indigo-600 hover:text-indigo-900 mr-3">Descargar</a>
                            <button onclick="showYearDetail({{ $year }})" class="text-gray-600 hover:text-gray-900">Ver Detalle</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No hay datos históricos disponibles
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for Individual Certificate -->
<div id="individualCertificateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Generar Certificado Individual</h3>
                
                <form id="individualCertificateForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Empleado</label>
                        <select name="employee_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Seleccionar empleado...</option>
                            @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->full_name }} - {{ $employee->document_number }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Año</label>
                        <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            @for($year = date('Y'); $year >= 2020; $year--)
                            <option value="{{ $year }}" {{ $year == date('Y') ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeIndividualCertificate()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Generar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openIndividualCertificate() {
    document.getElementById('individualCertificateModal').classList.remove('hidden');
}

function closeIndividualCertificate() {
    document.getElementById('individualCertificateModal').classList.add('hidden');
}

document.getElementById('individualCertificateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const employeeId = formData.get('employee_id');
    const year = formData.get('year');
    
    if (employeeId && year) {
        const url = `{{ route('reports.dian.certificate.pdf') }}?employee_id=${employeeId}&year=${year}`;
        window.open(url, '_blank');
        closeIndividualCertificate();
    }
});

function previewCertificate(employeeId) {
    const year = {{ date('Y') }};
    const url = `{{ route('reports.dian.certificate.pdf') }}?employee_id=${employeeId}&year=${year}`;
    window.open(url, '_blank');
}

function showAnnualSummary() {
    alert('Función de resumen anual en desarrollo. Por ahora, puede descargar el certificado masivo.');
}

function showYearDetail(year) {
    alert(`Ver detalle del año ${year} - Función en desarrollo`);
}

// Close modal when clicking outside
document.getElementById('individualCertificateModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeIndividualCertificate();
    }
});
</script>
@endsection