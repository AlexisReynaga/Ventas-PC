{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Valenzo's PC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
</head>
<body class="bg-slate-950 text-slate-100 antialiased">

    {{-- NAVBAR --}}
    <header class="bg-slate-950/95 border-b border-slate-800 sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">
            {{-- Logo + nombre --}}
            <div class="flex items-center gap-2">
                <div class="w-8 h-5 rounded-sm border border-emerald-400 flex items-center justify-center">
                    <span class="w-4 h-0.5 bg-emerald-400"></span>
                </div>
                <span class="font-semibold tracking-tight text-slate-50">
                    Valenzo's PC
                </span>
            </div>

            {{-- Menú escritorio --}}
            <div class="hidden md:flex items-center gap-8 text-sm">
                <a href="#inicio" class="hover:text-emerald-400 transition-colors">Inicio</a>
                <a href="#productos" class="hover:text-emerald-400 transition-colors">Productos</a>
                <a href="#servicios" class="hover:text-emerald-400 transition-colors">Servicios</a>
                <a href="#nosotros" class="hover:text-emerald-400 transition-colors">Sobre Nosotros</a>
                <a href="#contacto" class="hover:text-emerald-400 transition-colors">Contáctanos</a>
            </div>

            {{-- Botones --}}
            <div class="hidden md:flex items-center gap-3">
                @if(!Auth::check())
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 rounded-full border border-emerald-400 text-sm font-medium hover:bg-emerald-400 hover:text-slate-950 transition">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-2 rounded-full bg-fuchsia-500 text-sm font-semibold text-slate-950 hover:bg-fuchsia-400 shadow-lg shadow-fuchsia-500/40 transition">
                        Registrarse
                    </a>
                @else
                    <details class="relative group">
                        <summary class="list-none cursor-pointer px-4 py-2 rounded-full border border-emerald-400 text-sm font-medium hover:bg-emerald-400 hover:text-slate-950 transition flex items-center gap-2">
                            <span>{{ Auth::user()->name }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-800 text-emerald-400 border border-slate-700">{{ session('api_user_role') }}</span>
                        </summary>
                        <div class="absolute right-0 mt-2 w-48 bg-slate-900 border border-slate-700 rounded-lg shadow-lg p-2 flex flex-col text-sm">
                            <a href="{{ route('password.change') }}" class="px-3 py-2 rounded hover:bg-slate-800">Cambiar contraseña</a>
                            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-slate-800">Cerrar sesión</button>
                            </form>
                        </div>
                    </details>
                @endif
            </div>

            {{-- Hamburguesa móvil (solo maquetada, sin JS aún) --}}
            <button class="md:hidden inline-flex items-center justify-center p-2 rounded-md border border-slate-700">
                <span class="sr-only">Abrir menú</span>
                <div class="space-y-1">
                    <span class="block w-5 h-0.5 bg-slate-100"></span>
                    <span class="block w-5 h-0.5 bg-slate-100"></span>
                    <span class="block w-5 h-0.5 bg-slate-100"></span>
                </div>
            </button>
        </nav>
    </header>

    {{-- HERO --}}
    <section id="inicio"
             class="bg-gradient-to-r from-indigo-950 via-slate-950 to-slate-900">
        <div class="max-w-7xl mx-auto px-4 py-16 md:py-24 flex flex-col md:flex-row items-center gap-12">
            {{-- Texto izquierda --}}
            <div class="w-full md:w-1/2 space-y-6">
                <p class="text-sm uppercase tracking-[0.25em] text-emerald-400">Venta y reparación</p>
                <h1 class="text-3xl md:text-5xl font-extrabold leading-tight">
                    Venta y Reparación de
                    <span class="text-emerald-400">Computadoras</span>
                </h1>
                <p class="text-slate-300 text-base md:text-lg max-w-xl">
                    Computadoras industriales seminuevas en San Luis Potosí, con las mejores
                    marcas, excelente rendimiento y el mejor precio para tu negocio o uso personal.
                </p>

                <div class="flex flex-wrap gap-4 pt-2">
                    <a href="#productos"
                       class="px-6 py-3 rounded-full bg-emerald-500 text-slate-950 font-semibold text-sm md:text-base hover:bg-emerald-400 shadow-lg shadow-emerald-500/40 transition">
                        Ver Productos
                    </a>
                    <a href="#contacto"
                       class="px-6 py-3 rounded-full border border-slate-500 text-sm md:text-base font-medium hover:border-emerald-400 hover:text-emerald-400 transition">
                        Solicitar Cotización
                    </a>
                    @if(Auth::check())
                        <a href="{{ route('external.products.index') }}" class="px-6 py-3 rounded-full border border-emerald-500 text-sm md:text-base font-medium hover:bg-emerald-500 hover:text-slate-950 transition">
                            Productos API
                        </a>
                    @endif
                </div>

                <div class="pt-4 text-xs md:text-sm text-slate-400">
                    Atención en SLP y alrededores · Soporte técnico especializado · Garantía en equipos
                </div>
            </div>

            {{-- Lado derecho: logo o imagen --}}
            <div class="w-full md:w-1/2 flex justify-center">
                <div
                    class="bg-slate-900/60 border border-slate-700 rounded-3xl p-8 md:p-10 shadow-[0_0_80px_rgba(16,185,129,0.35)]
                           flex items-center justify-center">
                    {{-- Aquí puedes poner tu <img> real --}}
                    <div class="text-center">
                        <div class="w-40 h-40 md:w-56 md:h-56 rounded-2xl border border-emerald-500/60
                                    flex items-center justify-center mx-auto mb-4
                                    bg-gradient-to-br from-slate-800 to-slate-900">
                            <span class="text-4xl md:text-5xl font-black tracking-tight">
                                PC
                            </span>
                        </div>
                        <p class="text-sm uppercase tracking-[0.3em] text-slate-400">
                            Valenzo's PC
                        </p>
                        <p class="text-xs text-slate-500">
                            Venta de computadoras · Service Center
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Aquí ya podrías seguir con tus secciones de Productos, Servicios, etc. --}}
</body>
</html>
