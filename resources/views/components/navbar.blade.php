<nav class="sticky top-0 z-50 transition-all duration-300 bg-[#0B0E14]/90 backdrop-blur-md border-b border-white/5">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            {{-- Logo --}}
            <div class="flex items-center gap-3 group cursor-pointer">
                <div class="w-10 h-10 rounded-lg bg-emerald-500/10 border border-emerald-500/50 flex items-center justify-center group-hover:shadow-[0_0_15px_rgba(0,214,143,0.25)] transition-all duration-300">
                    <span class="font-bold text-emerald-400 text-xl">V</span>
                </div>
                <div class="flex flex-col leading-none">
                    <span class="font-bold text-lg text-white tracking-wide">Valenzo's</span>
                    <span class="text-xs text-gray-400 font-medium tracking-widest group-hover:text-emerald-400 transition-colors">COMPUTING</span>
                </div>
            </div>

            {{-- Clases para estado Activo/Inactivo --}}
            @php
                $active = "text-emerald-400 relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-emerald-400 after:shadow-[0_0_10px_rgba(0,214,143,0.5)]";
                $inactive = "text-gray-400 hover:text-white transition-colors";
            @endphp

            {{-- Menú Central (Escritorio) --}}
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? $active : $inactive }}">
                    Inicio
                </a>
                
                @php($isAdmin = Auth::check() && session('api_user_role')==='admin')
                
                @if($isAdmin)
                    {{-- Enlaces para ADMIN --}}
                    <a href="{{ route('productos.admin') }}" class="{{ request()->routeIs('productos.admin*') ? $active : $inactive }}">
                        Admin Productos
                    </a>
                    <a href="{{ route('servicios.admin') }}" class="{{ request()->routeIs('servicios.admin*') ? $active : $inactive }}">
                        Admin Servicios
                    </a>
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? $active : $inactive }}">
                        Usuarios
                    </a>
                    <a href="{{ route('admin.finanzas') }}" class="{{ request()->routeIs('admin.finanzas*') ? $active : $inactive }}">
                        Finanzas
                    </a>
                @else
                    {{-- Enlaces para CLIENTES / INVITADOS --}}
                    <a href="{{ route('productos.index') }}" class="{{ request()->routeIs('productos.index') ? $active : $inactive }}">
                        Catálogo
                    </a>
                    <a href="{{ route('servicios.index') }}" class="{{ request()->routeIs('servicios.index') ? $active : $inactive }}">
                        Servicios
                    </a>
                @endif
            </div>

            {{-- Zona Usuario (Derecha) --}}
            <div class="hidden md:flex items-center gap-4 h-full">
                @if(!Auth::check())
                    {{-- NO LOGUEADO --}}
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-[#0B0E14] font-bold text-sm shadow-[0_0_15px_rgba(0,214,143,0.25)] transition-all hover:-translate-y-0.5">
                            Registrarse
                        </a>
                    </div>
                @else
                    {{-- LOGUEADO --}}
                    {{-- IMPORTANTE: h-full aquí asegura que el área vertical cubra todo el navbar --}}
                    <div class="relative group h-full flex items-center">
                        <button class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-white/5 transition-colors border border-transparent hover:border-white/10 focus:outline-none">
                            <div class="text-right hidden lg:block">
                                <div class="text-sm font-bold text-white">{{ Auth::user()->name }}</div>
                                <div class="text-[10px] font-mono text-emerald-400 uppercase tracking-wider bg-emerald-500/10 px-1.5 rounded inline-block">
                                    {{ session('api_user_role') ?? 'Cliente' }}
                                </div>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 border border-gray-600 flex items-center justify-center text-white font-bold shadow-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <svg class="w-4 h-4 text-gray-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        {{-- Dropdown Menú --}}
                        {{-- CORRECCIÓN AQUÍ: 'top-full' lo pega al borde inferior y 'pt-2' crea un padding invisible --}}
                        <div class="absolute right-0 top-full pt-2 w-56 hidden group-hover:block">
                            <div class="bg-[#151A23] border border-gray-700 rounded-xl shadow-2xl overflow-hidden animate-[slideDown_0.2s_ease-out_forwards]">
                                
                                <div class="px-4 py-3 border-b border-gray-800 lg:hidden">
                                    <p class="text-sm text-white font-bold">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-emerald-400">{{ session('api_user_role') }}</p>
                                </div>
                                
                                <a href="{{ route('password.change') }}" class="block px-4 py-3 text-sm text-gray-300 hover:bg-gray-800 hover:text-emerald-400 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11.536 11l-4.414 2.207a1 1 0 00-.207.272L6 16l2 2-2.5 1.5L5 21a1 1 0 00.491.868L8 19l2 2 3.5-2 1.5-2.5a1 1 0 00.272-.207l2.207-4.414A6 6 0 0121 9z"></path></svg>
                                    Cambiar Contraseña
                                </a>

                                <div class="border-t border-gray-800">
                                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
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
    <div id="mobile-menu" class="hidden md:hidden bg-[#151A23] border-b border-gray-800">
        <div class="px-4 pt-2 pb-4 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md font-medium {{ request()->routeIs('home') ? 'text-emerald-400 bg-emerald-500/10' : 'text-gray-300' }}">Inicio</a>
            
            @if($isAdmin)
                <a href="{{ route('productos.admin') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('productos.admin*') ? 'text-white bg-gray-800' : 'text-gray-300' }}">Productos (Admin)</a>
                <a href="{{ route('servicios.admin') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('servicios.admin*') ? 'text-white bg-gray-800' : 'text-gray-300' }}">Servicios (Admin)</a>
                <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('admin.users*') ? 'text-white bg-gray-800' : 'text-gray-300' }}">Usuarios</a>
                <a href="{{ route('admin.finanzas') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('admin.finanzas*') ? 'text-white bg-gray-800' : 'text-gray-300' }}">Finanzas</a>
            @else
                <a href="{{ route('productos.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('productos.index') ? 'text-white bg-gray-800' : 'text-gray-300' }}">Catálogo</a>
                <a href="{{ route('servicios.index') }}" class="block px-3 py-2 rounded-md {{ request()->routeIs('servicios.index') ? 'text-white bg-gray-800' : 'text-gray-300' }}">Servicios</a>
            @endif

            <div class="border-t border-gray-800 my-2 pt-2">
                @if(!Auth::check())
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-300">Login</a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-emerald-400 font-bold">Registrarse</a>
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