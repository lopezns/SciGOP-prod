<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCompra extends Model
{
    protected $table = 'detalle_compras';
    
    protected $fillable = [
        'compra_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];
    
    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];
    
    // Relaciones
    public function compra()
    {
        return $this->belongsTo(Compra::class);
    }
    
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    
    // Mutators
    public function setSubtotalAttribute($value)
    {
        $this->attributes['subtotal'] = $this->cantidad * $this->precio_unitario;
    }
}
