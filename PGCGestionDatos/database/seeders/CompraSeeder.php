<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Compra;
use App\Models\DetalleCompra;
use App\Models\Producto;
use App\Models\Proveedor;
use Carbon\Carbon;

class CompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener proveedores y productos
        $proveedores = Proveedor::all();
        $productos = Producto::all();
        
        if ($proveedores->isEmpty() || $productos->isEmpty()) {
            $this->command->warn('No hay proveedores o productos para crear compras.');
            return;
        }

        $compras = [
            // Compra 1: Café Colombiano Premium - Bebidas calientes
            [
                'proveedor_id' => 1, // Café Colombiano Premium
                'numero_factura' => 'FC-2024-001',
                'fecha' => Carbon::now()->subDays(30)->format('Y-m-d'),
                'estado' => 'recibida',
                'observaciones' => 'Pedido de reposición mensual de café',
                'productos' => [
                    ['nombre' => 'Café Americano', 'cantidad' => 50, 'precio_unitario' => 1200],
                    ['nombre' => 'Café Espresso', 'cantidad' => 40, 'precio_unitario' => 1000],
                    ['nombre' => 'Cappuccino', 'cantidad' => 30, 'precio_unitario' => 1500],
                    ['nombre' => 'Latte', 'cantidad' => 25, 'precio_unitario' => 1600],
                ]
            ],
            
            // Compra 2: Distribuidora Los Andes - Bebidas frías y chocolate
            [
                'proveedor_id' => 2, // Distribuidora Los Andes
                'numero_factura' => 'ANDES-2024-045',
                'fecha' => Carbon::now()->subDays(25)->format('Y-m-d'),
                'estado' => 'recibida',
                'observaciones' => 'Pedido especial para temporada de calor',
                'productos' => [
                    ['nombre' => 'Café Frío', 'cantidad' => 35, 'precio_unitario' => 1300],
                    ['nombre' => 'Frappé de Vainilla', 'cantidad' => 20, 'precio_unitario' => 2200],
                    ['nombre' => 'Frappé de Chocolate', 'cantidad' => 18, 'precio_unitario' => 2300],
                    ['nombre' => 'Chocolate Caliente', 'cantidad' => 25, 'precio_unitario' => 1800],
                ]
            ],
            
            // Compra 3: Tés e Infusiones - Tés y bebidas naturales
            [
                'proveedor_id' => 3, // Tés e Infusiones Naturales
                'numero_factura' => 'TIN-2024-078',
                'fecha' => Carbon::now()->subDays(20)->format('Y-m-d'),
                'estado' => 'recibida',
                'observaciones' => 'Variedad de tés premium',
                'productos' => [
                    ['nombre' => 'Té Verde', 'cantidad' => 30, 'precio_unitario' => 800],
                    ['nombre' => 'Té Negro', 'cantidad' => 28, 'precio_unitario' => 850],
                    ['nombre' => 'Té de Manzanilla', 'cantidad' => 20, 'precio_unitario' => 900],
                    ['nombre' => 'Té Chai', 'cantidad' => 15, 'precio_unitario' => 1100],
                ]
            ],
            
            // Compra 4: Harinas El Trigal - Productos de panadería
            [
                'proveedor_id' => 4, // Harinas El Trigal
                'numero_factura' => 'TRIGAL-2024-156',
                'fecha' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'estado' => 'recibida',
                'observaciones' => 'Productos frescos de panadería',
                'productos' => [
                    ['nombre' => 'Croissant Simple', 'cantidad' => 40, 'precio_unitario' => 1000],
                    ['nombre' => 'Croissant de Almendras', 'cantidad' => 25, 'precio_unitario' => 1200],
                    ['nombre' => 'Muffin de Arándanos', 'cantidad' => 35, 'precio_unitario' => 1300],
                    ['nombre' => 'Muffin de Chocolate', 'cantidad' => 30, 'precio_unitario' => 1350],
                ]
            ],
            
            // Compra 5: Dulces Artesanales - Postres y dulces
            [
                'proveedor_id' => 5, // Dulces y Postres Artesanales
                'numero_factura' => 'DPA-2024-089',
                'fecha' => Carbon::now()->subDays(12)->format('Y-m-d'),
                'estado' => 'recibida',
                'observaciones' => 'Postres gourmet para ocasiones especiales',
                'productos' => [
                    ['nombre' => 'Cheesecake', 'cantidad' => 12, 'precio_unitario' => 3000],
                    ['nombre' => 'Tiramisu', 'cantidad' => 8, 'precio_unitario' => 3500],
                    ['nombre' => 'Brownie', 'cantidad' => 20, 'precio_unitario' => 1500],
                    ['nombre' => 'Cookie de Chips de Chocolate', 'cantidad' => 50, 'precio_unitario' => 800],
                ]
            ],
            
            // Compra 6: Alimentos Express - Snacks y comida preparada
            [
                'proveedor_id' => 8, // Alimentos Preparados Express
                'numero_factura' => 'EXPRESS-2024-234',
                'fecha' => Carbon::now()->subDays(8)->format('Y-m-d'),
                'estado' => 'recibida',
                'observaciones' => 'Pedido de snacks y comida rápida',
                'productos' => [
                    ['nombre' => 'Sandwich de Jamón y Queso', 'cantidad' => 15, 'precio_unitario' => 2500],
                    ['nombre' => 'Sandwich de Pollo', 'cantidad' => 12, 'precio_unitario' => 3000],
                    ['nombre' => 'Sandwich Vegetariano', 'cantidad' => 10, 'precio_unitario' => 2200],
                    ['nombre' => 'Empanada de Pollo', 'cantidad' => 25, 'precio_unitario' => 1200],
                    ['nombre' => 'Empanada de Carne', 'cantidad' => 22, 'precio_unitario' => 1300],
                ]
            ],
            
            // Compra 7: Frutas del Campo - Jugos y smoothies
            [
                'proveedor_id' => 10, // Frutas del Campo
                'numero_factura' => 'FRUTAS-2024-167',
                'fecha' => Carbon::now()->subDays(5)->format('Y-m-d'),
                'estado' => 'recibida',
                'observaciones' => 'Frutas frescas para jugos y smoothies',
                'productos' => [
                    ['nombre' => 'Smoothie de Fresa', 'cantidad' => 20, 'precio_unitario' => 2500],
                    ['nombre' => 'Jugo de Naranja Natural', 'cantidad' => 15, 'precio_unitario' => 1500],
                    ['nombre' => 'Limonada', 'cantidad' => 25, 'precio_unitario' => 1000],
                ]
            ],
            
            // Compra 8: Compra pendiente
            [
                'proveedor_id' => 6, // Ingredientes Gourmet Internacional
                'numero_factura' => 'IGI-2024-345',
                'fecha' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'estado' => 'pendiente',
                'observaciones' => 'Pedido de productos premium - en tránsito',
                'productos' => [
                    ['nombre' => 'Bagel con Salmón', 'cantidad' => 6, 'precio_unitario' => 4000],
                    ['nombre' => 'Panini de Tomate', 'cantidad' => 10, 'precio_unitario' => 2800],
                    ['nombre' => 'Tarta de Frutas', 'cantidad' => 5, 'precio_unitario' => 4500],
                ]
            ]
        ];

        foreach ($compras as $compraData) {
            // Crear la compra
            $total = 0;
            $productos = $compraData['productos'];
            unset($compraData['productos']);
            
            $compra = Compra::create(array_merge($compraData, ['total' => 0, 'usuario_id' => null]));
            
            // Crear los detalles de compra
            foreach ($productos as $productoData) {
                $producto = Producto::where('nombre', $productoData['nombre'])->first();
                if ($producto) {
                    $subtotal = $productoData['cantidad'] * $productoData['precio_unitario'];
                    $total += $subtotal;
                    
                    DetalleCompra::create([
                        'compra_id' => $compra->id,
                        'producto_id' => $producto->id,
                        'cantidad' => $productoData['cantidad'],
                        'precio_unitario' => $productoData['precio_unitario'],
                        'subtotal' => $subtotal
                    ]);
                }
            }
            
            // Actualizar el total de la compra
            $compra->update(['total' => $total]);
        }
    }
}
