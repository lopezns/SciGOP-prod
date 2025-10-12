@extends('layouts.cafe')

@section('title', 'Cliente: ' . $cliente->nombre)
@section('subtitle', 'Detalles completos del cliente')

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="{{ route('cafe.clientes.index') }}" class="text-coffee-600 hover:text-coffee-800">
                ← Volver a clientes
            </a>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('cafe.clientes.edit', $cliente) }}" class="btn btn-secondary">
                ✏️ Editar
            </a>
            <form method="POST" action="{{ route('cafe.clientes.destroy', $cliente) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn bg-red-100 text-red-700 hover:bg-red-200"
                        onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?')">
                    🗑️ Eliminar
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Datos básicos -->
            <div class="card">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-coffee-800">{{ $cliente->nombre }}</h2>
                        <p class="text-coffee-600">{{ $cliente->tipo_documento }}: {{ $cliente->numero_documento }}</p>
                    </div>
                    
                    <!-- Tipo de cliente -->
                    <div class="text-right">
                        @if($cliente->tipo_documento === 'NIT')
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                🏢 Empresa
                            </span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                👤 Persona Natural
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Información de contacto -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-coffee-800 mb-3">Información de Contacto</h3>
                        <div class="space-y-2 text-sm">
                            @if($cliente->telefono)
                                <div class="flex justify-between">
                                    <span class="text-coffee-600">Teléfono:</span>
                                    <span class="text-coffee-800 font-medium">{{ $cliente->telefono }}</span>
                                </div>
                            @endif
                            @if($cliente->email)
                                <div class="flex justify-between">
                                    <span class="text-coffee-600">Email:</span>
                                    <span class="text-coffee-800 font-medium">{{ $cliente->email }}</span>
                                </div>
                            @endif
                            @if($cliente->direccion)
                                <div>
                                    <span class="text-coffee-600">Dirección:</span>
                                    <p class="text-coffee-800 font-medium mt-1">{{ $cliente->direccion }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-coffee-800 mb-3">Información del Sistema</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-coffee-600">Cliente desde:</span>
                                <span class="text-coffee-800">{{ $cliente->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-coffee-600">Última actualización:</span>
                                <span class="text-coffee-800">{{ $cliente->updated_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-coffee-600">Tiempo como cliente:</span>
                                <span class="text-coffee-800">{{ $cliente->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Panel lateral -->
        <div class="space-y-6">
            <!-- Acciones rápidas -->
            <div class="card">
                <h3 class="text-lg font-semibold text-coffee-800 mb-4">Acciones Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('cafe.clientes.edit', $cliente) }}" 
                       class="block w-full text-center p-3 bg-coffee-100 text-coffee-700 rounded-lg hover:bg-coffee-200 transition-colors">
                        ✏️ Editar Cliente
                    </a>
                    
                    <button class="block w-full text-center p-3 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                        🛒 Nueva Venta
                    </button>
                    
                    <button class="block w-full text-center p-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                        📧 Enviar Email
                    </button>
                </div>
            </div>

            <!-- Estadísticas del cliente -->
            <div class="card">
                <h3 class="text-lg font-semibold text-coffee-800 mb-4">Estadísticas</h3>
                
                <div class="space-y-3">
                    <div class="text-center p-4 bg-cream-50 rounded-lg">
                        <div class="text-2xl font-bold text-coffee-800">{{ $cliente->facturasVenta->count() }}</div>
                        <div class="text-sm text-coffee-600">Facturas Totales</div>
                    </div>
                    
                    <div class="text-center p-4 bg-cream-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">
                            ${{ number_format($cliente->facturasVenta->where('estado', 'pagada')->sum('total'), 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-coffee-600">Total Facturado</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection