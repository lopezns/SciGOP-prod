@extends('layouts.cafe')

@section('title', 'Reportes')
@section('subtitle', 'Análisis y estadísticas de la cafetería')

@section('content')
<div class="space-y-6">
    <!-- Filtros de fecha -->
    <div class="card">
        <h3 class="text-lg font-semibold text-coffee-800 mb-4">Filtros de Reporte</h3>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-coffee-700 mb-1">Fecha Desde</label>
                <input type="date" 
                       name="fecha_desde" 
                       value="{{ request('fecha_desde', date('Y-m-01')) }}"
                       class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-coffee-700 mb-1">Fecha Hasta</label>
                <input type="date" 
                       name="fecha_hasta" 
                       value="{{ request('fecha_hasta', date('Y-m-d')) }}"
                       class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-coffee-700 mb-1">Tipo de Reporte</label>
                <select name="tipo" class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500">
                    <option value="ventas">Ventas</option>
                    <option value="productos">Productos</option>
                    <option value="clientes">Clientes</option>
                    <option value="inventario">Inventario</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full btn btn-primary">
                    📊 Generar Reporte
                </button>
            </div>
        </form>
    </div>

    <!-- Métricas principales -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card text-center">
            <div class="text-2xl font-bold text-green-600">$-</div>
            <div class="text-sm text-coffee-600">Ventas Totales</div>
        </div>
        <div class="card text-center">
            <div class="text-2xl font-bold text-blue-600">-</div>
            <div class="text-sm text-coffee-600">Productos Vendidos</div>
        </div>
        <div class="card text-center">
            <div class="text-2xl font-bold text-purple-600">-</div>
            <div class="text-sm text-coffee-600">Facturas Emitidas</div>
        </div>
        <div class="card text-center">
            <div class="text-2xl font-bold text-coffee-600">-</div>
            <div class="text-sm text-coffee-600">Clientes Activos</div>
        </div>
    </div>

    <!-- Reportes disponibles -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Reportes de ventas -->
        <div class="card">
            <h3 class="text-lg font-semibold text-coffee-800 mb-4">📊 Reportes de Ventas</h3>
            <div class="space-y-3">
                <button class="block w-full text-left p-3 bg-green-50 border border-green-200 rounded-lg hover:bg-green-100 transition-colors">
                    <div class="font-medium text-green-800">Ventas por Día</div>
                    <div class="text-sm text-green-600">Análisis diario de ventas</div>
                </button>
                <button class="block w-full text-left p-3 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <div class="font-medium text-blue-800">Productos Más Vendidos</div>
                    <div class="text-sm text-blue-600">Top de productos por cantidad</div>
                </button>
                <button class="block w-full text-left p-3 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors">
                    <div class="font-medium text-purple-800">Ingresos por Período</div>
                    <div class="text-sm text-purple-600">Comparativo de períodos</div>
                </button>
            </div>
        </div>

        <!-- Reportes de inventario -->
        <div class="card">
            <h3 class="text-lg font-semibold text-coffee-800 mb-4">📦 Reportes de Inventario</h3>
            <div class="space-y-3">
                <button class="block w-full text-left p-3 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors">
                    <div class="font-medium text-yellow-800">Stock Actual</div>
                    <div class="text-sm text-yellow-600">Estado actual del inventario</div>
                </button>
                <button class="block w-full text-left p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                    <div class="font-medium text-red-800">Productos con Stock Bajo</div>
                    <div class="text-sm text-red-600">Alertas de reabastecimiento</div>
                </button>
                <button class="block w-full text-left p-3 bg-coffee-50 border border-coffee-200 rounded-lg hover:bg-coffee-100 transition-colors">
                    <div class="font-medium text-coffee-800">Valorización de Inventario</div>
                    <div class="text-sm text-coffee-600">Valor total del stock</div>
                </button>
            </div>
        </div>
    </div>

    <!-- Estado de desarrollo -->
    <div class="card">
        <div class="text-center py-8">
            <div class="text-coffee-400 text-5xl mb-4">📈</div>
            <h3 class="text-lg font-medium text-coffee-900 mb-2">Sistema de Reportes en Desarrollo</h3>
            <p class="text-coffee-600 mb-4">
                Los reportes detallados estarán disponibles próximamente. 
                Mientras tanto, puedes revisar la información básica en el dashboard.
            </p>
            <a href="{{ route('cafe.dashboard') }}" class="btn btn-primary">
                🏠 Volver al Dashboard
            </a>
        </div>
    </div>
</div>
@endsection