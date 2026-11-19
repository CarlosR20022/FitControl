<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitControl - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class', 
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            orange: '#FFA000',
                            gold: '#CC9029',
                            lightBrown: '#99773D',
                            brown: '#66573D',
                            dark: '#332F29',
                        }
                    }
                }
            }
        }

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        /* Diseño adaptable: en pantallas grandes (MacBook Pro, monitores) todo el sistema crece proporcionalmente, como en una laptop normal */
        @media (min-width: 1440px) {
            html { font-size: 17px; }
            div.max-w-\[1600px\], main.max-w-\[1600px\] { max-width: 1900px; }
            main.max-w-\[1200px\] { max-width: 1500px; }
            main.max-w-\[1000px\] { max-width: 1300px; }
            a.min-h-\[164px\] { min-height: 205px; }
            div.min-h-\[140px\] { min-height: 175px; }
            div.min-h-\[158px\] { min-height: 195px; }
        }
        @media (min-width: 1800px) {
            html { font-size: 18px; }
            div.max-w-\[1600px\], main.max-w-\[1600px\] { max-width: 2100px; }
            main.max-w-\[1200px\] { max-width: 1650px; }
            main.max-w-\[1000px\] { max-width: 1450px; }
            a.min-h-\[164px\] { min-height: 220px; }
            div.min-h-\[140px\] { min-height: 190px; }
            div.min-h-\[158px\] { min-height: 210px; }
        }
        @media (min-width: 2200px) {
            html { font-size: 20px; }
            div.max-w-\[1600px\], main.max-w-\[1600px\] { max-width: 2400px; }
            main.max-w-\[1200px\] { max-width: 1850px; }
            main.max-w-\[1000px\] { max-width: 1600px; }
            a.min-h-\[164px\] { min-height: 245px; }
            div.min-h-\[140px\] { min-height: 210px; }
            div.min-h-\[158px\] { min-height: 230px; }
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen text-brand-dark dark:text-gray-100 flex flex-col relative transition-colors duration-200">

    <!-- ENCABEZADO RESPONSIVO -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-200">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
            
            <!-- LOGOTIPO -->
            <div class="flex items-center gap-2">
                <div class="bg-brand-orange text-white p-1.5 rounded-lg shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="font-black text-xl tracking-widest italic text-brand-dark dark:text-white">FIT<span class="text-brand-orange">CONTROL</span></span>
            </div>

            <!-- NAVEGACIÓN -->
            <nav class="flex items-center gap-1 lg:gap-2 overflow-x-auto pb-1 md:pb-0 w-full md:w-auto order-3 md:order-2">
                <a href="{{ route('clientes.create') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange dark:hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors">
                    + Agregar Nuevo Cliente
                </a>
                <a href="{{ route('clientes.consulta') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange dark:hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors">
                    🔍 Consulta
                </a>
                <a href="{{ route('modificaciones.index') }}" class="...">✏️ Modificaciones</a>
                
                <button onclick="abrirModalVenta()" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-white bg-brand-orange shadow-[0_4px_10px_rgba(255,160,0,0.3)] hover:bg-brand-gold hover:-translate-y-0.5 active:translate-y-0 transition-all cursor-pointer">
                    💵 Venta
                </button>
            </nav>

            <div class="flex items-center gap-4 pl-0 md:pl-4 md:border-l-2 border-gray-100 dark:border-gray-700 order-2 md:order-3">
                <!-- BOTÓN MODO OSCURO -->
                <button onclick="toggleTheme()" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none">
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>

                <!-- USUARIO LOGUEADO -->
                <div class="flex items-center gap-2 cursor-pointer group">
                    <div class="w-8 h-8 rounded-full bg-brand-lightBrown text-white flex items-center justify-center font-bold text-sm shadow-inner">
                        {{ substr(Auth::user()->name ?? 'C', 0, 1) }}
                    </div>
                    <span class="font-semibold text-sm text-brand-brown dark:text-gray-300 group-hover:text-brand-orange transition-colors hidden sm:block">
                        {{ Auth::user()->name ?? 'Carlos Rendón' }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

        </div>
    </header>

    <!-- ÁREA DE FILTROS / ACCIONES RÁPIDAS -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 sm:px-6 py-3 transition-colors duration-200">
        <div class="max-w-[1600px] mx-auto flex flex-col sm:flex-row items-center gap-3">
            <input type="date" class="w-full sm:w-auto bg-transparent border border-gray-300 dark:border-gray-600 rounded px-3 py-1.5 text-sm font-medium text-brand-dark dark:text-gray-200 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
            <button onclick="abrirModalReporte()" class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white text-sm font-bold px-4 py-1.5 rounded shadow-sm transition-colors">
                Generar Reporte
            </button>
        </div>
    </div>

    <!-- CONTENIDO DEL DASHBOARD -->
    <main class="flex-1 p-4 sm:p-6 overflow-y-auto max-w-[1600px] mx-auto w-full">
        
        <!-- FILA 1: TARJETAS DE COLORES -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <a href="{{ route('clientes.vigentes') }}" class="bg-[#10B981] rounded shadow-sm p-5 min-h-[164px] flex justify-between items-center text-white relative overflow-hidden hover:scale-[1.02] hover:shadow-md transition-all cursor-pointer block">
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-4xl font-black mb-1">{{ $sociosVigentes ?? 0 }}</h3>
                        <p class="text-sm font-medium opacity-90">Socios Vigentes</p>
                    </div>
                    <span class="text-xs font-bold mt-2 bg-white/20 inline-block px-2 py-1 rounded w-max">Ver lista →</span>
                </div>
                <svg class="w-16 h-16 opacity-20 absolute -right-2 -bottom-2 z-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
            </a>

            <a href="{{ route('clientes.porVencer') }}" class="bg-brand-orange rounded shadow-sm p-5 min-h-[164px] flex justify-between items-center text-white relative overflow-hidden hover:scale-[1.02] hover:shadow-md transition-all cursor-pointer block">
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-4xl font-black mb-1">{{ $porVencer ?? 0 }}</h3>
                        <p class="text-sm font-medium opacity-90">Por Vencer (Próx. 5 días)</p>
                    </div>
                    <span class="text-xs font-bold mt-2 bg-white/20 inline-block px-2 py-1 rounded w-max">Ver lista →</span>
                </div>
                <svg class="w-16 h-16 opacity-20 absolute -right-2 -bottom-2 z-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
            </a>

            <a href="{{ route('clientes.vencidos') }}" class="bg-[#EF4444] rounded shadow-sm p-5 min-h-[164px] flex justify-between items-center text-white relative overflow-hidden hover:scale-[1.02] hover:shadow-md transition-all cursor-pointer block">
                <div class="relative z-10 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-4xl font-black mb-1">{{ $vencidosRecientes ?? 0 }}</h3>
                        <p class="text-sm font-medium opacity-90">Vencidos Recientes</p>
                    </div>
                    <span class="text-xs font-bold mt-2 bg-white/20 inline-block px-2 py-1 rounded w-max">Ver lista →</span>
                </div>
                <svg class="w-16 h-16 opacity-20 absolute -right-2 -bottom-2 z-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
            </a>
        </div>

        <!-- FILA 2: MÉTRICAS SECUNDARIAS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <a href="{{ route('clientes.historico') }}" class="bg-white dark:bg-gray-800 rounded shadow-sm p-5 min-h-[140px] border border-gray-100 dark:border-gray-700 flex flex-col justify-center transition-colors hover:scale-[1.02] hover:shadow-md cursor-pointer block">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-green-500 bg-green-50 dark:bg-green-500/10 p-1 rounded">👤</span>
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Socios Histórico</span>
                </div>
                <span class="text-3xl font-light text-brand-dark dark:text-white">{{ $totalHistorico ?? 0 }}</span>
                <span class="text-xs font-bold mt-2 text-brand-orange">Ver lista →</span>
            </a>
            
            <a href="{{ route('clientes.nuevos') }}" class="bg-white dark:bg-gray-800 rounded shadow-sm p-5 min-h-[140px] border border-gray-100 dark:border-gray-700 flex flex-col justify-center transition-colors hover:scale-[1.02] hover:shadow-md cursor-pointer block">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-purple-500 bg-purple-50 dark:bg-purple-500/10 p-1 rounded">🎯</span>
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nuevos (Mes actual)</span>
                </div>
                <span class="text-3xl font-light text-brand-dark dark:text-white">{{ $nuevosMes ?? 0 }}</span>
                <span class="text-xs font-bold mt-2 text-brand-orange">Ver lista →</span>
            </a>

            <a href="{{ route('clientes.bajas') }}" class="bg-white dark:bg-gray-800 rounded shadow-sm p-5 min-h-[140px] border border-gray-100 dark:border-gray-700 flex flex-col justify-center transition-colors hover:scale-[1.02] hover:shadow-md cursor-pointer block">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-red-500 bg-red-50 dark:bg-red-500/10 p-1 rounded">📉</span>
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Bajas (Mes actual)</span>
                </div>
                <span class="text-3xl font-light text-brand-dark dark:text-white">{{ $bajasMes ?? 0 }}</span>
                <span class="text-xs font-bold mt-2 text-brand-orange">Ver lista →</span>
            </a>
        </div>

        <!-- FILA 3: INGRESOS Y RENOVACIONES -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded shadow-sm p-6 min-h-[158px] border border-gray-100 dark:border-gray-700 flex justify-between items-center relative overflow-hidden transition-colors">
                <div class="absolute inset-0 opacity-10 bg-gradient-to-r from-blue-400 to-transparent"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Ingresos del Día</span>
                        <svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <span class="text-4xl font-light text-blue-500 dark:text-blue-400">${{ number_format($ingresosDia ?? 0, 2) }}</span>
                    <a href="{{ route('ingresos.dia') }}" class="block mt-2 text-xs font-bold text-red-500 hover:text-red-600 transition-colors">Ver Detalles ></a>
                </div>
                <div class="relative z-10 bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border border-gray-100 dark:border-gray-600 transition-colors">
                    <span class="text-3xl">💵</span>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded shadow-sm p-6 min-h-[158px] border border-gray-100 dark:border-gray-700 flex justify-between items-center relative overflow-hidden transition-colors">
                <div class="absolute inset-0 opacity-10 bg-gradient-to-r from-brand-orange to-transparent"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Renovaciones Próximas</span>
                    </div>
                    <span class="text-4xl font-light text-brand-dark dark:text-white">{{ $renovacionesProximas ?? 0 }} <span class="text-base text-gray-400">PAGOS</span></span>
                    <a href="{{ route('clientes.porVencer') }}" class="block mt-2 text-xs font-bold text-red-500 hover:text-red-600 transition-colors">Ver Detalles ></a>
                </div>
                <div class="relative z-10 bg-gray-50 dark:bg-gray-700 p-4 rounded-xl border border-gray-100 dark:border-gray-600 transition-colors">
                    <span class="text-3xl">📅</span>
                </div>
            </div>
        </div>

    </main>

    <!-- MODAL DE VENTA RÁPIDA -->
    <div id="ventaModalOverlay" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative border border-gray-100 dark:border-gray-700 transition-colors">
            
            <button onclick="cerrarModalVenta()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <!-- VISTA 1: CATEGORÍAS -->
            <div id="vistaCategorias" class="p-6">
                <h2 class="text-2xl font-black text-brand-dark dark:text-white text-center mb-6">Selecciona una Categoría</h2>
                <div class="grid grid-cols-1 gap-3">
                    <button onclick="mostrarSubOpciones('scoop')" class="bg-orange-50 dark:bg-brand-orange/10 border border-orange-200 dark:border-brand-orange/30 hover:border-brand-orange hover:bg-orange-100 dark:hover:bg-brand-orange/20 text-brand-orange font-bold py-4 rounded-xl text-lg transition-all shadow-sm flex justify-center items-center gap-2">
                        💪 Scoop (Pre-Entreno)
                    </button>
                    <button class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 text-gray-400 dark:text-gray-500 font-bold py-4 rounded-xl text-lg cursor-not-allowed flex justify-center items-center gap-2">
                        🥤 Bebida (Próximamente)
                    </button>
                    <button class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 text-gray-400 dark:text-gray-500 font-bold py-4 rounded-xl text-lg cursor-not-allowed flex justify-center items-center gap-2">
                        🍫 Snack (Próximamente)
                    </button>
                </div>
            </div>

            <!-- VISTA 2: OPCIONES DE SCOOP -->
            <div id="vistaOpciones" class="p-6 hidden">
                <button onclick="volverCategorias()" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-brand-orange dark:hover:text-brand-orange mb-4 flex items-center gap-1 transition-colors">
                    ← Volver
                </button>
                <h2 class="text-2xl font-black text-brand-dark dark:text-white text-center mb-6">Tipo de Pre-Entreno</h2>
                
                <div class="grid grid-cols-1 gap-3">
                    @if(count($suplementos) === 0)
                        <div class="text-center py-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 text-gray-500 dark:text-gray-400 text-sm font-medium">
                            No hay suplementos registrados en la base de datos.<br>
                            <span class="text-xs">Agrégalos para empezar a vender.</span>
                        </div>
                    @else
                        @foreach ($suplementos as $suplemento)
                            <button onclick="prepararConfirmacion('{{ $suplemento->nombre }}', {{ $suplemento->precio }})" class="bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 hover:border-brand-orange dark:hover:border-brand-orange hover:bg-orange-50 dark:hover:bg-gray-600 font-bold py-3 px-4 rounded-xl transition-all shadow-sm flex justify-between items-center group">
                                <span class="text-brand-dark dark:text-gray-200 group-hover:text-brand-orange transition-colors">{{ $suplemento->nombre }}</span>
                                <span class="text-brand-orange text-xl">${{ number_format($suplemento->precio, 2) }}</span>
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- VISTA 3: CONFIRMACIÓN -->
            <div id="vistaConfirmacion" class="p-6 hidden text-center">
                <div class="bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-2xl font-black text-brand-dark dark:text-white mb-2">Confirmar Venta</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">Se registrará la venta de: <br>
                    <span id="textoConfirmacion" class="text-xl font-bold text-brand-orange block mt-2"></span>
                </p>

                <form action="{{ route('ventas.rapida') }}" method="POST" id="formVenta">
                    @csrf
                    <input type="hidden" name="nombre_producto" id="inputNombreProducto">
                    <input type="hidden" name="precio" id="inputPrecioProducto">
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="volverOpciones()" class="w-1/2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 border border-transparent dark:border-gray-600 font-bold py-3 rounded-xl transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="w-1/2 bg-brand-orange hover:bg-brand-gold text-white font-bold py-3 rounded-xl shadow-md transition-all">
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- MODAL DE REPORTE -->
    <div id="reporteModalOverlay" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md overflow-hidden relative border border-gray-100 dark:border-gray-700 transition-colors">
            <button onclick="cerrarModalReporte()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <div class="p-6">
                <h2 class="text-2xl font-black text-brand-dark dark:text-white text-center mb-2">Generar Reporte</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm text-center mb-6">Elige el periodo del reporte o usa un atajo rápido.</p>
                <div class="grid grid-cols-3 gap-2 mb-5">
                    <button type="button" onclick="setRango('dia')" class="bg-orange-50 dark:bg-brand-orange/10 border border-orange-200 dark:border-brand-orange/30 hover:border-brand-orange text-brand-orange font-bold py-2 rounded-xl text-sm transition-all">Día</button>
                    <button type="button" onclick="setRango('semana')" class="bg-orange-50 dark:bg-brand-orange/10 border border-orange-200 dark:border-brand-orange/30 hover:border-brand-orange text-brand-orange font-bold py-2 rounded-xl text-sm transition-all">Semana</button>
                    <button type="button" onclick="setRango('mes')" class="bg-orange-50 dark:bg-brand-orange/10 border border-orange-200 dark:border-brand-orange/30 hover:border-brand-orange text-brand-orange font-bold py-2 rounded-xl text-sm transition-all">Mes</button>
                </div>
                <form action="{{ route('reporte.pdf') }}" method="GET" target="_blank">
                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Desde</label>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" required value="{{ date('Y-m-d') }}" class="w-full bg-transparent border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm font-medium text-brand-dark dark:text-gray-200 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Hasta</label>
                            <input type="date" id="fecha_fin" name="fecha_fin" required value="{{ date('Y-m-d') }}" class="w-full bg-transparent border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm font-medium text-brand-dark dark:text-gray-200 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-5">El PDF incluye: clientes nuevos, socios vencidos, próximos a vencer con sus fechas, total de socios, bajas, ingresos por día y total del periodo, y próximas renovaciones.</p>
                    <div class="flex gap-3">
                        <button type="button" onclick="cerrarModalReporte()" class="w-1/2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold py-3 rounded-xl transition-colors">Cancelar</button>
                        <button type="submit" class="w-1/2 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl shadow-md transition-all">⬇ Descargar PDF</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        // --- LOGICA DEL MODO OSCURO ---
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        function toggleTheme() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.theme === 'dark') {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }

        // --- LOGICA DEL MODAL DE VENTA ---
        const overlay = document.getElementById('ventaModalOverlay');
        const vistaCategorias = document.getElementById('vistaCategorias');
        const vistaOpciones = document.getElementById('vistaOpciones');
        const vistaConfirmacion = document.getElementById('vistaConfirmacion');
        
        function abrirModalVenta() {
            vistaCategorias.classList.remove('hidden');
            vistaOpciones.classList.add('hidden');
            vistaConfirmacion.classList.add('hidden');
            overlay.classList.remove('hidden');
        }

        function cerrarModalVenta() {
            overlay.classList.add('hidden');
        }

        function mostrarSubOpciones(categoria) {
            vistaCategorias.classList.add('hidden');
            vistaOpciones.classList.remove('hidden');
        }

        function volverCategorias() {
            vistaOpciones.classList.add('hidden');
            vistaCategorias.classList.remove('hidden');
        }

        function prepararConfirmacion(nombre, precio) {
            vistaOpciones.classList.add('hidden');
            vistaConfirmacion.classList.remove('hidden');
            document.getElementById('textoConfirmacion').innerText = `${nombre} ($${precio}.00)`;
            document.getElementById('inputNombreProducto').value = nombre;
            document.getElementById('inputPrecioProducto').value = precio;
        }

        function volverOpciones() {
            vistaConfirmacion.classList.add('hidden');
            vistaOpciones.classList.remove('hidden');
        }

        // --- LOGICA DEL MODAL DE REPORTE ---
        const reporteOverlay = document.getElementById('reporteModalOverlay');

        function abrirModalReporte() {
            reporteOverlay.classList.remove('hidden');
        }

        function cerrarModalReporte() {
            reporteOverlay.classList.add('hidden');
        }

        function fechaLocal(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const dia = String(d.getDate()).padStart(2, '0');
            return y + '-' + m + '-' + dia;
        }

        function setRango(tipo) {
            const hoy = new Date();
            let inicio = new Date(hoy);
            let fin = new Date(hoy);
            if (tipo === 'semana') {
                const diaSemana = (hoy.getDay() + 6) % 7; // lunes = 0
                inicio.setDate(hoy.getDate() - diaSemana);
                fin.setDate(inicio.getDate() + 6);
            } else if (tipo === 'mes') {
                inicio = new Date(hoy.getFullYear(), hoy.getMonth(), 1);
                fin = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0);
            }
            document.getElementById('fecha_inicio').value = fechaLocal(inicio);
            document.getElementById('fecha_fin').value = fechaLocal(fin);
        }
    </script>
</body>
</html>