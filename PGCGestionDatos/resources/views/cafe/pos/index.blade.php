@extends('layouts.cafe')

@section('title', 'Punto de Venta')
@section('subtitle', 'Sistema POS para ventas rápidas')

@section('content')
<div class="flex gap-6 h-screen">
    <!-- Panel izquierdo: Productos y búsqueda -->
    <div class="flex-1 bg-white rounded-lg shadow-sm border border-orange-200 p-6 overflow-hidden flex flex-col">
        <!-- Búsqueda de productos -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" 
                       id="searchProduct" 
                       placeholder="Buscar productos por nombre o código..." 
                       class="w-full pl-10 pr-4 py-3 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent text-lg">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Grid de productos -->
        <div class="flex-1 overflow-y-auto">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="productsGrid">
                @foreach($productos as $producto)
                    <div class="product-card bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200 rounded-lg p-4 cursor-pointer hover:shadow-md transition-shadow"
                         data-product-id="{{ $producto->id }}"
                         data-product-name="{{ $producto->nombre }}"
                         data-product-price="{{ $producto->precio_venta }}"
                         data-product-stock="{{ $producto->stock }}"
                         onclick="addToCart({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->precio_venta }}, {{ $producto->stock }})">
                        <div class="text-center">
                            <div class="text-2xl mb-2">☕</div>
                            <h3 class="font-medium text-sm text-amber-900 mb-1">{{ $producto->nombre }}</h3>
                            <p class="text-xs text-amber-600 mb-2">{{ $producto->codigo }}</p>
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-amber-800">${{ number_format($producto->precio_venta, 0, '.', ',') }}</span>
                                <span class="text-amber-600">Stock: {{ $producto->stock }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Panel derecho: Carrito y facturación -->
    <div class="w-96 bg-white rounded-lg shadow-sm border border-orange-200 p-6 flex flex-col">
        <!-- Información del cliente -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-amber-800 mb-3">Cliente</h3>
            <select id="clienteSelect" class="w-full mb-2 p-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                <option value="">Cliente General</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->nombre }} ({{ $cliente->numero_documento }})</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <input type="text" id="clienteNombre" placeholder="Nombre cliente nuevo" class="flex-1 p-2 border border-orange-300 rounded-lg text-sm" disabled>
                <input type="text" id="clienteDocumento" placeholder="Documento" class="w-24 p-2 border border-orange-300 rounded-lg text-sm" disabled>
            </div>
            <button type="button" id="nuevoClienteBtn" class="text-xs text-amber-600 hover:text-amber-800 mt-1">+ Nuevo cliente</button>
        </div>

        <!-- Carrito de compras -->
        <div class="flex-1 mb-6">
            <h3 class="text-lg font-semibold text-amber-800 mb-3">Carrito de Compras</h3>
            <div id="cartItems" class="space-y-2 max-h-60 overflow-y-auto">
                <p class="text-gray-500 text-center py-8">No hay productos en el carrito</p>
            </div>
        </div>

        <!-- Totales -->
        <div class="border-t border-orange-200 pt-4 mb-6">
            <div class="flex justify-between items-center mb-2">
                <span class="text-amber-700">Subtotal:</span>
                <span class="font-semibold" id="subtotal">$0 COP</span>
            </div>
            <div class="flex justify-between items-center mb-2">
                <span class="text-amber-700">IVA (19%):</span>
                <span class="font-semibold" id="iva">$0 COP</span>
            </div>
            <div class="flex justify-between items-center text-lg font-bold border-t pt-2">
                <span class="text-amber-800">Total:</span>
                <span class="text-amber-800" id="total">$0 COP</span>
            </div>
        </div>

        <!-- Método de pago -->
        <div class="mb-6">
            <h4 class="text-sm font-semibold text-amber-800 mb-3">Método de Pago</h4>
            <div class="space-y-2">
                <button type="button" class="payment-method active w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-lg font-medium transition-all duration-200" data-method="efectivo">
                    <span class="text-lg">💵</span>
                    <span>Efectivo</span>
                </button>
                <button type="button" class="payment-method w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-lg font-medium transition-all duration-200" data-method="tarjeta">
                    <span class="text-lg">💳</span>
                    <span>Tarjeta</span>
                </button>
                <button type="button" class="payment-method w-full flex items-center justify-center space-x-2 px-4 py-3 rounded-lg font-medium transition-all duration-200" data-method="transferencia">
                    <span class="text-lg">📱</span>
                    <span>Transferencia</span>
                </button>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="space-y-3">
            <button type="button" id="processPayment" class="w-full bg-amber-600 text-white py-3 rounded-lg hover:bg-amber-700 font-semibold disabled:bg-gray-300" disabled>
                💳 Procesar Venta
            </button>
            <div class="flex gap-2">
                <button type="button" id="clearCart" class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2">
                    <span>🗑️</span>
                    <span>Limpiar</span>
                </button>
                <button type="button" id="previewInvoice" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center space-x-2 disabled:bg-gray-300" disabled>
                    <span>👁️</span>
                    <span>Vista Previa</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de vista previa de factura -->
<div id="invoiceModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Vista Previa de Factura</h3>
                    <button onclick="closeInvoiceModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="invoicePreview" class="border border-gray-200 p-6 rounded-lg bg-white">
                    <!-- Aquí se mostrará la vista previa -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-method {
    background: white;
    border: 2px solid #fed7aa;
    color: #92400e;
}
.payment-method:hover {
    background: #fed7aa;
    border-color: #f59e0b;
}
.payment-method.active {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border-color: #f59e0b;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    transform: translateY(-1px);
}
.product-card:hover {
    transform: scale(1.02);
}
</style>

<script>
let cart = [];
let selectedPaymentMethod = 'efectivo';

// Funciones del carrito
function addToCart(productId, productName, price, stock) {
    const existingItem = cart.find(item => item.producto_id === productId);
    
    if (existingItem) {
        if (existingItem.cantidad < stock) {
            existingItem.cantidad++;
            existingItem.subtotal = existingItem.cantidad * existingItem.precio;
        } else {
            alert('No hay suficiente stock disponible');
            return;
        }
    } else {
        cart.push({
            producto_id: productId,
            nombre: productName,
            precio: price,
            cantidad: 1,
            stock: stock,
            subtotal: price
        });
    }
    
    updateCartDisplay();
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.producto_id !== productId);
    updateCartDisplay();
}

