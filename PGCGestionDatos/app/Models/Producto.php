<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';
    
    protected $fillable = [
        'nombre',
        'codigo', 
        'precio_compra',
        'precio_venta',
        'stock'
    ];
    
    protected $casts = [
        'precio_compra' => 'decimal:2',
        'precio_venta' => 'decimal:2',
        'stock' => 'integer'
    ];
    
    // Relaciones
    public function movimientosInventario()
    {
        return $this->hasMany(MovimientoInventario::class);
    }
    
    // Scopes
    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }
    
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('codigo', 'like', "%{$termino}%");
    }
}
