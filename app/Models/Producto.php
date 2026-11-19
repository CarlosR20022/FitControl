<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Producto extends Model
{
    protected $fillable = [
        'nombre',
        'categoria',
        'precio',
        'stock',
        'duracion'
    ];

    // Productos que se venden por unidad y llevan stock: bebidas, snacks
    // y suplementos en bote. Se excluyen membresías, visitas y los scoops
    // sueltos (no se sabe cuántos scoops hay por envase).
    public function scopeConStock($query)
    {
        return $query->whereNotIn('categoria', ['membresia', 'visita'])
                     ->where('nombre', 'not like', '%scoop%');
    }

    // Duración del plan: 'dia', 'semana' o 'mes'. Si el producto aún no
    // tiene duración guardada (planes creados antes), se deduce del nombre
    // para no romper los vencimientos que ya existen.
    public function duracionEfectiva(): string
    {
        if (in_array($this->duracion, ['dia', 'semana', 'mes'], true)) {
            return $this->duracion;
        }
        $nombre = strtolower($this->nombre);
        if (str_contains($nombre, 'semana')) {
            return 'semana';
        }
        if (str_contains($nombre, 'mes')) {
            return 'mes';
        }
        return 'dia';
    }

    // Fecha en que vence una membresía que empieza en $base con este plan
    public function calcularVencimiento(Carbon $base): Carbon
    {
        $vencimiento = $base->copy();
        if ($this->duracionEfectiva() === 'mes') {
            $vencimiento->addMonth();
        } elseif ($this->duracionEfectiva() === 'semana') {
            $vencimiento->addDays(7);
        }
        return $vencimiento;
    }
}
