<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    // Campos que permitimos guardar desde el formulario
    protected $fillable = [
        'nombre_completo',
        'telefono',
        'fecha_registro',
        'fecha_vencimiento',
        'estado'
    ];
}