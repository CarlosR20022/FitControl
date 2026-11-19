<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios - FitControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { orange: '#FFA000', gold: '#CC9029', lightBrown: '#99773D', brown: '#66573D', dark: '#332F29' } }
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
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen text-brand-dark dark:text-gray-100 flex flex-col transition-colors duration-200">

    <!-- ENCABEZADO -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-colors duration-200">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
            <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <div class="bg-brand-orange text-white p-1.5 rounded-lg shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="font-black text-xl tracking-widest italic text-brand-dark dark:text-white">FIT<span class="text-brand-orange">CONTROL</span></span>
            </a>
            <nav class="flex items-center gap-1 lg:gap-2 overflow-x-auto pb-1 md:pb-0 w-full md:w-auto order-3 md:order-2">
                <a href="{{ route('clientes.create') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange dark:hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors">+ Agregar Nuevo Cliente</a>
                <button onclick="toggleMenuConsulta(event)" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange dark:hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">🔍 Consulta ▾</button>
                <a href="{{ route('modificaciones.index') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-orange dark:text-brand-orange bg-orange-50 dark:bg-gray-700 transition-colors">✏️ Modificaciones</a>
                <a href="/" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-white bg-brand-orange shadow-[0_4px_10px_rgba(255,160,0,0.3)] hover:bg-brand-gold transition-all">💵 Venta</a>
            </nav>
            <div class="flex items-center gap-4 pl-0 md:pl-4 md:border-l-2 border-gray-100 dark:border-gray-700 order-2 md:order-3">
                <button onclick="toggleTheme()" class="p-2 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none">
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>
                <div class="flex items-center gap-2 cursor-pointer group">
                    <div class="w-8 h-8 rounded-full bg-brand-lightBrown text-white flex items-center justify-center font-bold text-sm shadow-inner">{{ substr(Auth::user()->name ?? 'C', 0, 1) }}</div>
                    <span class="font-semibold text-sm text-brand-brown dark:text-gray-300 group-hover:text-brand-orange transition-colors hidden sm:block">{{ Auth::user()->name ?? 'Carlos Rendón' }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- MENÚ DESPLEGABLE DE CONSULTA -->
    <div id="menuConsulta" class="hidden fixed z-[100] w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1">
        <a href="{{ route('clientes.consulta') }}" class="block px-4 py-2 text-sm font-bold text-brand-dark dark:text-gray-200 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brand-orange">👤 Consulta de cliente</a>
        <a href="{{ route('consulta.stock') }}" class="block px-4 py-2 text-sm font-bold text-brand-dark dark:text-gray-200 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brand-orange">📦 Consulta de stock</a>
    </div>

    <!-- CONTENIDO -->
    <main class="flex-1 p-4 sm:p-6 max-w-[1200px] mx-auto w-full">
        <a href="{{ route('modificaciones.index') }}" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-brand-orange flex items-center gap-1 mb-2 w-max">← Volver a Configuración</a>
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-black text-brand-dark dark:text-white">Usuarios</h1>
                <p class="text-gray-500 dark:text-gray-400 font-medium">Cuentas de administrador: inician sesión con su ID y contraseña.</p>
            </div>
            <a href="{{ route('modificaciones.usuarios.create') }}" class="bg-brand-orange hover:bg-brand-gold text-white font-bold py-2.5 px-5 rounded-lg shadow-md transition-all whitespace-nowrap">＋ Agregar usuario</a>
        </div>

        @if(session('exito'))
            <div class="bg-green-100 dark:bg-green-500/20 border border-green-200 dark:border-green-500/30 text-green-700 dark:text-green-400 font-bold px-4 py-3 rounded-xl mb-4">{{ session('exito') }}</div>
        @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-5 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">ID</th>
                            <th class="px-5 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Nombre</th>
                            <th class="px-5 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Fecha de creación</th>
                            <th class="px-5 py-3 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($usuarios as $usuario)
                            <tr class="hover:bg-orange-50/50 dark:hover:bg-gray-700/30 transition-colors">
                                <td class="px-5 py-3 font-black text-brand-orange">{{ $usuario->codigo ?? '—' }}</td>
                                <td class="px-5 py-3 font-bold text-brand-dark dark:text-white">{{ $usuario->name }}</td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ $usuario->created_at ? $usuario->created_at->format('d/m/Y') : '—' }}</td>
                                <td class="px-5 py-3">
                                    <a href="{{ route('modificaciones.usuarios.edit', $usuario->id) }}" class="text-brand-orange hover:text-brand-gold font-bold text-sm">✏️ Editar</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400 font-medium">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400 font-medium">
                Mostrando {{ $usuarios->count() }} usuario(s)
            </div>
        </div>
    </main>

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
        function toggleMenuConsulta(e) {
            e.stopPropagation();
            const menu = document.getElementById('menuConsulta');
            const r = e.currentTarget.getBoundingClientRect();
            menu.style.top = (r.bottom + 6) + 'px';
            menu.style.left = Math.max(8, Math.min(r.left, window.innerWidth - 232)) + 'px';
            menu.classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('menuConsulta');
            if (menu && !menu.classList.contains('hidden') && !e.target.closest('#menuConsulta')) {
                menu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
