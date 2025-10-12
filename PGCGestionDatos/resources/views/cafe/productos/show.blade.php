@extends('layouts.cafe')

@section('title', 'Producto: ' . $producto->nombre)
@section('subtitle', 'Detalles completos del producto')

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="{{ route('cafe.productos.index') }}" class="text-coffee-600 hover:text-coffee-800">
                ← Volver a productos
            </a>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('cafe.productos.edit', $producto) }}" class="btn btn-secondary">
                ✏️ Editar
            </a>
            <form method="POST" action="{{ route('cafe.productos.destroy', $producto) }}" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="btn bg-red-100 text-red-700 hover:bg-red-200"
                        onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
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
                        <h2 class="text-2xl font-bold text-coffee-800">{{ $producto->nombre }}</h2>
                        <p class="text-coffee-600">Código: {{ $producto->codigo }}</p>
                    </div>
                    
                    <!-- Estado del stock -->
                    <div class="text-right">
                        @if($producto->stock <= 0)
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">
                                ❌ Sin stock
                            </span>
                        @elseif($producto->stock <= 10)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">
                                ⚠️ Stock bajo
                            </span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                ✅ En stock
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Métricas principales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-cream-50 rounded-lg">
                        <div class="text-sm text-coffee-600 mb-1">Stock Actual</div>
                        <div class="text-2xl font-bold text-coffee-800">{{ $producto->stock }}</div>
                        <div class="text-xs text-coffee-500">unidades</div>
                    </div>
                    
                    <div class="text-center p-4 bg-cream-50 rounded-lg">
                        <div class="text-sm text-coffee-600 mb-1">Precio de Venta</div>
                        <div class="text-2xl font-bold text-green-600">${{ number_format($producto->precio_venta, 0, '.', ',') }} COP</div>
                    </div>
                    
                    <div class="text-center p-4 bg-cream-50 rounded-lg">
                        <div class="text-sm text-coffee-600 mb-1">Precio de Compra</div>
                        <div class="text-2xl font-bold text-coffee-800">${{ number_format($producto->precio_compra, 0, '.', ',') }} COP</div>
                    </div>
                </div>

                <!-- Información detallada -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-coffee-800 mb-3">Información de Precios</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-coffee-600">Precio de compra:</span>
                                <span class="text-coffee-800 font-medium">${{ number_format($producto->precio_compra, 0, '.', ',') }} COP</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-coffee-600">Precio de venta:</span>
                                <span class="text-coffee-800 font-medium">${{ number_format($producto->precio_venta, 0, '.', ',') }} COP</span>
                            </div>
                            @if($producto->precio_compra > 0)
                                @php
                                    $ganancia = $producto->precio_venta - $producto->precio_compra;
                                    $margen = (($ganancia) / $producto->precio_compra) * 100;
                                @endphp
                                <div class="flex justify-between border-t border-cream-200 pt-2">
                                    <span class="text-coffee-600">Ganancia unitaria:</span>
                                    <span class="text-green-600 font-medium">${{ number_format($ganancia, 0, '.', ',') }} COP</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-coffee-600">Margen de ganancia:</span>
                                    <span class="font-medium {{ $margen >= 50 ? 'text-green-600' : ($margen >= 20 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($margen, 1) }}%
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-coffee-800 mb-3">Información del Sistema</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-coffee-600">Fecha de creación:</span>
                                <span class="text-coffee-800">{{ $producto->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-coffee-600">Última actualización:</span>
                                <span class="text-coffee-800">{{ $producto->updated_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-coffee-600">Tiempo transcurrido:</span>
                                <span class="text-coffee-800">{{ $producto->created_at->diffForHumans() }}</span>
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
                    <a href="{{ route('cafe.productos.edit', $producto) }}" 
                       class="block w-full text-center p-3 bg-coffee-100 text-coffee-700 rounded-lg hover:bg-coffee-200 transition-colors">
                        ✏️ Editar Producto
                    </a>
                    
                    @if($producto->stock > 0)
                        <button class="block w-full text-center p-3 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                            🛒 Agregar a Venta
                        </button>
                    @else
                        <button class="block w-full text-center p-3 bg-gray-100 text-gray-500 rounded-lg cursor-not-allowed" disabled>
                            🛒 Sin Stock para Venta
                        </button>
                    @endif
                    
                    <button class="block w-full text-center p-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                        📦 Ajustar Stock
                    </button>
                </div>
            </div>

            <!-- Estado de inventario -->
            <div class="card">
                <h3 class="text-lg font-semibold text-coffee-800 mb-4">Estado de Inventario</h3>
                
                @if($producto->stock <= 0)
                    <div class="p-4 bg-red-50 rounded-lg border border-red-200">
                        <div class="flex items-center">
                            <span class="text-red-600 text-xl mr-3">❌</span>
                            <div>
                                <p class="font-medium text-red-800">Sin stock</p>
                                <p class="text-sm text-red-600">No disponible para venta</p>
                            </div>
                        </div>
                    </div>
                @elseif($producto->stock <= 10)
                    <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                        <div class="flex items-center">
                            <span class="text-yellow-600 text-xl mr-3">⚠️</span>
                            <div>
                                <p class="font-medium text-yellow-800">Stock bajo</p>
                                <p class="text-sm text-yellow-600">{{ $producto->stock }} unidades restantes</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center">
                            <span class="text-green-600 text-xl mr-3">✅</span>
                            <div>
                                <p class="font-medium text-green-800">Stock saludable</p>
                                <p class="text-sm text-green-600">{{ $producto->stock }} unidades disponibles</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Barra de progreso visual del stock -->
                <div class="mt-4">
                    <div class="flex justify-between text-sm text-coffee-600 mb-1">
                        <span>Stock</span>
                        <span>{{ $producto->stock }}/{{ max($producto->stock, 50) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $maxStock = max($producto->stock, 50);
                            $percentage = ($producto->stock / $maxStock) * 100;
                        @endphp
                        <div class="h-2 rounded-full {{ $producto->stock <= 0 ? 'bg-red-500' : ($producto->stock <= 10 ? 'bg-yellow-500' : 'bg-green-500') }}" 
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Calculadora de rentabilidad -->
            @if($producto->precio_compra > 0 && $producto->stock > 0)
                <div class="card">
                    <h3 class="text-lg font-semibold text-coffee-800 mb-4">Valor del Inventario</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-coffee-600">Costo total:</span>
                            <span class="text-coffee-800 font-medium">${{ number_format($producto->precio_compra * $producto->stock, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-coffee-600">Valor de venta:</span>
                            <span class="text-green-600 font-medium">${{ number_format($producto->precio_venta * $producto->stock, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-t border-cream-200 pt-2">
                            <span class="text-coffee-600">Ganancia potencial:</span>
                            <span class="text-green-600 font-bold">${{ number_format(($producto->precio_venta - $producto->precio_compra) * $producto->stock, 2) }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection