@extends('layouts.cafe')

@section('title', 'Dashboard')
@section('subtitle', 'Resumen general de tu cafetería')

@section('content')
<div class="space-y-8">
    <!-- Métricas principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Ventas de hoy -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Ventas de Hoy</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">${{ number_format($ventasHoy, 0, '.', ',') }} COP</p>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($ventasHoy > 0)
                            +{{ number_format(($ventasHoy / max($ventasMes, 1)) * 100, 1) }}% del mes
                        @else
                            Sin ventas hoy
                        @endif
                    </p>
                </div>
                <div class="p-4 bg-green-100 rounded-full">
                    <span class="text-2xl">💰</span>
                </div>
            </div>
        </div>

        <!-- Ventas del mes -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Ventas del Mes</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">${{ number_format($ventasMes, 0, '.', ',') }} COP</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $facturasMes }} facturas</p>
                </div>
                <div class="p-4 bg-blue-100 rounded-full">
                    <span class="text-2xl">📊</span>
                </div>
            </div>
        </div>

        <!-- Productos -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Productos</h3>
                    <p class="text-3xl font-bold text-coffee-600 mt-2">{{ $totalProductos }}</p>
                    @if($productosSinStock > 0)
                        <p class="text-sm text-red-500 mt-1">⚠️ {{ $productosSinStock }} sin stock</p>
                    @else
                        <p class="text-sm text-green-600 mt-1">✅ Inventario OK</p>
                    @endif
                </div>
                <div class="p-4 bg-coffee-100 rounded-full">
                    <span class="text-2xl">☕</span>
                </div>
            </div>
        </div>

        <!-- Clientes -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-600 uppercase tracking-wider">Clientes</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalClientes }}</p>
                    <p class="text-sm text-gray-500 mt-1">{{ $facturasMes }} compras este mes</p>
                </div>
                <div class="p-4 bg-purple-100 rounded-full">
                    <span class="text-2xl">👥</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráfico de ventas y alertas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Gráfico de ventas de los últimos 7 días -->
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Ventas de los últimos 7 días</h3>
                    <p class="text-sm text-gray-600">Tendencia de ventas diarias</p>
                </div>
                <div class="flex items-center space-x-2 text-sm text-gray-600">
                    <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                    <span>Ventas pagadas</span>
                </div>
            </div>
            <div class="h-80 flex items-end justify-between space-x-4 bg-gray-50 border border-gray-100 p-6 rounded-lg">
                @php
                    $maxVenta = collect($ventasUltimosDias)->max('total');
                    $maxVenta = $maxVenta > 0 ? $maxVenta : 1;
                @endphp
                @foreach($ventasUltimosDias as $index => $dia)
                    <div class="flex flex-col items-center flex-1 group cursor-pointer">
                        <!-- Tooltip -->
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 mb-2 bg-gray-900 text-white text-xs rounded-lg px-3 py-2 shadow-lg">
                            <div class="font-semibold">${{ number_format($dia['total'], 0, '.', ',') }} COP</div>
                            <div class="text-xs text-gray-300">{{ $dia['fecha'] }}</div>
                        </div>
                        
                        <!-- Barra -->
                        <div class="w-full flex items-end justify-center" style="height: 240px;">
                            <div class="w-full max-w-16 rounded-t-lg shadow-md transition-all duration-300 group-hover:shadow-lg border-2 border-transparent group-hover:border-blue-300
                                        @if($dia['total'] > 0)
                                            bg-gradient-to-t from-blue-600 via-blue-500 to-blue-400 hover:from-blue-700 hover:via-blue-600 hover:to-blue-500
                                        @else
                                            bg-gray-300 hover:bg-gray-400
                                        @endif" 
                                 style="height: {{ $dia['total'] > 0 ? max(30, ($dia['total'] / $maxVenta) * 220) : 30 }}px;">
                            </div>
                        </div>
                        
                        <!-- Fecha -->
                        <span class="text-sm text-gray-700 mt-3 font-semibold">{{ $dia['fecha'] }}</span>
                        
                        <!-- Valor -->
                        <span class="text-xs font-bold text-blue-600 mt-1">
                            @if($dia['total'] > 0)
                                ${{ number_format($dia['total']/1000, 0) }}K
                            @else
                                $0
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
            
            <!-- Resumen del gráfico -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm">
                    <div class="text-gray-600">
                        Promedio diario: <span class="font-semibold text-gray-800">${{ number_format(collect($ventasUltimosDias)->avg('total'), 0, '.', ',') }} COP</span>
                    </div>
                    <div class="text-gray-600">
                        Total 7 días: <span class="font-semibold text-blue-600">${{ number_format(collect($ventasUltimosDias)->sum('total'), 0, '.', ',') }} COP</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas de inventario -->
        <div class="card">
            <h3 class="text-lg font-semibold text-coffee-800 mb-4">Alertas de Inventario</h3>
            <div class="space-y-3">
                @if($productosSinStock > 0)
                    <div class="flex items-center p-3 bg-red-50 rounded-lg border border-red-200">
                        <span class="text-red-600 mr-2">❌</span>
                        <div>
                            <p class="text-sm font-medium text-red-800">Sin Stock</p>
                            <p class="text-xs text-red-600">{{ $productosSinStock }} productos</p>
                        </div>
                    </div>
                @endif

                @if($productosStockBajo > 0)
                    <div class="flex items-center p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                        <span class="text-yellow-600 mr-2">⚠️</span>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Stock Bajo</p>
                            <p class="text-xs text-yellow-600">{{ $productosStockBajo }} productos (≤10 unidades)</p>
                        </div>
                    </div>
                @endif

                @if($productosSinStock == 0 && $productosStockBajo == 0)
                    <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200">
                        <span class="text-green-600 mr-2">✅</span>
                        <div>
                            <p class="text-sm font-medium text-green-800">Todo en orden</p>
                            <p class="text-xs text-green-600">Inventario saludable</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-4 pt-4 border-t border-cream-200">
                <a href="{{ route('cafe.productos.index') }}" class="text-sm text-coffee-600 hover:text-coffee-800 font-medium">
                    Ver todos los productos →
                </a>
            </div>
        </div>
    </div>

    <!-- Productos populares y acciones rápidas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Productos más populares -->
        <div class="card">
            <h3 class="text-lg font-semibold text-coffee-800 mb-4">Productos con Mayor Stock</h3>
            <div class="space-y-3">
                @forelse($productosPopulares as $producto)
                    <div class="flex items-center justify-between py-2 border-b border-cream-100 last:border-b-0">
                        <div>
                            <p class="font-medium text-coffee-800">{{ $producto->nombre }}</p>
                            <p class="text-sm text-coffee-600">${{ number_format($producto->precio_venta, 0, '.', ',') }} COP</p>
                        </div>
                        <span class="bg-coffee-100 text-coffee-800 px-2 py-1 rounded-full text-sm font-medium">
                            {{ $producto->stock }} unid.
                        </span>
                    </div>
                @empty
                    <p class="text-coffee-600">No hay productos registrados.</p>
                @endforelse
            </div>
        </div>

        <!-- Acciones rápidas -->
        <div class="card">
            <h3 class="text-lg font-semibold text-coffee-800 mb-4">Acciones Rápidas</h3>
            <div class="grid grid-cols-2 gap-4">
                <a href="{{ route('cafe.ventas.create') }}" class="p-4 bg-coffee-100 rounded-lg hover:bg-coffee-200 transition-colors duration-200 text-center">
                    <div class="text-2xl mb-2">🛒</div>
                    <p class="font-medium text-coffee-800">Nueva Venta</p>
                </a>

                <a href="{{ route('cafe.productos.create') }}" class="p-4 bg-cream-200 rounded-lg hover:bg-cream-300 transition-colors duration-200 text-center">
                    <div class="text-2xl mb-2">➕</div>
                    <p class="font-medium text-coffee-800">Agregar Producto</p>
                </a>

                <a href="{{ route('cafe.clientes.create') }}" class="p-4 bg-coffee-100 rounded-lg hover:bg-coffee-200 transition-colors duration-200 text-center">
                    <div class="text-2xl mb-2">👤</div>
                    <p class="font-medium text-coffee-800">Nuevo Cliente</p>
                </a>

                <a href="{{ route('cafe.reportes.index') }}" class="p-4 bg-cream-200 rounded-lg hover:bg-cream-300 transition-colors duration-200 text-center">
                    <div class="text-2xl mb-2">📈</div>
                    <p class="font-medium text-coffee-800">Ver Reportes</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
