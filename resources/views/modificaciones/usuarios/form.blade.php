<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($usuario) ? 'Editar Usuario' : 'Agregar Usuario' }} - FitControl</title>
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

    <!-- CONTENIDO PRINCIPAL DEL FORMULARIO -->
    <main class="flex-1 p-6 flex items-center justify-center max-w-[1600px] mx-auto w-full">
        <div class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow-lg border-t-4 border-brand-orange overflow-hidden transition-colors duration-200">
            <div class="bg-gray-50 dark:bg-gray-700/50 px-8 py-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center transition-colors">
                <div>
                    <h2 class="text-2xl font-black text-brand-dark dark:text-white tracking-tight">{{ isset($usuario) ? 'Editar Usuario' : 'Agregar Usuario' }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ isset($usuario) ? 'Corrige el nombre y la fecha de creación de ' . $usuario->name . '.' : 'Crea una cuenta de administrador nueva.' }}</p>
                </div>
                <div class="bg-orange-100 dark:bg-brand-orange/20 text-brand-orange p-3 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
            </div>

            <form action="{{ isset($usuario) ? route('modificaciones.usuarios.update', $usuario->id) : route('modificaciones.usuarios.store') }}" method="POST" class="p-8 flex flex-col gap-5">
                @csrf
                @if(isset($usuario))
                    @method('PUT')
                @endif

                @if($errors->any())
                    <div class="bg-red-100 dark:bg-red-500/20 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 font-bold px-4 py-3 rounded-xl text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- ID -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">ID de usuario</label>
                    <input type="text" value="{{ isset($usuario) ? $usuario->codigo : $siguienteId }}" disabled
                           class="w-full bg-gray-100 dark:bg-gray-700/50 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 font-black text-brand-orange text-lg cursor-not-allowed">
                    @if(!isset($usuario))
                        <p class="text-xs text-gray-500 dark:text-gray-400">Se asigna automáticamente (el siguiente disponible).</p>
                    @endif
                </div>

                <!-- Nombre -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', isset($usuario) ? $usuario->name : '') }}" required placeholder="Ej. Juan Pérez"
                           class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange dark:focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-medium text-brand-dark dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                @if(isset($usuario))
                    <!-- Fecha de creación (solo al editar) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Fecha de creación <span class="text-red-500">*</span></label>
                        <input type="date" name="fecha_creacion" required value="{{ old('fecha_creacion', $usuario->created_at ? $usuario->created_at->format('Y-m-d') : '') }}"
                               class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange dark:focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-medium text-brand-dark dark:text-white cursor-pointer" style="color-scheme: dark;">
                    </div>
                @else
                    <!-- Contraseña (solo al crear) -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Contraseña <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="3" placeholder="Contraseña para iniciar sesión"
                               class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange dark:focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-medium text-brand-dark dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Con este ID y esta contraseña el usuario podrá iniciar sesión.</p>
                    </div>
                @endif

                <!-- Botones -->
                <div class="flex gap-3 mt-4">
                    <a href="{{ route('modificaciones.usuarios') }}" class="w-1/3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold py-3 rounded-lg text-center transition-colors border border-gray-300 dark:border-gray-600 shadow-sm">Cancelar</a>
                    <button type="submit" class="w-2/3 bg-brand-orange hover:bg-brand-gold text-white font-bold py-3 rounded-lg shadow-md hover:shadow-lg transition-all active:scale-[0.98]">{{ isset($usuario) ? '💾 Guardar cambios' : '💾 Crear usuario' }}</button>
                </div>
            </form>
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
