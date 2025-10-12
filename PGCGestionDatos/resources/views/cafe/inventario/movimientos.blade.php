@extends('layouts.cafe')

@section('title', 'Movimientos de Inventario')
@section('subtitle', 'Historial de entradas y salidas de productos')

@section('actions')
    <a href="{{ route('cafe.inventario.ajustar') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">⚖️</span>
        Ajustar Stock
    </a>
@endsection

@section('content')
<!-- Filtros -->
<div class="mb-6 bg-white rounded-xl shadow-sm border border-orange-200 p-6">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-64">
            <label class="block text-sm font-medium text-amber-800 mb-2">Buscar por producto</label>
            <input type="text" 
                   placeholder="Nombre del producto..." 
                   class="w-full px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Tipo de movimiento</label>
            <select class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <option value="">Todos</option>
                <option value="entrada">Entrada</option>
                <option value="salida">Salida</option>
                <option value="ajuste">Ajuste</option>
                <option value="venta">Venta</option>
            </select>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Fecha desde</label>
            <input type="date" class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Fecha hasta</label>
            <input type="date" class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        
        <div class="flex items-end">
            <button class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors duration-200 font-medium">
                Filtrar
            </button>
        </div>
    </div>
</div>

<!-- Tabla de movimientos -->
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6 border-b border-orange-100">
        <h2 class="text-lg font-semibold text-amber-800">Historial de Movimientos</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-orange-200">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Cantidad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Stock Anterior</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Stock Nuevo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Motivo/Referencia</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Usuario</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-orange-200">
                @forelse($movimientos ?? [] as $movimiento)
                <tr class="hover:bg-orange-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        <div>{{ date('d/m/Y', strtotime($movimiento->fecha ?? '2024-01-15')) }}</div>
                        <div class="text-xs text-amber-600">{{ date('H:i', strtotime($movimiento->fecha ?? '2024-01-15 10:30:00')) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center">
                                    <span class="text-amber-600 text-xs">☕</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-amber-900">{{ $movimiento->producto->nombre ?? 'Café Americano' }}</div>
                                <div class="text-sm text-amber-600">{{ $movimiento->producto->codigo ?? 'CAF-001' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $tipo = $movimiento->tipo ?? 'entrada';
                            $tipoConfig = [
                                'entrada' => ['bg-green-100 text-green-800', '📦 Entrada'],
                                'salida' => ['bg-red-100 text-red-800', '📤 Salida'],
                                'ajuste' => ['bg-blue-100 text-blue-800', '⚖️ Ajuste'],
                                'venta' => ['bg-purple-100 text-purple-800', '🛒 Venta']
                            ];
                            $config = $tipoConfig[$tipo] ?? ['bg-gray-100 text-gray-800', '❓ Otro'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                            {{ $config[1] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @php $cantidad = $movimiento->cantidad ?? 10; @endphp
                        <span class="{{ $cantidad > 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $cantidad > 0 ? '+' : '' }}{{ $cantidad }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        {{ $movimiento->stock_anterior ?? 45 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-amber-800">
                        {{ $movimiento->stock_nuevo ?? 55 }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        {{ $movimiento->motivo ?? 'Compra #OC-2024-001' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        {{ $movimiento->usuario ?? 'Admin' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-6xl mb-4">📈</span>
                            <h3 class="text-lg font-medium text-amber-800 mb-2">No hay movimientos registrados</h3>
                            <p class="text-amber-600 mb-4">Los movimientos aparecerán aquí cuando se realicen cambios en el inventario</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Resumen de movimientos -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-green-600 text-sm">📦</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Entradas Hoy</p>
                <p class="text-2xl font-bold text-amber-800">12</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <span class="text-red-600 text-sm">📤</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Salidas Hoy</p>
                <p class="text-2xl font-bold text-amber-800">8</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 text-sm">⚖️</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Ajustes Este Mes</p>
                <p class="text-2xl font-bold text-amber-800">3</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-purple-600 text-sm">📊</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Total Movimientos</p>
                <p class="text-2xl font-bold text-amber-800">{{ count($movimientos ?? []) ?: 23 }}</p>
            </div>
        </div>
    </div>
</div>
@endsection