<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Crear usuario administrador por defecto
        Usuario::firstOrCreate(
            ['email' => 'admin@scigop.com'],
            [
                'rol_id' => 1,
                'nombre' => 'Administrador',
                'password' => Hash::make('admin123'),
                'activo' => true
            ]
        );
        
        // Crear usuario de prueba
        Usuario::firstOrCreate(
            ['email' => 'vendedor@scigop.com'],
            [
                'rol_id' => 2,
                'nombre' => 'Vendedor de Prueba',
                'password' => Hash::make('vendedor123'),
                'activo' => true
            ]
        );
        
        // Crear usuario de prueba principal
        Usuario::firstOrCreate(
            ['email' => 'test@scigop.com'],
            [
                'rol_id' => 1,
                'nombre' => 'Usuario de Prueba',
                'password' => Hash::make('scigop2025'),
                'activo' => true
            ]
        );
    }
}
