<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Valenzo's PC</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: '#0B0E14',      // Fondo principal
                        card: '#151A23',      // Fondo tarjetas
                        primary: '#00D68F',   // Verde Valenzo
                        primaryDark: '#00b87a',
                        secondary: '#1E293B',
                    },
                    boxShadow: {
                        'neon': '0 0 20px rgba(0, 214, 143, 0.25)',
                        'glow': '0 0 10px rgba(0, 214, 143, 0.5)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0B0E14; color: #e2e8f0; font-family: 'Inter', sans-serif; }
        .glass-nav {
            background: rgba(11, 14, 20, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        /* Animación suave para el dropdown */
        .dropdown-enter {
            animation: slideDown 0.2s ease-out forwards;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>
<body class="antialiased selection:bg-primary selection:text-dark">

    {{-- NAVBAR --}}
    <nav class="glass-nav sticky top-0 z-50 transition-all duration-300">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                {{-- Logo --}}
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 border border-primary/50 flex items-center justify-center group-hover:shadow-neon transition-all duration-300">
                        <span class="font-bold text-primary text-xl">V</span>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="font-bold text-lg text-white tracking-wide">Valenzo's</span>
                        <span class="text-xs text-gray-400 font-medium tracking-widest group-hover:text-primary transition-colors">COMPUTING</span>
                    </div>
                </div>

                {{-- Menú Central (Escritorio) --}}
                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="{{ route('home') }}" class="text-primary relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-primary after:shadow-glow">Inicio</a>
                    
                    @php($isAdmin = Auth::check() && session('api_user_role')==='admin')
                    
                    @if($isAdmin)
                        {{-- Enlaces para ADMIN --}}
                        <a href="{{ route('productos.admin') }}" class="text-gray-400 hover:text-white transition-colors">Admin Productos</a>
                        <a href="{{ route('servicios.admin') }}" class="text-gray-400 hover:text-white transition-colors">Admin Servicios</a>
                        <a href="{{ route('admin.users') }}" class="text-gray-400 hover:text-white transition-colors">Usuarios</a>
                        <a href="{{ route('admin.finanzas') }}" class="text-gray-400 hover:text-white transition-colors">Finanzas</a>
                    @else
                        {{-- Enlaces para CLIENTES / INVITADOS --}}
                        <a href="{{ route('productos.index') }}" class="text-gray-400 hover:text-white transition-colors">Catálogo</a>
                        <a href="{{ route('servicios.index') }}" class="text-gray-400 hover:text-white transition-colors">Servicios</a>
                    @endif
                </div>

                {{-- Zona Usuario (Derecha) --}}
                <div class="hidden md:flex items-center gap-4">
                    @if(!Auth::check())
                        {{-- NO LOGUEADO --}}
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primaryDark text-dark font-bold text-sm shadow-neon transition-all hover:-translate-y-0.5">
                            Registrarse
                        </a>
                    @else
                        {{-- LOGUEADO --}}
                        <div class="relative group">
                            <button id="user-menu-btn" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 transition-colors border border-transparent hover:border-white/10">
                                <div class="text-right hidden lg:block">
                                    <div class="text-sm font-bold text-white">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] font-mono text-primary uppercase tracking-wider bg-primary/10 px-1.5 rounded inline-block">
                                        {{ session('api_user_role') ?? 'Cliente' }}
                                    </div>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 border border-gray-600 flex items-center justify-center text-white font-bold shadow-lg">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            {{-- Dropdown Menú --}}
                            <div class="absolute right-0 mt-2 w-56 bg-card border border-gray-700 rounded-xl shadow-2xl overflow-hidden hidden group-hover:block dropdown-enter pt-1">
                                <div class="px-4 py-3 border-b border-gray-800 lg:hidden">
                                    <p class="text-sm text-white font-bold">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-primary">{{ session('api_user_role') }}</p>
                                </div>
                                
                                <a href="{{ route('password.change') }}" class="block px-4 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-primary transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 11l-4.414 2.207a1 1 0 00-.207.272L6 16l2 2-2.5 1.5L5 21a1 1 0 00.491.868L8 19l2 2 3.5-2 1.5-2.5a1 1 0 00.272-.207l2.207-4.414A6 6 0 0121 9z"></path></svg>
                                    Cambiar Contraseña
                                </a>

                                <div class="border-t border-gray-800">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Botón Móvil --}}
                <div class="md:hidden flex items-center">
                    <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-gray-300 hover:text-white p-2">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Menú Móvil Desplegable --}}
        <div id="mobile-menu" class="hidden md:hidden bg-card border-b border-gray-800">
            <div class="px-4 pt-2 pb-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-primary font-medium bg-primary/10">Inicio</a>
                
                @if($isAdmin)
                    <a href="{{ route('productos.admin') }}" class="block px-3 py-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800">Productos (Admin)</a>
                    <a href="{{ route('servicios.admin') }}" class="block px-3 py-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800">Servicios (Admin)</a>
                    <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800">Usuarios</a>
                @else
                    <a href="{{ route('productos.index') }}" class="block px-3 py-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800">Catálogo</a>
                    <a href="{{ route('servicios.index') }}" class="block px-3 py-2 rounded-md text-gray-300 hover:text-white hover:bg-gray-800">Servicios</a>
                @endif

                <div class="border-t border-gray-800 my-2 pt-2">
                    @if(!Auth::check())
                        <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-300">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 text-primary font-bold">Registrarse</a>
                    @else
                        <div class="px-3 py-2 text-sm text-gray-500 uppercase font-bold">Cuenta: {{ Auth::user()->name }}</div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-3 py-2 text-red-400">Cerrar Sesión</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION MEJORADA --}}
    <section class="relative overflow-hidden bg-dark">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-primary/20 rounded-full blur-[120px] opacity-20 pointer-events-none"></div>
        
        <div class="max-w-[1400px] mx-auto px-4 py-20 md:py-32 flex flex-col md:flex-row items-center gap-16 relative z-10">
            
            {{-- Lado Izquierdo: Texto --}}
            <div class="w-full md:w-1/2 space-y-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-primary/30 bg-primary/5 text-primary text-xs font-bold tracking-wider uppercase">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    Venta y Reparación
                </div>
                
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-[1.1]">
                    Tecnología que <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-emerald-600 drop-shadow-lg">Impulsa tu Mundo</span>
                </h1>
                
                <p class="text-gray-400 text-lg md:text-xl max-w-xl leading-relaxed">
                    Computadoras industriales seminuevas y componentes de alto rendimiento en San Luis Potosí. Calidad premium garantizada.
                </p>

                <div class="flex flex-wrap gap-4 pt-4">
                    @if($isAdmin)
                         <a href="{{ route('productos.admin') }}" class="px-8 py-4 rounded-xl bg-primary hover:bg-primaryDark text-dark font-bold text-lg shadow-neon hover:shadow-glow transition-all transform hover:-translate-y-1">
                            Gestionar Inventario
                        </a>
                    @else
                        <a href="{{ route('productos.index') }}" class="px-8 py-4 rounded-xl bg-primary hover:bg-primaryDark text-dark font-bold text-lg shadow-neon hover:shadow-glow transition-all transform hover:-translate-y-1">
                            Ver Catálogo
                        </a>
                        <a href="{{ route('servicios.index') }}" class="px-8 py-4 rounded-xl border border-gray-700 hover:border-primary text-gray-300 hover:text-primary font-bold text-lg hover:bg-primary/5 transition-all">
                            Servicios
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-6 pt-6 text-sm text-gray-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Garantía en equipos
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Atención en SLP
                    </div>
                </div>
            </div>

            {{-- Lado Derecho: Visual --}}
            <div class="w-full md:w-1/2 flex justify-center relative">
                <div class="absolute inset-0 bg-gradient-to-r from-primary to-blue-500 rounded-[3rem] blur-3xl opacity-20 -z-10 transform rotate-6 scale-90"></div>
                
                <div class="bg-card/80 backdrop-blur-xl border border-white/10 p-2 rounded-[2.5rem] shadow-2xl w-full max-w-md transform rotate-[-3deg] hover:rotate-0 transition-transform duration-500">
                    <div class="bg-dark rounded-[2rem] overflow-hidden relative min-h-[400px] flex items-center justify-center border border-gray-800 group">
                        <div class="text-center p-8">
                            <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-t from-gray-800 to-gray-700 flex items-center justify-center border-2 border-primary shadow-[0_0_30px_rgba(0,214,143,0.3)]">
                                <span class="text-5xl">💻</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Setup Gamer</h3>
                            <p class="text-gray-400 text-sm">Encuentra la PC de tus sueños con la configuración que necesitas.</p>
                            
                            <div class="mt-8 flex justify-center gap-2 opacity-50 group-hover:opacity-100 transition-opacity">
                                <span class="w-2 h-2 rounded-full bg-primary"></span>
                                <span class="w-2 h-2 rounded-full bg-gray-600"></span>
                                <span class="w-2 h-2 rounded-full bg-gray-600"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

</body>
</html>