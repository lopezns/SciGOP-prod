@extends('layouts.cafe')

@section('title', 'Crear Nómina')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Nueva Nómina</h2>
            <p class="text-gray-600 mt-1">Procesar nómina para empleados activos</p>
        </div>
        <a href="{{ route('payroll.nominas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Volver a Lista
        </a>
    </div>

    <!-- Formulario -->
    <form action="{{ route('payroll.nominas.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Información del Período -->
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <h3 class="text-lg font-medium text-green-900 flex items-center">
                    <span class="text-2xl mr-2">📅</span>
                    Período de Nómina
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Nómina *</label>
                        <select name="payroll_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            <option value="regular">Nómina Regular</option>
                            <option value="extra">Nómina Extraordinaria</option>
                            <option value="bonus">Prima/Bonus</option>
                            <option value="vacation">Liquidación Vacaciones</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Código de Nómina</label>
                        <input type="text" name="payroll_code" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="Ej: NOM-{{ date('Y-m') }}-001" value="NOM-{{ date('Y-m') }}-001">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Inicio del Período *</label>
                        <input type="date" name="period_start" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ date('Y-m-01') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Fin del Período *</label>
                        <input type="date" name="period_end" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ date('Y-m-t') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Pago *</label>
                        <input type="date" name="payment_date" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               value="{{ date('Y-m-d', strtotime('last day of this month')) }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="status"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="draft">Borrador</option>
                            <option value="calculated">Calculada</option>
                            <option value="approved">Aprobada</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del Período</label>
                    <textarea name="description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                              placeholder="Ej: Nómina correspondiente al mes de {{ date('F Y') }}">Nómina correspondiente al mes de {{ date('F Y') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Selección de Empleados -->
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-medium text-blue-900 flex items-center">
                    <span class="text-2xl mr-2">👥</span>
                    Empleados a Incluir
                </h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="flex items-center space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="employee_selection" value="all" checked
                                   class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2">Todos los empleados activos</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="employee_selection" value="selected"
                                   class="text-blue-600 focus:ring-blue-500">
                            <span class="ml-2">Empleados seleccionados</span>
                        </label>
                    </div>
                </div>

                <!-- Lista de empleados (se mostrará cuando se seleccione "empleados seleccionados") -->
                <div id="employeeList" class="hidden">
                    <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-lg">
                        <div class="p-4">
                            <p class="text-sm text-gray-600 mb-3">Seleccione los empleados a incluir en esta nómina:</p>
                            <!-- Aquí se cargarán los empleados dinámicamente o se pueden precargar -->
                            <div class="space-y-2">
                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" name="selected_employees[]" value="1"
                                           class="text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3">
                                        <span class="font-medium">Juan Pérez</span>
                                        <span class="text-gray-500 text-sm">- Barista</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                    <input type="checkbox" name="selected_employees[]" value="2"
                                           class="text-blue-600 focus:ring-blue-500">
                                    <div class="ml-3">
                                        <span class="font-medium">María García</span>
                                        <span class="text-gray-500 text-sm">- Cajera</span>
                                    </div>
                                </label>
                                <!-- Más empleados... -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex">
                        <span class="text-yellow-600 mr-2">⚠️</span>
                        <div class="text-sm">
                            <p class="font-medium text-yellow-800">Nota importante:</p>
                            <p class="text-yellow-700">La nómina se calculará automáticamente basada en los salarios base, deducciones, y aportes legales vigentes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Configuración de Cálculos -->
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-50">
                <h3 class="text-lg font-medium text-purple-900 flex items-center">
                    <span class="text-2xl mr-2">🧮</span>
                    Configuración de Cálculos
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center">
                        <input type="checkbox" name="calculate_overtime" value="1" checked
                               class="text-purple-600 focus:ring-purple-500">
                        <label class="ml-2 text-sm text-gray-700">Calcular horas extras automáticamente</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="apply_deductions" value="1" checked
                               class="text-purple-600 focus:ring-purple-500">
                        <label class="ml-2 text-sm text-gray-700">Aplicar deducciones legales (Salud, Pensión)</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="calculate_benefits" value="1" checked
                               class="text-purple-600 focus:ring-purple-500">
                        <label class="ml-2 text-sm text-gray-700">Calcular auxilio de transporte</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="withhold_taxes" value="1" checked
                               class="text-purple-600 focus:ring-purple-500">
                        <label class="ml-2 text-sm text-gray-700">Aplicar retención en la fuente</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('payroll.nominas.index') }}" 
               class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Cancelar
            </a>
            <button type="submit" name="action" value="draft"
                    class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Guardar como Borrador
            </button>
            <button type="submit" name="action" value="calculate"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Calcular Nómina
            </button>
        </div>
    </form>
</div>

<script>
// Mostrar/ocultar lista de empleados según selección
document.querySelectorAll('input[name="employee_selection"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const employeeList = document.getElementById('employeeList');
        if (this.value === 'selected') {
            employeeList.classList.remove('hidden');
        } else {
            employeeList.classList.add('hidden');
        }
    });
});

// Generar código automático basado en el tipo y fecha
document.querySelector('select[name="payroll_type"]').addEventListener('change', function() {
    const type = this.value;
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    
    let prefix = '';
    switch(type) {
        case 'regular': prefix = 'NOM'; break;
        case 'extra': prefix = 'EXT'; break;
        case 'bonus': prefix = 'BON'; break;
        case 'vacation': prefix = 'VAC'; break;
        default: prefix = 'NOM';
    }
    
    document.querySelector('input[name="payroll_code"]').value = `${prefix}-${year}-${month}-001`;
});
</script>
@endsection
