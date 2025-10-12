<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Actualizar precios en tabla productos (multiplicar por 1000)
        DB::statement('UPDATE productos SET precio_compra = precio_compra * 1000');
        DB::statement('UPDATE productos SET precio_venta = precio_venta * 1000');
        
        // Actualizar totales en tabla facturas_venta (multiplicar por 1000)
        DB::statement('UPDATE facturas_venta SET total = total * 1000');
        
        // Si existen detalles de factura, también actualizarlos
        if (Schema::hasTable('detalle_factura_venta')) {
            if (Schema::hasColumn('detalle_factura_venta', 'precio_unitario')) {
                DB::statement('UPDATE detalle_factura_venta SET precio_unitario = precio_unitario * 1000');
            }
            if (Schema::hasColumn('detalle_factura_venta', 'subtotal')) {
                DB::statement('UPDATE detalle_factura_venta SET subtotal = subtotal * 1000');
            }
        }
        
        // Si existe tabla compras, actualizarla también
        if (Schema::hasTable('compras')) {
            if (Schema::hasColumn('compras', 'total')) {
                DB::statement('UPDATE compras SET total = total * 1000');
            }
        }
        
        // Si existe tabla detalle_compras, actualizarla
        if (Schema::hasTable('detalle_compras')) {
            if (Schema::hasColumn('detalle_compras', 'precio_unitario')) {
                DB::statement('UPDATE detalle_compras SET precio_unitario = precio_unitario * 1000');
            }
            if (Schema::hasColumn('detalle_compras', 'costo_total')) {
                DB::statement('UPDATE detalle_compras SET costo_total = costo_total * 1000');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir precios en tabla productos (dividir por 1000)
        DB::statement('UPDATE productos SET precio_compra = precio_compra / 1000');
        DB::statement('UPDATE productos SET precio_venta = precio_venta / 1000');
        
        // Revertir totales en tabla facturas_venta
        DB::statement('UPDATE facturas_venta SET total = total / 1000');
        
        // Revertir detalles de factura si existen
        if (Schema::hasTable('detalle_factura_venta')) {
            if (Schema::hasColumn('detalle_factura_venta', 'precio_unitario')) {
                DB::statement('UPDATE detalle_factura_venta SET precio_unitario = precio_unitario / 1000');
            }
            if (Schema::hasColumn('detalle_factura_venta', 'subtotal')) {
                DB::statement('UPDATE detalle_factura_venta SET subtotal = subtotal / 1000');
            }
        }
        
        // Revertir tabla compras si existe
        if (Schema::hasTable('compras')) {
            if (Schema::hasColumn('compras', 'total')) {
                DB::statement('UPDATE compras SET total = total / 1000');
            }
        }
        
        // Revertir detalle_compras si existe
        if (Schema::hasTable('detalle_compras')) {
            if (Schema::hasColumn('detalle_compras', 'precio_unitario')) {
                DB::statement('UPDATE detalle_compras SET precio_unitario = precio_unitario / 1000');
            }
            if (Schema::hasColumn('detalle_compras', 'costo_total')) {
                DB::statement('UPDATE detalle_compras SET costo_total = costo_total / 1000');
            }
        }
    }
};
