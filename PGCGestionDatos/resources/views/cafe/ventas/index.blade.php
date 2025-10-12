@extends('layouts.cafe')

@section('title', 'Ventas')
@section('subtitle', 'Gestión de ventas de la cafetería')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex space-x-4">
            <form method="GET" class="flex space-x-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar ventas..."
                       class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500">
                <button type="submit" class="px-4 py-2 bg-coffee-600 text-white rounded-lg hover:bg-coffee-700">
                    Buscar
                </button>
            </form>
        </div>
        
        <a href="{{ route('cafe.ventas.create') }}" class="btn btn-primary">
            🛒 Nueva Venta
        </a>
    </div>

    <!-- Estado de desarrollo -->
    <div class="card">
        <div class="text-center py-12">
            <div class="text-coffee-400 text-6xl mb-4">🛒</div>
            <h3 class="text-lg font-medium text-coffee-900 mb-2">Sistema de Ventas en Desarrollo</h3>
            <p class="text-coffee-600 mb-4">
                Esta funcionalidad estará disponible próximamente. 
                Mientras tanto, puedes gestionar los productos desde el módulo de inventario.
            </p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('cafe.productos.index') }}" class="btn btn-primary">
                    📦 Ver Productos
                </a>
                <a href="{{ route('cafe.dashboard') }}" class="btn btn-secondary">
                    🏠 Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection