<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Precios - FitControl</title>
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
            </div>
        </div>
    </header>

    <!-- CONTENIDO -->
    <main class="flex-1 p-4 sm:p-6 max-w-[1000px] mx-auto w-full">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('modificaciones.index') }}" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-brand-orange flex items-center gap-1 mb-2 w-max">← Volver a Configuración</a>
                <h1 class="text-3xl font-black text-brand-dark dark:text-white">Ajuste de Precios</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 dark:bg-green-500/20 border border-green-200 dark:border-green-500/30 text-green-700 dark:text-green-400 font-bold px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
        @endif


        <!-- LISTA DE PRODUCTOS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($productos as $producto)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 flex justify-between items-center transition-colors">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">{{ $producto->categoria }}</span>
                        <h3 class="text-lg font-bold text-brand-dark dark:text-white">{{ $producto->nombre }}</h3>
                        <p class="text-2xl font-black text-brand-orange mt-1">${{ number_format($producto->precio, 2) }}</p>
                    </div>
                    <button onclick="abrirModalEdicion({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->precio }})" class="bg-gray-100 dark:bg-gray-700 hover:bg-orange-100 dark:hover:bg-brand-orange/20 text-gray-700 dark:text-gray-300 hover:text-brand-orange font-bold py-2 px-4 rounded-lg transition-colors border border-gray-200 dark:border-gray-600">
                        ✏️ Modificar
                    </button>
                </div>
            @endforeach
        </div>
    </main>

    <!-- MODAL DE EDICIÓN DE PRECIO -->
    <div id="modalEdicion" class="fixed inset-0 bg-black/60 z-[100] hidden flex items-center justify-center p-4 backdrop-blur-sm transition-opacity">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden relative border border-gray-100 dark:border-gray-700 p-6">
            
            <button onclick="cerrarModalEdicion()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <h2 class="text-xl font-black text-brand-dark dark:text-white mb-1">Actualizar Precio</h2>
            <p id="nombreProductoModal" class="text-brand-orange font-bold mb-6 text-lg"></p>

            <form id="formEditarPrecio" method="POST" class="flex flex-col gap-4">
                @csrf
                <!-- El action del form se llena con JS -->
                
                <div>
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase block mb-1">Nuevo Precio ($)</label>
                    <input type="number" step="0.01" min="0" name="precio" id="inputPrecio" required class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 focus:outline-none focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-black text-2xl text-brand-dark dark:text-white">
                </div>

                <button type="submit" class="w-full bg-brand-orange hover:bg-brand-gold text-white font-bold py-3 rounded-xl shadow-md transition-all mt-2">
                    💾 Guardar Cambios
                </button>
            </form>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) { themeToggleLightIcon.classList.remove('hidden'); } else { themeToggleDarkIcon.classList.remove('hidden'); }
        function toggleTheme() {
            themeToggleDarkIcon.classList.toggle('hidden'); themeToggleLightIcon.classList.toggle('hidden');
            if (localStorage.theme === 'dark') { document.documentElement.classList.remove('dark'); localStorage.theme = 'light'; } else { document.documentElement.classList.add('dark'); localStorage.theme = 'dark'; }
        }

        // Lógica del Modal de Precios
        function abrirModalEdicion(id, nombre, precioActual) {
            document.getElementById('nombreProductoModal').innerText = nombre;
            document.getElementById('inputPrecio').value = precioActual;
            
            // Ruta dinámica hacia Laravel
            document.getElementById('formEditarPrecio').action = `/modificaciones/precios/${id}`;
            
            document.getElementById('modalEdicion').classList.remove('hidden');
            
            // Auto-focus en el input para escritura rápida
            setTimeout(() => document.getElementById('inputPrecio').focus(), 100);
        }

        function cerrarModalEdicion() {
            document.getElementById('modalEdicion').classList.add('hidden');
        }
    </script>
</body>
</html>