@extends('layouts.cafe')

@section('title', 'Reporte de Inventario')
@section('subtitle', 'Análisis detallado del stock y valoración')

@section('actions')
    <div class="flex space-x-2">
        <button class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
            <span class="mr-2">📊</span>
            Exportar Excel
        </button>
        <button class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
            <span class="mr-2">📄</span>
            Exportar PDF
        </button>
    </div>
@endsection

@section('content')
<!-- Resumen general -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 text-sm">📦</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Total Productos</p>
                <p class="text-2xl font-bold text-amber-800">24</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-green-600 text-sm">💰</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Valor Total</p>
                <p class="text-2xl font-bold text-amber-800">$12.450.000 COP</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <span class="text-red-600 text-sm">⚠️</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Stock Bajo</p>
                <p class="text-2xl font-bold text-amber-800">3</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <span class="text-yellow-600 text-sm">📈</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Productos Activos</p>
                <p class="text-2xl font-bold text-amber-800">21</p>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="mb-6 bg-white rounded-xl shadow-sm border border-orange-200 p-6">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-64">
            <label class="block text-sm font-medium text-amber-800 mb-2">Buscar producto</label>
            <input type="text" 
                   placeholder="Nombre o código del producto..." 
                   class="w-full px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Categoría</label>
            <select class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <option value="">Todas</option>
                <option value="bebidas-calientes">Bebidas Calientes</option>
                <option value="bebidas-frias">Bebidas Frías</option>
                <option value="postres">Postres</option>
                <option value="snacks">Snacks</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Estado Stock</label>
            <select class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <option value="">Todos</option>
                <option value="normal">Normal</option>
                <option value="bajo">Stock Bajo</option>
                <option value="agotado">Agotado</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors duration-200 font-medium">
                Filtrar
            </button>
        </div>
    </div>
</div>

