<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FacturaVenta extends Model
{
    protected $table = 'facturas_venta';
    
    protected $fillable = [
        'cliente_id',
        'numero_factura',
        'fecha',
        'total',
        'estado'
    ];
    
    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2',
        'estado' => 'string'
    ];
    
    // Relaciones
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    public function detalles()
    {
        return $this->hasMany(DetalleFacturaVenta::class, 'factura_id');
    }
    
    // Scopes
    public function scopePagadas($query)
    {
        return $query->where('estado', 'pagada');
    }
    
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
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
            'pagada' => 'success',
            'pendiente' => 'warning', 
            'anulada' => 'danger',
            default => 'secondary'
        };
    }
}
