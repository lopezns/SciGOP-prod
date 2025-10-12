@extends('layouts.cafe')

@section('title', 'Nueva Compra')
@section('subtitle', 'Registrar nueva orden de compra')

@section('actions')
    <a href="{{ route('cafe.compras.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">←</span>
        Volver
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6">
        <form method="POST" action="{{ route('cafe.compras.store') }}" id="compraForm" class="space-y-6">
            @csrf
            
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
                            <option value="{{ $proveedor->id }}" {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
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
                           value="{{ old('numero_factura') }}"
                           required
                           placeholder="FC-2024-001"
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
                           value="{{ old('fecha', date('Y-m-d')) }}"
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
                         placeholder="Notas adicionales sobre la compra..."
                         class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('observaciones') }}</textarea>
                @error('observaciones')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Productos -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-amber-800">Productos</h3>
                    <button type="button" 
                            id="agregarProducto"
                            class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                        <span class="mr-1">+</span>
                        Agregar Producto
                    </button>
                </div>

                <div id="productosContainer" class="space-y-3">
                    <!-- Los productos se agregarán dinámicamente aquí -->
                </div>

                @error('productos')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Total -->
            <div class="bg-amber-50 rounded-lg p-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-amber-800">Total:</span>
                    <span id="totalCompra" class="text-2xl font-bold text-amber-800">$0 COP</span>
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
                    Guardar Compra
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Template para productos -->
<template id="productoTemplate">
    <div class="producto-item border border-orange-200 rounded-lg p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-amber-700 mb-2">Producto</label>
                <select name="productos[INDEX][producto_id]" required class="producto-select w-full px-3 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="">Seleccionar...</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }} (Stock: {{ $producto->stock }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-amber-700 mb-2">Cantidad</label>
                <input type="number" name="productos[INDEX][cantidad]" min="1" required class="cantidad-input w-full px-3 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-amber-700 mb-2">Precio Unitario</label>
                <input type="number" name="productos[INDEX][precio_unitario]" min="0" step="0.01" required class="precio-input w-full px-3 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500">
            </div>
            <div class="flex items-end space-x-2">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-amber-700 mb-2">Subtotal</label>
                    <div class="subtotal-display px-3 py-2 bg-gray-50 border border-gray-300 rounded-lg text-gray-700">$0 COP</div>
                </div>
                <button type="button" class="eliminar-producto text-red-600 hover:text-red-800 p-2">
                    🗑️
                </button>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
let productoIndex = 0;

document.getElementById('agregarProducto').addEventListener('click', function() {
    const template = document.getElementById('productoTemplate');
    const container = document.getElementById('productosContainer');
    
    const clone = template.content.cloneNode(true);
    
    // Reemplazar INDEX con el índice actual
    clone.innerHTML = clone.innerHTML.replace(/INDEX/g, productoIndex);
    
    container.appendChild(clone);
    
    // Agregar event listeners
    const productoItem = container.lastElementChild;
    const cantidadInput = productoItem.querySelector('.cantidad-input');
    const precioInput = productoItem.querySelector('.precio-input');
    const eliminarBtn = productoItem.querySelector('.eliminar-producto');
    
    cantidadInput.addEventListener('input', calcularSubtotal);
    precioInput.addEventListener('input', calcularSubtotal);
    eliminarBtn.addEventListener('click', function() {
        productoItem.remove();
        calcularTotal();
    });
    
    productoIndex++;
});

function calcularSubtotal(event) {
    const productoItem = event.target.closest('.producto-item');
    const cantidad = parseFloat(productoItem.querySelector('.cantidad-input').value) || 0;
    const precio = parseFloat(productoItem.querySelector('.precio-input').value) || 0;
    const subtotal = cantidad * precio;
    
    productoItem.querySelector('.subtotal-display').textContent = '$' + subtotal.toLocaleString('es-CO') + ' COP';
    
    calcularTotal();
}

function calcularTotal() {
    let total = 0;
    document.querySelectorAll('.producto-item').forEach(function(item) {
        const cantidad = parseFloat(item.querySelector('.cantidad-input').value) || 0;
        const precio = parseFloat(item.querySelector('.precio-input').value) || 0;
        total += cantidad * precio;
    });
    
    document.getElementById('totalCompra').textContent = '$' + total.toLocaleString('es-CO') + ' COP';
}

// Agregar al menos un producto al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('agregarProducto').click();
});
</script>
@endpush