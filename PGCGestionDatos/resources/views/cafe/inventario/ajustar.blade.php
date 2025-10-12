@extends('layouts.cafe')

@section('title', 'Ajuste de Inventario')
@section('subtitle', 'Corrección manual de stock de productos')

@section('actions')
    <a href="{{ route('cafe.inventario.movimientos') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
        <span class="mr-2">📈</span>
        Ver Movimientos
    </a>
@endsection

@section('content')
<!-- Formulario de ajuste -->
<div class="bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6 border-b border-orange-100">
        <h2 class="text-lg font-semibold text-amber-800">Ajustar Stock de Productos</h2>
        <p class="text-sm text-amber-600 mt-1">Realiza correcciones manuales en el stock cuando sea necesario</p>
    </div>

    <form action="{{ route('cafe.inventario.procesar-ajuste') }}" method="POST" class="p-6">
        @csrf
        
        <!-- Búsqueda de productos -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-amber-800 mb-2">Buscar Producto</label>
            <div class="flex gap-4">
                <div class="flex-1">
                    <input type="text" 
                           id="producto-search"
                           placeholder="Buscar por nombre o código del producto..." 
                           class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                           autocomplete="off">
                    
                    <!-- Resultados de búsqueda -->
                    <div id="search-results" class="hidden absolute z-10 w-full bg-white border border-orange-300 rounded-lg shadow-lg mt-1 max-h-60 overflow-y-auto">
                        <!-- Los resultados se llenarán con JavaScript -->
                    </div>
                </div>
                
                <button type="button" id="scan-barcode" class="px-4 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors duration-200">
                    📱 Escanear
                </button>
            </div>
        </div>

        <!-- Lista de productos seleccionados -->
        <div class="mb-6">
            <h3 class="text-md font-semibold text-amber-800 mb-4">Productos a Ajustar</h3>
            
            <div id="selected-products" class="space-y-4">
                <!-- Ejemplo de producto para mostrar la estructura -->
                <div class="hidden" id="product-template">
                    <div class="flex items-center gap-4 p-4 bg-orange-50 rounded-lg border border-orange-200">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                <span class="text-amber-600 text-lg">☕</span>
                            </div>
                        </div>
                        
                        <div class="flex-1">
                            <div class="font-medium text-amber-900 product-name">Producto</div>
                            <div class="text-sm text-amber-600 product-code">Código</div>
                        </div>
                        
                        <div class="text-center">
                            <label class="block text-xs font-medium text-amber-700 mb-1">Stock Actual</label>
                            <div class="text-sm font-semibold text-amber-800 current-stock">0</div>
                        </div>
                        
                        <div class="text-center">
                            <label class="block text-xs font-medium text-amber-700 mb-1">Nuevo Stock</label>
                            <input type="number" 
                                   class="w-20 px-3 py-2 text-center border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent new-stock"
                                   min="0" 
                                   step="1">
                        </div>
                        
                        <div class="text-center">
                            <label class="block text-xs font-medium text-amber-700 mb-1">Diferencia</label>
                            <div class="text-sm font-semibold difference text-gray-500">0</div>
                        </div>
                        
                        <div class="text-center min-w-32">
                            <label class="block text-xs font-medium text-amber-700 mb-1">Motivo</label>
                            <select class="px-3 py-2 text-sm border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 reason">
                                <option value="inventario_fisico">Inventario físico</option>
                                <option value="merma">Merma</option>
                                <option value="vencimiento">Vencimiento</option>
                                <option value="averia">Avería</option>
                                <option value="error_registro">Error de registro</option>
                                <option value="otros">Otros</option>
                            </select>
                        </div>
                        
                        <button type="button" class="text-red-600 hover:text-red-800 transition-colors remove-product" title="Eliminar">
                            🗑️
                        </button>
                    </div>
                </div>
                
                <!-- Mensaje cuando no hay productos -->
                <div id="no-products-message" class="text-center py-8 text-amber-600">
                    <span class="text-4xl mb-2 block">📦</span>
                    <p>No hay productos seleccionados para ajustar</p>
                    <p class="text-sm">Usa la búsqueda de arriba para agregar productos</p>
                </div>
            </div>
        </div>

        <!-- Motivo general del ajuste -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-amber-800 mb-2">Observaciones Generales</label>
            <textarea name="observaciones" 
                      class="w-full px-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                      rows="3"
                      placeholder="Describe el motivo general del ajuste (opcional)..."></textarea>
        </div>

        <!-- Botones de acción -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('cafe.inventario.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors duration-200">
                Cancelar
            </a>
            <button type="submit" 
                    id="submit-adjustment"
                    class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors duration-200 disabled:bg-gray-300" 
                    disabled>
                ⚖️ Procesar Ajuste
            </button>
        </div>
    </form>
</div>

<!-- Historial de ajustes recientes -->
<div class="mt-6 bg-white rounded-xl shadow-sm border border-orange-200 overflow-hidden">
    <div class="p-6 border-b border-orange-100">
        <h2 class="text-lg font-semibold text-amber-800">Ajustes Recientes</h2>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-orange-200">
            <thead class="bg-orange-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Productos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Total Ajustado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Usuario</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-amber-800 uppercase tracking-wider">Observaciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-orange-200">
                @for($i = 0; $i < 5; $i++)
                <tr class="hover:bg-orange-50 transition-colors duration-200">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        <div>{{ date('d/m/Y', strtotime('-' . $i . ' days')) }}</div>
                        <div class="text-xs text-amber-600">{{ date('H:i', strtotime('14:' . (30 + $i * 5) . ':00')) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        {{ rand(1, 5) }} producto(s)
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @php $total = rand(-20, 50); @endphp
                        <span class="{{ $total >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $total >= 0 ? '+' : '' }}{{ $total }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-amber-700">
                        Admin
                    </td>
                    <td class="px-6 py-4 text-sm text-amber-700 max-w-xs truncate">
                        {{ ['Inventario físico mensual', 'Corrección por merma', 'Ajuste por vencimiento', 'Error en registro anterior'][$i % 4] }}
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedProducts = [];
const productsData = [
    {id: 1, nombre: 'Café Americano', codigo: 'CAF-001', stock: 45},
    {id: 2, nombre: 'Cappuccino', codigo: 'CAP-002', stock: 32},
    {id: 3, nombre: 'Latte', codigo: 'LAT-003', stock: 28},
    {id: 4, nombre: 'Espresso', codigo: 'ESP-004', stock: 55},
    {id: 5, nombre: 'Mocha', codigo: 'MOC-005', stock: 18}
];

// Búsqueda de productos
const searchInput = document.getElementById('producto-search');
const searchResults = document.getElementById('search-results');

searchInput.addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase().trim();
    
    if (query.length < 2) {
        searchResults.classList.add('hidden');
        return;
    }
    
    const filtered = productsData.filter(product => 
        product.nombre.toLowerCase().includes(query) || 
        product.codigo.toLowerCase().includes(query)
    );
    
    if (filtered.length > 0) {
        searchResults.innerHTML = filtered.map(product => `
            <div class="px-4 py-3 hover:bg-orange-50 cursor-pointer border-b border-orange-100 last:border-0" 
                 onclick="selectProduct(${product.id})">
                <div class="font-medium text-amber-900">${product.nombre}</div>
                <div class="text-sm text-amber-600">${product.codigo} - Stock actual: ${product.stock}</div>
            </div>
        `).join('');
        searchResults.classList.remove('hidden');
    } else {
        searchResults.classList.add('hidden');
    }
});

// Cerrar resultados al hacer clic fuera
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.classList.add('hidden');
    }
});

