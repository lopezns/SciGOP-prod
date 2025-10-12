@extends('layouts.cafe')

@section('title', 'Factura ' . $factura->numero_factura)
@section('subtitle', 'Detalles de la factura')

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="{{ route('cafe.facturas.index') }}" class="text-coffee-600 hover:text-coffee-800">
                ← Volver a facturas
            </a>
        </div>
        
        <div class="flex space-x-3">
            <button class="btn btn-secondary">
                🖨️ Imprimir
            </button>
            <button class="btn bg-blue-100 text-blue-700 hover:bg-blue-200">
                📧 Enviar por Email
            </button>
            @if($factura->estado === 'pendiente')
                <form method="POST" action="#" class="inline">
                    @csrf
                    <button type="submit" class="btn bg-green-100 text-green-700 hover:bg-green-200">
                        💰 Marcar como Pagada
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información principal de la factura -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Datos de la factura -->
            <div class="card">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-coffee-800">{{ $factura->numero_factura }}</h2>
                        <p class="text-coffee-600">Fecha: {{ $factura->fecha->format('d/m/Y') }}</p>
                    </div>
                    
                    <!-- Estado -->
                    <div class="text-right">
                        @if($factura->estado === 'pagada')
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                ✅ Pagada
                            </span>
                        @elseif($factura->estado === 'pendiente')
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                ⏳ Pendiente
                            </span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                ❌ Anulada
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Información del cliente -->
                <div class="border-b border-cream-200 pb-6 mb-6">
                    <h3 class="text-lg font-semibold text-coffee-800 mb-3">Información del Cliente</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-coffee-600">Cliente:</p>
                            <p class="font-medium text-coffee-900">{{ $factura->cliente->nombre }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-coffee-600">Documento:</p>
                            <p class="font-medium text-coffee-900">{{ $factura->cliente->tipo_documento }}: {{ $factura->cliente->numero_documento }}</p>
                        </div>
                        @if($factura->cliente->direccion)
                            <div>
                                <p class="text-sm text-coffee-600">Dirección:</p>
                                <p class="font-medium text-coffee-900">{{ $factura->cliente->direccion }}</p>
                            </div>
                        @endif
                        @if($factura->cliente->telefono)
                            <div>
                                <p class="text-sm text-coffee-600">Teléfono:</p>
                                <p class="font-medium text-coffee-900">{{ $factura->cliente->telefono }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Resumen financiero -->
                <div class="text-center p-6 bg-cream-50 rounded-lg">
                    <div class="text-3xl font-bold text-coffee-800 mb-2">
                        ${{ number_format($factura->total, 0, ',', '.') }}
                    </div>
                    <div class="text-coffee-600">Total de la Factura</div>
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="space-y-6">
            <!-- Acciones rápidas -->
            <div class="card">
                <h3 class="text-lg font-semibold text-coffee-800 mb-4">Acciones</h3>
                <div class="space-y-3">
                    <button class="block w-full text-center p-3 bg-coffee-100 text-coffee-700 rounded-lg hover:bg-coffee-200 transition-colors">
                        🖨️ Imprimir Factura
                    </button>
                    <button class="block w-full text-center p-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                        📧 Enviar por Email
                    </button>
                    @if($factura->estado === 'pendiente')
                        <button class="block w-full text-center p-3 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                            💰 Marcar como Pagada
                        </button>
                    @endif
                    @if($factura->estado !== 'anulada')
                        <button class="block w-full text-center p-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                            ❌ Anular Factura
                        </button>
                    @endif
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card">
                <h3 class="text-lg font-semibold text-coffee-800 mb-4">Información Adicional</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-coffee-600">Fecha de creación:</span>
                        <span class="text-coffee-800">{{ $factura->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-coffee-600">Última actualización:</span>
                        <span class="text-coffee-800">{{ $factura->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-coffee-600">Tiempo transcurrido:</span>
                        <span class="text-coffee-800">{{ $factura->fecha->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Navegación rápida -->
            <div class="card">
                <h3 class="text-lg font-semibold text-coffee-800 mb-4">Navegación Rápida</h3>
                <div class="space-y-2">
                    <a href="{{ route('cafe.facturas.index') }}" class="block w-full text-center p-2 bg-cream-100 text-coffee-700 rounded hover:bg-cream-200 transition-colors text-sm">
                        📋 Ver Todas las Facturas
                    </a>
                    <a href="{{ route('cafe.clientes.show', $factura->cliente->id) }}" class="block w-full text-center p-2 bg-cream-100 text-coffee-700 rounded hover:bg-cream-200 transition-colors text-sm">
                        👤 Ver Cliente
                    </a>
                    <a href="{{ route('cafe.ventas.create') }}" class="block w-full text-center p-2 bg-cream-100 text-coffee-700 rounded hover:bg-cream-200 transition-colors text-sm">
                        ➕ Nueva Venta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection