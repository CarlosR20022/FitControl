<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte FitControl {{ $inicio->format('d/m/Y') }} - {{ $fin->format('d/m/Y') }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm 14mm 18mm 14mm;
            @bottom-center {
                content: "FitControl · Reporte del gimnasio · Página " counter(page) " de " counter(pages);
                font-family: DejaVu Sans, sans-serif;
                font-size: 8.5px;
                color: #9a8f80;
            }
        }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11.5px; color: #332F29; margin: 0; line-height: 1.45; }

        /* Encabezado con la marca del sistema */
        .header { width: 100%; background: #332F29; border-collapse: collapse; }
        .header td { padding: 16px 18px; vertical-align: middle; }
        .logo { font-size: 24px; font-weight: bold; color: #ffffff; letter-spacing: 2px; }
        .logo span { color: #FFA000; }
        .logo small { display: block; font-size: 8.5px; letter-spacing: 3px; color: #c9bda9; font-weight: normal; margin-top: 2px; }
        .reporte-titulo { text-align: right; color: #ffffff; }
        .reporte-titulo .titulo { font-size: 15px; font-weight: bold; letter-spacing: 2px; }
        .reporte-titulo .fecha { font-size: 10px; color: #FFA000; margin-top: 2px; }

        /* Barra naranja del periodo */
        .periodo { width: 100%; background: #FFA000; color: #ffffff; border-collapse: collapse; margin-top: 0; }
        .periodo td { padding: 9px 18px; font-size: 11px; }
        .periodo .der { text-align: right; }
        .periodo b { letter-spacing: 0.5px; }

        /* Tarjetas de resumen */
        .kpi { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-top: 14px; }
        .kpi td { width: 25%; background: #FAF6F0; border-top: 3px solid #332F29; padding: 10px 12px; }
        .kpi td.verde { border-top-color: #10B981; }
        .kpi td.rojo { border-top-color: #EF4444; }
        .kpi td.naranja { border-top-color: #FFA000; }
        .kpi .etiqueta { font-size: 8.5px; letter-spacing: 1.5px; color: #99773D; text-transform: uppercase; }
        .kpi .valor { font-size: 20px; font-weight: bold; color: #332F29; margin-top: 2px; }
        .kpi .detalle { font-size: 9.5px; color: #8a7f70; }

        /* Secciones */
        h2 { font-size: 13.5px; color: #332F29; background: #FFF3DC; border-left: 4px solid #FFA000; padding: 7px 10px; margin: 22px 0 0 0; }
        h2 .count { background: #332F29; color: #ffffff; font-size: 10px; padding: 2px 9px; border-radius: 10px; margin-left: 6px; }
        .vacio { color: #9a8f80; font-style: italic; margin: 8px 0 0 0; }

        /* Tablas */
        table.datos { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.datos th { background: #332F29; color: #ffffff; text-align: left; padding: 7px 9px; font-size: 10px; letter-spacing: 1px; text-transform: uppercase; }
        table.datos td { padding: 6px 9px; border-bottom: 1px solid #EDE6DA; }
        table.datos tr.alt td { background: #FAF6F0; }
        .der { text-align: right; }
        .centro { text-align: center; }
        .monto { font-weight: bold; }

        /* Bloque de ingresos por día */
        .dia { width: 100%; background: #FFF3DC; border: 1px solid #F5DFB8; border-collapse: collapse; margin-top: 12px; }
        .dia td { padding: 7px 10px; font-size: 11.5px; }
        .dia .subtotal { text-align: right; font-weight: bold; color: #332F29; }

        /* Total general */
        .total-general { width: 100%; background: #332F29; color: #ffffff; border-collapse: collapse; margin-top: 14px; }
        .total-general td { padding: 10px 14px; font-size: 13px; }
        .total-general .monto { text-align: right; font-size: 16px; color: #FFA000; }

        .nota { margin-top: 18px; font-size: 9.5px; color: #9a8f80; text-align: center; }

        .aviso { background: #FFF3CD; border: 1px solid #FFA000; padding: 12px; margin-bottom: 16px; font-size: 11px; }
        .aviso code { background: #ffffff; padding: 2px 6px; border: 1px solid #dddddd; }
    </style>
</head>
<body>
    @if(!empty($sinPdf))
    <div class="aviso">
        <strong>Para descargar este reporte como PDF:</strong> en tu Terminal, dentro de la carpeta FitControl, corre
        <code>composer require barryvdh/laravel-dompdf</code> y vuelve a generar el reporte.
        Mientras tanto puedes imprimir esta página a PDF con Ctrl+P / Cmd+P.
        <br><br>
        <button onclick="window.print()" style="padding:8px 16px; font-weight:bold; cursor:pointer;">Imprimir / Guardar como PDF</button>
    </div>
    @endif

    <!-- Encabezado FitControl -->
    <table class="header">
        <tr>
            <td class="logo">FIT<span>CONTROL</span><small>SISTEMA DE CONTROL DE GIMNASIO</small></td>
            <td class="reporte-titulo">
                <div class="titulo">REPORTE DEL GIMNASIO</div>
                <div class="fecha">Generado el {{ $hoy->format('d/m/Y') }}</div>
            </td>
        </tr>
    </table>

    <!-- Periodo -->
    <table class="periodo">
        <tr>
            <td>Periodo del reporte: <b>{{ $inicio->format('d/m/Y') }} al {{ $fin->format('d/m/Y') }}</b></td>
            <td class="der">Total de socios histórico: <b>{{ $totalSocios }}</b></td>
        </tr>
    </table>

    <!-- Resumen -->
    <table class="kpi">
        <tr>
            <td>
                <div class="etiqueta">Total socios</div>
                <div class="valor">{{ $totalSocios }}</div>
                <div class="detalle">En toda la historia</div>
            </td>
            <td class="verde">
                <div class="etiqueta">Clientes nuevos</div>
                <div class="valor">{{ $nuevos->count() }}</div>
                <div class="detalle">En el periodo</div>
            </td>
            <td class="rojo">
                <div class="etiqueta">Bajas</div>
                <div class="valor">{{ $bajas->count() }}</div>
                <div class="detalle">En el periodo</div>
            </td>
            <td class="naranja">
                <div class="etiqueta">Ingresos</div>
                <div class="valor">${{ number_format($totalIngresos, 2) }}</div>
                <div class="detalle">Total del periodo</div>
            </td>
        </tr>
    </table>

    <h2>Clientes nuevos <span class="count">{{ $nuevos->count() }}</span></h2>
    @if($nuevos->isEmpty())
        <p class="vacio">Sin clientes nuevos en este periodo.</p>
    @else
    <table class="datos">
        <tr><th>Nombre</th><th>Teléfono</th><th>Fecha de registro</th><th>Vence</th></tr>
        @foreach($nuevos as $cliente)
        <tr @if($loop->iteration % 2 == 0) class="alt" @endif>
            <td><b>{{ $cliente->nombre_completo }}</b></td>
            <td>{{ $cliente->telefono ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($cliente->fecha_registro)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <h2>Socios vencidos <span class="count">{{ $vencidos->count() }}</span></h2>
    @if($vencidos->isEmpty())
        <p class="vacio">Sin socios vencidos.</p>
    @else
    <table class="datos">
        <tr><th>Nombre</th><th>Teléfono</th><th>Venció</th></tr>
        @foreach($vencidos as $cliente)
        <tr @if($loop->iteration % 2 == 0) class="alt" @endif>
            <td><b>{{ $cliente->nombre_completo }}</b></td>
            <td>{{ $cliente->telefono ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <h2>Próximos a vencer / Próximas renovaciones <span class="count">{{ $porVencer->count() }}</span></h2>
    @if($porVencer->isEmpty())
        <p class="vacio">Sin renovaciones próximas en los siguientes 5 días.</p>
    @else
    <table class="datos">
        <tr><th>Nombre</th><th>Teléfono</th><th>Fecha de vencimiento</th></tr>
        @foreach($porVencer as $cliente)
        <tr @if($loop->iteration % 2 == 0) class="alt" @endif>
            <td><b>{{ $cliente->nombre_completo }}</b></td>
            <td>{{ $cliente->telefono ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <h2>Bajas en el periodo <span class="count">{{ $bajas->count() }}</span></h2>
    @if($bajas->isEmpty())
        <p class="vacio">Sin bajas en este periodo.</p>
    @else
    <table class="datos">
        <tr><th>Nombre</th><th>Teléfono</th><th>Venció</th></tr>
        @foreach($bajas as $cliente)
        <tr @if($loop->iteration % 2 == 0) class="alt" @endif>
            <td><b>{{ $cliente->nombre_completo }}</b></td>
            <td>{{ $cliente->telefono ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y') }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    <h2>Ingresos del periodo <span class="count">${{ number_format($totalIngresos, 2) }}</span></h2>
    @if($pagos->isEmpty())
        <p class="vacio">Sin ingresos en este periodo.</p>
    @else
        @foreach($ingresosPorDia as $fecha => $pagosDia)
        <table class="dia">
            <tr>
                <td><b>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</b></td>
                <td class="subtotal">Subtotal del día: ${{ number_format($pagosDia->sum('total'), 2) }}</td>
            </tr>
        </table>
        <table class="datos">
            <tr><th>Cliente</th><th>Producto</th><th class="centro">Cantidad</th><th>Método</th><th class="der">Total</th></tr>
            @foreach($pagosDia as $pago)
            <tr @if($loop->iteration % 2 == 0) class="alt" @endif>
                <td><b>{{ $pago->cliente->nombre_completo ?? 'Venta mostrador' }}</b></td>
                <td>{{ $pago->producto->nombre ?? '-' }}</td>
                <td class="centro">{{ $pago->cantidad }}</td>
                <td>{{ $pago->metodo_pago }}</td>
                <td class="der monto">${{ number_format($pago->total, 2) }}</td>
            </tr>
            @endforeach
        </table>
        @endforeach
        <table class="total-general">
            <tr>
                <td>Total del periodo {{ $inicio->format('d/m/Y') }} al {{ $fin->format('d/m/Y') }}</td>
                <td class="monto">${{ number_format($totalIngresos, 2) }}</td>
            </tr>
        </table>
    @endif

    <p class="nota">Documento generado por FitControl — Sistema de control de gimnasio</p>
</body>
</html>
