<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    
    protected $fillable = [
        'producto_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'motivo',
        'observaciones',
        'usuario_id'
    ];
    
    protected $casts = [
        'cantidad' => 'integer',
        'stock_anterior' => 'integer',
        'stock_nuevo' => 'integer'
    ];
    
    // Relaciones
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
    
    // Scopes
    public function scopeEntradas($query)
    {
        return $query->where('tipo', 'entrada');
    }
    
    public function scopeSalidas($query)
    {
        return $query->where('tipo', 'salida');
    }
    
    public function scopeAjustes($query)
    {
        return $query->where('tipo', 'ajuste');
    }
    
    public function scopeDelProducto($query, $productoId)
    {
        return $query->where('producto_id', $productoId);
    }
}
