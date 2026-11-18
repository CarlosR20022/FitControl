<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Socios Por Vencer - FitControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class', // Habilita el modo oscuro
            theme: {
                extend: { 
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { orange: '#FFA000', gold: '#CC9029', lightBrown: '#99773D', brown: '#66573D', dark: '#332F29' } } 
                }
            }
        }

        // Lee la preferencia de modo oscuro guardada en el navegador
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen text-brand-dark dark:text-gray-100 flex flex-col transition-colors duration-200">

    <!-- ENCABEZADO RESPONSIVO IDENTICO AL DASHBOARD -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-200">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
            
            <!-- LOGOTIPO -->
            <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <div class="bg-brand-orange text-white p-1.5 rounded-lg shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="font-black text-xl tracking-widest italic text-brand-dark dark:text-white">FIT<span class="text-brand-orange">CONTROL</span></span>
            </a>

            <!-- NAVEGACIÓN -->
            <nav class="flex items-center gap-1 lg:gap-2 overflow-x-auto pb-1 md:pb-0 w-full md:w-auto order-3 md:order-2">
                <a href="{{ route('clientes.create') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange dark:hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors">
                    + Agregar Nuevo Cliente
                </a>
                <a href="{{ route('clientes.consulta') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange dark:hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors">
                    🔍 Consulta
                </a>
                <a href="{{ route('modificaciones.index') }}" class="...">✏️ Modificaciones</a>
                
                <!-- El botón de Venta te regresa al dashboard para abrir el modal -->
                <a href="/" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-white bg-brand-orange shadow-[0_4px_10px_rgba(255,160,0,0.3)] hover:bg-brand-gold hover:-translate-y-0.5 active:translate-y-0 transition-all cursor-pointer">
                    💵 Venta
                </a>
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

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-1 p-4 sm:p-6 overflow-y-auto max-w-[1600px] mx-auto w-full">
        
        <!-- SUB-ENCABEZADO (TARJETA BLANCA DE TÍTULO) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 sm:px-8 py-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-colors">
            <div class="flex items-center gap-4">
                <div class="bg-green-100 dark:bg-green-500/20 text-green-600 dark:text-green-400 p-3 rounded-xl hidden sm:block">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-black text-brand-dark dark:text-white tracking-tight">Socios Por Vencer</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Clientes cuya membresía vence en los próximos 5 días.</p>
                </div>
            </div>
            
            <a href="/" class="w-full sm:w-auto bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:border-brand-orange dark:hover:border-brand-orange hover:text-brand-orange font-bold py-2.5 px-5 rounded-lg transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Panel
            </a>
        </div>

        <!-- TABLA DE DATOS -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 transition-colors">
                            <th class="px-6 py-4 font-bold">Nombre del Cliente</th>
                            <th class="px-6 py-4 font-bold">Teléfono</th>
                            <th class="px-6 py-4 font-bold">Fecha de Ingreso</th>
                            <th class="px-6 py-4 font-bold">Fecha de Vencimiento</th>
                            <th class="px-6 py-4 font-bold text-center">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($clientes as $cliente)
                        <tr class="hover:bg-orange-50 dark:hover:bg-gray-700/50 transition-colors group">
                            <td class="px-6 py-4 font-bold text-brand-dark dark:text-white flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-brand-orange/20 text-brand-orange flex items-center justify-center font-bold text-xs">
                                    {{ substr($cliente->nombre_completo, 0, 1) }}
                                </div>
                                {{ $cliente->nombre_completo }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400 font-medium">{{ $cliente->telefono ?? 'Sin registro' }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($cliente->fecha_registro)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 font-bold text-brand-dark dark:text-gray-200">{{ \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-orange-100 dark:bg-orange-500/20 text-orange-700 dark:text-orange-400 text-xs font-bold px-3 py-1.5 rounded-full border border-orange-200 dark:border-orange-500/30 shadow-sm inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-600 dark:bg-orange-400"></span>
                                    Por vencer
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">No hay socios por vencer en los próximos 5 días.</p>
                                    <a href="{{ route('clientes.create') }}" class="mt-4 text-brand-orange hover:text-brand-gold font-bold transition-colors">
                                        + Agregar tu primer socio
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pie de tabla -->
            <div class="bg-gray-50 dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400 font-medium flex justify-between items-center transition-colors">
                <span>Mostrando {{ count($clientes) }} cliente(s)</span>
            </div>
        </div>

    </main>

    <!-- Script del Modo Oscuro -->
    <script>
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
    </script>
</body>
</html>