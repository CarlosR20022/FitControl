<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - FitControl</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { brand: { orange: '#FFA000', gold: '#CC9029', lightBrown: '#99773D', brown: '#66573D', dark: '#332F29' } }
                }
            }
        }
    </script>
</head>
<body class="min-h-screen flex items-center justify-center p-4 font-sans" style="background: linear-gradient(180deg, #7c3aed 0%, #5b21b6 40%, #2e1065 100%);">

    <div class="w-full max-w-sm">
        <!-- TARJETA DE LOGIN -->
        <div id="tarjetaLogin" class="rounded-2xl overflow-hidden shadow-2xl">

            <!-- ILUSTRACIÓN: atardecer, globos y montañas -->
            <svg viewBox="0 0 400 190" class="w-full h-44 block" preserveAspectRatio="xMidYMax slice" role="img" aria-label="Montañas al atardecer con globos aerostáticos">
                <defs>
                    <linearGradient id="cielo" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#2b1055"/>
                        <stop offset="55%" stop-color="#7b5ea7"/>
                        <stop offset="100%" stop-color="#c084fc"/>
                    </linearGradient>
                    <linearGradient id="globoAmarillo" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#fde68a"/>
                        <stop offset="100%" stop-color="#f59e0b"/>
                    </linearGradient>
                    <linearGradient id="globoMorado" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f0abfc"/>
                        <stop offset="100%" stop-color="#a855f7"/>
                    </linearGradient>
                </defs>

                <rect width="400" height="190" fill="url(#cielo)"/>

                <!-- Estrellas -->
                <g fill="#ffffff" opacity="0.8">
                    <circle cx="28" cy="22" r="1.5"/><circle cx="88" cy="14" r="1"/><circle cx="148" cy="28" r="1.5"/>
                    <circle cx="208" cy="16" r="1"/><circle cx="268" cy="26" r="1.5"/><circle cx="330" cy="12" r="1"/>
                    <circle cx="368" cy="32" r="1.2"/><circle cx="58" cy="52" r="1"/><circle cx="238" cy="48" r="1"/>
                </g>

                <!-- Luna -->
                <circle cx="332" cy="38" r="15" fill="#fef3c7" opacity="0.9"/>
                <circle cx="327" cy="34" r="3" fill="#fde68a" opacity="0.6"/>
                <circle cx="338" cy="42" r="2" fill="#fde68a" opacity="0.6"/>

                <!-- Nubes -->
                <g fill="#e9d5ff" opacity="0.5">
                    <ellipse cx="88" cy="68" rx="42" ry="11"/>
                    <ellipse cx="118" cy="60" rx="28" ry="9"/>
                    <ellipse cx="298" cy="82" rx="46" ry="12"/>
                    <ellipse cx="328" cy="74" rx="28" ry="9"/>
                </g>

                <!-- Globo amarillo (grande) -->
                <g>
                    <path d="M118 26 C 98 26, 90 44, 98 58 C 104 68, 112 72, 118 72 C 124 72, 132 68, 138 58 C 146 44, 138 26, 118 26 Z" fill="url(#globoAmarillo)"/>
                    <path d="M118 26 C 110 40, 110 58, 118 72 C 126 58, 126 40, 118 26 Z" fill="#ffffff" opacity="0.35"/>
                    <line x1="110" y1="72" x2="108" y2="82" stroke="#78350f" stroke-width="1.5"/>
                    <line x1="126" y1="72" x2="128" y2="82" stroke="#78350f" stroke-width="1.5"/>
                    <rect x="106" y="82" width="24" height="10" rx="2" fill="#78350f"/>
                </g>

                <!-- Globo morado (chico, al fondo) -->
                <g opacity="0.9">
                    <path d="M278 52 C 266 52, 260 63, 265 73 C 269 80, 274 83, 278 83 C 282 83, 287 80, 291 73 C 296 63, 290 52, 278 52 Z" fill="url(#globoMorado)"/>
                    <path d="M278 52 C 273 62, 273 74, 278 83 C 283 74, 283 62, 278 52 Z" fill="#ffffff" opacity="0.3"/>
                    <line x1="272" y1="83" x2="271" y2="90" stroke="#581c87" stroke-width="1.2"/>
                    <line x1="284" y1="83" x2="285" y2="90" stroke="#581c87" stroke-width="1.2"/>
                    <rect x="269" y="90" width="18" height="8" rx="2" fill="#581c87"/>
                </g>

                <!-- Montañas del fondo -->
                <polygon points="0,190 0,132 58,88 118,142 178,94 238,148 298,104 358,142 400,112 400,190" fill="#5b21b6" opacity="0.65"/>

                <!-- Montañas del frente -->
                <polygon points="0,190 0,162 48,122 108,168 168,128 228,172 288,132 348,168 400,142 400,190" fill="#3b0764"/>

                <!-- Nieve en las cimas -->
                <polygon points="48,122 60,134 36,134" fill="#e9d5ff" opacity="0.85"/>
                <polygon points="168,128 180,140 156,140" fill="#e9d5ff" opacity="0.85"/>
                <polygon points="288,132 300,144 276,144" fill="#e9d5ff" opacity="0.85"/>
            </svg>

            <!-- FORMULARIO -->
            <div class="px-8 py-8" style="background-color: #232d3a;">

                <!-- Logotipo -->
                <div class="flex flex-col items-center mb-6">
                    <div class="bg-brand-orange text-white p-2.5 rounded-full shadow-lg mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <p class="font-black text-xl tracking-widest italic text-white">FIT<span class="text-brand-orange">CONTROL</span></p>
                    <p class="text-gray-400 text-xs font-bold tracking-[0.3em] mt-2">INICIAR SESIÓN</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-500/20 border border-red-500/40 text-red-300 text-sm font-bold px-4 py-2.5 rounded-xl text-center mb-5">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form id="formLogin" action="{{ route('login') }}" method="POST" class="flex flex-col gap-4">
                    @csrf

                    <!-- ID de usuario -->
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </span>
                        <input type="text" name="codigo" value="{{ old('codigo') }}" required autofocus placeholder="ID de usuario" autocomplete="username"
                               class="w-full bg-white/10 border border-white/10 rounded-full pl-11 pr-4 py-2.5 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                    </div>

                    <!-- Contraseña -->
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </span>
                        <input type="password" name="password" required placeholder="Contraseña" autocomplete="current-password"
                               class="w-full bg-white/10 border border-white/10 rounded-full pl-11 pr-4 py-2.5 text-sm text-white placeholder-gray-400 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange">
                    </div>

                    <!-- Mantener sesión iniciada -->
                    <label class="flex items-center gap-2 text-xs text-gray-400 font-medium cursor-pointer select-none">
                        <input type="checkbox" name="remember" value="1" class="accent-orange-500 w-4 h-4">
                        Mantener sesión iniciada
                    </label>

                    <button id="btnEntrar" type="submit" class="w-full text-white font-bold py-2.5 rounded-full shadow-lg transition-all hover:brightness-110 active:scale-[0.98]" style="background: linear-gradient(90deg, #FFA000, #FF6D00);">
                        Entrar
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-white/50 text-xs mt-4">FitControl · Sistema de control de gimnasio</p>
    </div>

    <script>
        // Transición hacia el Dashboard: la tarjeta se desvanece y luego se envía el login
        document.getElementById('formLogin').addEventListener('submit', function (e) {
            e.preventDefault();
            var tarjeta = document.getElementById('tarjetaLogin');
            var boton = document.getElementById('btnEntrar');
            tarjeta.style.transition = 'opacity .35s ease, transform .35s ease';
            tarjeta.style.opacity = '0';
            tarjeta.style.transform = 'translateY(12px) scale(.97)';
            boton.textContent = 'Entrando…';
            boton.disabled = true;
            var form = this;
            setTimeout(function () { form.submit(); }, 350);
        });
    </script>

</body>
</html>
