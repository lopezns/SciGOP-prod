@extends('layouts.cafe')

@section('title', 'Clientes')
@section('subtitle', 'Gestión de clientes de la cafetería')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div class="flex space-x-4">
            <form method="GET" class="flex space-x-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Buscar clientes..."
                       class="px-4 py-2 border border-cream-300 rounded-lg focus:ring-2 focus:ring-coffee-500">
                <button type="submit" class="px-4 py-2 bg-coffee-600 text-white rounded-lg hover:bg-coffee-700">
                    Buscar
                </button>
            </form>
        </div>
        
        <a href="{{ route('cafe.clientes.create') }}" class="btn btn-primary">
            👤 Nuevo Cliente
        </a>
    </div>

    <!-- Lista de clientes -->
    <div class="card overflow-hidden">
        @if($clientes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-cream-200">
                    <thead class="bg-cream-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Documento
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Contacto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Tipo
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-coffee-600 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-cream-100">
                        @foreach($clientes as $cliente)
                            <tr class="hover:bg-cream-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-coffee-900">{{ $cliente->nombre }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-coffee-600">{{ $cliente->tipo_documento }}: {{ $cliente->numero_documento }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-coffee-900">{{ $cliente->telefono ?? 'N/A' }}</div>
                                    <div class="text-sm text-coffee-500">{{ $cliente->email ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($cliente->tipo_documento === 'NIT')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Empresa
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Persona
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('cafe.clientes.show', $cliente) }}" class="text-coffee-600 hover:text-coffee-900">
                                        👁️
                                    </a>
                                    <a href="{{ route('cafe.clientes.edit', $cliente) }}" class="text-blue-600 hover:text-blue-900">
                                        ✏️
                                    </a>
                                    <form method="POST" action="{{ route('cafe.clientes.destroy', $cliente) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('¿Estás seguro de que quieres eliminar este cliente?')">
                                            🗑️
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="px-6 py-4 border-t border-cream-200">
                {{ $clientes->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-coffee-400 text-6xl mb-4">👥</div>
                <h3 class="text-lg font-medium text-coffee-900 mb-2">No hay clientes</h3>
                <p class="text-coffee-600 mb-4">
                    @if(request('search'))
                        No se encontraron clientes que coincidan con la búsqueda.
                    @else
                        Comienza agregando tu primer cliente a la cafetería.
                    @endif
                </p>
                <a href="{{ route('cafe.clientes.create') }}" class="btn btn-primary">
                    👤 Agregar Primer Cliente
                </a>
            </div>
        @endif
    </div>
</div>
@endsection