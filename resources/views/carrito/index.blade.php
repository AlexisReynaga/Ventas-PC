<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carrito | Ventas PC</title>
    
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
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0B0E14; color: #e2e8f0; font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(21, 26, 35, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .fade-out {
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script>
        function getCsrf() { return document.querySelector('meta[name="csrf-token"]').getAttribute('content'); }
        
        function updateBadgeFromCart(cart) {
            var badge = document.getElementById('cart-badge');
            if (!badge) return;
            var count = (cart.products ? cart.products.length : 0) + (cart.services ? cart.services.length : 0);
            badge.textContent = count;
            if (count > 0) badge.classList.remove('opacity-0');
            else badge.classList.add('opacity-0');
        }

        async function cartPost(url) {
            const res = await fetch(url, { 
                method: 'POST', 
                headers: { 
                    'X-CSRF-TOKEN': getCsrf(), 
                    'Accept':'application/json',
                    'Content-Type': 'application/json'
                } 
            });
            if (!res.ok) throw new Error('Error HTTP ' + res.status);
            return res.json();
        }

        function updateTotals(cart) {
            var totalEl = document.getElementById('cart-total');
            var emptyMsg = document.getElementById('empty-cart-msg');
            var contentGrid = document.getElementById('cart-content-grid');

            if (totalEl) {
                totalEl.textContent = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(cart.total);
            }

            if(cart.total <= 0 && emptyMsg && contentGrid) {
                contentGrid.classList.add('hidden');
                emptyMsg.classList.remove('hidden');
            }
        }

        function updateProductRowFromCart(cart, id) {
            var prod = (cart.products || []).find(p => (p.id||null) == id);
            var row = document.getElementById('row-product-'+id);
            
            if (!row) return;
            
            if (!prod) {
                row.classList.add('fade-out');
                setTimeout(() => row.remove(), 300);
                return;
            }
            
            var qtyEl = document.getElementById('qty-'+id);
            var subEl = document.getElementById('sub-'+id);
            
            if (qtyEl) qtyEl.textContent = (prod.quantity || 1);
            if (subEl) subEl.textContent = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format((prod.price||0) * (prod.quantity||1));
        }

        async function cartIncrement(id) {
            try {
                const data = await cartPost('{{ url('carrito/producto') }}/'+id+'/incrementar');
                updateProductRowFromCart(data.cart, id);
                updateTotals(data.cart);
                updateBadgeFromCart(data.cart);
            } catch (e) { console.error(e); }
        }

        async function cartDecrement(id) {
            try {
                const data = await cartPost('{{ url('carrito/producto') }}/'+id+'/disminuir');
                updateProductRowFromCart(data.cart, id);
                updateTotals(data.cart);
                updateBadgeFromCart(data.cart);
            } catch (e) { console.error(e); }
        }

        let pendingAction = null;
        let pendingId = null;

        function openConfirmModal(type, id) {
            pendingAction = type; pendingId = id || null;
            document.getElementById('confirmTitle').textContent = type === 'product' ? '¿Eliminar Producto?' : (type === 'service' ? '¿Eliminar Servicio?' : '¿Vaciar Carrito?');
            document.getElementById('confirmMessage').textContent = type === 'clear'
                ? 'Esta acción no se puede deshacer. Se eliminarán todos los productos y servicios del carrito.'
                : 'Esta acción no se puede deshacer.';
            document.getElementById('confirmModal').classList.remove('hidden');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            pendingAction = null; pendingId = null;
        }

        async function confirmProceed() {
            try {
                if (pendingAction === 'product' && pendingId) {
                    const data = await cartPost('{{ url('carrito/producto') }}/'+pendingId+'/eliminar');
                    updateProductRowFromCart(data.cart, pendingId);
                    updateTotals(data.cart);
                    updateBadgeFromCart(data.cart);
                } else if (pendingAction === 'service' && pendingId) {
                    const data = await cartPost('{{ url('carrito/servicio') }}/'+pendingId+'/eliminar');
                    var row = document.getElementById('row-service-'+pendingId);
                    if (row) { row.classList.add('fade-out'); setTimeout(() => row.remove(), 300); }
                    updateTotals(data.cart);
                    updateBadgeFromCart(data.cart);
                } else if (pendingAction === 'clear') {
                    await cartPost('{{ route('carrito.clear') }}');
                    location.reload();
                }
            } catch (e) { console.error(e); }
            finally { closeConfirmModal(); }
        }

        function cartRemoveProduct(id) { openConfirmModal('product', id); }

        function cartRemoveService(id) { openConfirmModal('service', id); }

        function cartClear() { openConfirmModal('clear'); }
    </script>
