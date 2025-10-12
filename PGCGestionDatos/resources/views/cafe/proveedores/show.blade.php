@extends('layouts.cafe')

@section('title', 'Detalles del Proveedor')
@section('subtitle', $proveedor->nombre)

@section('actions')
    <div class="flex space-x-3">
        <a href="{{ route('cafe.proveedores.edit', $proveedor) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
            <span class="mr-2">✏️</span>
            Editar
        </a>
        <a href="{{ route('cafe.proveedores.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
            <span class="mr-2">←</span>
            Volver
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información del Proveedor -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center">
                        <span class="text-amber-600 text-2xl">🏪</span>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-2xl font-bold text-amber-800">{{ $proveedor->nombre }}</h2>
                        <div class="flex items-center mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $proveedor->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-amber-600 mb-3">Información Legal</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo de Documento</dt>
                                <dd class="text-sm text-gray-900">{{ strtoupper($proveedor->tipo_documento) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Número de Documento</dt>
                                <dd class="text-sm text-gray-900">{{ $proveedor->numero_documento }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-amber-600 mb-3">Información de Contacto</h3>
                        <dl class="space-y-3">
                            @if($proveedor->contacto)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Persona de Contacto</dt>
                                <dd class="text-sm text-gray-900">{{ $proveedor->contacto }}</dd>
                            </div>
                            @endif
                            @if($proveedor->telefono)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="text-sm text-gray-900">
                                    <a href="tel:{{ $proveedor->telefono }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $proveedor->telefono }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                            @if($proveedor->email)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">
                                    <a href="mailto:{{ $proveedor->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $proveedor->email }}
                                    </a>
                                </dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                </div>

                @if($proveedor->direccion)
                <div class="mt-6 pt-6 border-t border-orange-100">
                    <h3 class="text-sm font-medium text-amber-600 mb-3">Dirección</h3>
                    <p class="text-sm text-gray-900">{{ $proveedor->direccion }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Estadísticas y Compras -->
    <div class="space-y-6">
        <!-- Estadísticas -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-6">
            <h3 class="text-lg font-medium text-amber-800 mb-4">Estadísticas</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total de Compras</span>
                    <span class="text-lg font-bold text-amber-800">{{ $proveedor->compras->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Compras Recibidas</span>
                    <span class="text-lg font-bold text-green-600">{{ $proveedor->compras->where('estado', 'recibida')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Compras Pendientes</span>
                    <span class="text-lg font-bold text-yellow-600">{{ $proveedor->compras->where('estado', 'pendiente')->count() }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-orange-100 pt-4">
                    <span class="text-sm text-gray-600">Total Facturado</span>
                    <span class="text-lg font-bold text-amber-800">${{ number_format($proveedor->compras->sum('total'), 0, ',', '.') }} COP</span>
                </div>
            </div>
        </div>

        <!-- Compras Recientes -->
        @if($proveedor->compras->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-6">
            <h3 class="text-lg font-medium text-amber-800 mb-4">Compras Recientes</h3>
            <div class="space-y-3">
                @foreach($proveedor->compras->take(5) as $compra)
                <div class="flex justify-between items-center py-2">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $compra->numero_factura }}</p>
                        <p class="text-xs text-gray-500">{{ $compra->fecha->format('d/m/Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">${{ number_format($compra->total, 0, ',', '.') }} COP</p>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $compra->estado === 'recibida' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($compra->estado) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @if($proveedor->compras->count() > 5)
            <div class="mt-4 pt-4 border-t border-orange-100 text-center">
                <a href="{{ route('cafe.compras.index', ['proveedor_id' => $proveedor->id]) }}" class="text-amber-600 hover:text-amber-800 text-sm font-medium">
                    Ver todas las compras
                </a>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection