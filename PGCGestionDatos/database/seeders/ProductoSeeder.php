<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            // Bebidas Calientes
            ['nombre' => 'Café Americano', 'codigo' => 'CAF001', 'precio_compra' => 1.20, 'precio_venta' => 2.50, 'stock' => 100],
            ['nombre' => 'Café Espresso', 'codigo' => 'CAF002', 'precio_compra' => 1.00, 'precio_venta' => 2.00, 'stock' => 85],
            ['nombre' => 'Cappuccino', 'codigo' => 'CAF003', 'precio_compra' => 1.50, 'precio_venta' => 3.00, 'stock' => 75],
            ['nombre' => 'Latte', 'codigo' => 'CAF004', 'precio_compra' => 1.60, 'precio_venta' => 3.20, 'stock' => 60],
            ['nombre' => 'Macchiato', 'codigo' => 'CAF005', 'precio_compra' => 1.40, 'precio_venta' => 2.80, 'stock' => 45],
            ['nombre' => 'Mocha', 'codigo' => 'CAF006', 'precio_compra' => 2.00, 'precio_venta' => 4.00, 'stock' => 35],
            ['nombre' => 'Chocolate Caliente', 'codigo' => 'CHO001', 'precio_compra' => 1.80, 'precio_venta' => 3.50, 'stock' => 50],
            ['nombre' => 'Té Verde', 'codigo' => 'TEA001', 'precio_compra' => 0.80, 'precio_venta' => 1.80, 'stock' => 40],
            ['nombre' => 'Té Negro', 'codigo' => 'TEA002', 'precio_compra' => 0.85, 'precio_venta' => 1.90, 'stock' => 38],
            ['nombre' => 'Té de Manzanilla', 'codigo' => 'TEA003', 'precio_compra' => 0.90, 'precio_venta' => 2.00, 'stock' => 25],

            // Bebidas Frías
            ['nombre' => 'Café Frío', 'codigo' => 'CAFF001', 'precio_compra' => 1.30, 'precio_venta' => 2.80, 'stock' => 70],
            ['nombre' => 'Frappé de Vainilla', 'codigo' => 'FRA001', 'precio_compra' => 2.20, 'precio_venta' => 4.50, 'stock' => 30],
            ['nombre' => 'Frappé de Chocolate', 'codigo' => 'FRA002', 'precio_compra' => 2.30, 'precio_venta' => 4.70, 'stock' => 28],
            ['nombre' => 'Smoothie de Fresa', 'codigo' => 'SMO001', 'precio_compra' => 2.50, 'precio_venta' => 5.00, 'stock' => 20],
            ['nombre' => 'Jugo de Naranja Natural', 'codigo' => 'JUG001', 'precio_compra' => 1.50, 'precio_venta' => 3.00, 'stock' => 15],
            ['nombre' => 'Limonada', 'codigo' => 'LIM001', 'precio_compra' => 1.00, 'precio_venta' => 2.20, 'stock' => 25],

            // Pasteles y Dulces
            ['nombre' => 'Croissant Simple', 'codigo' => 'PAN001', 'precio_compra' => 1.00, 'precio_venta' => 2.50, 'stock' => 40],
            ['nombre' => 'Croissant de Almendras', 'codigo' => 'PAN002', 'precio_compra' => 1.20, 'precio_venta' => 3.00, 'stock' => 25],
            ['nombre' => 'Muffin de Arándanos', 'codigo' => 'MUF001', 'precio_compra' => 1.30, 'precio_venta' => 2.80, 'stock' => 35],
            ['nombre' => 'Muffin de Chocolate', 'codigo' => 'MUF002', 'precio_compra' => 1.35, 'precio_venta' => 2.90, 'stock' => 30],
            ['nombre' => 'Cheesecake', 'codigo' => 'CAK001', 'precio_compra' => 3.00, 'precio_venta' => 6.50, 'stock' => 12],
            ['nombre' => 'Tiramisu', 'codigo' => 'CAK002', 'precio_compra' => 3.50, 'precio_venta' => 7.00, 'stock' => 8],
            ['nombre' => 'Brownie', 'codigo' => 'BRO001', 'precio_compra' => 1.50, 'precio_venta' => 3.20, 'stock' => 20],
            ['nombre' => 'Cookie de Chips de Chocolate', 'codigo' => 'COO001', 'precio_compra' => 0.80, 'precio_venta' => 1.80, 'stock' => 50],

            // Snacks Salados
            ['nombre' => 'Sandwich de Jamón y Queso', 'codigo' => 'SAN001', 'precio_compra' => 2.50, 'precio_venta' => 5.50, 'stock' => 15],
            ['nombre' => 'Sandwich de Pollo', 'codigo' => 'SAN002', 'precio_compra' => 3.00, 'precio_venta' => 6.20, 'stock' => 12],
            ['nombre' => 'Sandwich Vegetariano', 'codigo' => 'SAN003', 'precio_compra' => 2.20, 'precio_venta' => 4.80, 'stock' => 10],
            ['nombre' => 'Bagel con Salmón', 'codigo' => 'BAG001', 'precio_compra' => 4.00, 'precio_venta' => 8.50, 'stock' => 6],
            ['nombre' => 'Empanada de Pollo', 'codigo' => 'EMP001', 'precio_compra' => 1.20, 'precio_venta' => 2.80, 'stock' => 25],
            ['nombre' => 'Empanada de Carne', 'codigo' => 'EMP002', 'precio_compra' => 1.30, 'precio_venta' => 2.90, 'stock' => 22],

            // Productos con stock bajo (para demostrar alertas)
            ['nombre' => 'Café Descafeinado', 'codigo' => 'CAF007', 'precio_compra' => 1.40, 'precio_venta' => 2.90, 'stock' => 8],
            ['nombre' => 'Té Chai', 'codigo' => 'TEA004', 'precio_compra' => 1.10, 'precio_venta' => 2.40, 'stock' => 5],
            ['nombre' => 'Panini de Tomate', 'codigo' => 'PAN003', 'precio_compra' => 2.80, 'precio_venta' => 5.80, 'stock' => 3],

            // Productos sin stock (para demostrar alertas)
            ['nombre' => 'Tarta de Frutas', 'codigo' => 'TAR001', 'precio_compra' => 4.50, 'precio_venta' => 9.00, 'stock' => 0],
            ['nombre' => 'Café de Especialidad', 'codigo' => 'CAF008', 'precio_compra' => 2.50, 'precio_venta' => 5.50, 'stock' => 0],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}