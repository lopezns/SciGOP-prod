@extends('layouts.cafe')

@section('title', 'Editar Proveedor')
@section('subtitle', 'Actualizar información del proveedor')

@section('actions')
    <a href="{{ route('cafe.proveedores.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">←</span>
        Volver
    </a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6">
        <form method="POST" action="{{ route('cafe.proveedores.update', $proveedor) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-amber-700 mb-2">
                        Nombre del Proveedor *
                    </label>
                    <input type="text" 
                           id="nombre" 
                           name="nombre" 
                           value="{{ old('nombre', $proveedor->nombre) }}"
                           required
                           class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('nombre')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tipo de Documento -->
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-amber-700 mb-2">
                        Tipo de Documento *
                    </label>
                    <select id="tipo_documento" 
                            name="tipo_documento" 
                            required
                            class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <option value="nit" {{ old('tipo_documento', $proveedor->tipo_documento) === 'nit' ? 'selected' : '' }}>NIT</option>
                        <option value="cedula" {{ old('tipo_documento', $proveedor->tipo_documento) === 'cedula' ? 'selected' : '' }}>Cédula</option>
                        <option value="rut" {{ old('tipo_documento', $proveedor->tipo_documento) === 'rut' ? 'selected' : '' }}>RUT</option>
                    </select>
                    @error('tipo_documento')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Número de Documento -->
                <div>
                    <label for="numero_documento" class="block text-sm font-medium text-amber-700 mb-2">
                        Número de Documento *
                    </label>
                    <input type="text" 
                           id="numero_documento" 
                           name="numero_documento" 
                           value="{{ old('numero_documento', $proveedor->numero_documento) }}"
                           required
                           class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('numero_documento')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contacto -->
                <div>
                    <label for="contacto" class="block text-sm font-medium text-amber-700 mb-2">
                        Persona de Contacto
                    </label>
                    <input type="text" 
                           id="contacto" 
                           name="contacto" 
                           value="{{ old('contacto', $proveedor->contacto) }}"
                           class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('contacto')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-amber-700 mb-2">
                        Teléfono
                    </label>
                    <input type="text" 
                           id="telefono" 
                           name="telefono" 
                           value="{{ old('telefono', $proveedor->telefono) }}"
                           class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('telefono')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-amber-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $proveedor->email) }}"
                           class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="activo" class="block text-sm font-medium text-amber-700 mb-2">
                        Estado
                    </label>
                    <select id="activo" 
                            name="activo" 
                            class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                        <option value="1" {{ old('activo', $proveedor->activo) ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !old('activo', $proveedor->activo) ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('activo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dirección -->
            <div>
                <label for="direccion" class="block text-sm font-medium text-amber-700 mb-2">
                    Dirección
                </label>
                <textarea id="direccion" 
                         name="direccion" 
                         rows="3"
                         class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('direccion', $proveedor->direccion) }}</textarea>
                @error('direccion')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-4 pt-6">
                <a href="{{ route('cafe.proveedores.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors duration-200">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Actualizar Proveedor
                </button>
            </div>
        </form>
    </div>
</div>
@endsection