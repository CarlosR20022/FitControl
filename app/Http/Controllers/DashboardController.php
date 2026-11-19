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

        // Las columnas fecha_vencimiento y fecha_registro son DATE (sin hora).
        // Se comparan como texto 'YYYY-MM-DD': si se pasa un Carbon con hora
        // ('2026-11-18 00:00:00'), SQLite trata el día de hoy como si ya hubiera
        // pasado y los vencimientos de hoy salen como vencidos en vez de vigentes.
        $hoyStr = $hoy->toDateString();
        $inicioMesStr = Carbon::now()->startOfMonth()->toDateString();
        $en5DiasStr = $hoy->copy()->addDays(5)->toDateString();
        $hace7DiasStr = $hoy->copy()->subDays(7)->toDateString();
        $ayerStr = $hoy->copy()->subDay()->toDateString();

        // 1. Tarjetas Principales
        $sociosVigentes = Cliente::where('fecha_vencimiento', '>=', $hoyStr)->count();
        $porVencer = Cliente::whereBetween('fecha_vencimiento', [$hoyStr, $en5DiasStr])->count();
        $vencidosRecientes = Cliente::whereBetween('fecha_vencimiento', [$hace7DiasStr, $ayerStr])->count();

        // 2. Métricas Secundarias
        $totalHistorico = Cliente::count();
        $nuevosMes = Cliente::where('fecha_registro', '>=', $inicioMesStr)->count();
        // Solo cuentan las bajas de mensualidad o semana (ver Cliente::filtrarConMembresia)
        $bajasMes = Cliente::filtrarConMembresia(
            Cliente::where('fecha_vencimiento', '>=', $inicioMesStr)
                ->where('fecha_vencimiento', '<', $hoyStr)
                ->pluck('id')
        )->count();

        // 3. Finanzas
        $ingresosDia = Pago::whereDate('fecha_pago', $hoy)->sum('total');

        // 4. Productos por agotarse (stock menor a 3, sin membresías, visitas ni scoops)
        $productosPorAgotarse = Producto::conStock()
            ->whereNotNull('stock')
            ->where('stock', '<', 3)
            ->count();

        // 5. Catálogo para el Modal de Ventas Rápidas
        $suplementos = Producto::where('categoria', 'suplemento')->get();

        return view('welcome', compact(
            'sociosVigentes', 'porVencer', 'vencidosRecientes', 
            'totalHistorico', 'nuevosMes', 'bajasMes', 
            'ingresosDia', 'productosPorAgotarse',
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