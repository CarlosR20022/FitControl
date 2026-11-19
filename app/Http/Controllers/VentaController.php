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

        // 3. Si el producto lleva stock y ya no queda, no se puede vender
        if ($producto->stock !== null && $producto->stock < 1) {
            return redirect('/')->with('error', 'No queda stock de "' . $producto->nombre . '".');
        }

        // 4. Registrar el ingreso del dinero (Venta de mostrador, sin cliente específico)
        Pago::create([
            'cliente_id' => null, // Es venta general
            'producto_id' => $producto->id,
            'cantidad' => 1,
            'total' => $request->precio,
            'metodo_pago' => 'efectivo',
            'fecha_pago' => Carbon::today()
        ]);

        // 5. Restar una unidad del stock (solo los productos que llevan stock:
        //    bebidas, snacks y suplementos en bote; los scoops y membresías no)
        if ($producto->stock !== null) {
            $producto->decrement('stock');
        }

        // 6. Regresar al dashboard
        return redirect('/')->with('success', 'Venta registrada: ' . $producto->nombre . ' ($' . number_format($request->precio, 2) . ').');
    }
}