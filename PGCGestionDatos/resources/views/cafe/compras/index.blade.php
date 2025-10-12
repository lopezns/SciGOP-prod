@extends('layouts.cafe')

@section('title', 'Compras')
@section('subtitle', 'Gestión de compras y pedidos')

@section('actions')
    <a href="{{ route('cafe.compras.create') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">➕</span>
        Nueva Compra
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6 border-b border-orange-100">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-amber-800">Lista de Compras</h2>
            <div class="flex items-center space-x-4">
                <select class="border border-orange-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <option>Todas las compras</option>
                    <option>Pendientes</option>
                    <option>Recibidas</option>
                    <option>Canceladas</option>
                </select>
                <div class="relative">
                    <input type="text" 
                           placeholder="Buscar compras..." 
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Orden #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Proveedor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-amber-800 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-orange-200">
                @forelse($compras ?? [] as $compra)
                <tr class="hover:bg-orange-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-amber-900">#{{ $compra->id ?? '00001' }}</div>
                        <div class="text-sm text-amber-600">OC-{{ date('Y') }}-{{ str_pad($compra->id ?? 1, 4, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center">
                                    <span class="text-amber-600 font-medium text-xs">🏪</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-amber-900">{{ $compra->proveedor->nombre ?? 'Distribuidora ABC' }}</div>
                                <div class="text-sm text-amber-600">{{ $compra->proveedor->tipo_documento ?? 'NIT' }}: {{ $compra->proveedor->numero_documento ?? '900123456-7' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        <div>{{ date('d/m/Y', strtotime($compra->fecha ?? '2024-01-01')) }}</div>
                        <div class="text-xs text-amber-600">{{ date('H:i', strtotime($compra->fecha ?? '2024-01-01 09:00:00')) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-800">
                        ${{ number_format(($compra->total ?? 1250000), 0, '.', ',') }} COP
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $estado = $compra->estado ?? 'pendiente';
                            $clases = [
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                'recibida' => 'bg-green-100 text-green-800',
                                'cancelada' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $clases[$estado] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($estado) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('cafe.compras.show', $compra->id ?? 1) }}" 
                               class="text-amber-600 hover:text-amber-900 transition-colors duration-200" 
                               title="Ver detalles">
                                👁️
                            </a>
                            @if(($compra->estado ?? 'pendiente') === 'pendiente')
                            <button type="button" 
                                    onclick="marcarRecibida({{ $compra->id ?? 1 }})"
                                    class="text-green-600 hover:text-green-900 transition-colors duration-200" 
                                    title="Marcar como recibida">
                                ✅
                            </button>
                            @endif
                            <a href="{{ route('cafe.compras.edit', $compra->id ?? 1) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                               title="Editar">
                                ✏️
                            </a>
                            <button type="button" 
                                    onclick="eliminarCompra({{ $compra->id ?? 1 }})"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200" 
                                    title="Eliminar">
                                🗑️
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-6xl mb-4">📦</span>
                            <h3 class="text-lg font-medium text-amber-800 mb-2">No hay compras registradas</h3>
                            <p class="text-amber-600 mb-4">Comienza creando tu primera orden de compra</p>
                            <a href="{{ route('cafe.compras.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <span class="mr-2">➕</span>
                                Nueva Compra
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
                    <span class="text-blue-600 text-sm">📦</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Total Compras</p>
                <p class="text-2xl font-bold text-amber-800">{{ $compras->total() ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <span class="text-yellow-600 text-sm">⏳</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Pendientes</p>
                <p class="text-2xl font-bold text-amber-800">{{ $pendientes ?? 0 }}</p>
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
                <p class="text-sm font-medium text-amber-600">Recibidas</p>
                <p class="text-2xl font-bold text-amber-800">{{ ($compras->total() - $pendientes) ?? 0 }}</p>
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
                <p class="text-sm font-medium text-amber-600">Total Este Mes</p>
                <p class="text-2xl font-bold text-amber-800">${{ number_format($comprasMes ?? 0, 0, ',', '.') }} COP</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function marcarRecibida(id) {
    if (confirm('¿Confirmar que se recibió esta compra?')) {
        fetch(`/cafe/compras/${id}/recibir`, {
            method: 'POST',
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
                alert('Error al marcar la compra como recibida');
            }
        });
    }
}

function eliminarCompra(id) {
    if (confirm('¿Estás seguro de que deseas eliminar esta compra?')) {
        fetch(`/cafe/compras/${id}`, {
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
                alert('Error al eliminar la compra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la compra');
        });
    }
}
</script>
@endpush