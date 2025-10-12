@extends('layouts.cafe')

@section('title', 'Detalles de Compra')
@section('subtitle', 'Orden #' . $compra->numero_factura)

@section('actions')
    <div class="flex space-x-3">
        @if($compra->estado === 'pendiente')
            <form method="POST" action="{{ route('cafe.compras.recibir', $compra) }}" class="inline">
                @csrf
                <button type="submit" 
                        onclick="return confirm('¿Confirmar que se recibió esta compra?')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <span class="mr-2">✅</span>
                    Marcar como Recibida
                </button>
            </form>
        @endif
        @if($compra->estado !== 'recibida')
            <a href="{{ route('cafe.compras.edit', $compra) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <span class="mr-2">✏️</span>
                Editar
            </a>
        @endif
        <a href="{{ route('cafe.compras.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
            <span class="mr-2">←</span>
            Volver
        </a>
    </div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Información de la Compra -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Datos principales -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                            <span class="text-amber-600 text-xl">📦</span>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-xl font-bold text-amber-800">{{ $compra->numero_factura }}</h2>
                            <p class="text-sm text-gray-600">{{ $compra->fecha->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div>
                        @php
                            $clases = [
                                'pendiente' => 'bg-yellow-100 text-yellow-800',
                                'recibida' => 'bg-green-100 text-green-800',
                                'cancelada' => 'bg-red-100 text-red-800'
                            ];
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $clases[$compra->estado] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($compra->estado) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-amber-600 mb-3">Proveedor</h3>
                        <div class="space-y-2">
                            <p class="text-lg font-medium text-gray-900">{{ $compra->proveedor->nombre }}</p>
                            <p class="text-sm text-gray-600">{{ strtoupper($compra->proveedor->tipo_documento) }}: {{ $compra->proveedor->numero_documento }}</p>
                            @if($compra->proveedor->contacto)
                                <p class="text-sm text-gray-600">{{ $compra->proveedor->contacto }}</p>
                            @endif
                            @if($compra->proveedor->telefono)
                                <p class="text-sm text-gray-600">📞 {{ $compra->proveedor->telefono }}</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-amber-600 mb-3">Información de Compra</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha</dt>
                                <dd class="text-sm text-gray-900">{{ $compra->fecha->format('d/m/Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total</dt>
                                <dd class="text-lg font-bold text-amber-800">${{ number_format($compra->total, 0, ',', '.') }} COP</dd>
                            </div>
                            @if($compra->usuario)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Registrado por</dt>
                                    <dd class="text-sm text-gray-900">{{ $compra->usuario->nombre }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                @if($compra->observaciones)
                <div class="mt-6 pt-6 border-t border-orange-100">
                    <h3 class="text-sm font-medium text-amber-600 mb-3">Observaciones</h3>
                    <p class="text-sm text-gray-900">{{ $compra->observaciones }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Productos -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-medium text-amber-800 mb-4">Productos</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-orange-200">
                        <thead class="bg-orange-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Producto</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-amber-800 uppercase tracking-wider">Cantidad</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-amber-800 uppercase tracking-wider">Precio Unit.</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-amber-800 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-orange-200">
                            @foreach($compra->detalles as $detalle)
                            <tr>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $detalle->producto->nombre }}</p>
                                        <p class="text-xs text-gray-500">{{ $detalle->producto->codigo }}</p>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">
                                    {{ $detalle->cantidad }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">
                                    ${{ number_format($detalle->precio_unitario, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">
                                    ${{ number_format($detalle->subtotal, 0, ',', '.') }} COP
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-amber-50">
                            <tr>
                                <th colspan="3" class="px-4 py-3 text-right text-sm font-medium text-amber-800">
                                    Total:
                                </th>
                                <th class="px-4 py-3 text-right text-lg font-bold text-amber-800">
                                    ${{ number_format($compra->total, 0, ',', '.') }} COP
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel lateral -->
    <div class="space-y-6">
        <!-- Resumen -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-6">
            <h3 class="text-lg font-medium text-amber-800 mb-4">Resumen</h3>
            <div class="space-y-4">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Productos</span>
                    <span class="text-sm font-medium text-gray-900">{{ $compra->detalles->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Cantidad Total</span>
                    <span class="text-sm font-medium text-gray-900">{{ $compra->detalles->sum('cantidad') }}</span>
                </div>
                <div class="border-t border-orange-100 pt-4">
                    <div class="flex justify-between">
                        <span class="text-base font-medium text-gray-900">Total</span>
                        <span class="text-xl font-bold text-amber-800">${{ number_format($compra->total, 0, ',', '.') }} COP</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historial -->
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-6">
            <h3 class="text-lg font-medium text-amber-800 mb-4">Historial</h3>
            <div class="space-y-3">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-blue-400 rounded-full mt-2"></div>
                    <div>
                        <p class="text-sm text-gray-900">Compra registrada</p>
                        <p class="text-xs text-gray-500">{{ $compra->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @if($compra->estado === 'recibida')
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-2 h-2 bg-green-400 rounded-full mt-2"></div>
                    <div>
                        <p class="text-sm text-gray-900">Compra recibida</p>
                        <p class="text-xs text-gray-500">{{ $compra->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Acciones rápidas -->
        @if($compra->estado === 'pendiente')
        <div class="bg-white rounded-xl shadow-sm border border-orange-200 p-6">
            <h3 class="text-lg font-medium text-amber-800 mb-4">Acciones</h3>
            <div class="space-y-3">
                <form method="POST" action="{{ route('cafe.compras.recibir', $compra) }}" class="w-full">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('¿Confirmar que se recibió esta compra? Esto actualizará el inventario.')"
                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        ✅ Marcar como Recibida
                    </button>
                </form>
                <a href="{{ route('cafe.compras.edit', $compra) }}" 
                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    ✏️ Editar Compra
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection