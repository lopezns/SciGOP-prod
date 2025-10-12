@extends('layouts.cafe')

@section('title', 'Facturas Emitidas')
@section('subtitle', 'Registro de todas las facturas del sistema')

@section('actions')
    <a href="{{ route('cafe.pos.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">💳</span>
        Realizar Venta
    </a>
@endsection

@section('content')
<!-- Filtros -->
<div class="mb-6 bg-white rounded-xl shadow-sm border border-orange-200 p-6">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-64">
            <label class="block text-sm font-medium text-amber-800 mb-2">Buscar factura</label>
            <input type="text" 
                   name="search" 
                   value="{{ request('search') }}"
                   placeholder="Número de factura, cliente..."
                   class="w-full px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
        </div>
        
        <div>
            <label class="block text-sm font-medium text-amber-800 mb-2">Estado</label>
            <select name="estado" class="px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                <option value="">Todos</option>
                <option value="pagada">Pagadas</option>
                <option value="pendiente">Pendientes</option>
                <option value="anulada">Anuladas</option>
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

<!-- Resumen de facturas -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6 text-center">
        <div class="text-3xl font-bold text-amber-800">124</div>
        <div class="text-sm font-medium text-amber-600 mt-1">Total Facturas</div>
        <div class="text-xs text-amber-600 mt-1">Este mes</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6 text-center">
        <div class="text-3xl font-bold text-green-600">$15.420.000 COP</div>
        <div class="text-sm font-medium text-amber-600 mt-1">Facturado</div>
        <div class="text-xs text-green-600 mt-1">Este mes</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6 text-center">
        <div class="text-3xl font-bold text-yellow-600">8</div>
        <div class="text-sm font-medium text-amber-600 mt-1">Pendientes</div>
        <div class="text-xs text-yellow-600 mt-1">Por cobrar</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm border border-orange-200 p-6 text-center">
        <div class="text-3xl font-bold text-red-600">2</div>
        <div class="text-sm font-medium text-amber-600 mt-1">Anuladas</div>
        <div class="text-xs text-red-600 mt-1">Este mes</div>
    </div>
</div>

<!-- Tabla de facturas -->
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6 border-b border-orange-100">
        <h2 class="text-lg font-semibold text-amber-800">Facturas Emitidas</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-orange-200">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Factura</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Productos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-amber-800 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-orange-200">
                @php
                    $facturas = [
                        ['id' => 1, 'numero' => 'F001-00124', 'fecha' => '2024-10-12 14:30', 'cliente' => 'María García', 'documento' => 'DNI: 12345678', 'productos' => 3, 'total' => 85500, 'estado' => 'pagada'],
                        ['id' => 2, 'numero' => 'F001-00123', 'fecha' => '2024-10-12 13:45', 'cliente' => 'Carlos Ruiz', 'documento' => 'DNI: 87654321', 'productos' => 2, 'total' => 45000, 'estado' => 'pagada'],
                        ['id' => 3, 'numero' => 'F001-00122', 'fecha' => '2024-10-12 12:15', 'cliente' => 'Cliente General', 'documento' => '', 'productos' => 1, 'total' => 12500, 'estado' => 'pagada'],
                        ['id' => 4, 'numero' => 'F001-00121', 'fecha' => '2024-10-12 11:30', 'cliente' => 'Ana Torres', 'documento' => 'RUC: 20123456789', 'productos' => 4, 'total' => 120000, 'estado' => 'pendiente'],
                        ['id' => 5, 'numero' => 'F001-00120', 'fecha' => '2024-10-12 10:45', 'cliente' => 'Luis Méndez', 'documento' => 'DNI: 11223344', 'productos' => 2, 'total' => 67500, 'estado' => 'pagada'],
                        ['id' => 6, 'numero' => 'F001-00119', 'fecha' => '2024-10-11 16:20', 'cliente' => 'Empresa ABC', 'documento' => 'RUC: 20987654321', 'productos' => 8, 'total' => 245750, 'estado' => 'pendiente'],
                        ['id' => 7, 'numero' => 'F001-00118', 'fecha' => '2024-10-11 15:10', 'cliente' => 'Patricia López', 'documento' => 'DNI: 55667788', 'productos' => 5, 'total' => 145750, 'estado' => 'pagada'],
                        ['id' => 8, 'numero' => 'F001-00117', 'fecha' => '2024-10-11 14:25', 'cliente' => 'Roberto Vega', 'documento' => 'DNI: 99887766', 'productos' => 3, 'total' => 92250, 'estado' => 'anulada']
                    ];
                @endphp
                
                @forelse($facturas as $factura)
                <tr class="hover:bg-orange-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-amber-900">{{ $factura['numero'] }}</div>
                        <div class="text-sm text-amber-600">#{{ str_pad($factura['id'], 6, '0', STR_PAD_LEFT) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        <div>{{ date('d/m/Y', strtotime($factura['fecha'])) }}</div>
                        <div class="text-xs text-amber-600">{{ date('H:i', strtotime($factura['fecha'])) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center">
                                    <span class="text-amber-600 text-xs">👤</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-amber-900">{{ $factura['cliente'] }}</div>
                                @if($factura['documento'])
                                    <div class="text-sm text-amber-600">{{ $factura['documento'] }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        {{ $factura['productos'] }} {{ $factura['productos'] == 1 ? 'producto' : 'productos' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-amber-800">
                        ${{ number_format($factura['total'], 0, '.', ',') }} COP
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $estadoConfig = [
                                'pagada' => ['bg-green-100 text-green-800', '✅ Pagada'],
                                'pendiente' => ['bg-yellow-100 text-yellow-800', '⏳ Pendiente'],
                                'anulada' => ['bg-red-100 text-red-800', '❌ Anulada']
                            ];
                            $config = $estadoConfig[$factura['estado']] ?? ['bg-gray-100 text-gray-800', '❓ Desconocido'];
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config[0] }}">
                            {{ $config[1] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('cafe.facturas.show', $factura['id']) }}" 
                               class="text-amber-600 hover:text-amber-900 transition-colors duration-200" 
                               title="Ver factura">
                                👁️
                            </a>
                            <button type="button" 
                                    onclick="imprimirFactura({{ $factura['id'] }})"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200" 
                                    title="Imprimir">
                                🖨️
                            </button>
                            @if($factura['estado'] === 'pagada')
                            <button type="button" 
                                    onclick="enviarPorEmail({{ $factura['id'] }})"
                                    class="text-green-600 hover:text-green-900 transition-colors duration-200" 
                                    title="Enviar por email">
                                📧
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-6xl mb-4">🧾</span>
                            <h3 class="text-lg font-medium text-amber-800 mb-2">No hay facturas registradas</h3>
                            <p class="text-amber-600 mb-4">Las facturas aparecerán aquí cuando realices ventas</p>
                            <a href="{{ route('cafe.pos.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
                                <span class="mr-2">💳</span>
                                Realizar Primera Venta
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
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

function enviarPorEmail(id) {
    alert('Funcionalidad de envío por email en desarrollo');
}
</script>
@endpush
