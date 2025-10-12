<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';
    
    protected $fillable = [
        'nombre',
        'tipo_documento',
        'numero_documento',
        'direccion',
        'telefono',
        'email'
    ];
    
    protected $casts = [
        'tipo_documento' => 'string'
    ];
    
    // Valores permitidos para tipo_documento
    public static function getTiposDocumento()
    {
        return ['CC' => 'Cédula de Ciudadanía', 'NIT' => 'NIT', 'CE' => 'Cédula de Extranjería'];
    }
    
    // Relaciones
    public function facturasVenta()
    {
        return $this->hasMany(FacturaVenta::class);
    }
    
    // Nota: PagoCliente no implementado aún
    // public function pagos()
    // {
    //     return $this->hasMany(PagoCliente::class);
    // }
    
    // Scopes
    public function scopeBuscar($query, $termino)
    {
        return $query->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('numero_documento', 'like', "%{$termino}%")
                    ->orWhere('email', 'like', "%{$termino}%");
    }
    
    // Accessors
    public function getDocumentoCompletoAttribute()
    {
        return $this->tipo_documento . ' ' . $this->numero_documento;
    }
}
