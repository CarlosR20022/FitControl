<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $producto ? 'Editar Producto' : 'Agregar Producto' }} - FitControl</title>
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
                <button onclick="toggleMenuConsulta(event)" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-dark dark:text-gray-300 hover:text-brand-orange dark:hover:text-brand-orange hover:bg-orange-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                    🔍 Consulta ▾
                </button>
                <a href="{{ route('modificaciones.index') }}" class="whitespace-nowrap px-3 py-2 rounded-md text-sm font-bold text-brand-orange dark:text-brand-orange bg-orange-50 dark:bg-gray-700 transition-colors">✏️ Modificaciones</a>
                
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
                </div>
            </div>

        </div>
    </header>

    <!-- MENÚ DESPLEGABLE DE CONSULTA (posición fija para que no lo recorte el menú) -->
    <div id="menuConsulta" class="hidden fixed z-[100] w-56 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 py-1">
        <a href="{{ route('clientes.consulta') }}" class="block px-4 py-2 text-sm font-bold text-brand-dark dark:text-gray-200 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brand-orange">👤 Consulta de cliente</a>
        <a href="{{ route('consulta.stock') }}" class="block px-4 py-2 text-sm font-bold text-brand-dark dark:text-gray-200 hover:bg-orange-50 dark:hover:bg-gray-700 hover:text-brand-orange">📦 Consulta de stock</a>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="flex-1 p-6 flex items-center justify-center max-w-[1600px] mx-auto w-full">
        <div class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors">
            <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700">
                <h2 class="text-2xl font-black text-brand-dark dark:text-white">{{ $producto ? 'Editar Producto' : 'Agregar Producto' }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">{{ $producto ? 'Modifica los datos del producto.' : 'Llena los datos del producto nuevo.' }}</p>
            </div>

            <form action="{{ $producto ? route('productos.update', $producto->id) : route('productos.store') }}" method="POST" class="p-8 flex flex-col gap-5">
                @csrf
                @if($producto)
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

                <div>
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase block mb-1">Nombre del producto</label>
                    <input type="text" name="nombre" id="inputNombre" value="{{ old('nombre', $producto->nombre ?? '') }}" oninput="actualizarCampoStock()" required placeholder="Ej: Bote de Proteína 1kg" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-bold text-brand-dark dark:text-white">
                </div>

                <div>
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase block mb-1">Categoría</label>
                    <select name="categoria" id="inputCategoria" onchange="actualizarCampoStock(true)" required class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-bold text-brand-dark dark:text-white">
                        <option value="" disabled {{ old('categoria', $producto->categoria ?? '') == '' ? 'selected' : '' }}>Selecciona una categoría</option>
                        <option value="membresia" {{ old('categoria', $producto->categoria ?? '') == 'membresia' ? 'selected' : '' }}>Membresía</option>
                        <option value="visita" {{ old('categoria', $producto->categoria ?? '') == 'visita' ? 'selected' : '' }}>Visita</option>
                        <option value="suplemento" {{ old('categoria', $producto->categoria ?? '') == 'suplemento' ? 'selected' : '' }}>Suplemento</option>
                        <option value="bebida" {{ old('categoria', $producto->categoria ?? '') == 'bebida' ? 'selected' : '' }}>Bebida</option>
                        <option value="snack" {{ old('categoria', $producto->categoria ?? '') == 'snack' ? 'selected' : '' }}>Snack</option>
                    </select>
                </div>

                <div id="campoDuracion">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase block mb-1">Duración del plan</label>
                    <select name="duracion" id="inputDuracion" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-bold text-brand-dark dark:text-white">
                        <option value="dia" {{ old('duracion', $producto ? $producto->duracionEfectiva() : 'mes') == 'dia' ? 'selected' : '' }}>Día (vence el mismo día)</option>
                        <option value="semana" {{ old('duracion', $producto ? $producto->duracionEfectiva() : 'mes') == 'semana' ? 'selected' : '' }}>Semana (7 días)</option>
                        <option value="mes" {{ old('duracion', $producto ? $producto->duracionEfectiva() : 'mes') == 'mes' ? 'selected' : '' }}>Mes (1 mes)</option>
                    </select>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Solo para membresías y visitas: cuánto dura el plan.</p>
                </div>

                <div>
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase block mb-1">Precio ($)</label>
                    <input type="number" step="0.01" min="0" name="precio" value="{{ old('precio', $producto->precio ?? '') }}" required placeholder="0.00" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-bold text-brand-dark dark:text-white">
                </div>

                <div id="campoStock">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase block mb-1">Stock (unidades)</label>
                    <input type="number" step="1" min="0" name="stock" id="inputStock" value="{{ old('stock', $producto->stock ?? '') }}" placeholder="Ej: 10" class="w-full bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-bold text-brand-dark dark:text-white">
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Solo para bebidas, snacks y suplementos en bote.</p>
                </div>

                <div class="flex gap-3 mt-2">
                    <a href="{{ route('productos.index') }}" class="w-1/2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold py-3 rounded-xl transition-colors text-center">Cancelar</a>
                    <button type="submit" class="w-1/2 bg-brand-orange hover:bg-brand-gold text-white font-bold py-3 rounded-xl shadow-md transition-all">💾 Guardar Producto</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Script del Modo Oscuro, del menú de Consulta y del campo de stock -->
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

        // Menú desplegable de Consulta (posición fija para que no lo recorte el menú)
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

        // El campo de stock solo aparece cuando el producto lo lleva:
        // bebidas, snacks y suplementos en bote (no membresías, visitas ni scoops).
        // La duración solo aplica a los planes (membresías y visitas).
        function actualizarCampoStock(ajustarDuracion) {
            const categoria = document.getElementById('inputCategoria').value;
            const nombre = document.getElementById('inputNombre').value.toLowerCase();
            const campo = document.getElementById('campoStock');
            const sinStock = categoria === 'membresia' || categoria === 'visita' || nombre.includes('scoop');
            if (sinStock) {
                campo.classList.add('hidden');
                document.getElementById('inputStock').value = '';
            } else {
                campo.classList.remove('hidden');
            }
            const campoDuracion = document.getElementById('campoDuracion');
            const esPlan = categoria === 'membresia' || categoria === 'visita';
            if (esPlan) {
                campoDuracion.classList.remove('hidden');
            } else {
                campoDuracion.classList.add('hidden');
            }
            // Al cambiar de categoría, la duración se ajusta a lo habitual de ese plan
            if (ajustarDuracion) {
                const inputDuracion = document.getElementById('inputDuracion');
                if (categoria === 'visita') {
                    inputDuracion.value = 'dia';
                } else if (categoria === 'membresia') {
                    inputDuracion.value = 'mes';
                }
            }
        }

        actualizarCampoStock(false);
    </script>
</body>
</html>
