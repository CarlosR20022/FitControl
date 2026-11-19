<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// Stock inicial para los productos que ya existen en la base de datos.
// La columna stock ya existía en la tabla productos, pero estaba vacía (null).
// Son valores de arranque: ajústalos a tu stock real en Modificaciones > Precios.
// Uso (una sola vez): php artisan db:seed --class=StockSeeder
class StockSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('productos')->where('nombre', 'Agua embotellada 1L')->update([
            'stock' => 18,
            'updated_at' => now(),
        ]);

        DB::table('productos')->where('nombre', 'Bote Pre-Entreno')->update([
            'stock' => 2,
            'updated_at' => now(),
        ]);

        $this->command->info('StockSeeder: stock inicial asignado a Agua (18) y Bote Pre-Entreno (2).');
    }
}