function updateQuantity(productId, newQuantity) {
    const item = cart.find(item => item.producto_id === productId);
    if (item) {
        if (newQuantity > 0 && newQuantity <= item.stock) {
            item.cantidad = newQuantity;
            item.subtotal = item.cantidad * item.precio;
        } else if (newQuantity <= 0) {
            removeFromCart(productId);
            return;
        } else {
            alert('No hay suficiente stock disponible');
            return;
        }
    }
    updateCartDisplay();
}

function updateCartDisplay() {
    const cartContainer = document.getElementById('cartItems');
    const subtotalElement = document.getElementById('subtotal');
    const ivaElement = document.getElementById('iva');
    const totalElement = document.getElementById('total');
    const processButton = document.getElementById('processPayment');
    const previewButton = document.getElementById('previewInvoice');
    
    if (cart.length === 0) {
        cartContainer.innerHTML = '<p class="text-gray-500 text-center py-8">No hay productos en el carrito</p>';
        subtotalElement.textContent = '$0 COP';
        ivaElement.textContent = '$0 COP';
        totalElement.textContent = '$0 COP';
        processButton.disabled = true;
        previewButton.disabled = true;
        return;
    }
    
    let html = '';
    let subtotal = 0;
    
    cart.forEach(item => {
        subtotal += item.subtotal;
        html += `
            <div class="flex items-center justify-between bg-orange-50 p-3 rounded-lg mb-2">
                <div class="flex-1 min-w-0">
                    <div class="font-medium text-sm truncate">${item.nombre}</div>
                    <div class="text-xs text-gray-600">$${numberFormat(item.precio)} COP c/u</div>
                    <div class="flex items-center gap-2 mt-1">
                        <button onclick="updateQuantity(${item.producto_id}, ${item.cantidad - 1})" class="w-6 h-6 bg-red-500 text-white text-xs rounded hover:bg-red-600 flex items-center justify-center">-</button>
                        <span class="w-8 text-center text-sm font-semibold bg-white px-1 rounded">${item.cantidad}</span>
                        <button onclick="updateQuantity(${item.producto_id}, ${item.cantidad + 1})" class="w-6 h-6 bg-green-500 text-white text-xs rounded hover:bg-green-600 flex items-center justify-center">+</button>
                    </div>
                </div>
                <div class="w-20 text-right flex-shrink-0 ml-2">
                    <div class="font-semibold text-sm">$${numberFormat(item.subtotal)}</div>
                    <div class="text-xs text-gray-500">COP</div>
                </div>
            </div>
        `;
    });
    
    cartContainer.innerHTML = html;
    
    const iva = subtotal * 0.19;
    const total = subtotal + iva;
    
    subtotalElement.textContent = '$' + numberFormat(subtotal) + ' COP';
    ivaElement.textContent = '$' + numberFormat(iva) + ' COP';
    totalElement.textContent = '$' + numberFormat(total) + ' COP';
    
    processButton.disabled = false;
    previewButton.disabled = false;
}

// Función auxiliar para formatear números
function numberFormat(number) {
    return new Intl.NumberFormat('es-CO', { maximumFractionDigits: 0 }).format(parseFloat(number));
}

// Métodos de pago
document.querySelectorAll('.payment-method').forEach(button => {
    button.addEventListener('click', function() {
        document.querySelectorAll('.payment-method').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        selectedPaymentMethod = this.dataset.method;
    });
});

// Nuevo cliente
document.getElementById('nuevoClienteBtn').addEventListener('click', function() {
    const isEnabled = document.getElementById('clienteNombre').disabled;
    const nombreInput = document.getElementById('clienteNombre');
    const documentoInput = document.getElementById('clienteDocumento');
    const selectCliente = document.getElementById('clienteSelect');
    
    if (isEnabled) {
        nombreInput.disabled = false;
        documentoInput.disabled = false;
        selectCliente.disabled = true;
        selectCliente.value = '';
        this.textContent = '← Cliente existente';
        nombreInput.focus();
    } else {
        nombreInput.disabled = true;
        documentoInput.disabled = true;
        selectCliente.disabled = false;
        nombreInput.value = '';
        documentoInput.value = '';
        this.textContent = '+ Nuevo cliente';
    }
});

// Limpiar carrito
document.getElementById('clearCart').addEventListener('click', function() {
    cart = [];
    updateCartDisplay();
});

// Vista previa de factura
document.getElementById('previewInvoice').addEventListener('click', function() {
    if (cart.length === 0) return;
    
    const cliente = document.getElementById('clienteSelect').value ? 
        document.getElementById('clienteSelect').options[document.getElementById('clienteSelect').selectedIndex].text :
        (document.getElementById('clienteNombre').value || 'Cliente General');
    
    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const iva = subtotal * 0.19;
    const total = subtotal + iva;
    
    const invoiceHTML = generateInvoiceHTML(cliente, cart, subtotal, iva, total);
    document.getElementById('invoicePreview').innerHTML = invoiceHTML;
    document.getElementById('invoiceModal').classList.remove('hidden');
});

function closeInvoiceModal() {
    document.getElementById('invoiceModal').classList.add('hidden');
}

