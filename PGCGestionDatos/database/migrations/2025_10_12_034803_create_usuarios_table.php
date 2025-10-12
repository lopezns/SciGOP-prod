<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar si la tabla ya existe
        if (Schema::hasTable('usuarios')) {
            // Si existe, solo agregar columnas faltantes
            Schema::table('usuarios', function (Blueprint $table) {
                if (!Schema::hasColumn('usuarios', 'rol_id')) {
                    $table->foreignId('rol_id')->default(1)->constrained('roles')->onDelete('cascade');
                }
                if (!Schema::hasColumn('usuarios', 'activo')) {
                    $table->boolean('activo')->default(true);
                }
            });
        } else {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id();
                $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
                $table->string('nombre');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->boolean('activo')->default(true);
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
