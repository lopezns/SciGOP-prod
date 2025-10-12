@extends('layouts.cafe')

@section('title', 'Nuevo Cliente')
@section('subtitle', 'Registrar un nuevo cliente')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Estado de desarrollo -->
    <div class="card">
        <div class="text-center py-12">
            <div class="text-coffee-400 text-6xl mb-4">👤</div>
            <h3 class="text-lg font-medium text-coffee-900 mb-2">Registro de Clientes en Desarrollo</h3>
            <p class="text-coffee-600 mb-4">
                El formulario de registro de clientes estará disponible próximamente. 
                Incluirá campos para datos personales, información de contacto y preferencias.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('cafe.clientes.index') }}" class="btn btn-secondary">
                    ← Volver a Clientes
                </a>
                <a href="{{ route('cafe.ventas.create') }}" class="btn btn-primary">
                    🛒 Nueva Venta
                </a>
            </div>
        </div>
    </div>
    
    <!-- Preview del formulario -->
    <div class="card mt-6">
        <h3 class="text-lg font-semibold text-coffee-800 mb-4">Vista Previa del Formulario (Próximamente)</h3>
        <div class="bg-gray-50 p-6 rounded-lg">
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nombre *</label>
                        <div class="w-full p-2 bg-white border rounded text-gray-400">[Campo de texto]</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Apellido *</label>
                        <div class="w-full p-2 bg-white border rounded text-gray-400">[Campo de texto]</div>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                    <div class="w-full p-2 bg-white border rounded text-gray-400">[Campo de email]</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Teléfono</label>
                    <div class="w-full p-2 bg-white border rounded text-gray-400">[Campo de teléfono]</div>
                </div>
                
                <div class="flex justify-end space-x-4 pt-4">
                    <div class="px-4 py-2 bg-gray-200 rounded text-gray-600">Cancelar</div>
                    <div class="px-4 py-2 bg-coffee-500 text-white rounded">Guardar Cliente</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection