<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Pago;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function storeRapida(Request $request)
    {
        // 1. Validar los datos que vienen del modal
        $request->validate([
            'nombre_producto' => 'required|string',
            'precio' => 'required|numeric'
        ]);

        // 2. Buscar el producto o crearlo si no existe (para no fallar con la base de datos)
        $producto = Producto::firstOrCreate(
            ['nombre' => $request->nombre_producto],
            ['categoria' => 'suplemento', 'precio' => $request->precio]
        );

        // 3. Registrar el ingreso del dinero (Venta de mostrador, sin cliente específico)
        Pago::create([
            'cliente_id' => null, // Es venta general
            'producto_id' => $producto->id,
            'cantidad' => 1,
            'total' => $request->precio,
            'metodo_pago' => 'efectivo',
            'fecha_pago' => Carbon::today()
        ]);

        // 4. Regresar al dashboard
        return redirect('/');
    }
}