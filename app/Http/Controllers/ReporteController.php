<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Pago;
use Carbon\Carbon;

class ReporteController extends Controller
{
    // Genera el reporte del periodo elegido y lo descarga como PDF.
    // Si la librería de PDF aún no está instalada, muestra el reporte
    // en pantalla para imprimirlo y avisa el comando para instalarla.
    public function generar(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        $inicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fin = Carbon::parse($request->fecha_fin)->endOfDay();
        $hoy = Carbon::today();

        // Si las fechas vienen al revés, las ordenamos
        if ($inicio->gt($fin)) {
            [$inicio, $fin] = [$fin->copy()->startOfDay(), $inicio->copy()->endOfDay()];
        }

        // 1. Clientes nuevos en el periodo
        $nuevos = Cliente::whereBetween('fecha_registro', [$inicio->toDateString(), $fin->toDateString()])
                          ->orderBy('fecha_registro', 'asc')
                          ->get();

        // 2. Socios vencidos a la fecha de fin del periodo
        $vencidos = Cliente::where('fecha_vencimiento', '<', $hoy)
                           ->orderBy('fecha_vencimiento', 'desc')
                           ->get();

        // 3. Próximos a vencer (próximos 5 días) = próximas renovaciones, con su fecha de vencimiento
        $porVencer = Cliente::whereBetween('fecha_vencimiento', [$hoy, $hoy->copy()->addDays(5)])
                             ->orderBy('fecha_vencimiento', 'asc')
                             ->get();
        $renovaciones = $porVencer;

        // 4. Total de socios en la historia
        $totalSocios = Cliente::count();

        // 5. Bajas en el periodo: vencieron dentro de esas fechas y ya pasó su fecha
        $bajas = Cliente::whereBetween('fecha_vencimiento', [$inicio->toDateString(), $fin->toDateString()])
                         ->where('fecha_vencimiento', '<', $hoy)
                         ->orderBy('fecha_vencimiento', 'desc')
                         ->get();

        // 6. Ingresos del periodo, con el total por día y el total general
        $pagos = Pago::with(['cliente', 'producto'])
                     ->whereBetween('fecha_pago', [$inicio->toDateString(), $fin->toDateString()])
                     ->orderBy('fecha_pago', 'asc')
                     ->orderBy('created_at', 'asc')
                     ->get();
        $ingresosPorDia = $pagos->groupBy(fn ($pago) => Carbon::parse($pago->fecha_pago)->toDateString());
        $totalIngresos = $pagos->sum('total');

        $datos = compact(
            'inicio', 'fin', 'hoy',
            'nuevos', 'vencidos', 'porVencer', 'renovaciones',
            'totalSocios', 'bajas', 'pagos', 'ingresosPorDia', 'totalIngresos'
        );

        // Con dompdf instalado (composer require barryvdh/laravel-dompdf) descarga el PDF
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reportes.pdf', $datos)
                ->setPaper('a4', 'portrait');
            return $pdf->download('reporte-fitcontrol-' . $inicio->format('Y-m-d') . '_a_' . $fin->format('Y-m-d') . '.pdf');
        }

        // Sin la librería, se muestra el reporte en pantalla para imprimirlo a PDF
        $datos['sinPdf'] = true;
        return view('reportes.pdf', $datos);
    }
}
