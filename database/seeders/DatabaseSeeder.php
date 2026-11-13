<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $hoy = Carbon::today();

        // 1. CREAR PRODUCTOS (Catálogo)
        $idMensualidad = DB::table('productos')->insertGetId([
            'nombre' => 'Mensualidad General',
            'categoria' => 'membresia',
            'precio' => 400.00,
            'stock' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $idAgua = DB::table('productos')->insertGetId([
            'nombre' => 'Agua embotellada 1L',
            'categoria' => 'bebida',
            'precio' => 20.00,
            'stock' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. CREAR CLIENTES
        // Cliente 1: Totalmente Vigente (Vence en 20 días)
        $idCliente1 = DB::table('clientes')->insertGetId([
            'nombre_completo' => 'Carlos Rendón',
            'telefono' => '9981234567',
            'fecha_registro' => $hoy->copy()->subDays(10),
            'fecha_vencimiento' => $hoy->copy()->addDays(20),
            'estado' => 'activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Cliente 2: Por Vencer (Vence en 3 días) -> Aparecerá en tarjeta naranja
        $idCliente2 = DB::table('clientes')->insertGetId([
            'nombre_completo' => 'María López',
            'telefono' => '9987654321',
            'fecha_registro' => $hoy->copy()->subMonth(),
            'fecha_vencimiento' => $hoy->copy()->addDays(3),
            'estado' => 'activo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. CREAR PAGOS (Ingresos del día de hoy)
        // Carlos pagó su mensualidad hoy
        DB::table('pagos')->insert([
            'cliente_id' => $idCliente1,
            'producto_id' => $idMensualidad,
            'cantidad' => 1,
            'total' => 400.00,
            'metodo_pago' => 'efectivo',
            'fecha_pago' => $hoy,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Alguien "de paso" compró 2 aguas hoy (cliente_id = null)
        DB::table('pagos')->insert([
            'cliente_id' => null,
            'producto_id' => $idAgua,
            'cantidad' => 2,
            'total' => 40.00,
            'metodo_pago' => 'efectivo',
            'fecha_pago' => $hoy,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        // En tu DatabaseSeeder.php, debajo de donde creaste la Mensualidad:
        DB::table('productos')->insert([
            ['nombre' => 'Semana', 'categoria' => 'membresia', 'precio' => 200.00, 'created_at' => now()],
            ['nombre' => 'Visita Diaria', 'categoria' => 'visita', 'precio' => 40.00, 'created_at' => now()],
        ]);
        DB::table('productos')->insert([
            ['nombre' => 'Medio Scoop', 'categoria' => 'suplemento', 'precio' => 10.00, 'created_at' => now()],
            ['nombre' => 'Scoop Entero', 'categoria' => 'suplemento', 'precio' => 25.00, 'created_at' => now()],
            ['nombre' => 'Bote Pre-Entreno', 'categoria' => 'suplemento', 'precio' => 500.00, 'created_at' => now()],
        ]);
    }
}