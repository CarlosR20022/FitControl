<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // La duración del plan (dia, semana o mes) se guarda en el producto,
    // para que el vencimiento no dependa de adivinarlo por el nombre del plan.
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('duracion', 20)->nullable()->after('categoria');
        });

        // Asignar la duración a los planes que ya existen, según su nombre
        DB::table('productos')->where('nombre', 'like', '%semana%')->update(['duracion' => 'semana']);
        DB::table('productos')->where('nombre', 'like', '%mensual%')->update(['duracion' => 'mes']);
        DB::table('productos')->where('nombre', 'like', '%visita%')->update(['duracion' => 'dia']);
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('duracion');
        });
    }
};
