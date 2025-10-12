@extends('layouts.cafe')

@section('title', 'Historial de Ventas')
@section('subtitle', 'Registro completo de todas las ventas realizadas')

@section('actions')
    <a href="{{ route('cafe.pos.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">💳</span>
        Realizar Venta (POS)
    </a>
@endsection

@section('content')
<!-- Filtros -->
<div class="mb-6 bg-white rounded-xl shadow-sm border border-orange-200 p-6">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-64">
            <label class="block text-sm font-medium text-amber-800 mb-2">Buscar venta</label>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Número de factura, cliente..."
                   class="w-full px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Fecha desde</label>
            <input type="date" class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Fecha hasta</label>
            <input type="date" class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Método de pago</label>
            <select class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <option value="">Todos</option>
                <option value="efectivo">Efectivo</option>
                <option value="tarjeta">Tarjeta</option>
                <option value="transferencia">Transferencia</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors duration-200 font-medium">
                Filtrar
            </button>
        </div>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-green-600 text-sm">💰</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Ventas Hoy</p>
                <p class="text-2xl font-bold text-amber-800">$1.245.000 COP</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 text-sm">🧾</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Facturas Hoy</p>
                <p class="text-2xl font-bold text-amber-800">18</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-purple-600 text-sm">🔢</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Promedio por Venta</p>
                <p class="text-2xl font-bold text-amber-800">$69.000 COP</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <span class="text-yellow-600 text-sm">🕰️</span>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-amber-600">Ventas Este Mes</p>
                <p class="text-2xl font-bold text-amber-800">$15.420.000 COP</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabla de ventas -->
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6 border-b border-orange-100">
        <h2 class="text-lg font-semibold text-amber-800">Historial de Ventas</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-orange-200">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Factura</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Productos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Método Pago</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-amber-800 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-orange-200">
                @php
                    $ventas = [
                        ['id' => 1, 'factura' => 'F001-00124', 'fecha' => '2024-10-12 14:30', 'cliente' => 'María García', 'productos' => 3, 'metodo_pago' => 'Efectivo', 'total' => 85500],
                        ['id' => 2, 'factura' => 'F001-00123', 'fecha' => '2024-10-12 13:45', 'cliente' => 'Carlos Ruiz', 'productos' => 2, 'metodo_pago' => 'Tarjeta', 'total' => 45000],
                        ['id' => 3, 'factura' => 'F001-00122', 'fecha' => '2024-10-12 12:15', 'cliente' => 'Cliente General', 'productos' => 1, 'metodo_pago' => 'Efectivo', 'total' => 12500],
                        ['id' => 4, 'factura' => 'F001-00121', 'fecha' => '2024-10-12 11:30', 'cliente' => 'Ana Torres', 'productos' => 4, 'metodo_pago' => 'Transferencia', 'total' => 120000],
                        ['id' => 5, 'factura' => 'F001-00120', 'fecha' => '2024-10-12 10:45', 'cliente' => 'Luis Méndez', 'productos' => 2, 'metodo_pago' => 'Tarjeta', 'total' => 67500],
                        ['id' => 6, 'factura' => 'F001-00119', 'fecha' => '2024-10-11 16:20', 'cliente' => 'Cliente General', 'productos' => 1, 'metodo_pago' => 'Efectivo', 'total' => 8000],
                        ['id' => 7, 'factura' => 'F001-00118', 'fecha' => '2024-10-11 15:10', 'cliente' => 'Patricia López', 'productos' => 5, 'metodo_pago' => 'Tarjeta', 'total' => 145750],
                        ['id' => 8, 'factura' => 'F001-00117', 'fecha' => '2024-10-11 14:25', 'cliente' => 'Roberto Vega', 'productos' => 3, 'metodo_pago' => 'Efectivo', 'total' => 92250]
                    ];
                @endphp
                
                @foreach($ventas as $venta)
                <tr class="hover:bg-orange-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-amber-900">{{ $venta['factura'] }}</div>
                        <div class="text-sm text-amber-600">#{{ str_pad($venta['id'], 6, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        <div>{{ date('d/m/Y', strtotime($venta['fecha'])) }}</div>
                        <div class="text-xs text-amber-600">{{ date('H:i', strtotime($venta['fecha'])) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center">
                                    <span class="text-amber-600 text-xs">👤</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-amber-900">{{ $venta['cliente'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        {{ $venta['productos'] }} {{ $venta['productos'] == 1 ? 'producto' : 'productos' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $metodoConfig = [
                                'Efectivo' => ['bg-green-100 text-green-800', '💵'],
                                'Tarjeta' => ['bg-blue-100 text-blue-800', '💳'],
                                'Transferencia' => ['bg-purple-100 text-purple-800', '📱']
                            ];
                            $config = $metodoConfig[$venta['metodo_pago']] ?? ['bg-gray-100 text-gray-800', '❓'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                            <span class="mr-1">{{ $config[1] }}</span>
                            {{ $venta['metodo_pago'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-amber-800">
                        ${{ number_format($venta['total'], 0, '.', ',') }} COP
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('cafe.facturas.show', $venta['id']) }}" 
                               class="text-amber-600 hover:text-amber-900 transition-colors duration-200" 
                               title="Ver factura">
                                👁️
                            </a>
                            <button type="button" 
                                    onclick="imprimirFactura({{ $venta['id'] }})"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                                    title="Imprimir">
                                🖨️
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
function imprimirFactura(id) {
    window.open(`{{ url('cafe/pos/factura') }}/${id}`, '_blank');
}
</script>
@endpush
