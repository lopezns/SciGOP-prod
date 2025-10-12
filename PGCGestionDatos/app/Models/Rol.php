<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'permisos',
        'activo'
    ];
    
    protected $casts = [
        'permisos' => 'array',
        'activo' => 'boolean'
    ];
    
    // Relaciones
    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }
    
    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}
