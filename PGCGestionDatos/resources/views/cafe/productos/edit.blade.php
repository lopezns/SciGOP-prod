@extends('layouts.cafe')

@section('title', 'Editar Producto')
@section('subtitle', 'Actualizar información del producto')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="card">
        <form method="POST" action="{{ route('cafe.productos.update', $producto) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Información básica -->
            <div class="border-b border-cream-200 pb-6">
                <h3 class="text-lg font-medium text-coffee-800 mb-4">Información Básica</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-coffee-700 mb-1">
                            Nombre del Producto *
                        </label>
                        <input type="text" 
                               id="nombre" 
                               name="nombre" 
                               value="{{ old('nombre', $producto->nombre) }}"
                               class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500 focus:border-transparent @error('nombre') border-red-300 @enderror"
                               placeholder="Ej: Café Americano"
                               required>
                        @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="codigo" class="block text-sm font-medium text-coffee-700 mb-1">
                            Código del Producto *
                        </label>
                        <input type="text" 
                               id="codigo" 
                               name="codigo" 
                               value="{{ old('codigo', $producto->codigo) }}"
                               class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500 focus:border-transparent @error('codigo') border-red-300 @enderror"
                               placeholder="Ej: CAF001"
                               required>
                        @error('codigo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Precios -->
            <div class="border-b border-cream-200 pb-6">
                <h3 class="text-lg font-medium text-coffee-800 mb-4">Precios</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="precio_compra" class="block text-sm font-medium text-coffee-700 mb-1">
                            Precio de Compra
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-coffee-600">$</span>
                            <input type="number" 
                                   id="precio_compra" 
                                   name="precio_compra" 
                                   value="{{ old('precio_compra', $producto->precio_compra) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-8 pr-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500 focus:border-transparent @error('precio_compra') border-red-300 @enderror"
                                   placeholder="0.00">
                        </div>
                        @error('precio_compra')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="precio_venta" class="block text-sm font-medium text-coffee-700 mb-1">
                            Precio de Venta *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-coffee-600">$</span>
                            <input type="number" 
                                   id="precio_venta" 
                                   name="precio_venta" 
                                   value="{{ old('precio_venta', $producto->precio_venta) }}"
                                   step="0.01"
                                   min="0"
                                   class="w-full pl-8 pr-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500 focus:border-transparent @error('precio_venta') border-red-300 @enderror"
                                   placeholder="0.00"
                                   required>
                        </div>
                        @error('precio_venta')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Cálculo automático de margen -->
                <div id="margen-info" class="mt-4 p-3 bg-cream-50 rounded-lg">
                    <div class="flex justify-between text-sm">
                        <span class="text-coffee-600">Margen de ganancia:</span>
                        <span id="margen-valor" class="font-medium text-coffee-800">-</span>
                    </div>
                </div>
            </div>

            <!-- Inventario -->
            <div class="pb-6">
                <h3 class="text-lg font-medium text-coffee-800 mb-4">Inventario</h3>
                
                <div>
                    <label for="stock" class="block text-sm font-medium text-coffee-700 mb-1">
                        Stock Actual
                    </label>
                    <input type="number" 
                           id="stock" 
                           name="stock" 
                           value="{{ old('stock', $producto->stock) }}"
                           min="0"
                           class="w-full px-3 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500 focus:border-transparent @error('stock') border-red-300 @enderror"
                           placeholder="0">
                    @error('stock')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-coffee-600">
                        Stock actual en inventario
                        @if($producto->stock <= 0)
                            <span class="text-red-600 font-medium">⚠️ Sin stock</span>
                        @elseif($producto->stock <= 10)
                            <span class="text-yellow-600 font-medium">⚠️ Stock bajo</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="pb-6 bg-cream-50 -m-6 p-6 rounded-lg">
                <h3 class="text-sm font-medium text-coffee-700 mb-3">Información del Producto</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-coffee-600">Creado:</span>
                        <span class="text-coffee-800">{{ $producto->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-coffee-600">Última actualización:</span>
                        <span class="text-coffee-800">{{ $producto->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
                
                @if($producto->precio_compra > 0)
                    @php
                        $margenActual = (($producto->precio_venta - $producto->precio_compra) / $producto->precio_compra) * 100;
                    @endphp
                    <div class="mt-2 text-sm">
                        <span class="text-coffee-600">Margen actual:</span>
                        <span class="text-coffee-800 font-medium">{{ number_format($margenActual, 1) }}%</span>
                    </div>
                @endif
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('cafe.productos.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <a href="{{ route('cafe.productos.show', $producto) }}" class="btn bg-coffee-100 text-coffee-700 hover:bg-coffee-200">
                    👁️ Ver Producto
                </a>
                <button type="submit" class="btn btn-primary">
                    💾 Actualizar Producto
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para calcular margen automáticamente -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const precioCompra = document.getElementById('precio_compra');
    const precioVenta = document.getElementById('precio_venta');
    const margenInfo = document.getElementById('margen-info');
    const margenValor = document.getElementById('margen-valor');
    
    function calcularMargen() {
        const compra = parseFloat(precioCompra.value) || 0;
        const venta = parseFloat(precioVenta.value) || 0;
        
        if (compra > 0 && venta > 0) {
            const margen = ((venta - compra) / compra) * 100;
            margenValor.textContent = margen.toFixed(1) + '%';
            margenInfo.classList.remove('hidden');
            
            // Colorear según el margen
            if (margen < 20) {
                margenValor.className = 'font-medium text-red-600';
            } else if (margen < 50) {
                margenValor.className = 'font-medium text-yellow-600';
            } else {
                margenValor.className = 'font-medium text-green-600';
            }
        } else if (venta > 0) {
            margenValor.textContent = 'N/A (sin precio de compra)';
            margenInfo.classList.remove('hidden');
            margenValor.className = 'font-medium text-gray-600';
        } else {
            margenInfo.classList.add('hidden');
        }
    }
    
    precioCompra.addEventListener('input', calcularMargen);
    precioVenta.addEventListener('input', calcularMargen);
    
    // Calcular al cargar
    calcularMargen();
});
</script>
@endsection