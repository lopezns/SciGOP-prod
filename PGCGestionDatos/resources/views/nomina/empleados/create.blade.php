@extends('layouts.cafe')

@section('title', 'Crear Empleado')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Nuevo Empleado</h2>
            <p class="text-gray-600 mt-1">Registrar información completa del empleado</p>
        </div>
        <a href="{{ route('payroll.empleados.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            ← Volver a Lista
        </a>
    </div>

    <!-- Formulario -->
    <form action="{{ route('payroll.empleados.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Información Personal -->
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-medium text-blue-900 flex items-center">
                    <span class="text-2xl mr-2">👤</span>
                    Información Personal
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombres *</label>
                        <input type="text" name="first_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ingrese los nombres">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Apellidos *</label>
                        <input type="text" name="last_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ingrese los apellidos">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento *</label>
                        <select name="document_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="TI">Tarjeta de Identidad</option>
                            <option value="PP">Pasaporte</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de Documento *</label>
                        <input type="text" name="document_number" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Sin puntos ni espacios">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico *</label>
                        <input type="email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="empleado@sciGOP.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input type="tel" name="phone"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: +57 300 123 4567">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                        <input type="date" name="birth_date"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Género</label>
                        <select name="gender"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            <option value="M">Masculino</option>
                            <option value="F">Femenino</option>
                            <option value="O">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                    <textarea name="address" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Dirección de residencia"></textarea>
                </div>
            </div>
        </div>

        <!-- Información Laboral -->
        <div class="bg-white rounded-xl shadow-sm border border-amber-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <h3 class="text-lg font-medium text-green-900 flex items-center">
                    <span class="text-2xl mr-2">💼</span>
                    Información Laboral
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cargo *</label>
                        <input type="text" name="position" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Ej: Barista, Cajero, Gerente">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Departamento *</label>
                        <select name="department" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            <option value="Operaciones">Operaciones</option>
                            <option value="Administración">Administración</option>
                            <option value="Finanzas">Finanzas</option>
                            <option value="Recursos Humanos">Recursos Humanos</option>
                            <option value="Ventas">Ventas</option>
                            <option value="Marketing">Marketing</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Ingreso *</label>
                        <input type="date" name="hire_date" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Contrato *</label>
                        <select name="contract_type" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Seleccionar...</option>
                            <option value="indefinido">Término Indefinido</option>
                            <option value="fijo">Término Fijo</option>
                            <option value="obra">Por Obra</option>
                            <option value="prestacion">Prestación de Servicios</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Salario Básico (COP) *</label>
                        <input type="number" name="base_salary" required min="0" step="1000"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="1300000">
                        <p class="text-xs text-gray-500 mt-1">Salario mínimo 2024: $1,300,000</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select name="active"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('payroll.empleados.index') }}" 
               class="px-6 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                Cancelar
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Crear Empleado
            </button>
        </div>
    </form>
</div>
@endsection
