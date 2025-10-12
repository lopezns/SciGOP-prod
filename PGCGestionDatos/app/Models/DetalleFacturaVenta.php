<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleFacturaVenta extends Model
{
    protected $table = 'detalle_factura_venta';
    
    protected $fillable = [
        'factura_id',
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
    public function factura()
    {
        return $this->belongsTo(FacturaVenta::class, 'factura_id');
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
