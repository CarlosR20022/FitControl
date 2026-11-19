<?php

namespace Database\Seeders;

use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

// Datos de prueba para ver el sistema lleno: 50 clientes repartidos en
// vigentes, por vencer, vencidos recientes, bajas, nuevos del mes e
// ingresos del día. Los teléfonos empiezan con 5551 para identificarlos
// y poder borrarlos después. No borra ningún cliente real.
// Uso: php artisan db:seed --class=DemoSeeder
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $hoy = Carbon::today();

        $mensualidad = Producto::where('nombre', 'Mensualidad General')->first();
        $semana = Producto::where('nombre', 'Semana')->first();
        $visita = Producto::where('nombre', 'Visita Diaria')->first();

        if (! $mensualidad || ! $semana || ! $visita) {
            $this->command->error('Faltan los planes (Mensualidad General, Semana, Visita Diaria). Corre primero: php artisan db:seed');
            return;
        }

        $nombres = ['Alejandro', 'Mariana', 'José Luis', 'Fernanda', 'Carlos', 'Valeria', 'Miguel Ángel', 'Sofía', 'Diego', 'Camila', 'Jorge', 'Daniela', 'Ricardo', 'Paola', 'Andrés', 'Lucía', 'Fernando', 'Isabella', 'Raúl', 'Natalia', 'Eduardo', 'Regina', 'Hugo', 'Ana Paula'];
        $paternos = ['García', 'Hernández', 'Martínez', 'López', 'González', 'Rodríguez', 'Pérez', 'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez', 'Díaz', 'Reyes', 'Morales', 'Vargas', 'Castillo', 'Ortiz', 'Jiménez', 'Herrera', 'Medina', 'Castro', 'Ruiz'];
        $maternos = ['Fernández', 'Chávez', 'Mendoza', 'Aguilar', 'Guerrero', 'Rojas', 'Delgado', 'Vega', 'Ortiz', 'Navarro', 'Ramos', 'Cortés', 'Santos', 'Peña', 'Luna', 'Salazar', 'Campos', 'Vázquez', 'Ríos', 'Miranda', 'Domínguez', 'Silva', 'Ortega', 'Maldonado'];
        $metodos = ['efectivo', 'tarjeta', 'transferencia'];
        $planes = [$mensualidad, $mensualidad, $mensualidad, $semana, $semana, $visita];

        for ($i = 0; $i < 50; $i++) {
            $nombre = $nombres[array_rand($nombres)] . ' ' . $paternos[array_rand($paternos)] . ' ' . $maternos[array_rand($maternos)];

            if ($i < 18) {
                // Socios vigentes (vencen en más de 5 días)
                $plan = rand(0, 1) ? $mensualidad : $semana;
                $vencimiento = $plan->id === $mensualidad->id
                    ? $hoy->copy()->addDays(rand(6, 28))
                    : $hoy->copy()->addDays(rand(6, 12));
                $registro = $this->restarPlan($vencimiento, $plan);
            } elseif ($i < 26) {
                // Por vencer (de hoy a 5 días) = próximas renovaciones
                $plan = $planes[array_rand($planes)];
                $vencimiento = $hoy->copy()->addDays(rand(0, 5));
                $registro = $this->restarPlan($vencimiento, $plan);
            } elseif ($i < 34) {
                // Vencidos recientes (últimos 7 días, cuentan como bajas del mes)
                $plan = $planes[array_rand($planes)];
                $vencimiento = $hoy->copy()->subDays(rand(1, 7));
                $registro = $this->restarPlan($vencimiento, $plan);
            } elseif ($i < 40) {
                // Vencidos de hace más tiempo
                $plan = $planes[array_rand($planes)];
                $vencimiento = $hoy->copy()->subDays(rand(8, 40));
                $registro = $this->restarPlan($vencimiento, $plan);
            } else {
                // Nuevos del mes: los 3 primeros registrados hoy (dan ingresos del día)
                $plan = $planes[array_rand($planes)];
                $registro = $i < 43 ? $hoy->copy() : $hoy->copy()->subDays(rand(1, 17));
                $vencimiento = $this->sumarPlan($registro, $plan);
            }

            // Nunca registrar a alguien en el futuro
            if ($registro->gt($hoy)) {
                $registro = $hoy->copy();
                $vencimiento = $this->sumarPlan($registro, $plan);
            }

            $cliente = Cliente::create([
                'nombre_completo' => $nombre,
                'telefono' => '555' . str_pad(1000000 + $i, 7, '0', STR_PAD_LEFT),
                'fecha_registro' => $registro->toDateString(),
                'fecha_vencimiento' => $vencimiento->toDateString(),
                'estado' => $vencimiento->gte($hoy) ? 'activo' : 'vencido',
            ]);

            Pago::create([
                'cliente_id' => $cliente->id,
                'producto_id' => $plan->id,
                'cantidad' => 1,
                'total' => $plan->precio,
                'metodo_pago' => $metodos[array_rand($metodos)],
                'fecha_pago' => $registro->toDateString(),
            ]);
        }

        $this->command->info('DemoSeeder: 50 clientes de prueba creados (teléfonos 5551...).');
    }

    // Fecha de vencimiento según el plan, igual que en ClienteController@store
    private function sumarPlan(Carbon $fecha, $plan): Carbon
    {
        $nombre = strtolower($plan->nombre);
        if (str_contains($nombre, 'mes')) {
            return $fecha->copy()->addMonth();
        }
        if (str_contains($nombre, 'semana')) {
            return $fecha->copy()->addDays(7);
        }
        return $fecha->copy(); // Visita Diaria: vence el mismo día
    }

    private function restarPlan(Carbon $fecha, $plan): Carbon
    {
        $nombre = strtolower($plan->nombre);
        if (str_contains($nombre, 'mes')) {
            return $fecha->copy()->subMonth();
        }
        if (str_contains($nombre, 'semana')) {
            return $fecha->copy()->subDays(7);
        }
        return $fecha->copy();
    }
}
