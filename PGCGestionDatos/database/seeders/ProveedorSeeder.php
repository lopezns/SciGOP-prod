<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            // Proveedores de café y bebidas
            [
                'nombre' => 'Café Colombiano Premium S.A.S.',
                'tipo_documento' => 'nit',
                'numero_documento' => '900123456-7',
                'direccion' => 'Cra 15 #85-32, Bogotá, Colombia',
                'telefono' => '+57 1 234-5678',
                'email' => 'ventas@cafecolombiano.com',
                'contacto' => 'María González - Gerente de Ventas',
                'activo' => true
            ],
            [
                'nombre' => 'Distribuidora de Bebidas Los Andes Ltda.',
                'tipo_documento' => 'nit',
                'numero_documento' => '800987654-3',
                'direccion' => 'Av. Caracas #45-67, Medellín, Colombia',
                'telefono' => '+57 4 321-9876',
                'email' => 'compras@bebidasandes.com',
                'contacto' => 'Carlos Rodríguez - Director Comercial',
                'activo' => true
            ],
            [
                'nombre' => 'Tés e Infusiones Naturales S.A.',
                'tipo_documento' => 'nit',
                'numero_documento' => '900555888-1',
                'direccion' => 'Cl 72 #10-15, Cali, Colombia',
                'telefono' => '+57 2 555-0123',
                'email' => 'info@tesnaturales.co',
                'contacto' => 'Ana Martínez - Jefe de Ventas',
                'activo' => true
            ],
            
            // Proveedores de panadería y pastelería
            [
                'nombre' => 'Harinas y Masas El Trigal S.A.S.',
                'tipo_documento' => 'nit',
                'numero_documento' => '900111222-5',
                'direccion' => 'Zona Industrial, Km 5 Vía Bogotá, Colombia',
                'telefono' => '+57 1 777-8888',
                'email' => 'pedidos@eltrigal.com',
                'contacto' => 'Luis Pérez - Gerente de Producción',
                'activo' => true
            ],
            [
                'nombre' => 'Dulces y Postres Artesanales Ltda.',
                'tipo_documento' => 'nit',
                'numero_documento' => '800444555-9',
                'direccion' => 'Cra 20 #55-30, Bucaramanga, Colombia',
                'telefono' => '+57 7 444-5555',
                'email' => 'artesanales@dulcesypostres.co',
                'contacto' => 'Patricia Jiménez - Coordinadora de Ventas',
                'activo' => true
            ],
            
            // Proveedores de ingredientes y materias primas
            [
                'nombre' => 'Ingredientes Gourmet Internacional S.A.',
                'tipo_documento' => 'nit',
                'numero_documento' => '900777999-2',
                'direccion' => 'Zona Franca, Barranquilla, Colombia',
                'telefono' => '+57 5 666-7777',
                'email' => 'internacional@gourmet.com',
                'contacto' => 'Roberto Silva - Director de Importaciones',
                'activo' => true
            ],
            [
                'nombre' => 'Lácteos y Derivados La Pradera S.A.S.',
                'tipo_documento' => 'nit',
                'numero_documento' => '900333444-6',
                'direccion' => 'Vereda La Pradera, Chía, Colombia',
                'telefono' => '+57 1 888-9999',
                'email' => 'ventas@lapradera.co',
                'contacto' => 'Carmen Rodríguez - Jefe Comercial',
                'activo' => true
            ],
            
            // Proveedores de snacks y alimentos preparados
            [
                'nombre' => 'Alimentos Preparados Express Ltda.',
                'tipo_documento' => 'nit',
                'numero_documento' => '800222333-4',
                'direccion' => 'Cra 50 #23-45, Pereira, Colombia',
                'telefono' => '+57 6 222-3333',
                'email' => 'express@alimentosprep.com',
                'contacto' => 'Diego Morales - Gerente Regional',
                'activo' => true
            ],
            [
                'nombre' => 'Carnes y Embutidos Don Juan S.A.',
                'tipo_documento' => 'nit',
                'numero_documento' => '900888777-8',
                'direccion' => 'Autopista Norte Km 12, Bogotá, Colombia',
                'telefono' => '+57 1 999-0000',
                'email' => 'donjuan@carnesyembutidos.co',
                'contacto' => 'Fernando López - Director de Ventas',
                'activo' => true
            ],
            
            // Proveedor de frutas y verduras
            [
                'nombre' => 'Frutas y Verduras Frescas del Campo S.A.S.',
                'tipo_documento' => 'nit',
                'numero_documento' => '900456789-0',
                'direccion' => 'Central de Abastos, Local 15-20, Medellín, Colombia',
                'telefono' => '+57 4 456-7890',
                'email' => 'frescas@frutasdelcampo.co',
                'contacto' => 'Isabel Ramírez - Coordinadora de Distribución',
                'activo' => true
            ]
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}
