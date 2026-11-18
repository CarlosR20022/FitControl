<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Pago;
use App\Models\Producto;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();
        $inicioMes = Carbon::now()->startOfMonth();

        // 1. Tarjetas Principales
        $sociosVigentes = Cliente::where('fecha_vencimiento', '>=', $hoy)->count();
        $porVencer = Cliente::whereBetween('fecha_vencimiento', [$hoy, $hoy->copy()->addDays(5)])->count();
        $vencidosRecientes = Cliente::whereBetween('fecha_vencimiento', [$hoy->copy()->subDays(7), $hoy->copy()->subDay()])->count();

        // 2. Métricas Secundarias
        $totalHistorico = Cliente::count();
        $nuevosMes = Cliente::where('fecha_registro', '>=', $inicioMes)->count();
        $bajasMes = Cliente::where('fecha_vencimiento', '>=', $inicioMes)
                            ->where('fecha_vencimiento', '<', $hoy)
                            ->count();

        // 3. Finanzas
        $ingresosDia = Pago::whereDate('fecha_pago', $hoy)->sum('total');
        $renovacionesProximas = $porVencer;

        // 4. Catálogo para el Modal de Ventas Rápidas
        $suplementos = Producto::where('categoria', 'suplemento')->get();

        return view('welcome', compact(
            'sociosVigentes', 'porVencer', 'vencidosRecientes', 
            'totalHistorico', 'nuevosMes', 'bajasMes', 
            'ingresosDia', 'renovacionesProximas',
            'suplementos'
        ));
    }

    // Detalle de los ingresos del día: todos los pagos de hoy
    public function ingresosDia()
    {
        $hoy = Carbon::today();

        $pagos = Pago::with(['cliente', 'producto'])
                     ->whereDate('fecha_pago', $hoy)
                     ->orderBy('created_at', 'desc')
                     ->get();

        $totalDia = $pagos->sum('total');

        return view('ingresos.dia', compact('pagos', 'totalDia', 'hoy'));
    }
}