<!-- Tabla de inventario -->
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6 border-b border-orange-100">
        <h2 class="text-lg font-semibold text-amber-800">Inventario Detallado</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-orange-200">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Stock Mínimo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Precio Compra</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Precio Venta</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Valor Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Última Actualización</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-orange-200">
                @php
                    $productos = [
                        ['id' => 1, 'nombre' => 'Café Americano', 'codigo' => 'CAF-001', 'stock' => 45, 'stock_min' => 20, 'precio_compra' => 2500, 'precio_venta' => 5000, 'categoria' => 'Bebidas Calientes'],
                        ['id' => 2, 'nombre' => 'Cappuccino', 'codigo' => 'CAP-002', 'stock' => 32, 'stock_min' => 15, 'precio_compra' => 3200, 'precio_venta' => 6500, 'categoria' => 'Bebidas Calientes'],
                        ['id' => 3, 'nombre' => 'Latte', 'codigo' => 'LAT-003', 'stock' => 28, 'stock_min' => 15, 'precio_compra' => 3500, 'precio_venta' => 7000, 'categoria' => 'Bebidas Calientes'],
                        ['id' => 4, 'nombre' => 'Espresso', 'codigo' => 'ESP-004', 'stock' => 55, 'stock_min' => 25, 'precio_compra' => 2000, 'precio_venta' => 4500, 'categoria' => 'Bebidas Calientes'],
                        ['id' => 5, 'nombre' => 'Mocha', 'codigo' => 'MOC-005', 'stock' => 8, 'stock_min' => 15, 'precio_compra' => 4000, 'precio_venta' => 8000, 'categoria' => 'Bebidas Calientes'],
                        ['id' => 6, 'nombre' => 'Frappé Vainilla', 'codigo' => 'FRA-006', 'stock' => 22, 'stock_min' => 10, 'precio_compra' => 3800, 'precio_venta' => 8500, 'categoria' => 'Bebidas Frías'],
                        ['id' => 7, 'nombre' => 'Smoothie Fresa', 'codigo' => 'SMO-007', 'stock' => 15, 'stock_min' => 8, 'precio_compra' => 4200, 'precio_venta' => 9000, 'categoria' => 'Bebidas Frías'],
                        ['id' => 8, 'nombre' => 'Cheesecake', 'codigo' => 'CHE-008', 'stock' => 12, 'stock_min' => 5, 'precio_compra' => 6500, 'precio_venta' => 14000, 'categoria' => 'Postres'],
                        ['id' => 9, 'nombre' => 'Muffin Chocolate', 'codigo' => 'MUF-009', 'stock' => 18, 'stock_min' => 10, 'precio_compra' => 2800, 'precio_venta' => 6500, 'categoria' => 'Postres'],
                        ['id' => 10, 'nombre' => 'Croissant', 'codigo' => 'CRO-010', 'stock' => 5, 'stock_min' => 15, 'precio_compra' => 1500, 'precio_venta' => 4000, 'categoria' => 'Snacks']
                    ];
                @endphp
                
                @foreach($productos as $producto)
                <tr class="hover:bg-orange-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center">
                                    <span class="text-amber-600 text-xs">☕</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-amber-900">{{ $producto['nombre'] }}</div>
                                <div class="text-sm text-amber-600">{{ $producto['codigo'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-amber-800">{{ $producto['stock'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-amber-700">{{ $producto['stock_min'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-amber-700">${{ number_format($producto['precio_compra'], 0, '.', ',') }} COP</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-amber-800">${{ number_format($producto['precio_venta'], 0, '.', ',') }} COP</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-green-600">${{ number_format($producto['stock'] * $producto['precio_compra'], 0, '.', ',') }} COP</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $estado = $producto['stock'] <= 0 ? 'agotado' : ($producto['stock'] <= $producto['stock_min'] ? 'bajo' : 'normal');
                            $estadoConfig = [
                                'normal' => ['bg-green-100 text-green-800', '✅ Normal'],
                                'bajo' => ['bg-yellow-100 text-yellow-800', '⚠️ Stock Bajo'],
                                'agotado' => ['bg-red-100 text-red-800', '❌ Agotado']
                            ];
                            $config = $estadoConfig[$estado];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                            {{ $config[1] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        <div>{{ date('d/m/Y', strtotime('-' . rand(0, 7) . ' days')) }}</div>
                        <div class="text-xs text-amber-600">{{ date('H:i', strtotime(rand(8, 17) . ':' . rand(0, 59) . ':00')) }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Análisis adicional -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Productos con stock bajo -->
    <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
        <div class="p-6 border-b border-orange-100">
            <h3 class="text-lg font-semibold text-amber-800">Productos con Stock Bajo</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php
                    $stockBajo = array_filter($productos, function($p) { return $p['stock'] <= $p['stock_min']; });
                @endphp
                @foreach(array_slice($stockBajo, 0, 5) as $producto)
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-amber-600 text-xs">⚠️</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-amber-900">{{ $producto['nombre'] }}</div>
                            <div class="text-xs text-amber-600">{{ $producto['codigo'] }}</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-red-600">{{ $producto['stock'] }} unidades</div>
                        <div class="text-xs text-amber-600">Mín: {{ $producto['stock_min'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top productos por valor -->
    <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
        <div class="p-6 border-b border-orange-100">
            <h3 class="text-lg font-semibold text-amber-800">Top Productos por Valor de Stock</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php
                    $topValor = collect($productos)->map(function($p) {
                        $p['valor_total'] = $p['stock'] * $p['precio_compra'];
                        return $p;
                    })->sortByDesc('valor_total')->take(5);
                @endphp
                @foreach($topValor as $producto)
                <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-amber-600 text-xs">💰</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-amber-900">{{ $producto['nombre'] }}</div>
                            <div class="text-xs text-amber-600">{{ $producto['stock'] }} unidades</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-green-600">${{ number_format($producto['valor_total'], 0, '.', ',') }} COP</div>
                        <div class="text-xs text-amber-600">${{ number_format($producto['precio_compra'], 0, '.', ',') }} COP c/u</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection