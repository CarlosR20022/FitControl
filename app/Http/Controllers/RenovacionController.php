<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Pago;
use Carbon\Carbon;

class RenovacionController extends Controller
{
    // Formulario de renovación: muestra los datos del cliente y los planes
    public function create($id)
    {
        $cliente = Cliente::findOrFail($id);
        $planes = Producto::whereIn('categoria', ['membresia', 'visita'])->get();
        return view('renovaciones.form', compact('cliente', 'planes'));
    }

    // Renueva la membresía: registra el pago y extiende el vencimiento
    public function store(Request $request, $id)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'metodo_pago' => 'required|in:efectivo,tarjeta,transferencia',
        ]);

        $cliente = Cliente::findOrFail($id);
        $producto = Producto::findOrFail($request->producto_id);
        $hoy = Carbon::today();

        // Si la membresía aún está activa, el nuevo periodo empieza cuando
        // termina el actual (no pierde los días que le quedan).
        // Si ya venció, el nuevo periodo empieza hoy.
        $base = Carbon::parse($cliente->fecha_vencimiento)->gte($hoy)
            ? Carbon::parse($cliente->fecha_vencimiento)
            : $hoy->copy();

        $nuevoVencimiento = $producto->calcularVencimiento($base);

        // Registrar el pago de la renovación
        Pago::create([
            'cliente_id' => $cliente->id,
            'producto_id' => $producto->id,
            'cantidad' => 1,
            'total' => $producto->precio,
            'metodo_pago' => $request->metodo_pago,
            'fecha_pago' => $hoy,
        ]);

        // Extender el vencimiento y dejarlo activo (la fecha de registro no cambia)
        $cliente->update([
            'fecha_vencimiento' => $nuevoVencimiento,
            'estado' => 'activo',
        ]);

        return redirect()->route('clientes.vigentes')
            ->with('success', 'Membresía de ' . $cliente->nombre_completo . ' renovada hasta el ' . $nuevoVencimiento->format('d/m/Y') . '.');
    }
}