// Procesar venta
document.getElementById('processPayment').addEventListener('click', function() {
    if (cart.length === 0) return;
    
    const clienteId = document.getElementById('clienteSelect').value || null;
    const clienteNombre = document.getElementById('clienteNombre').value || null;
    const clienteDocumento = document.getElementById('clienteDocumento').value || null;
    
    const subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    const iva = subtotal * 0.19;
    const total = subtotal + iva;
    
    const data = {
        cliente_id: clienteId,
        cliente_nombre: clienteNombre,
        cliente_documento: clienteDocumento,
        items: cart,
        total: total,
        metodo_pago: selectedPaymentMethod,
        _token: '{{ csrf_token() }}'
    };
    
    this.disabled = true;
    this.textContent = 'Procesando...';
    
    fetch('{{ route("cafe.pos.procesar") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`¡Venta procesada exitosamente!\nFactura: ${data.numero_factura}\nTotal: $${numberFormat(data.total)} COP`);
            
            // Abrir factura en nueva ventana
            window.open(`{{ url('cafe/pos/factura') }}/${data.factura_id}`, '_blank');
            
            // Limpiar carrito
            cart = [];
            updateCartDisplay();
            
            // Reset cliente
            document.getElementById('clienteSelect').value = '';
            document.getElementById('clienteNombre').value = '';
            document.getElementById('clienteDocumento').value = '';
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la venta');
    })
    .finally(() => {
        this.disabled = false;
        this.textContent = '💳 Procesar Venta';
    });
});

// Búsqueda de productos
document.getElementById('searchProduct').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        const productName = card.dataset.productName.toLowerCase();
        if (productName.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

function generateInvoiceHTML(cliente, items, subtotal, iva, total) {
    let itemsHTML = '';
    items.forEach(item => {
        itemsHTML += `
            <tr>
                <td class="border px-4 py-2">${item.nombre}</td>
                <td class="border px-4 py-2 text-center">${item.cantidad}</td>
                <td class="border px-4 py-2 text-right">$${numberFormat(item.precio)} COP</td>
                <td class="border px-4 py-2 text-right">$${numberFormat(item.subtotal)} COP</td>
            </tr>
        `;
    });
    
    return `
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-coffee-900">SciGOP Café</h1>
            <p class="text-gray-600">Sistema de Gestión</p>
            <p class="text-sm text-gray-500">NIT: 900.123.456-7</p>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <h3 class="font-semibold mb-2">Cliente:</h3>
                <p>${cliente}</p>
            </div>
            <div class="text-right">
                <h3 class="font-semibold mb-2">Fecha:</h3>
                <p>${new Date().toLocaleDateString('es-CO')}</p>
                <p class="text-sm text-gray-600">${new Date().toLocaleTimeString('es-CO')}</p>
            </div>
        </div>
        
        <table class="w-full mb-6">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2 text-left">Producto</th>
                    <th class="border px-4 py-2 text-center">Cant.</th>
                    <th class="border px-4 py-2 text-right">Precio Unit.</th>
                    <th class="border px-4 py-2 text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                ${itemsHTML}
            </tbody>
        </table>
        
        <div class="text-right">
            <div class="flex justify-between mb-2">
                <span>Subtotal:</span>
                <span>$${numberFormat(subtotal)} COP</span>
            </div>
            <div class="flex justify-between mb-2">
                <span>IVA (19%):</span>
                <span>$${numberFormat(iva)} COP</span>
            </div>
            <div class="flex justify-between text-lg font-bold border-t pt-2">
                <span>Total:</span>
                <span>$${numberFormat(total)} COP</span>
            </div>
        </div>
        
        <div class="text-center mt-6 text-sm text-gray-600">
            <p>¡Gracias por su compra!</p>
            <p>Método de pago: ${selectedPaymentMethod.charAt(0).toUpperCase() + selectedPaymentMethod.slice(1)}</p>
        </div>
    `;
}

// Cerrar modal con Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeInvoiceModal();
    }
});

// Cerrar modal clickeando fuera
document.getElementById('invoiceModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeInvoiceModal();
    }
});
</script>
@endsection