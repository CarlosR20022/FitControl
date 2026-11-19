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

    // De una lista de IDs de clientes, devuelve solo los que su último pago
    // fue una membresía (mensualidad o semana). Las visitas de un día no
    // cuentan como baja: solo fueron un día al gimnasio, no dejaron de
    // renovar una mensualidad. Se usa en Bajas del Mes, en la tarjeta de
    // Bajas del dashboard y en las bajas del reporte, para que el número
    // sea el mismo en los tres lados.
    public static function filtrarConMembresia($ids)
    {
        $ids = collect($ids);

        if ($ids->isEmpty()) {
            return collect();
        }

        $ultimosPagos = Pago::with('producto')
            ->whereIn('cliente_id', $ids)
            ->orderBy('fecha_pago', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('cliente_id')
            ->map(fn ($pagos) => $pagos->first());

        return $ids->filter(function ($id) use ($ultimosPagos) {
            $pago = $ultimosPagos->get($id);
            return $pago && $pago->producto && $pago->producto->categoria === 'membresia';
        })->values();
    }
}