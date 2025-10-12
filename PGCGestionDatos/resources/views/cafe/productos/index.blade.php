@extends('layouts.cafe')

@section('title', 'Productos')
@section('subtitle', 'Gestión de productos de la cafetería')

@section('content')
<div class="space-y-6">
    <!-- Header con botones de acción -->
    <div class="flex justify-between items-center">
        <div class="flex space-x-4">
            <!-- Búsqueda -->
            <form method="GET" class="flex space-x-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar productos..."
                       class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500 focus:border-transparent">
                
                <!-- Filtro de stock -->
                <select name="stock_filter" class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500">
                    <option value="">Todos los productos</option>
                    <option value="con_stock" {{ request('stock_filter') === 'con_stock' ? 'selected' : '' }}>Con stock</option>
                    <option value="bajo" {{ request('stock_filter') === 'bajo' ? 'selected' : '' }}>Stock bajo (≤10)</option>
                    <option value="sin_stock" {{ request('stock_filter') === 'sin_stock' ? 'selected' : '' }}>Sin stock</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-coffee-600 text-white rounded-lg hover:bg-coffee-700">
                    Filtrar
                </button>
                
                @if(request('search') || request('stock_filter'))
                    <a href="{{ route('cafe.productos.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>
        
        <a href="{{ route('cafe.productos.create') }}" class="btn btn-primary">
            ➕ Nuevo Producto
        </a>
    </div>

    <!-- Tabla de productos -->
    <div class="card overflow-hidden">
        @if($productos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cream-200">
                    <thead class="bg-cream-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Producto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Código
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Precios
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Stock
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Margen
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cream-100">
                        @foreach($productos as $producto)
                            <tr class="hover:bg-cream-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-coffee-900">{{ $producto->nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-coffee-600">{{ $producto->codigo }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-coffee-900">
                                        <div>Compra: ${{ number_format($producto->precio_compra, 0, '.', ',') }} COP</div>
                                        <div class="font-semibold">Venta: ${{ number_format($producto->precio_venta, 0, '.', ',') }} COP</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($producto->stock <= 0)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Sin stock
                                        </span>
                                    @elseif($producto->stock <= 10)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $producto->stock }} unidades
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $producto->stock }} unidades
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($producto->precio_compra > 0)
                                        @php
                                            $margen = (($producto->precio_venta - $producto->precio_compra) / $producto->precio_compra) * 100;
                                        @endphp
                                        <div class="text-sm text-coffee-900">{{ number_format($margen, 1) }}%</div>
                                    @else
                                        <div class="text-sm text-gray-500">-</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('cafe.productos.show', $producto) }}" class="text-coffee-600 hover:text-coffee-900">
                                        👁️
                                    </a>
                                    <a href="{{ route('cafe.productos.edit', $producto) }}" class="text-blue-600 hover:text-blue-900">
                                        ✏️
                                    </a>
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="openDeleteModal('{{ $producto->id }}', '{{ $producto->nombre }}', '{{ route('cafe.productos.destroy', $producto) }}')">
                                        🗑️
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-cream-200">
                {{ $productos->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-coffee-400 text-6xl mb-4">☕</div>
                <h3 class="text-lg font-medium text-coffee-900 mb-2">No hay productos</h3>
                <p class="text-coffee-600 mb-4">
                    @if(request('search') || request('stock_filter'))
                        No se encontraron productos que coincidan con los filtros aplicados.
                    @else
                        Comienza agregando tu primer producto a la cafetería.
                    @endif
                </p>
                <a href="{{ route('cafe.productos.create') }}" class="btn btn-primary">
                    ➕ Agregar Primer Producto
                </a>
            </div>
        @endif
    </div>

    <!-- Estadísticas rápidas -->
    @if($productos->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card text-center">
                <div class="text-2xl font-bold text-coffee-800">{{ $productos->total() }}</div>
                <div class="text-sm text-coffee-600">Total Productos</div>
            </div>
            <div class="card text-center">
                <div class="text-2xl font-bold text-green-600">
                    {{ $productos->where('stock', '>', 10)->count() }}
                </div>
                <div class="text-sm text-coffee-600">Con Stock Bueno</div>
            </div>
            <div class="card text-center">
                <div class="text-2xl font-bold text-yellow-600">
                    {{ $productos->whereBetween('stock', [1, 10])->count() }}
                </div>
                <div class="text-sm text-coffee-600">Stock Bajo</div>
            </div>
            <div class="card text-center">
                <div class="text-2xl font-bold text-red-600">
                    {{ $productos->where('stock', '<=', 0)->count() }}
                </div>
                <div class="text-sm text-coffee-600">Sin Stock</div>
            </div>
        </div>
    @endif
</div>

<!-- Modal de confirmación de eliminación -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center mb-4">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        ¿Eliminar producto?
                    </h3>
                    <p class="text-sm text-gray-500 mb-4">
                        Estás a punto de eliminar el producto <strong id="productName"></strong>. 
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex space-x-3 justify-end">
                    <button type="button" 
                            onclick="closeDeleteModal()" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-coffee-500">
                        Cancelar
                    </button>
                    <form id="deleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Sí, eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal(productId, productName, deleteUrl) {
    document.getElementById('productName').textContent = productName;
    document.getElementById('deleteForm').action = deleteUrl;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Cerrar modal al hacer clic fuera de él
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Cerrar modal con tecla Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection
