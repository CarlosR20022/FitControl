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
            // Al registrar un cliente nuevo la fecha de ingreso es hoy o
            // una fecha futura; las fechas pasadas se corrigen después en
            // Modificaciones > Clientes, no al registrar.
            'fecha_registro' => 'required|date|after_or_equal:today',
        ]);

        // 0. Si ya existe un cliente con ese mismo nombre completo, no lo
        //    duplicamos: se le avisa y se le ofrece renovar su membresía.
        //    (Se compara sin importar mayúsculas/minúsculas ni espacios extra.)
        $nombreNormalizado = trim(preg_replace('/\s+/', ' ', $request->nombre_completo));
        $existente = Cliente::whereRaw('LOWER(nombre_completo) = ?', [mb_strtolower($nombreNormalizado)])->first();

        if ($existente && !$request->boolean('forzar')) {
            // Se pasa como arreglo simple (no el modelo completo), para que
            // la vista lo lea igual siempre y no falle al mostrar el aviso.
            return redirect()->back()
                ->withInput()
                ->with('duplicado', $existente->only(['id', 'nombre_completo', 'fecha_vencimiento']));
        }

        // 1. Obtener el producto/plan elegido y la fecha
        $producto = Producto::findOrFail($request->producto_id);
        $fechaIngreso = Carbon::parse($request->fecha_registro);

        // 2. Fecha de vencimiento según la duración del plan
        $fechaVencimiento = $producto->calcularVencimiento($fechaIngreso);

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

        return redirect('/')->with('success', 'Cliente ' . $cliente->nombre_completo . ' registrado.');
    }

    // --- Módulo Clientes (Modificaciones): corregir los datos de un cliente ---
    // Lista con buscador por nombre
    public function adminIndex(Request $request)
    {
        $q = trim($request->get('q', ''));
        $clientes = Cliente::when($q, fn ($query) => $query->where('nombre_completo', 'like', '%' . $q . '%'))
            ->orderBy('nombre_completo')
            ->get();

        // Último pago de cada cliente, para mostrar su plan actual
        $ultimosPagos = Pago::with('producto')
            ->whereIn('cliente_id', $clientes->pluck('id'))
            ->orderBy('fecha_pago', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->groupBy('cliente_id')
            ->map(fn ($pagos) => $pagos->first());

        return view('modificaciones.clientes.index', compact('clientes', 'ultimosPagos', 'q'));
    }

    // Formulario para corregir los datos de un cliente
    public function adminEdit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $planes = Producto::whereIn('categoria', ['membresia', 'visita'])->get();
        $ultimoPago = Pago::where('cliente_id', $id)
            ->orderBy('fecha_pago', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        return view('modificaciones.clientes.form', compact('cliente', 'planes', 'ultimoPago'));
    }

    // Guarda los datos corregidos y ajusta el último pago y el vencimiento
    public function adminUpdate(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'producto_id' => 'required|exists:productos,id',
            'fecha_registro' => 'required|date',
        ]);

        // El nombre no puede quedar igual al de otro cliente
        $nombreNormalizado = trim(preg_replace('/\s+/', ' ', $request->nombre_completo));
        $otro = Cliente::whereRaw('LOWER(nombre_completo) = ?', [mb_strtolower($nombreNormalizado)])
            ->where('id', '!=', $id)
            ->first();
        if ($otro) {
            return back()->withInput()->withErrors(['nombre_completo' => 'Ya existe otro cliente con ese nombre.']);
        }

        $producto = Producto::findOrFail($request->producto_id);
        $fechaIngreso = Carbon::parse($request->fecha_registro);

        // Último pago: es el que se corrige (plan y precio). Su fecha solo se
        // mueve si ese pago es el de registro; si el cliente ya renovó antes,
        // la fecha de ese pago no se toca.
        $pago = Pago::where('cliente_id', $id)
            ->orderBy('fecha_pago', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        $esPagoRegistro = $pago && $pago->fecha_pago === $cliente->fecha_registro;

        $base = $fechaIngreso->copy();
        if ($pago) {
            $pago->producto_id = $producto->id;
            $pago->total = $producto->precio;
            if ($esPagoRegistro) {
                $pago->fecha_pago = $fechaIngreso;
            } else {
                $base = Carbon::parse($pago->fecha_pago);
            }
            $pago->save();
        }

        // Nueva fecha de vencimiento según el plan corregido
        $fechaVencimiento = $producto->calcularVencimiento($base);

        $cliente->update([
            'nombre_completo' => $request->nombre_completo,
            'telefono' => $request->telefono,
            'fecha_registro' => $fechaIngreso,
            'fecha_vencimiento' => $fechaVencimiento,
            'estado' => $fechaVencimiento->gte(Carbon::today()) ? 'activo' : 'vencido',
        ]);

        return redirect()->route('modificaciones.clientes')
            ->with('exito', 'Datos de ' . $cliente->nombre_completo . ' actualizados.');
    }

    // Lista de socios vigentes
    public function vigentes()
    {
        // Fecha como texto 'YYYY-MM-DD' (ver nota en DashboardController)
        $hoy = Carbon::today()->toDateString();
        
        // Buscamos clientes cuya fecha de vencimiento sea mayor o igual a hoy
        $clientes = Cliente::where('fecha_vencimiento', '>=', $hoy)
                           ->orderBy('fecha_vencimiento', 'asc') // Los ordenamos por los que vencen más pronto
                           ->get();

        return view('clientes.vigentes', compact('clientes'));
    }
    // Lista de socios vencidos
    public function vencidos()
    {
        // Fecha como texto 'YYYY-MM-DD' (ver nota en DashboardController)
        $hoy = \Carbon\Carbon::today()->toDateString();
        
        $clientes = Cliente::where('fecha_vencimiento', '<', $hoy)
                           ->orderBy('fecha_vencimiento', 'desc')
                           ->get();

        return view('clientes.vencidos', compact('clientes'));
    }

    // Lista de socios por vencer (próximos 5 días, igual que el dashboard)
    public function porVencer()
    {
        $hoy = Carbon::today();

        // Fechas como texto 'YYYY-MM-DD' (ver nota en DashboardController)
        $clientes = Cliente::whereBetween('fecha_vencimiento', [$hoy->toDateString(), $hoy->copy()->addDays(5)->toDateString()])
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
        // Fecha como texto 'YYYY-MM-DD' (ver nota en DashboardController)
        $inicioMes = Carbon::now()->startOfMonth()->toDateString();

        $clientes = Cliente::where('fecha_registro', '>=', $inicioMes)
                           ->orderBy('fecha_registro', 'desc')
                           ->get();

        return view('clientes.nuevos', compact('clientes'));
    }

    // Lista de bajas del mes actual: vencieron este mes y ya pasó su fecha
    public function bajas()
    {
        // Fechas como texto 'YYYY-MM-DD' (ver nota en DashboardController)
        $hoy = Carbon::today()->toDateString();
        $inicioMes = Carbon::now()->startOfMonth()->toDateString();

        $candidatos = Cliente::where('fecha_vencimiento', '>=', $inicioMes)
                           ->where('fecha_vencimiento', '<', $hoy)
                           ->orderBy('fecha_vencimiento', 'desc')
                           ->get();

        // Solo cuentan las bajas de mensualidad o semana: las visitas de un
        // día no son bajas, solo fueron un día al gimnasio.
        $ids = Cliente::filtrarConMembresia($candidatos->pluck('id'));
        $clientes = $candidatos->filter(fn ($cliente) => $ids->contains($cliente->id))->values();

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