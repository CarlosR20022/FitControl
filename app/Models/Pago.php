<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    // Le decimos a Laravel qué columnas sí puede llenar automáticamente
    protected $fillable = [
        'cliente_id',
        'producto_id',
        'cantidad',
        'total',
        'metodo_pago',
        'fecha_pago'
    ];
}