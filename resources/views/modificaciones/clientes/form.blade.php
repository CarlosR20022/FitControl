<!DOCTYPE html>
<html lang="es" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - FitControl</title>
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
                    <h2 class="text-2xl font-black text-brand-dark dark:text-white tracking-tight">Editar Cliente</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-1">Corrige los datos de {{ $cliente->nombre_completo }}.</p>
                </div>
                <div class="bg-orange-100 dark:bg-brand-orange/20 text-brand-orange p-3 rounded-full">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
            </div>

            <form action="{{ route('modificaciones.clientes.actualizar', $cliente->id) }}" method="POST" class="p-8 flex flex-col gap-5">
                @csrf
                @method('PUT')

                @if($errors->any())
                    <div class="bg-red-100 dark:bg-red-500/20 border border-red-200 dark:border-red-500/30 text-red-700 dark:text-red-400 font-bold px-4 py-3 rounded-xl text-sm">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-orange-50 dark:bg-brand-orange/10 border border-orange-200 dark:border-brand-orange/30 rounded-xl p-4 text-sm text-gray-600 dark:text-gray-300">
                    Al cambiar el plan también se corrige el último pago (precio y fecha de vencimiento), así que los ingresos del día se ajustan solos. Su vencimiento actual es el {{ $cliente->fecha_vencimiento ? \Carbon\Carbon::parse($cliente->fecha_vencimiento)->format('d/m/Y') : '—' }}.
                </div>

                <!-- Nombre Completo -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Nombre Completo <span class="text-red-500">*</span></label>
                    <input type="text" name="nombre_completo" value="{{ old('nombre_completo', $cliente->nombre_completo) }}" required
                           class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange dark:focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-medium text-brand-dark dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <!-- Teléfono -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Teléfono (Opcional)</label>
                    <input type="tel" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                           class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange dark:focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-medium text-brand-dark dark:text-white placeholder-gray-400 dark:placeholder-gray-500">
                </div>

                <!-- Tipo de Pago / Plan -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Tipo de Pago <span class="text-red-500">*</span></label>
                    <select name="producto_id" id="planSelect" required onchange="actualizarPrecio()"
                            class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange dark:focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-medium text-brand-dark dark:text-white cursor-pointer">
                        @foreach($planes as $plan)
                            <option value="{{ $plan->id }}" data-precio="{{ $plan->precio }}" {{ (int) old('producto_id', $ultimoPago ? $ultimoPago->producto_id : ($planes->first() ? $planes->first()->id : 0)) === $plan->id ? 'selected' : '' }} class="dark:bg-gray-800">
                                {{ $plan->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha de Ingreso -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase">Fecha de Ingreso <span class="text-red-500">*</span></label>
                    <input type="date" name="fecha_registro" required value="{{ old('fecha_registro', $cliente->fecha_registro) }}"
                           class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:border-brand-orange dark:focus:border-brand-orange focus:ring-2 focus:ring-orange-100 dark:focus:ring-brand-orange/30 transition-all font-medium text-brand-dark dark:text-white cursor-pointer" style="color-scheme: dark;">
                </div>

                <!-- Total corregido -->
                <div class="mt-2 bg-orange-50 dark:bg-brand-orange/10 border border-orange-200 dark:border-brand-orange/30 rounded-lg p-4 flex justify-between items-center transition-colors">
                    <span class="font-bold text-gray-700 dark:text-gray-300 uppercase text-sm">Total corregido:</span>
                    <span class="text-3xl font-black text-brand-orange" id="precioDisplay">$0.00</span>
                </div>

                <!-- Botones -->
                <div class="flex gap-3 mt-4">
                    <a href="{{ route('modificaciones.clientes') }}" class="w-1/3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold py-3 rounded-lg text-center transition-colors border border-gray-300 dark:border-gray-600 shadow-sm">Cancelar</a>
                    <button type="submit" class="w-2/3 bg-brand-orange hover:bg-brand-gold text-white font-bold py-3 rounded-lg shadow-md hover:shadow-lg transition-all active:scale-[0.98]">💾 Guardar cambios</button>
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
        function actualizarPrecio() {
            const select = document.getElementById('planSelect');
            const precio = parseFloat(select.options[select.selectedIndex].dataset.precio || 0);
            document.getElementById('precioDisplay').innerText = '$' + precio.toFixed(2);
        }
        actualizarPrecio();
    </script>
</body>
</html>
