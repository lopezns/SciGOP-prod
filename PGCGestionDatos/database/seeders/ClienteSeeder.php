<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = [
            // Clientes con cédula
            ['nombre' => 'María García López', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567890', 'direccion' => 'Calle 123 #45-67', 'telefono' => '300-123-4567', 'email' => 'maria.garcia@email.com'],
            ['nombre' => 'Carlos Rodríguez Pérez', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567891', 'direccion' => 'Carrera 45 #12-34', 'telefono' => '301-234-5678', 'email' => 'carlos.rodriguez@email.com'],
            ['nombre' => 'Ana Martínez Silva', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567892', 'direccion' => 'Avenida 67 #89-01', 'telefono' => '302-345-6789', 'email' => 'ana.martinez@email.com'],
            ['nombre' => 'Luis Hernández Torres', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567893', 'direccion' => 'Diagonal 23 #56-78', 'telefono' => '303-456-7890', 'email' => 'luis.hernandez@email.com'],
            ['nombre' => 'Elena Vargas Ruiz', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567894', 'direccion' => 'Transversal 34 #90-12', 'telefono' => '304-567-8901', 'email' => 'elena.vargas@email.com'],
            ['nombre' => 'Jorge Morales Castro', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567895', 'direccion' => 'Circular 78 #23-45', 'telefono' => '305-678-9012', 'email' => 'jorge.morales@email.com'],
            ['nombre' => 'Sofía Delgado Jiménez', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567896', 'direccion' => 'Autopista 12 #67-89', 'telefono' => '306-789-0123', 'email' => 'sofia.delgado@email.com'],
            ['nombre' => 'Roberto Guzmán Soto', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567897', 'direccion' => 'Boulevard 56 #01-23', 'telefono' => '307-890-1234', 'email' => 'roberto.guzman@email.com'],
            ['nombre' => 'Patricia Herrera Vega', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567898', 'direccion' => 'Pasaje 89 #45-67', 'telefono' => '308-901-2345', 'email' => 'patricia.herrera@email.com'],
            ['nombre' => 'Fernando Ramos Ortiz', 'tipo_documento' => 'cedula', 'numero_documento' => '1234567899', 'direccion' => 'Glorieta 34 #78-90', 'telefono' => '309-012-3456', 'email' => 'fernando.ramos@email.com'],
            
            // Más clientes individuales
            ['nombre' => 'Claudia Mendoza Ríos', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678901', 'direccion' => 'Sector Norte #123-45', 'telefono' => '310-123-4567', 'email' => 'claudia.mendoza@email.com'],
            ['nombre' => 'Diego Castillo Aguilar', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678902', 'direccion' => 'Barrio Centro #67-89', 'telefono' => '311-234-5678', 'email' => 'diego.castillo@email.com'],
            ['nombre' => 'Isabel Franco Núñez', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678903', 'direccion' => 'Zona Sur #90-12', 'telefono' => '312-345-6789', 'email' => 'isabel.franco@email.com'],
            ['nombre' => 'Andrés Peña Guerrero', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678904', 'direccion' => 'Urbanización #34-56', 'telefono' => '313-456-7890', 'email' => 'andres.pena@email.com'],
            ['nombre' => 'Beatriz Salinas Cruz', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678905', 'direccion' => 'Residencial #78-01', 'telefono' => '314-567-8901', 'email' => 'beatriz.salinas@email.com'],
            ['nombre' => 'Gabriel Flores Medina', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678906', 'direccion' => 'Conjunto #23-45', 'telefono' => '315-678-9012', 'email' => 'gabriel.flores@email.com'],
            ['nombre' => 'Valeria Sandoval León', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678907', 'direccion' => 'Multifamiliar #67-89', 'telefono' => '316-789-0123', 'email' => 'valeria.sandoval@email.com'],
            ['nombre' => 'Maximiliano Ibarra Rojas', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678908', 'direccion' => 'Villa #01-23', 'telefono' => '317-890-1234', 'email' => 'maximiliano.ibarra@email.com'],
            ['nombre' => 'Lorena Padilla Moreno', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678909', 'direccion' => 'Hacienda #45-67', 'telefono' => '318-901-2345', 'email' => 'lorena.padilla@email.com'],
            ['nombre' => 'Sebastián Acosta Paredes', 'tipo_documento' => 'cedula', 'numero_documento' => '2345678910', 'direccion' => 'Finca #78-90', 'telefono' => '319-012-3456', 'email' => 'sebastian.acosta@email.com'],

            // Empresas con NIT
            ['nombre' => 'Panadería El Buen Pan S.A.S.', 'tipo_documento' => 'nit', 'numero_documento' => '900123456-1', 'direccion' => 'Centro Comercial Plaza Mayor Local 101', 'telefono' => '601-234-5678', 'email' => 'info@elbuentpan.com'],
            ['nombre' => 'Restaurante La Mesa Redonda Ltda.', 'tipo_documento' => 'nit', 'numero_documento' => '900234567-2', 'direccion' => 'Zona Rosa Calle 85 #15-23', 'telefono' => '601-345-6789', 'email' => 'contacto@lamesdaredonda.com'],
            ['nombre' => 'Hotel Boutique Casa Blanca S.A.S.', 'tipo_documento' => 'nit', 'numero_documento' => '900345678-3', 'direccion' => 'Carrera 13 #93-07', 'telefono' => '601-456-7890', 'email' => 'reservas@hotelcasablanca.com'],
            ['nombre' => 'Cafetería Aroma y Sabor E.U.', 'tipo_documento' => 'nit', 'numero_documento' => '900456789-4', 'direccion' => 'Universidad Nacional Edificio 471', 'telefono' => '601-567-8901', 'email' => 'pedidos@aromaypasion.com'],
            ['nombre' => 'Supermercado Fresh Market S.A.S.', 'tipo_documento' => 'nit', 'numero_documento' => '900567890-5', 'direccion' => 'Avenida 68 #45-67', 'telefono' => '601-678-9012', 'email' => 'gerencia@freshmarket.com'],
            ['nombre' => 'Oficinas Torres del Parque Ltda.', 'tipo_documento' => 'nit', 'numero_documento' => '900678901-6', 'direccion' => 'Torres del Parque Torre A Piso 15', 'telefono' => '601-789-0123', 'email' => 'administracion@torresparque.com'],
            ['nombre' => 'Clínica Dental Sonrisas S.A.S.', 'tipo_documento' => 'nit', 'numero_documento' => '900789012-7', 'direccion' => 'Carrera 15 #123-45', 'telefono' => '601-890-1234', 'email' => 'citas@clinicasonrisas.com'],
            ['nombre' => 'Gimnasio FitLife Colombia E.U.', 'tipo_documento' => 'nit', 'numero_documento' => '900890123-8', 'direccion' => 'Zona T Calle 82 #11-32', 'telefono' => '601-901-2345', 'email' => 'info@fitlifecolombia.com'],
            ['nombre' => 'Librería Mundo de Libros S.A.S.', 'tipo_documento' => 'nit', 'numero_documento' => '900901234-9', 'direccion' => 'Centro Histórico Plaza Bolívar', 'telefono' => '601-012-3456', 'email' => 'ventas@mundodelibros.com'],
            ['nombre' => 'Floristería Jardín Secreto Ltda.', 'tipo_documento' => 'nit', 'numero_documento' => '900012345-0', 'direccion' => 'Chapinero Carrera 11 #67-89', 'telefono' => '601-123-4567', 'email' => 'pedidos@jardinsecreto.com']
        ];

        foreach ($clientes as $cliente) {
            Cliente::create($cliente);
        }
    }
}