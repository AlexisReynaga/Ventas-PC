<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo | Ventas PC</title>
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
        /* Animación suave para el carrito */
        @keyframes bounce-sm {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        .animate-cart-add {
            animation: bounce-sm 0.3s ease-in-out;
        }
        /* Animación dropdown usuario */
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

<x-navbar />


    <div class="bg-gradient-to-b from-card to-dark py-12 border-b border-white/5">
        <div class="max-w-[1400px] mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 tracking-tight">
                Hardware de <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-emerald-600">Alto Rendimiento</span>
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto">Encuentra los mejores componentes y equipos en San Luis Potosí. Calidad garantizada para gaming y trabajo profesional.</p>
        </div>
    </div>

    <div class="flex-grow max-w-[1400px] mx-auto px-4 py-8 w-full">
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            <aside class="w-full lg:w-64 flex-shrink-0">
                <div class="lg:sticky lg:top-24 space-y-6">
                    
                    <button onclick="document.getElementById('mobile-filters').classList.toggle('hidden')" class="lg:hidden w-full bg-card border border-gray-700 p-3 rounded-lg text-left flex justify-between items-center text-white font-semibold">
                        <span>Filtros</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>

                    <div id="mobile-filters" class="hidden lg:block bg-card/50 border border-white/5 rounded-xl p-5 backdrop-blur-sm">
                        <form method="GET" action="{{ route('productos.index') }}">
                            <div class="mb-6">
                                <label class="text-xs font-bold text-primary uppercase tracking-wider mb-2 block">Buscar</label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nombre..." class="filter-input w-full p-2.5 rounded-lg text-sm pl-9">
                                    <svg class="w-4 h-4 text-gray-500 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="text-xs font-bold text-primary uppercase tracking-wider mb-2 block">Categoría</label>
                                <select name="category" class="filter-input w-full p-2.5 rounded-lg text-sm">
                                    <option value="">Todas</option>
                                    @php($cats = $categories ?? [])
                                    @foreach($cats as $cat)
                                        <option value="{{ $cat }}" @selected(request('category') == $cat)>{{ $cat }}</option>
                                    @endforeach
                                    @if(request('category') && !in_array(request('category'), $cats ?? []))
                                        <option value="{{ request('category') }}" selected>{{ request('category') }}</option>
                                    @endif
                                </select>
                            </div>

                            <div class="mb-6">
                                <label class="text-xs font-bold text-primary uppercase tracking-wider mb-2 block">Precio</label>
                                <div class="flex gap-2 items-center">
                                    <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="filter-input w-full p-2 rounded text-sm text-center">
                                    <span class="text-gray-500">-</span>
                                    <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="filter-input w-full p-2 rounded text-sm text-center">
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="text-xs font-bold text-primary uppercase tracking-wider mb-2 block">Ordenar por</label>
                                <select name="sort" class="filter-input w-full p-2.5 rounded-lg text-sm mb-2">
                                    <option value="">Relevancia</option>
                                    <option value="price" @selected(request('sort') == 'price')>Precio</option>
                                    <option value="name" @selected(request('sort') == 'name')>Nombre</option>
                                </select>
                                <select name="direction" class="filter-input w-full p-2.5 rounded-lg text-sm">
                                    <option value="asc" @selected(request('direction') == 'asc')>Ascendente</option>
                                    <option value="desc" @selected(request('direction') == 'desc')>Descendente</option>
                                </select>
                            </div>

                            <div class="flex flex-col gap-2 pt-2 border-t border-gray-800">
                                <button type="submit" class="w-full bg-primary hover:bg-primaryDark text-dark font-bold py-2.5 rounded-lg transition-colors shadow-neon">Aplicar Filtros</button>
                                @if(request()->anyFilled(['search', 'category', 'min_price', 'max_price', 'sort']))
                                    <a href="{{ route('productos.index') }}" class="w-full text-center text-sm text-gray-500 hover:text-white py-2">Limpiar todo</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex-grow">
                
                @php($items = $products['items'] ?? ($products['data'] ?? ($products['items'] ?? [])))
                @php($current = $products['current_page'] ?? 1)
                @php($last = $products['last_page'] ?? 1)

                <div class="flex justify-between items-center mb-6">
                    <span class="text-gray-400 text-sm">Mostrando resultados página {{ $current }}</span>
                    <div class="flex bg-card rounded p-1 border border-gray-800">
                        <button class="p-1.5 bg-gray-700 rounded text-white"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg></button>
                    </div>
                </div>

                @if(count($items) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($items as $p)
                    <div class="group bg-card rounded-2xl border border-gray-800 overflow-hidden hover:border-primary/50 transition-all duration-300 hover:shadow-card-hover flex flex-col h-full">
                        
                        <div class="relative h-56 bg-gradient-to-br from-gray-800 to-gray-900 p-4 flex items-center justify-center overflow-hidden">
                            @if(!empty($p['image_url']))
                                <img src="{{ $p['image_url'] }}" alt="{{ $p['name'] }}" class="object-contain max-h-full max-w-full group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="text-gray-600 flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs">Sin Imagen</span>
                                </div>
                            @endif
                            
                            <div class="absolute top-3 left-3 flex gap-2">
                                @if(($p['stock'] ?? 0) < 5)
                                    <span class="bg-red-500/90 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">ÚLTIMOS</span>
                                @endif
                                @if($p['category'] ?? false)
                                    <span class="bg-dark/80 backdrop-blur text-gray-300 text-[10px] font-bold px-2 py-1 rounded border border-white/10">{{ $p['category'] }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-white leading-tight mb-2 group-hover:text-primary transition-colors line-clamp-2">
                                {{ $p['name'] ?? $p['nombre'] }}
                            </h3>
                            <p class="text-gray-400 text-sm mb-4 line-clamp-3 flex-grow">
                                {{ $p['description'] ?? 'Excelente rendimiento y calidad garantizada por Valenzo\'s PC.' }}
                            </p>

                            <div class="mt-auto pt-4 border-t border-gray-800 flex items-center justify-between">
                                <div>
                                    <span class="block text-xs text-gray-500">Precio</span>
                                    <span class="text-2xl font-bold text-primary">${{ number_format($p['price'], 2) }}</span>
                                </div>
                                
                                <button type="button" onclick="addToCart({{ $p['id'] ?? 0 }}, '{{ addslashes($p['name'] ?? 'Producto') }}')" class="bg-gray-800 hover:bg-white text-white hover:text-dark w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 group-hover:bg-primary group-hover:text-dark shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    <div class="flex gap-2">
                        @if($current > 1)
                            <a href="{{ route('productos.index', array_merge(request()->except('page'), ['page'=>$current-1])) }}" class="px-4 py-2 rounded-lg bg-card border border-gray-700 text-gray-300 hover:border-primary hover:text-primary transition">Anterior</a>
                        @endif
                        
                        <span class="px-4 py-2 text-gray-500 font-mono">Pág {{ $current }}</span>

                        @if($current < $last)
                            <a href="{{ route('productos.index', array_merge(request()->except('page'), ['page'=>$current+1])) }}" class="px-4 py-2 rounded-lg bg-card border border-gray-700 text-gray-300 hover:border-primary hover:text-primary transition">Siguiente</a>
                        @endif
                    </div>
                </div>

                @else
                <div class="text-center py-20 bg-card/30 rounded-2xl border border-dashed border-gray-800">
                    <svg class="w-16 h-16 text-gray-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-xl font-bold text-white mb-2">No encontramos productos</h3>
                    <p class="text-gray-500">Intenta ajustar los filtros de búsqueda.</p>
                    <a href="{{ route('productos.index') }}" class="inline-block mt-4 text-primary hover:underline">Ver todo el catálogo</a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <footer class="bg-dark border-t border-gray-800 mt-12 py-10">
        <div class="max-w-[1400px] mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold text-white mb-2">Ventas <span class="text-primary">PC</span></h2>
            <p class="text-gray-500 text-sm mb-6">Tu experto en hardware en San Luis Potosí.</p>
            <div class="flex justify-center gap-6 text-gray-400 text-sm">
                <a href="#" class="hover:text-white">Términos</a>
                <a href="#" class="hover:text-white">Privacidad</a>
                <a href="#" class="hover:text-white">Contacto</a>
            </div>
            <p class="mt-8 text-xs text-gray-700">&copy; 2025 Ventas PC. Todos los derechos reservados.</p>
        </div>
    </footer>

    <div id="toast" class="fixed bottom-5 right-5 bg-card border border-primary/50 text-white px-6 py-4 rounded-lg shadow-neon transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-3">
        <div class="bg-primary/20 p-2 rounded-full text-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <div>
            <h4 class="font-bold text-sm">¡Añadido al carrito!</h4>
            <p id="toast-message" class="text-xs text-gray-400">El producto se agregó correctamente.</p>
        </div>
    </div>

    <script>
        let cartCount = {{ count((session('cart.products') ?? [])) + count((session('cart.services') ?? [])) }};

        async function addToCart(id, productName) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toast = document.getElementById('toast');
            try {
                const base = '{{ url('carrito/agregar/producto') }}';
                const res = await fetch(base + '/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) throw new Error('Fallo al agregar');
                const data = await res.json();
                cartCount = (data.cart.products.length + data.cart.services.length);
                const badge = document.getElementById('cart-badge');
                if (badge) {
                    badge.innerText = cartCount;
                    badge.classList.remove('opacity-0');
                    badge.classList.add('animate-cart-add');
                    setTimeout(() => badge.classList.remove('animate-cart-add'), 300);
                }
                document.getElementById('toast-message').innerText = `${productName} agregado al carrito.`;
                toast.classList.remove('translate-y-20','opacity-0');
                setTimeout(() => toast.classList.add('translate-y-20','opacity-0'), 3000);
            } catch (e) {
                document.getElementById('toast-message').innerText = `Error: no se pudo agregar.`;
                toast.classList.remove('translate-y-20','opacity-0');
                setTimeout(() => toast.classList.add('translate-y-20','opacity-0'), 3000);
            }
        }
    </script>
</body>
</html>