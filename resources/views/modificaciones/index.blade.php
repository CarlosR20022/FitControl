<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificaciones - FitControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class', 
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] }, colors: { brand: { orange: '#FFA000', gold: '#CC9029', lightBrown: '#99773D', brown: '#66573D', dark: '#332F29' } } } }
        }
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) { document.documentElement.classList.add('dark'); } else { document.documentElement.classList.remove('dark'); }
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
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-3">
            <a href="/" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                <div class="bg-brand-orange text-white p-1.5 rounded-lg shadow-md">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <span class="font-black text-xl tracking-widest italic text-brand-dark dark:text-white">FIT<span class="text-brand-orange">CONTROL</span></span>
            </a>
            <nav class="flex items-center gap-1 lg:gap-2 overflow-x-auto pb-1 md:pb-0 w-full md:w-auto order-3 md:order-2">
                <a href="{{ route('clientes.create') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors">+ Agregar Nuevo Cliente</a>
                <a href="{{ route('clientes.consulta') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors">🔍 Consulta</a>
                <a href="{{ route('modificaciones.index') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-orange dark:text-brand-orange bg-orange-50 dark:bg-gray-700 transition-colors">✏️ Modificaciones</a>
                <a href="/" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-white bg-brand-orange shadow-[0_4px_10px_rgba(255,160,0,0.3)] hover:bg-brand-gold transition-all">💵 Venta</a>
            </nav>
            <div class="flex items-center gap-4 pl-0 md:pl-4 md:border-l-2 border-gray-100 dark:border-gray-700 order-2 md:order-3">
                <button onclick="toggleTheme()" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none">
                    <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path></svg>
                    <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </button>
                <div class="flex items-center gap-2 cursor-pointer group">
                    <div class="w-8 h-8 rounded-full bg-brand-lightBrown text-white flex items-center justify-center font-bold text-sm shadow-inner">{{ substr(Auth::user()->name ?? 'C', 0, 1) }}</div>
                </div>
            </div>
        </div>
    </header>

    <!-- CONTENIDO -->
    <main class="flex-1 p-4 sm:p-6 max-w-[1200px] mx-auto w-full">
        <h1 class="text-3xl font-black text-brand-dark dark:text-white mb-2">Panel de Modificaciones</h1>
        <p class="text-gray-500 dark:text-gray-400 font-medium mb-8">Selecciona el módulo que deseas configurar.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Botón Clientes -->
            <a href="{{ route('modificaciones.clientes') }}" class="bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-2xl p-6 text-left hover:border-brand-orange dark:hover:border-brand-orange transition-all hover:shadow-md block">
                <span class="text-4xl mb-4 block">👥</span>
                <h2 class="text-xl font-bold text-brand-orange">Clientes</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Corrige los datos de un cliente: nombre, teléfono, plan y fecha de ingreso.</p>
            </a>

            <!-- Botón Usuarios -->
            <a href="{{ route('modificaciones.usuarios') }}" class="bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-2xl p-6 text-left hover:border-brand-orange dark:hover:border-brand-orange transition-all hover:shadow-md block">
                <span class="text-4xl mb-4 block">🛡️</span>
                <h2 class="text-xl font-bold text-brand-orange">Usuarios</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Cuentas de administradores: agrega usuarios con su ID y corrige su nombre.</p>
            </a>

            <!-- Botón PRODUCTOS (Activo) -->
            <a href="{{ route('productos.index') }}" class="bg-white dark:bg-gray-800 border-2 border-transparent hover:border-brand-orange dark:hover:border-brand-orange rounded-2xl p-6 text-left shadow-md hover:shadow-lg transition-all group">
                <span class="text-4xl mb-4 block group-hover:scale-110 transition-transform w-max">📦</span>
                <h2 class="text-xl font-bold text-brand-orange">Productos</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Agrega productos nuevos con su precio y stock.</p>
            </a>

            <!-- Botón PRECIOS (Activo) -->
            <a href="{{ route('modificaciones.precios') }}" class="bg-white dark:bg-gray-800 border-2 border-transparent hover:border-brand-orange dark:hover:border-brand-orange rounded-2xl p-6 text-left shadow-md hover:shadow-lg transition-all group">
                <span class="text-4xl mb-4 block group-hover:scale-110 transition-transform w-max">💲</span>
                <h2 class="text-xl font-bold text-brand-orange">Precios</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Modifica el costo de membresías, scoops, bebidas y snacks.</p>
            </a>
        </div>
    </main>

    <script>
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) { themeToggleLightIcon.classList.remove('hidden'); } else { themeToggleDarkIcon.classList.remove('hidden'); }
        function toggleTheme() {
            themeToggleDarkIcon.classList.toggle('hidden'); themeToggleLightIcon.classList.toggle('hidden');
            if (localStorage.theme === 'dark') { document.documentElement.classList.remove('dark'); localStorage.theme = 'light'; } else { document.documentElement.classList.add('dark'); localStorage.theme = 'dark'; }
        }
    </script>
</body>
</html>