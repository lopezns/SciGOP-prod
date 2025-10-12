<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Seed cafe data
        $this->call([
            UsuarioSeeder::class,
            ClienteSeeder::class,
            ProductoSeeder::class,
            FacturaVentaSeeder::class,
            ProveedorSeeder::class,
            CompraSeeder::class,
        ]);
    }
}
