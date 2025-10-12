@extends('layouts.cafe')

@section('title', 'Dashboard de Reportes')
@section('subtitle', 'Análisis y estadísticas de la cafetería')

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
<!-- Filtros de fecha -->
<div class="mb-6 bg-white rounded-xl shadow-sm border border-orange-200 p-6">
    <h3 class="text-lg font-semibold text-amber-800 mb-4">Filtros de Reporte</h3>
    <form method="GET" class="flex flex-wrap items-center gap-4">
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Fecha Desde</label>
            <input type="date" 
                   name="fecha_desde" 
                   value="{{ request('fecha_desde', date('Y-m-01')) }}"
                   class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Fecha Hasta</label>
            <input type="date" 
                   name="fecha_hasta" 
                   value="{{ request('fecha_hasta', date('Y-m-d')) }}"
                   class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Tipo de Reporte</label>
            <select name="tipo" class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <option value="ventas">Ventas</option>
                <option value="productos">Productos</option>
                <option value="clientes">Clientes</option>
                <option value="inventario">Inventario</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="px-6 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
                Generar Reporte
            </button>
        </div>
    </form>
</div>

<!-- Métricas principales -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6 text-center">
        <div class="text-3xl font-bold text-green-600">$15.420.000 COP</div>
        <div class="text-sm font-medium text-amber-600 mt-1">Ventas Este Mes</div>
        <div class="text-xs text-green-600 mt-1">+12% vs mes anterior</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6 text-center">
        <div class="text-3xl font-bold text-blue-600">342</div>
        <div class="text-sm font-medium text-amber-600 mt-1">Productos Vendidos</div>
        <div class="text-xs text-blue-600 mt-1">+8% vs mes anterior</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6 text-center">
        <div class="text-3xl font-bold text-purple-600">89</div>
        <div class="text-sm font-medium text-amber-600 mt-1">Facturas Emitidas</div>
        <div class="text-xs text-purple-600 mt-1">+15% vs mes anterior</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6 text-center">
        <div class="text-3xl font-bold text-amber-600">67</div>
        <div class="text-sm font-medium text-amber-600 mt-1">Clientes Activos</div>
        <div class="text-xs text-amber-600 mt-1">+5% vs mes anterior</div>
    </div>
</div>

<!-- Gráficos y reportes detallados -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Ventas por día (tabla) -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
        <div class="p-6 border-b border-orange-100">
            <h3 class="text-lg font-semibold text-amber-800">📊 Ventas por Día (Últimos 7 días)</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @for($i = 6; $i >= 0; $i--)
                @php
                    $fecha = date('d/m/Y', strtotime('-' . $i . ' days'));
                    $dia = date('l', strtotime('-' . $i . ' days'));
                    $ventas = rand(8, 25);
                    $ingresos = rand(450, 1200);
                @endphp
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                    <div>
                        <div class="font-medium text-amber-900">{{ $fecha }}</div>
                        <div class="text-sm text-amber-600">{{ $dia }}</div>
                    </div>
                    <div class="text-center">
                        <div class="font-semibold text-blue-600">{{ $ventas }} ventas</div>
                        <div class="text-sm text-amber-700">${{ number_format($ingresos * 1000, 0, '.', ',') }} COP</div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </div>

    <!-- Productos más vendidos -->
    <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
        <div class="p-6 border-b border-orange-100">
            <h3 class="text-lg font-semibold text-amber-800">🏆 Top Productos</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @php
                    $topProductos = [
                        ['nombre' => 'Café Americano', 'ventas' => 45, 'ingresos' => 225000],
                        ['nombre' => 'Cappuccino', 'ventas' => 32, 'ingresos' => 208000],
                        ['nombre' => 'Latte', 'ventas' => 28, 'ingresos' => 196000],
                        ['nombre' => 'Espresso', 'ventas' => 24, 'ingresos' => 108000],
                        ['nombre' => 'Mocha', 'ventas' => 18, 'ingresos' => 144000]
                    ];
                @endphp
                @foreach($topProductos as $index => $producto)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-6 h-6 bg-amber-100 rounded-full flex items-center justify-center mr-3 text-xs font-bold text-amber-600">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div class="text-sm font-medium text-amber-900">{{ $producto['nombre'] }}</div>
                            <div class="text-xs text-amber-600">{{ $producto['ventas'] }} unidades</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold text-green-600">${{ number_format($producto['ingresos'], 0, '.', ',') }} COP</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Reportes disponibles -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Reportes de ventas -->
    <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
        <div class="p-6 border-b border-orange-100">
            <h3 class="text-lg font-semibold text-amber-800">📊 Reportes de Ventas</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <a href="{{ route('cafe.ventas.index') }}" class="block w-full text-left p-4 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-green-800">Historial de Ventas</div>
                            <div class="text-sm text-green-600">Todas las ventas realizadas</div>
                        </div>
                        <span class="text-green-600">→</span>
                    </div>
                </a>
                <a href="{{ route('cafe.facturas.index') }}" class="block w-full text-left p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-blue-800">Facturas Emitidas</div>
                            <div class="text-sm text-blue-600">Registro de facturas</div>
                        </div>
                        <span class="text-blue-600">→</span>
                    </div>
                </a>
                <div class="block w-full text-left p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <div class="font-medium text-purple-800">Ingresos por Período</div>
                    <div class="text-sm text-purple-600">Comparativo mensual: +12% crecimiento</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reportes de inventario -->
    <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
        <div class="p-6 border-b border-orange-100">
            <h3 class="text-lg font-semibold text-amber-800">📦 Reportes de Inventario</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <a href="{{ route('cafe.inventario.reporte') }}" class="block w-full text-left p-4 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-yellow-800">Reporte de Stock</div>
                            <div class="text-sm text-yellow-600">Estado actual del inventario</div>
                        </div>
                        <span class="text-yellow-600">→</span>
                    </div>
                </a>
                <a href="{{ route('cafe.inventario.movimientos') }}" class="block w-full text-left p-4 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="font-medium text-red-800">Movimientos de Stock</div>
                            <div class="text-sm text-red-600">Historial de entradas y salidas</div>
                        </div>
                        <span class="text-red-600">→</span>
                    </div>
                </a>
                <div class="block w-full text-left p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="font-medium text-green-800">Valorización Total</div>
                    <div class="text-sm text-green-600">Valor del inventario: $12.450.000 COP</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
