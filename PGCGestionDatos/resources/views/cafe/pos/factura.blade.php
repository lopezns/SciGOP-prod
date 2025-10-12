<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura {{ $factura->numero_factura }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            background: white;
            padding: 20px;
            max-width: 300px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 10px;
            margin-bottom: 2px;
        }
        
        .invoice-info {
            margin-bottom: 15px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        .items-table th,
        .items-table td {
            text-align: left;
            padding: 3px 2px;
            font-size: 11px;
        }
        
        .items-table th {
            border-bottom: 1px solid #000;
            font-weight: bold;
        }
        
        .items-table .qty,
        .items-table .price {
            text-align: right;
            width: 60px;
        }
        
        .totals {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        
        .total-final {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #000;
            font-size: 10px;
        }
        
        .print-button {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .print-button button {
            background: #4F46E5;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .print-button button:hover {
            background: #4338CA;
        }
        
        @media print {
            body {
                padding: 0;
                font-size: 11px;
            }
            
            .print-button {
                display: none;
            }
        }
        
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .status-pagada {
            background: #10B981;
            color: white;
        }
        
        .status-pendiente {
            background: #F59E0B;
            color: white;
        }
        
        .payment-method {
            text-transform: capitalize;
            font-weight: bold;
        }
        
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="print-button">
        <button onclick="window.print()">🖨️ Imprimir Factura</button>
        <button onclick="window.close()" style="background: #6B7280; margin-left: 10px;">✖️ Cerrar</button>
    </div>

    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <div class="company-name">SciGOP CAFÉ</div>
            <div class="company-info">Sistema de Gestión Empresarial</div>
            <div class="company-info">NIT: 900.123.456-7</div>
            <div class="company-info">Tel: (601) 123-4567</div>
            <div class="company-info">info@scigop.com</div>
        </div>

        <!-- Invoice Info -->
        <div class="invoice-info">
            <div class="info-row">
                <span>Factura:</span>
                <span><strong>{{ $factura->numero_factura }}</strong></span>
            </div>
            <div class="info-row">
                <span>Fecha:</span>
                <span>{{ $factura->fecha->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span>Hora:</span>
                <span>{{ $factura->created_at->format('H:i:s') }}</span>
            </div>
            <div class="info-row">
                <span>Cliente:</span>
                <span>{{ $factura->cliente->nombre }}</span>
            </div>
            @if($factura->cliente->numero_documento !== 'GENERICO-001')
                <div class="info-row">
                    <span>Documento:</span>
                    <span>{{ $factura->cliente->numero_documento }}</span>
                </div>
            @endif
            <div class="info-row">
                <span>Estado:</span>
                <span class="status-badge status-{{ $factura->estado }}">{{ strtoupper($factura->estado) }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>PRODUCTO</th>
                    <th class="qty">CANT</th>
                    <th class="price">PRECIO</th>
                    <th class="price">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($factura->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto->nombre }}</td>
                        <td class="qty">{{ $detalle->cantidad }}</td>
                        <td class="price">${{ number_format($detalle->precio_unitario, 0, ',', '.') }}</td>
                        <td class="price">${{ number_format($detalle->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals">
            @php
                $subtotal = $factura->detalles->sum('subtotal');
                $iva = $subtotal * 0.19;
                $descuento = $subtotal + $iva - $factura->total;
            @endphp
            
            <div class="total-row">
                <span>Subtotal:</span>
                <span>${{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            
            <div class="total-row">
                <span>IVA (19%):</span>
                <span>${{ number_format($iva, 0, ',', '.') }}</span>
            </div>
            
            @if($descuento > 0)
                <div class="total-row">
                    <span>Descuento:</span>
                    <span>-${{ number_format($descuento, 0, ',', '.') }}</span>
                </div>
            @endif
            
            <div class="total-row total-final">
                <span>TOTAL A PAGAR:</span>
                <span>${{ number_format($factura->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="dashed-line"></div>

        <!-- Payment Info -->
        <div class="invoice-info">
            <div class="info-row">
                <span>Método de pago:</span>
                <span class="payment-method">
                    @php
                        // Extraer método de pago de observaciones si existe
                        $metodoPago = 'Efectivo'; // Default
                        if($factura->detalles->count() > 0) {
                            $observaciones = $factura->detalles->first()->producto->movimientosInventario()
                                                ->where('motivo', 'Venta POS')
                                                ->whereRaw("observaciones LIKE '%{$factura->numero_factura}%'")
                                                ->latest()
                                                ->first();
                            if($observaciones && str_contains($observaciones->observaciones, 'Método:')) {
                                $metodoPago = trim(explode('Método:', $observaciones->observaciones)[1]);
                            }
                        }
                    @endphp
                    {{ ucfirst($metodoPago) }}
                </span>
            </div>
            @if($factura->estado === 'pagada')
                <div class="info-row">
                    <span>Pago recibido:</span>
                    <span>${{ number_format($factura->total, 0, ',', '.') }}</span>
                </div>
                <div class="info-row">
                    <span>Cambio:</span>
                    <span>$0</span>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>¡GRACIAS POR SU COMPRA!</p>
            <p>Vuelva pronto</p>
            <br>
            <p style="font-size: 9px;">
                Esta factura es válida como documento<br>
                tributario. Resolución DIAN No. 18764003565<br>
                del 15/08/2024. Rango autorizado:<br>
                POS000001 - POS999999
            </p>
            <br>
            <p style="font-size: 8px;">
                Software: SciGOP v1.0<br>
                Impreso: {{ now()->format('d/m/Y H:i:s') }}
            </p>
        </div>
    </div>

    <script>
        // Auto-imprimir después de 2 segundos si viene de POS
        if (document.referrer.includes('pos') || window.opener) {
            setTimeout(() => {
                window.print();
            }, 2000);
        }
        
        // Cerrar ventana después de imprimir
        window.addEventListener('afterprint', function() {
            setTimeout(() => {
                if (window.opener) {
                    window.close();
                }
            }, 1000);
        });
    </script>
</body>
</html>