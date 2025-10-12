<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FacturaVenta;
use Carbon\Carbon;

class FacturaVentaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facturas = [
            // Facturas de los últimos 3 meses
            ['cliente_id' => 1, 'numero_factura' => 'FAC-2024-001', 'fecha' => '2024-07-15', 'total' => 15.50, 'estado' => 'pagada'],
            ['cliente_id' => 2, 'numero_factura' => 'FAC-2024-002', 'fecha' => '2024-07-16', 'total' => 28.75, 'estado' => 'pagada'],
            ['cliente_id' => 3, 'numero_factura' => 'FAC-2024-003', 'fecha' => '2024-07-18', 'total' => 12.30, 'estado' => 'pagada'],
            ['cliente_id' => 4, 'numero_factura' => 'FAC-2024-004', 'fecha' => '2024-07-20', 'total' => 45.20, 'estado' => 'pagada'],
            ['cliente_id' => 5, 'numero_factura' => 'FAC-2024-005', 'fecha' => '2024-07-22', 'total' => 22.80, 'estado' => 'pagada'],
            ['cliente_id' => 21, 'numero_factura' => 'FAC-2024-006', 'fecha' => '2024-07-25', 'total' => 156.40, 'estado' => 'pagada'], // Empresa
            ['cliente_id' => 6, 'numero_factura' => 'FAC-2024-007', 'fecha' => '2024-07-28', 'total' => 18.90, 'estado' => 'pagada'],
            ['cliente_id' => 7, 'numero_factura' => 'FAC-2024-008', 'fecha' => '2024-08-02', 'total' => 33.50, 'estado' => 'pagada'],
            ['cliente_id' => 8, 'numero_factura' => 'FAC-2024-009', 'fecha' => '2024-08-05', 'total' => 27.40, 'estado' => 'pagada'],
            ['cliente_id' => 22, 'numero_factura' => 'FAC-2024-010', 'fecha' => '2024-08-08', 'total' => 89.75, 'estado' => 'pagada'], // Empresa
            
            ['cliente_id' => 9, 'numero_factura' => 'FAC-2024-011', 'fecha' => '2024-08-10', 'total' => 19.60, 'estado' => 'pagada'],
            ['cliente_id' => 10, 'numero_factura' => 'FAC-2024-012', 'fecha' => '2024-08-12', 'total' => 41.25, 'estado' => 'pagada'],
            ['cliente_id' => 11, 'numero_factura' => 'FAC-2024-013', 'fecha' => '2024-08-15', 'total' => 16.75, 'estado' => 'pagada'],
            ['cliente_id' => 12, 'numero_factura' => 'FAC-2024-014', 'fecha' => '2024-08-18', 'total' => 52.30, 'estado' => 'pagada'],
            ['cliente_id' => 23, 'numero_factura' => 'FAC-2024-015', 'fecha' => '2024-08-20', 'total' => 234.60, 'estado' => 'pagada'], // Empresa
            ['cliente_id' => 13, 'numero_factura' => 'FAC-2024-016', 'fecha' => '2024-08-25', 'total' => 24.80, 'estado' => 'pagada'],
            ['cliente_id' => 14, 'numero_factura' => 'FAC-2024-017', 'fecha' => '2024-08-28', 'total' => 37.90, 'estado' => 'pagada'],
            ['cliente_id' => 24, 'numero_factura' => 'FAC-2024-018', 'fecha' => '2024-09-02', 'total' => 145.20, 'estado' => 'pagada'], // Empresa
            ['cliente_id' => 15, 'numero_factura' => 'FAC-2024-019', 'fecha' => '2024-09-05', 'total' => 29.40, 'estado' => 'pagada'],
            ['cliente_id' => 16, 'numero_factura' => 'FAC-2024-020', 'fecha' => '2024-09-08', 'total' => 21.75, 'estado' => 'pagada'],
            
            // Facturas más recientes (septiembre)
            ['cliente_id' => 17, 'numero_factura' => 'FAC-2024-021', 'fecha' => '2024-09-10', 'total' => 18.50, 'estado' => 'pagada'],
            ['cliente_id' => 25, 'numero_factura' => 'FAC-2024-022', 'fecha' => '2024-09-12', 'total' => 178.90, 'estado' => 'pagada'], // Empresa
            ['cliente_id' => 18, 'numero_factura' => 'FAC-2024-023', 'fecha' => '2024-09-13', 'total' => 32.60, 'estado' => 'pendiente'],
            ['cliente_id' => 19, 'numero_factura' => 'FAC-2024-024', 'fecha' => '2024-09-14', 'total' => 26.80, 'estado' => 'pendiente'],
            ['cliente_id' => 20, 'numero_factura' => 'FAC-2024-025', 'fecha' => '2024-09-14', 'total' => 44.30, 'estado' => 'pagada'],
            
            // Algunas facturas pendientes y anuladas
            ['cliente_id' => 1, 'numero_factura' => 'FAC-2024-026', 'fecha' => '2024-09-13', 'total' => 23.40, 'estado' => 'pendiente'],
            ['cliente_id' => 3, 'numero_factura' => 'FAC-2024-027', 'fecha' => '2024-09-12', 'total' => 67.80, 'estado' => 'pendiente'],
            ['cliente_id' => 26, 'numero_factura' => 'FAC-2024-028', 'fecha' => '2024-09-11', 'total' => 298.50, 'estado' => 'pendiente'], // Empresa
            ['cliente_id' => 5, 'numero_factura' => 'FAC-2024-029', 'fecha' => '2024-09-10', 'total' => 15.20, 'estado' => 'anulada'],
            ['cliente_id' => 7, 'numero_factura' => 'FAC-2024-030', 'fecha' => '2024-09-09', 'total' => 38.90, 'estado' => 'anulada'],
        ];

        foreach ($facturas as $factura) {
            FacturaVenta::create($factura);
        }
    }
}