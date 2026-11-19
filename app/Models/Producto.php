<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'categoria',
        'precio',
        'stock'
    ];

    // Productos que se venden por unidad y llevan stock: bebidas, snacks
    // y suplementos en bote. Se excluyen membresías, visitas y los scoops
    // sueltos (no se sabe cuántos scoops hay por envase).
    public function scopeConStock($query)
    {
        return $query->whereNotIn('categoria', ['membresia', 'visita'])
                     ->where('nombre', 'not like', '%scoop%');
    }
}
