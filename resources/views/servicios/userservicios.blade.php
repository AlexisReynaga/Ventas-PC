<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios | Valenzo's PC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: '#0B0E14',
                        card: '#151A23',
                        primary: '#00D68F',
                        primaryDark: '#00b87a',
                    },
                    boxShadow: {
                        'neon': '0 0 15px rgba(0, 214, 143, 0.2)',
                        'card-hover': '0 10px 30px -10px rgba(0, 214, 143, 0.15)',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0B0E14; color: #e2e8f0; font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(21, 26, 35, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .filter-input {
            background: #0B0E14;
            border: 1px solid #334155;
            color: white;
        }
        .filter-input:focus {
            border-color: #00D68F;
            outline: none;
        }
        @keyframes bounce-sm {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        .animate-cart-add {
            animation: bounce-sm 0.3s ease-in-out;
        }
        /* Animación dropdown */
        .dropdown-enter {
            animation: slideDown 0.2s ease-out forwards;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col">

    <!-- NAVBAR (Consistente con Productos) -->
    <nav class="border-b border-white/5 bg-dark/90 backdrop-blur sticky top-0 z-50">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center gap-3">
                    <div class="w-10 h-6 border-2 border-primary rounded-sm flex items-center justify-center">
                        <div class="w-full h-0.5 bg-primary"></div>
                    </div>
                    <span class="font-bold text-xl tracking-wider text-white">Valenzo's <span class="font-light text-gray-400">PC</span></span>
                </div>

                <div class="hidden md:flex gap-8 text-sm font-medium text-gray-300">
                    <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Inicio</a>
                    <a href="{{ route('productos.index') }}" class="hover:text-primary transition-colors">Productos</a>
                    <a href="{{ route('servicios.index') }}" class="text-primary relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-primary">Servicios</a>
                    @if(Auth::check() && session('api_user_role') === 'admin')
                        <a href="{{ route('admin.users') }}" class="hover:text-primary transition-colors">Usuarios</a>
                    @endif
                </div>

                <!-- Usuario / Login -->
                <div class="flex items-center gap-6">
                    <!-- Icono "Cita/Carrito" -->
                    <button class="relative p-2 text-gray-400 hover:text-white transition group" title="Mis Citas">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span id="cart-badge" class="absolute top-1 right-0 w-4 h-4 bg-primary text-dark text-[10px] font-bold rounded-full flex items-center justify-center opacity-0 transition-opacity">0</span>
                    </button>
                    
                    @if(!Auth::check())
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-400 hover:text-white">Login</a>
                            <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-primary hover:bg-primaryDark text-dark text-xs font-bold transition-all">Registro</a>
                        </div>
                    @else
                        <div class="relative group">
                            <button class="flex items-center gap-3 focus:outline-none">
                                <div class="text-right hidden lg:block">
                                    <div class="text-sm font-bold text-white leading-none">{{ Auth::user()->name }}</div>
                                    <div class="text-[10px] font-mono text-primary uppercase tracking-wider bg-primary/10 px-1.5 rounded inline-block mt-1">
                                        {{ session('api_user_role') ?? 'Cliente' }}
                                    </div>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 border border-gray-600 flex items-center justify-center text-white font-bold shadow-lg hover:border-primary transition-colors">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>
                            <!-- Dropdown -->
                            <div class="absolute right-0 mt-2 w-48 bg-card border border-gray-700 rounded-xl shadow-2xl overflow-hidden hidden group-hover:block dropdown-enter pt-1">
                                @if(session('api_user_role') === 'admin')
                                    <a href="{{ route('servicios.admin') }}" class="block px-4 py-3 text-sm text-primary hover:bg-gray-800 transition-colors font-bold border-b border-gray-800">
                                        Admin Servicios
                                    </a>
                                @endif
                                <a href="{{ route('password.change') }}" class="block px-4 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-white transition-colors">
                                    Cambiar Contraseña
                                </a>
                                <div class="border-t border-gray-800">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors">
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <div class="bg-gradient-to-b from-card to-dark py-12 border-b border-white/5">
        <div class="max-w-[1400px] mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Soluciones y <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-emerald-600">Soporte Técnico</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto">Reparación profesional, mantenimiento preventivo y diagnósticos especializados para tu equipo.</p>
        </div>
    </div>

    <!-- Contenido -->
    <div class="flex-grow max-w-[1400px] mx-auto px-4 py-8 w-full">
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filtros -->
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="lg:sticky lg:top-24 space-y-6">
                    <button onclick="document.getElementById('mobile-filters').classList.toggle('hidden')" class="lg:hidden w-full bg-card border border-gray-700 p-3 rounded-lg text-left flex justify-between items-center text-white font-semibold">
                        <span>Filtros</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div id="mobile-filters" class="hidden lg:block bg-card/50 border border-white/5 rounded-xl p-5 backdrop-blur-sm">
                        <form method="GET" action="{{ route('servicios.index') }}">
                            <div class="mb-6">
                                <label class="text-xs font-bold text-primary uppercase tracking-wider mb-2 block">Buscar</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Mantenimiento..." class="filter-input w-full p-2.5 rounded-lg text-sm pl-9">
                                    <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="text-xs font-bold text-primary uppercase tracking-wider mb-2 block">Tipo</label>
                                <input type="text" name="type" value="{{ request('type') }}" placeholder="Ej: Hardware" class="filter-input w-full p-2.5 rounded-lg text-sm">
                            </div>

                            <div class="mb-6">
                                <label class="text-xs font-bold text-primary uppercase tracking-wider mb-2 block">Ordenar por</label>
                                <select name="sort" class="filter-input w-full p-2.5 rounded-lg text-sm mb-2">
                                    <option value="">Relevancia</option>
                                    <option value="price" @selected(request('sort') == 'price')>Precio</option>
                                    <option value="estimated_time" @selected(request('sort') == 'estimated_time')>Duración</option>
                                    <option value="name" @selected(request('sort') == 'name')>Nombre</option>
                                </select>
                                <select name="direction" class="filter-input w-full p-2.5 rounded-lg text-sm">
                                    <option value="asc" @selected(request('direction') == 'asc')>Ascendente</option>
                                    <option value="desc" @selected(request('direction') == 'desc')>Descendente</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-2 pt-2 border-t border-gray-800">
                                <button type="submit" class="w-full bg-primary hover:bg-primaryDark text-dark font-bold py-2.5 rounded-lg transition-colors shadow-neon">Aplicar</button>
                                @if(request()->anyFilled(['search', 'type', 'sort']))
                                    <a href="{{ route('servicios.index') }}" class="w-full text-center text-sm text-gray-500 hover:text-white py-2">Limpiar</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Grid de Servicios -->
            <div class="flex-grow">
                
                @php($items = $services['items'] ?? [])
                @php($current = $services['current_page'] ?? 1)
                @php($last = $services['last_page'] ?? 1)

                @if(isset($services['error']))
                     <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 mb-6">{{ $services['error'] }}</div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-400 text-sm">Mostrando resultados página {{ $current }}</span>
                </div>

                @if(count($items) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($items as $s)
                    <div class="group bg-card rounded-2xl border border-gray-800 overflow-hidden hover:border-primary/50 transition-all duration-300 hover:shadow-card-hover flex flex-col h-full relative">
                        
                        <!-- Top Decoration -->
                        <div class="h-2 bg-gradient-to-r from-gray-800 to-gray-700 group-hover:from-primary group-hover:to-emerald-500 transition-all"></div>

                        <div class="p-6 flex flex-col flex-grow">
                            <!-- Header Card -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 rounded-xl bg-gray-800 border border-gray-700 flex items-center justify-center text-primary group-hover:shadow-neon transition-all">
                                    <!-- Icono Genérico de Servicio -->
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                @if($s['type'] ?? false)
                                    <span class="text-[10px] font-bold uppercase tracking-wider bg-gray-800 text-gray-400 px-2 py-1 rounded border border-gray-700">{{ $s['type'] }}</span>
                                @endif
                            </div>

                            <h3 class="text-xl font-bold text-white mb-2 leading-tight group-hover:text-primary transition-colors">
                                {{ $s['name'] ?? 'Servicio' }}
                            </h3>
                            
                            <p class="text-gray-400 text-sm mb-6 line-clamp-3 flex-grow">
                                {{ $s['description'] ?? 'Contáctanos para más detalles sobre este servicio profesional.' }}
                            </p>

                            <!-- Detalles Técnicos -->
                            <div class="flex items-center gap-4 text-xs text-gray-500 mb-6 border-t border-gray-800 pt-4">
                                @if(!empty($s['estimated_time']))
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $s['estimated_time'] }} Hrs est.</span>
                                </div>
                                @endif
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Garantía</span>
                                </div>
                            </div>

                            <!-- Footer Card -->
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">Costo aproximado</span>
                                    <span class="text-2xl font-bold text-white group-hover:text-primary transition-colors">
                                        @if(isset($s['price']) && $s['price'] > 0)
                                            ${{ number_format($s['price'], 2) }}
                                        @else
                                            <span class="text-lg">A cotizar</span>
                                        @endif
                                    </span>
                                </div>
                                <button onclick="addToInquiry('{{ $s['name'] }}')" class="bg-gray-800 hover:bg-white text-white hover:text-dark px-4 py-2 rounded-lg font-bold text-sm transition-all shadow-lg group-hover:bg-primary group-hover:text-dark">
                                    Agendar
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="mt-12 flex justify-center">
                    <div class="flex gap-2">
                        @if($current > 1)
                            <a href="{{ route('servicios.index', array_merge(request()->except('page'), ['page'=>$current-1])) }}" class="px-4 py-2 rounded-lg bg-card border border-gray-700 text-gray-300 hover:border-primary hover:text-primary transition">Anterior</a>
                        @endif
                        <span class="px-4 py-2 text-gray-500 font-mono">Pág {{ $current }}</span>
                        @if($current < $last)
                            <a href="{{ route('servicios.index', array_merge(request()->except('page'), ['page'=>$current+1])) }}" class="px-4 py-2 rounded-lg bg-card border border-gray-700 text-gray-300 hover:border-primary hover:text-primary transition">Siguiente</a>
                        @endif
                    </div>
                </div>

                @else
                <div class="text-center py-20 bg-card/30 rounded-2xl border border-dashed border-gray-800">
                    <svg class="w-16 h-16 text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <h3 class="text-xl font-bold text-white mb-2">No hay servicios disponibles</h3>
                    <p class="text-gray-500">Intenta con otros filtros.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark border-t border-gray-800 mt-12 py-10">
        <div class="max-w-[1400px] mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold text-white mb-2">Valenzo's <span class="text-primary">PC</span></h2>
            <p class="text-gray-500 text-sm mb-6">Tu experto en hardware en San Luis Potosí.</p>
            <p class="mt-8 text-xs text-gray-700">&copy; 2024 Valenzo's PC. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Toast Notification -->
    <div id="toast" class="fixed bottom-5 right-5 bg-card border border-primary/50 text-white px-6 py-4 rounded-lg shadow-neon transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-3">
        <div class="bg-primary/20 p-2 rounded-full text-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h4 class="font-bold text-sm">¡Servicio Agendado!</h4>
            <p id="toast-message" class="text-xs text-gray-400">Te contactaremos pronto.</p>
        </div>
    </div>

    <script>
        let inquiryCount = 0;

        function addToInquiry(serviceName) {
            inquiryCount++;
            const badge = document.getElementById('cart-badge');
            badge.innerText = inquiryCount;
            badge.classList.remove('opacity-0');
            badge.classList.add('animate-cart-add');
            setTimeout(() => badge.classList.remove('animate-cart-add'), 300);

            const toast = document.getElementById('toast');
            document.getElementById('toast-message').innerText = `Has solicitado: ${serviceName}`;
            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }
    </script>
</body>
</html>