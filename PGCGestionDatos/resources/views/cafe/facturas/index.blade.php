@extends('layouts.cafe')

@section('title', 'Facturas')
@section('subtitle', 'Historial de facturas emitidas')

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
                       placeholder="Buscar facturas..."
                       class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500 focus:border-transparent">
                
                <!-- Filtros -->
                <select name="estado" class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500">
                    <option value="">Todos los estados</option>
                    <option value="pagada">Pagadas</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="cancelada">Canceladas</option>
                </select>
                
                <button type="submit" class="px-4 py-2 bg-coffee-600 text-white rounded-lg hover:bg-coffee-700">
                    Filtrar
                </button>
                
                @if(request('search') || request('estado'))
                    <a href="{{ route('cafe.facturas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Limpiar
                    </a>
                @endif
            </form>
        </div>
        
        <a href="{{ route('cafe.ventas.create') }}" class="btn btn-primary">
            📄 Nueva Venta/Factura
        </a>
    </div>

    <!-- Resumen de facturas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card text-center">
            <div class="text-2xl font-bold text-coffee-800">{{ number_format($totalFacturas) }}</div>
            <div class="text-sm text-coffee-600">Total Facturas</div>
        </div>
        <div class="card text-center">
            <div class="text-2xl font-bold text-green-600">${{ number_format($ventasMes, 0, ',', '.') }}</div>
            <div class="text-sm text-coffee-600">Ventas del Mes</div>
        </div>
        <div class="card text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ number_format($pendientes) }}</div>
            <div class="text-sm text-coffee-600">Pendientes</div>
        </div>
        <div class="card text-center">
            <div class="text-2xl font-bold text-red-600">{{ number_format($canceladas) }}</div>
            <div class="text-sm text-coffee-600">Canceladas</div>
        </div>
    </div>

    <!-- Lista de facturas -->
    <div class="card overflow-hidden">
        @if($facturas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cream-200">
                    <thead class="bg-cream-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Factura
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cream-100">
                        @foreach($facturas as $factura)
                            <tr class="hover:bg-cream-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-coffee-900">{{ $factura->numero_factura }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-coffee-900">{{ $factura->cliente->nombre }}</div>
                                    <div class="text-sm text-coffee-500">{{ $factura->cliente->tipo_documento }}: {{ $factura->cliente->numero_documento }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-coffee-900">{{ $factura->fecha->format('d/m/Y') }}</div>
                                    <div class="text-sm text-coffee-500">{{ $factura->fecha->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-coffee-900">${{ number_format($factura->total, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($factura->estado === 'pagada')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Pagada
                                        </span>
                                    @elseif($factura->estado === 'pendiente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Anulada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('cafe.facturas.show', $factura->id) }}" class="text-coffee-600 hover:text-coffee-900">
                                        👁️
                                    </a>
                                    <button class="text-blue-600 hover:text-blue-900">
                                        🖨️
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-cream-200">
                {{ $facturas->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-coffee-400 text-6xl mb-4">📄</div>
                <h3 class="text-lg font-medium text-coffee-900 mb-2">No hay facturas</h3>
                <p class="text-coffee-600 mb-4">
                    @if(request('search') || request('estado'))
                        No se encontraron facturas que coincidan con los filtros aplicados.
                    @else
                        Aún no se han generado facturas en el sistema.
                    @endif
                </p>
                <a href="{{ route('cafe.ventas.create') }}" class="btn btn-primary">
                    ➕ Crear Primera Factura
                </a>
            </div>
        @endif
    </div>
</div>
@endsection