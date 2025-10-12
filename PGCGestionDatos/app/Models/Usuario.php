<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Usuario extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'usuarios';
    
    protected $fillable = [
        'rol_id',
        'nombre',
        'email',
        'password',
        'activo'
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'activo' => 'boolean',
        'password' => 'hashed',
    ];
    
    // Relaciones
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
    
    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
    
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('email', 'like', "%{$termino}%");
    }
}
