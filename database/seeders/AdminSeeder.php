<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Crea el usuario administrador de FitControl.
// Se inicia sesión con el ID 001 y la contraseña 001.
// Si se corre de nuevo, no duplica al usuario, solo lo actualiza.
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['codigo' => '001'],
            [
                'name' => 'Carlos Adrian Rendon Ramos',
                'email' => 'admin@fitcontrol.local',
                'password' => Hash::make('001'),
            ]
        );
    }
}
