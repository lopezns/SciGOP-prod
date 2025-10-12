@extends('layouts.cafe')

@section('title', 'Editar Compra')
@section('subtitle', 'Actualizar orden de compra')

@section('actions')
    <a href="{{ route('cafe.compras.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">←</span>
        Volver
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6">
        <form method="POST" action="{{ route('cafe.compras.update', $compra) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Proveedor -->
                <div>
                    <label for="proveedor_id" class="block text-sm font-medium text-amber-700 mb-2">
                        Proveedor *
                    </label>
                    <select id="proveedor_id" 
                            name="proveedor_id" 
                            required
                            class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <option value="">Seleccionar proveedor...</option>
                        @foreach($proveedores as $proveedor)
                            <option value="{{ $proveedor->id }}" {{ old('proveedor_id', $compra->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                {{ $proveedor->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('proveedor_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número de Factura -->
                <div>
                    <label for="numero_factura" class="block text-sm font-medium text-amber-700 mb-2">
                        Número de Factura *
                    </label>
                    <input type="text" 
                           id="numero_factura" 
                           name="numero_factura" 
                           value="{{ old('numero_factura', $compra->numero_factura) }}"
                           required
                           class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('numero_factura')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fecha -->
                <div>
                    <label for="fecha" class="block text-sm font-medium text-amber-700 mb-2">
                        Fecha *
                    </label>
                    <input type="date" 
                           id="fecha" 
                           name="fecha" 
                           value="{{ old('fecha', $compra->fecha->format('Y-m-d')) }}"
                           required
                           class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('fecha')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Observaciones -->
            <div>
                <label for="observaciones" class="block text-sm font-medium text-amber-700 mb-2">
                    Observaciones
                </label>
                <textarea id="observaciones" 
                         name="observaciones" 
                         rows="3"
                         class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('observaciones', $compra->observaciones) }}</textarea>
                @error('observaciones')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Estado de la compra -->
            @if($compra->estado === 'recibida')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <span class="text-yellow-600">⚠️</span>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            Compra ya recibida
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>Esta compra ya fue marcada como recibida. Solo se pueden editar los datos básicos.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Productos (solo lectura si está recibida) -->
            <div>
                <h3 class="text-lg font-medium text-amber-800 mb-4">Productos</h3>
                <div class="space-y-3">
                    @foreach($compra->detalles as $detalle)
                    <div class="border border-orange-200 rounded-lg p-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Producto</label>
                                <div class="text-sm text-gray-900">{{ $detalle->producto->nombre }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Cantidad</label>
                                <div class="text-sm text-gray-900">{{ $detalle->cantidad }}</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Precio Unitario</label>
                                <div class="text-sm text-gray-900">${{ number_format($detalle->precio_unitario, 0, ',', '.') }} COP</div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Subtotal</label>
                                <div class="text-sm font-medium text-gray-900">${{ number_format($detalle->subtotal, 0, ',', '.') }} COP</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Total -->
            <div class="bg-amber-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-amber-800">Total:</span>
                    <span class="text-2xl font-bold text-amber-800">${{ number_format($compra->total, 0, ',', '.') }} COP</span>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('cafe.compras.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Actualizar Compra
                </button>
            </div>
        </form>
    </div>
</div>
@endsection