// Seleccionar producto
function selectProduct(productId) {
    const product = productsData.find(p => p.id === productId);
    if (!product || selectedProducts.find(p => p.id === productId)) {
        return;
    }
    
    selectedProducts.push(product);
    renderSelectedProducts();
    
    searchInput.value = '';
    searchResults.classList.add('hidden');
}

// Renderizar productos seleccionados
function renderSelectedProducts() {
    const container = document.getElementById('selected-products');
    const template = document.getElementById('product-template');
    const noProductsMessage = document.getElementById('no-products-message');
    
    // Limpiar productos existentes
    container.querySelectorAll('.product-item').forEach(item => item.remove());
    
    if (selectedProducts.length === 0) {
        noProductsMessage.style.display = 'block';
        updateSubmitButton();
        return;
    }
    
    noProductsMessage.style.display = 'none';
    
    selectedProducts.forEach((product, index) => {
        const productElement = template.cloneNode(true);
        productElement.classList.remove('hidden');
        productElement.classList.add('product-item');
        productElement.id = `product-${product.id}`;
        
        productElement.querySelector('.product-name').textContent = product.nombre;
        productElement.querySelector('.product-code').textContent = product.codigo;
        productElement.querySelector('.current-stock').textContent = product.stock;
        
        const newStockInput = productElement.querySelector('.new-stock');
        const differenceElement = productElement.querySelector('.difference');
        
        newStockInput.value = product.stock;
        newStockInput.name = `productos[${index}][nuevo_stock]`;
        
        // Input hidden para datos del producto
        productElement.innerHTML += `
            <input type="hidden" name="productos[${index}][id]" value="${product.id}">
            <input type="hidden" name="productos[${index}][stock_actual]" value="${product.stock}">
        `;
        
        // Event listener para calcular diferencia
        newStockInput.addEventListener('input', function() {
            const difference = parseInt(this.value || 0) - product.stock;
            differenceElement.textContent = difference >= 0 ? `+${difference}` : difference;
            differenceElement.className = `text-sm font-semibold difference ${difference >= 0 ? 'text-green-600' : 'text-red-600'}`;
            
            const reasonSelect = productElement.querySelector('.reason');
            reasonSelect.name = `productos[${index}][motivo]`;
            
            updateSubmitButton();
        });
        
        // Botón eliminar
        productElement.querySelector('.remove-product').addEventListener('click', function() {
            removeProduct(product.id);
        });
        
        container.appendChild(productElement);
        
        // Trigger inicial para calcular diferencia
        newStockInput.dispatchEvent(new Event('input'));
    });
}

// Eliminar producto
function removeProduct(productId) {
    selectedProducts = selectedProducts.filter(p => p.id !== productId);
    renderSelectedProducts();
}

// Actualizar estado del botón submit
function updateSubmitButton() {
    const submitButton = document.getElementById('submit-adjustment');
    const hasProducts = selectedProducts.length > 0;
    const hasChanges = selectedProducts.some(product => {
        const productElement = document.getElementById(`product-${product.id}`);
        if (!productElement) return false;
        const newStock = parseInt(productElement.querySelector('.new-stock').value || 0);
        return newStock !== product.stock;
    });
    
    submitButton.disabled = !(hasProducts && hasChanges);
}

// Inicializar
renderSelectedProducts();
</script>
@endpush