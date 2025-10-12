@extends('layouts.cafe')

@section('title', 'Control de Inventario')
@section('subtitle', 'Gestión de stock y productos')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card text-center">
            <div class="text-3xl font-bold text-coffee-800">{{ $totalProductos }}</div>
            <div class="text-sm text-coffee-600">Total Productos</div>
            <div class="mt-2">
                <a href="{{ route('cafe.productos.index') }}" class="text-xs text-blue-600 hover:underline">Ver todos →</a>
            </div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-green-600">{{ $totalProductos - $sinStock - $stockBajo }}</div>
            <div class="text-sm text-coffee-600">Stock Normal</div>
            <div class="text-xs text-gray-500 mt-1">Más de 10 unidades</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-yellow-600">{{ $stockBajo }}</div>
            <div class="text-sm text-coffee-600">Stock Bajo</div>
            <div class="text-xs text-gray-500 mt-1">10 unidades o menos</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-red-600">{{ $sinStock }}</div>
            <div class="text-sm text-coffee-600">Sin Stock</div>
            <div class="text-xs text-gray-500 mt-1">Agotados</div>
        </div>
    </div>

    <!-- Valor del inventario -->
    <div class="card">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-coffee-900">Valor del Inventario</h3>
                <div class="text-2xl font-bold text-coffee-800">${{ number_format($valorInventario, 0, '.', ',') }} COP</div>
                <p class="text-sm text-coffee-600">Valor total basado en precio de compra</p>
            </div>
            <div class="text-6xl">💰</div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('cafe.inventario.ajustar') }}" class="card hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">⚖️</span>
                </div>
                <div>
                    <h3 class="font-semibold text-coffee-900">Ajustar Inventario</h3>
                    <p class="text-sm text-coffee-600">Entradas, salidas y ajustes</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('cafe.inventario.movimientos') }}" class="card hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📈</span>
                </div>
                <div>
                    <h3 class="font-semibold text-coffee-900">Ver Movimientos</h3>
                    <p class="text-sm text-coffee-600">Historial de cambios</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('cafe.inventario.reporte') }}" class="card hover:shadow-md transition-shadow">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📋</span>
                </div>
                <div>
                    <h3 class="font-semibold text-coffee-900">Generar Reporte</h3>
                    <p class="text-sm text-coffee-600">Reporte completo</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Lista de productos con filtros -->
    <div class="card">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-coffee-900">Productos en Inventario</h3>
            <div class="flex space-x-2">
                <form method="GET" class="flex space-x-2">
                    <select name="stock_filter" class="px-3 py-2 border border-cream-300 rounded-lg text-sm focus:ring-2 focus:ring-coffee-500">
                        <option value="">Todos los productos</option>
                        <option value="con_stock" {{ request('stock_filter') === 'con_stock' ? 'selected' : '' }}>Con stock</option>
                        <option value="bajo" {{ request('stock_filter') === 'bajo' ? 'selected' : '' }}>Stock bajo</option>
                        <option value="sin_stock" {{ request('stock_filter') === 'sin_stock' ? 'selected' : '' }}>Sin stock</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-coffee-600 text-white rounded-lg hover:bg-coffee-700">
                        Filtrar
                    </button>
                </form>
            </div>
        </div>

        @if($productos->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cream-200">
                    <thead class="bg-cream-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Producto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Stock Actual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Precio Compra</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Valor Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase">Estado</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-coffee-600 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cream-100">
                        @foreach($productos as $producto)
                            <tr class="hover:bg-cream-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-coffee-900">{{ $producto->nombre }}</div>
                                    <div class="text-xs text-coffee-500">{{ $producto->codigo }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-coffee-900">{{ $producto->stock }} unidades</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-coffee-900">${{ number_format($producto->precio_compra, 0, '.', ',') }} COP</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-coffee-900">
                                        ${{ number_format($producto->stock * $producto->precio_compra, 0, '.', ',') }} COP
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($producto->stock <= 0)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Sin stock
                                        </span>
                                    @elseif($producto->stock <= 10)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Stock bajo
                                        </span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Normal
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('cafe.productos.edit', $producto) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                        ✏️ Editar
                                    </a>
                                    <button onclick="ajustarStock({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->stock }})" 
                                            class="text-green-600 hover:text-green-900">
                                        ⚖️ Ajustar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $productos->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-coffee-400 text-6xl mb-4">📦</div>
                <h3 class="text-lg font-medium text-coffee-900 mb-2">No hay productos</h3>
                <p class="text-coffee-600">Comienza agregando productos a tu inventario.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal de ajuste rápido -->
<div id="ajusteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Ajuste Rápido de Stock</h3>
                    <button onclick="cerrarAjusteModal()" class="text-gray-500 hover:text-gray-700">✖️</button>
                </div>
                
                <form action="{{ route('cafe.inventario.procesar-ajuste') }}" method="POST">
                    @csrf
                    <input type="hidden" id="producto_id" name="producto_id">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Producto:</label>
                        <div id="producto_nombre" class="text-sm text-gray-900"></div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Stock actual:</label>
                        <div id="stock_actual" class="text-sm text-gray-900 font-semibold"></div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de movimiento:</label>
                        <select name="tipo" id="tipo" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee-500" required>
                            <option value="entrada">Entrada (agregar stock)</option>
                            <option value="salida">Salida (reducir stock)</option>
                            <option value="ajuste">Ajuste (establecer cantidad exacta)</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="cantidad" class="block text-sm font-medium text-gray-700 mb-2">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" min="1" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee-500" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="motivo" class="block text-sm font-medium text-gray-700 mb-2">Motivo:</label>
                        <input type="text" name="motivo" id="motivo" placeholder="Ej: Compra, Daño, Corrección..." class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee-500" required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">Observaciones (opcional):</label>
                        <textarea name="observaciones" id="observaciones" rows="2" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-coffee-500" placeholder="Notas adicionales..."></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="cerrarAjusteModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-coffee-600 text-white rounded-lg hover:bg-coffee-700">
                            Procesar Ajuste
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function ajustarStock(productoId, nombre, stockActual) {
    document.getElementById('producto_id').value = productoId;
    document.getElementById('producto_nombre').textContent = nombre;
    document.getElementById('stock_actual').textContent = stockActual + ' unidades';
    document.getElementById('ajusteModal').classList.remove('hidden');
}

function cerrarAjusteModal() {
    document.getElementById('ajusteModal').classList.add('hidden');
    document.querySelector('form').reset();
}

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        cerrarAjusteModal();
    }
});

// Cerrar modal clickeando fuera
document.getElementById('ajusteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarAjusteModal();
    }
});
</script>
@endsection