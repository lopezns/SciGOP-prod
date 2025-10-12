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
                                        <div>Compra: ${{ number_format($producto->precio_compra, 0, ',', '.') }}</div>
                                        <div class="font-semibold">Venta: ${{ number_format($producto->precio_venta, 0, ',', '.') }}</div>
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
                                    <form method="POST" action="{{ route('cafe.productos.destroy', $producto) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
                                            🗑️
                                        </button>
                                    </form>
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
@endsection