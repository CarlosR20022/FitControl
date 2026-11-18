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

    // Lista de socios por vencer (próximos 5 días, igual que el dashboard)
    public function porVencer()
    {
        $hoy = Carbon::today();

        $clientes = Cliente::whereBetween('fecha_vencimiento', [$hoy, $hoy->copy()->addDays(5)])
                           ->orderBy('fecha_vencimiento', 'asc')
                           ->get();

        return view('clientes.por-vencer', compact('clientes'));
    }

    // Lista de todos los socios de la historia
    public function historico()
    {
        $clientes = Cliente::orderBy('fecha_registro', 'desc')
                           ->get();

        return view('clientes.historico', compact('clientes'));
    }

    // Lista de socios nuevos del mes actual
    public function nuevos()
    {
        $inicioMes = Carbon::now()->startOfMonth();

        $clientes = Cliente::where('fecha_registro', '>=', $inicioMes)
                           ->orderBy('fecha_registro', 'desc')
                           ->get();

        return view('clientes.nuevos', compact('clientes'));
    }

    // Lista de bajas del mes actual: vencieron este mes y ya pasó su fecha
    public function bajas()
    {
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();

        $clientes = Cliente::where('fecha_vencimiento', '>=', $inicioMes)
                           ->where('fecha_vencimiento', '<', $hoy)
                           ->orderBy('fecha_vencimiento', 'desc')
                           ->get();

        return view('clientes.bajas', compact('clientes'));
    }

    // Consulta de cliente por nombre completo: muestra todos sus datos
    public function consulta(Request $request)
    {
        $busqueda = trim($request->query('nombre', ''));
        $clientes = collect();

        if ($busqueda !== '') {
            $hoy = Carbon::today();

            $clientes = Cliente::where('nombre_completo', 'LIKE', '%' . $busqueda . '%')
                               ->orderBy('fecha_registro', 'asc')
                               ->get();

            foreach ($clientes as $cliente) {
                // Fecha en que se registró por primera vez (el registro más viejo con ese nombre)
                $cliente->primera_vez = Cliente::where('nombre_completo', $cliente->nombre_completo)
                                                ->min('fecha_registro');

                // Membresía actual: el producto de su último pago (Mensualidad General, Semana o Visita Diaria)
                $ultimoPago = Pago::with('producto')
                                   ->where('cliente_id', $cliente->id)
                                   ->orderBy('fecha_pago', 'desc')
                                   ->orderBy('id', 'desc')
                                   ->first();
                $cliente->membresia = $ultimoPago?->producto?->nombre ?? 'Sin membresía registrada';

                // Activo si su fecha de vencimiento aún no pasa
                $cliente->esta_activo = Carbon::parse($cliente->fecha_vencimiento)->gte($hoy);
            }
        }

        return view('clientes.consulta', compact('clientes', 'busqueda'));
    }
}