</head>
<body class="antialiased min-h-screen flex flex-col relative">

    <x-navbar />

    <main class="flex-grow p-6 max-w-[1400px] mx-auto w-full">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                <span class="bg-primary/20 p-2 rounded-lg text-primary">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </span>
                Tu Carrito
            </h1>
        </div>

        @if(session('status'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('status') }}
            </div>
        @endif

        @php($cart = $cart ?? ['products'=>[], 'services'=>[], 'total'=>0])

        <div id="empty-cart-msg" class="{{ ($cart['total'] > 0) ? 'hidden' : '' }} text-center py-20 bg-card rounded-2xl border border-dashed border-gray-800">
            <div class="w-20 h-20 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Tu carrito está vacío</h3>
            <p class="text-gray-500 mb-6">Parece que no has agregado nada aún.</p>
            <a href="{{ route('productos.index') }}" class="bg-primary hover:bg-primaryDark text-dark font-bold py-2.5 px-6 rounded-lg transition-colors">
                Ir al Catálogo
            </a>
        </div>

        <div id="cart-content-grid" class="flex flex-col lg:flex-row gap-8 {{ ($cart['total'] <= 0) ? 'hidden' : '' }}">
            
            <div class="w-full lg:w-2/3 space-y-8">
                
                @if(!empty($cart['products']))
                <div>
                    <h2 class="text-lg font-bold text-gray-300 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Productos
                    </h2>
                    <div class="space-y-4" id="products-body">
                        @foreach($cart['products'] as $p)
                        <div id="row-product-{{ $p['id'] }}" class="glass-panel p-4 rounded-xl flex flex-col sm:flex-row items-center gap-4 group hover:border-primary/30 transition-colors">
                            <div class="w-20 h-20 bg-gray-800 rounded-lg flex-shrink-0 overflow-hidden border border-gray-700 relative">
                                @if(!empty($p['image_url']))
                                    <img src="{{ $p['image_url'] }}" 
                                         alt="{{ $p['name'] }}"
                                         class="w-full h-full object-cover relative z-10" 
                                         onerror="this.style.display='none'"> 
                                @endif
                                <div class="absolute inset-0 flex items-center justify-center text-gray-600 bg-gray-800 z-0">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            </div>
                            
                            <div class="flex-grow text-center sm:text-left">
                                <h3 class="font-bold text-white">{{ $p['name'] }}</h3>
                                <p class="text-sm text-primary font-mono">${{ number_format($p['price'], 2) }} c/u</p>
                            </div>

                            <div class="flex items-center bg-gray-800 rounded-lg p-1 border border-gray-700">
                                <button onclick="cartDecrement({{ $p['id'] }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 rounded transition">-</button>
                                <span id="qty-{{ $p['id'] }}" class="w-8 text-center text-sm font-bold text-white">{{ $p['quantity'] ?? 1 }}</span>
                                <button onclick="cartIncrement({{ $p['id'] }})" class="w-8 h-8 flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 rounded transition">+</button>
                            </div>

                            <div class="text-right min-w-[80px]">
                                <p class="text-xs text-gray-500">Subtotal</p>
                                <p id="sub-{{ $p['id'] }}" class="font-bold text-white font-mono">
                                    ${{ number_format(($p['price'] ?? 0) * ($p['quantity'] ?? 1), 2) }}
                                </p>
                            </div>

                            <!-- Eliminar -->
                            <button onclick="cartRemoveProduct({{ $p['id'] }})" class="p-2 text-gray-500 hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($cart['services']))
                <div>
                    <h2 class="text-lg font-bold text-gray-300 mb-4 mt-8 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Servicios Agendados
                    </h2>
                    <div class="space-y-4" id="services-body">
                        @foreach($cart['services'] as $s)
                        <div id="row-service-{{ $s['id'] }}" class="glass-panel p-4 rounded-xl flex flex-col sm:flex-row items-center gap-4 border-l-4 border-blue-500">
                            <!-- Icono Servicio -->
                            <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center text-blue-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>

                            <div class="flex-grow text-center sm:text-left">
                                <h3 class="font-bold text-white">{{ $s['name'] }}</h3>
                                <div class="text-xs text-gray-400 flex flex-col sm:flex-row gap-2 mt-1">
                                    <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> {{ $s['scheduled_date'] ?? 'Fecha pendiente' }}</span>
                                    <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> {{ $s['scheduled_time'] ?? '--:--' }}</span>
                                </div>
                            </div>

                            <div class="text-right min-w-[80px]">
                                <p class="font-bold text-white font-mono">${{ number_format($s['price'], 2) }}</p>
                            </div>

                            <button onclick="cartRemoveService({{ $s['id'] }})" class="p-2 text-gray-500 hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            <div class="w-full lg:w-1/3">
                <div class="glass-panel p-6 rounded-2xl sticky top-28 shadow-2xl">
                    <h3 class="text-xl font-bold text-white mb-6 pb-4 border-b border-gray-800">Resumen del Pedido</h3>
                    
                    <div class="flex justify-between items-center mb-2 text-gray-400 text-sm">
                        @php($calcSubtotal = 0)
                        @foreach(($cart['products'] ?? []) as $pp)
                            @php($calcSubtotal += (float)($pp['price'] ?? 0) * (int)($pp['quantity'] ?? 1))
                        @endforeach
                        @foreach(($cart['services'] ?? []) as $ss)
                            @php($calcSubtotal += (float)($ss['price'] ?? 0))
                        @endforeach
                        <span>Subtotal</span>
                        <span id="cart-subtotal">${{ number_format($calcSubtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-6 text-gray-400 text-sm">
                        @php($taxRate = 0.16)
                        @php($calcTax = round($calcSubtotal * $taxRate, 2))
                        <span>Impuestos est. (IVA {{ (int)($taxRate*100) }}%)</span>
                        <span id="cart-tax">${{ number_format($calcTax, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center mb-8 pt-4 border-t border-gray-800">
                        <span class="text-lg font-bold text-white">Total</span>
                        @php($calcTotal = round($calcSubtotal + $calcTax, 2))
                        <span class="text-2xl font-black text-primary font-mono" id="cart-total">
                            ${{ number_format($calcTotal, 2) }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('carrito.checkout') }}" class="space-y-3">
                        @csrf
                        <button type="submit" class="w-full bg-primary hover:bg-primaryDark text-dark font-bold py-3.5 rounded-xl shadow-neon transition-all hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                            <span>Proceder al Pago</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </button>
                    </form>

                    <button onclick="cartClear()" class="w-full mt-4 text-xs text-red-400 hover:text-red-300 hover:underline text-center">
                        Vaciar carrito por completo
                    </button>

                    <div class="mt-8 flex justify-center gap-4 opacity-30 grayscale">
                        <div class="w-8 h-5 bg-white rounded"></div>
                        <div class="w-8 h-5 bg-white rounded"></div>
                        <div class="w-8 h-5 bg-white rounded"></div>
                    </div>
                    <p class="text-center text-[10px] text-gray-600 mt-2">Pagos 100% seguros y encriptados</p>
                </div>
            </div>

        </div>

    </main>

    <!-- Modal de Confirmación Genérico -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-sm">
            <div class="bg-card border border-red-900/50 rounded-xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </div>
                    <h3 id="confirmTitle" class="text-xl font-bold text-white mb-2">Confirmar acción</h3>
                    <p id="confirmMessage" class="text-gray-400 text-sm mb-6">Esta acción no se puede deshacer.</p>
                    <div class="flex gap-3 justify-center">
                        <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 rounded-lg bg-gray-800 text-gray-300 hover:bg-gray-700 text-sm font-medium transition">
                            Cancelar
                        </button>
                        <button type="button" onclick="confirmProceed()" class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 text-sm font-bold shadow-lg transition">
                            Sí, Continuar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>