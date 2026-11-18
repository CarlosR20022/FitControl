<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Insertar planes, membresías y productos iniciales
        DB::table('productos')->insert([
            // Membresías y Visitas
            [
                'nombre' => 'Mensualidad General',
                'precio' => 400.00,
                'categoria' => 'membresia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Semana',
                'precio' => 200.00,
                'categoria' => 'membresia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Visita Diaria',
                'precio' => 40.00,
                'categoria' => 'visita',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Suplementos y Scoops
            [
                'nombre' => 'Medio Scoop',
                'precio' => 10.00,
                'categoria' => 'suplemento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Scoop Entero',
                'precio' => 25.00,
                'categoria' => 'suplemento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Bote Pre-Entreno',
                'precio' => 500.00,
                'categoria' => 'suplemento',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Bebidas y Otros
            [
                'nombre' => 'Agua embotellada 1L',
                'precio' => 20.00,
                'categoria' => 'bebida',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}