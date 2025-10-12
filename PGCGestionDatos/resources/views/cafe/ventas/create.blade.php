@extends('layouts.cafe')

@section('title', 'Nueva Venta')
@section('subtitle', 'Registrar una nueva venta')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Estado de desarrollo -->
    <div class="card">
        <div class="text-center py-12">
            <div class="text-coffee-400 text-6xl mb-4">🛒</div>
            <h3 class="text-lg font-medium text-coffee-900 mb-2">Punto de Venta en Desarrollo</h3>
            <p class="text-coffee-600 mb-4">
                El sistema de punto de venta estará disponible próximamente. 
                Incluirá funcionalidades como selección de productos, cálculo automático, 
                gestión de clientes y generación de facturas.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('cafe.ventas.index') }}" class="btn btn-secondary">
                    ← Volver a Ventas
                </a>
                <a href="{{ route('cafe.productos.index') }}" class="btn btn-primary">
                    📦 Ver Productos
                </a>
            </div>
        </div>
    </div>
    
    <!-- Preview de la interfaz futura -->
    <div class="card mt-6">
        <h3 class="text-lg font-semibold text-coffee-800 mb-4">Vista Previa del Sistema (Próximamente)</h3>
        <div class="bg-gray-50 p-6 rounded-lg">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Lista de productos -->
                <div>
                    <h4 class="font-medium text-coffee-700 mb-3">📦 Selección de Productos</h4>
                    <div class="space-y-2">
                        <div class="p-3 bg-white rounded border">
                            <div class="flex justify-between items-center">
                                <span class="text-sm">Café Americano - $2.50</span>
                                <button class="text-xs bg-coffee-100 px-2 py-1 rounded">+ Agregar</button>
                            </div>
                        </div>
                        <div class="p-3 bg-white rounded border">
                            <div class="flex justify-between items-center">
                                <span class="text-sm">Cappuccino - $3.00</span>
                                <button class="text-xs bg-coffee-100 px-2 py-1 rounded">+ Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Carrito de compras -->
                <div>
                    <h4 class="font-medium text-coffee-700 mb-3">🛒 Carrito de Compras</h4>
                    <div class="bg-white p-4 rounded border">
                        <div class="text-center text-gray-400 py-4">
                            <p class="text-sm">Carrito vacío</p>
                        </div>
                        <div class="border-t pt-3 mt-3">
                            <div class="flex justify-between font-semibold">
                                <span>Total:</span>
                                <span>$0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection