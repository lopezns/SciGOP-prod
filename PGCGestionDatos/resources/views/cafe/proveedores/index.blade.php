@extends('layouts.cafe')

@section('title', 'Proveedores')
@section('subtitle', 'Gestión de proveedores')

@section('actions')
    <a href="{{ route('cafe.proveedores.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">➕</span>
        Nuevo Proveedor
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6 border-b border-orange-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-amber-800">Lista de Proveedores</h2>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" 
                           placeholder="Buscar proveedores..." 
                           class="pl-10 pr-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-400">🔍</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-orange-200">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider w-1/4">Proveedor</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider w-1/5">Contacto</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider w-1/4">Email</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider w-1/6">Estado</th>
                    <th class="px-3 py-3 text-center text-xs font-medium text-amber-800 uppercase tracking-wider w-1/6">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-orange-200">
                @forelse($proveedores ?? [] as $proveedor)
                <tr class="hover:bg-orange-50 transition-colors duration-200">
                    <td class="px-4 py-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center">
                                    <span class="text-amber-600 font-medium text-xs">🏪</span>
                                </div>
                            </div>
                            <div class="ml-3 min-w-0 flex-1">
                                <div class="text-sm font-medium text-amber-900 truncate" title="{{ $proveedor->nombre ?? 'Proveedor Ejemplo' }}">
                                    {{ Str::limit($proveedor->nombre ?? 'Proveedor Ejemplo', 25) }}
                                </div>
                                <div class="text-xs text-amber-600">
                                    {{ strtoupper($proveedor->tipo_documento ?? 'NIT') }}: {{ $proveedor->numero_documento ?? '900123456-7' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-4">
                        <div class="text-sm text-amber-700 truncate" title="{{ $proveedor->contacto ?? 'Juan Pérez' }}">
                            {{ Str::limit($proveedor->contacto ?? 'Juan Pérez', 20) }}
                        </div>
                        <div class="text-xs text-amber-500 truncate" title="{{ $proveedor->telefono ?? '+57 999 123 456' }}">
                            {{ $proveedor->telefono ?? '+57 999 123 456' }}
                        </div>
                    </td>
                    <td class="px-3 py-4">
                        <div class="text-sm text-amber-700 truncate" title="{{ $proveedor->email ?? 'contacto@proveedor.com' }}">
                            {{ Str::limit($proveedor->email ?? 'contacto@proveedor.com', 25) }}
                        </div>
                    </td>
                    <td class="px-3 py-4">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ ($proveedor->activo ?? true) ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ($proveedor->activo ?? true) ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-3 py-4 text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1">
                            <a href="{{ route('cafe.proveedores.show', $proveedor->id ?? 1) }}" 
                               class="text-amber-600 hover:text-amber-900 transition-colors duration-200 p-1" 
                               title="Ver detalles">
                                👁️
                            </a>
                            <a href="{{ route('cafe.proveedores.edit', $proveedor->id ?? 1) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200 p-1" 
                               title="Editar">
                                ✏️
                            </a>
                            <button type="button" 
                                    onclick="eliminarProveedor({{ $proveedor->id ?? 1 }})"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200 p-1" 
                                    title="Eliminar">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-6xl mb-4">🏪</span>
                            <h3 class="text-lg font-medium text-amber-800 mb-2">No hay proveedores registrados</h3>
                            <p class="text-amber-600 mb-4">Comienza agregando tu primer proveedor</p>
                            <a href="{{ route('cafe.proveedores.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <span class="mr-2">➕</span>
                                Agregar Proveedor
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 text-sm">🏪</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Total Proveedores</p>
                <p class="text-2xl font-bold text-amber-800">{{ $stats['total_proveedores'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-green-600 text-sm">✅</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Activos</p>
                <p class="text-2xl font-bold text-amber-800">{{ $stats['proveedores_activos'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <span class="text-yellow-600 text-sm">📦</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Compras Este Mes</p>
                <p class="text-2xl font-bold text-amber-800">{{ $stats['compras_mes_actual'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-purple-600 text-sm">💰</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Total Gastado</p>
                <p class="text-2xl font-bold text-amber-800">${{ number_format($stats['total_gastado_mes'] ?? 0, 0, ',', '.') }} COP</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function eliminarProveedor(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este proveedor?')) {
        fetch(`/cafe/proveedores/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error al eliminar el proveedor');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el proveedor');
        });
    }
}
</script>
@endpush