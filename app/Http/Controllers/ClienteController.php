<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Pago;
use Carbon\Carbon;

class ClienteController extends Controller
{
    // Muestra la vista del formulario enviando los planes disponibles
    public function create()
    {
        // Traemos todos los productos que sean membresías o visitas
        $planes = Producto::whereIn('categoria', ['membresia', 'visita'])->get();
        return view('clientes.create', compact('planes'));
    }

    // Recibe los datos, guarda al cliente y registra el pago
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'producto_id' => 'required|exists:productos,id',
            'fecha_registro' => 'required|date',
        ]);

        // 1. Obtener el producto/plan elegido y la fecha
        $producto = Producto::findOrFail($request->producto_id);
        $fechaIngreso = Carbon::parse($request->fecha_registro);
        $fechaVencimiento = $fechaIngreso->copy();

        // 2. Calcular fecha de vencimiento basándonos en el nombre del plan
        $nombrePlan = strtolower($producto->nombre);
        if (str_contains($nombrePlan, 'mes')) {
            $fechaVencimiento->addMonth(); // Suma 1 mes
        } elseif (str_contains($nombrePlan, 'semana')) {
            $fechaVencimiento->addDays(7); // Suma 1 semana
        }

        // 3. Crear Cliente
        $cliente = Cliente::create([
            'nombre_completo' => $request->nombre_completo,
            'telefono' => $request->telefono,
            'fecha_registro' => $fechaIngreso,
            'fecha_vencimiento' => $fechaVencimiento,
            'estado' => 'activo'
        ]);

        // 4. Registrar el Pago automáticamente para las finanzas
        Pago::create([
            'cliente_id' => $cliente->id,
            'producto_id' => $producto->id,
            'cantidad' => 1,
            'total' => $producto->precio,
            'metodo_pago' => 'efectivo',
            'fecha_pago' => $fechaIngreso
        ]);

        return redirect('/');
    }
    // Lista de socios vigentes
    public function vigentes()
    {
        $hoy = Carbon::today();
        
        // Buscamos clientes cuya fecha de vencimiento sea mayor o igual a hoy
        $clientes = Cliente::where('fecha_vencimiento', '>=', $hoy)
                           ->orderBy('fecha_vencimiento', 'asc') // Los ordenamos por los que vencen más pronto
                           ->get();

        return view('clientes.vigentes', compact('clientes'));
    }
    // Lista de socios vencidos
    public function vencidos()
    {
        $hoy = \Carbon\Carbon::today();
        
        $clientes = Cliente::where('fecha_vencimiento', '<', $hoy)
                           ->orderBy('fecha_vencimiento', 'desc')
                           ->get();

        return view('clientes.vencidos', compact('clientes'));
    }
}