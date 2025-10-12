<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $table = 'compras';
    
    protected $fillable = [
        'proveedor_id',
        'numero_factura',
        'fecha',
        'total',
        'estado',
        'observaciones',
        'usuario_id'
    ];
    
    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2'
    ];
    
    // Relaciones
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }
    
    public function detalles()
    {
        return $this->hasMany(DetalleCompra::class);
    }
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
    
    // Scopes
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }
    
    public function scopeRecibidas($query)
    {
        return $query->where('estado', 'recibida');
    }
    
    public function scopeDelMes($query, $mes = null, $año = null)
    {
        $mes = $mes ?? now()->month;
        $año = $año ?? now()->year;
        
        return $query->whereYear('fecha', $año)
                    ->whereMonth('fecha', $mes);
    }
    
    // Accessors
    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'recibida' => 'success',
            'pendiente' => 'warning',
            'cancelada' => 'danger',
            default => 'secondary'
        };
    }
}
