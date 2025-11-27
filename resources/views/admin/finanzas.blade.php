<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanzas | Valenzo's PC</title>
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
                        'neon': '0 0 15px rgba(0, 214, 143, 0.25)',
                        'glow': '0 0 20px rgba(0, 214, 143, 0.1)',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0B0E14; color: #e2e8f0; font-family: 'Inter', sans-serif; }
        .stat-card {
            background: linear-gradient(145deg, rgba(21, 26, 35, 0.9), rgba(21, 26, 35, 0.4));
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(0, 214, 143, 0.3);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col relative">

    <x-navbar />

    <main class="flex-grow p-6 max-w-7xl mx-auto w-full">
        
        <!-- Header -->
        <div class="mb-10">
            <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
                <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Panel Financiero
            </h1>
            <p class="text-gray-400">Resumen global: inventario (costo vs público), servicios y desempeño de ventas.</p>
        </div>

        <!-- SECCIÓN PRODUCTOS -->
        <div class="mb-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white border-l-4 border-primary pl-3">Inventario de Productos</h2>
                <a href="{{ route('productos.admin') }}" class="text-xs text-primary hover:underline flex items-center gap-1">
                    Ver detalles <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            @if($productsSummary['costo_mayor_precio_count'] > 0)
            <div class="mb-6 p-4 rounded-xl border border-red-500/30 bg-red-500/10 flex items-start gap-3">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                <div class="text-sm text-red-300">
                    <strong>{{ $productsSummary['costo_mayor_precio_count'] }}</strong> producto(s) tienen <span class="font-semibold">costo mayor</span> al precio público. Revisa márgenes y ajusta precios o costos.
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Valor Inventario Público -->
                <div class="stat-card rounded-2xl p-6 col-span-1 md:col-span-2 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-primary/20 transition-all"></div>
                    <div class="relative z-10">
                        <p class="text-sm text-gray-400 uppercase tracking-wider font-bold mb-1">Valor Inventario Público</p>
                        <h3 class="text-4xl font-black text-white tracking-tight mb-1">
                            $ {{ number_format($productsSummary['valor_inventario_publico'], 2) }}
                        </h3>
                        <p class="text-xs text-primary mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            Potencial de venta a precio público
                        </p>
                        <div class="mt-4 grid grid-cols-3 gap-4 text-xs">
                            <div class="flex flex-col">
                                <span class="text-gray-400">Costo Base</span>
                                <span class="font-semibold text-amber-300">$ {{ number_format($productsSummary['valor_inventario_costo'], 2) }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-gray-400">Margen Potencial</span>
                                <span class="font-semibold text-emerald-400">$ {{ number_format($productsSummary['margen_potencial_inventario'], 2) }}</span>
                            </div>
                            @php($invMarginPct = $productsSummary['valor_inventario_publico'] > 0 ? ($productsSummary['margen_potencial_inventario'] / $productsSummary['valor_inventario_publico']) * 100 : 0)
                            <div class="flex flex-col">
                                <span class="text-gray-400">% Margen</span>
                                <span class="font-semibold text-cyan-300">{{ number_format($invMarginPct,2) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Productos -->
                <div class="stat-card rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <span class="text-xs font-bold bg-blue-500/10 text-blue-400 px-2 py-1 rounded">Total Items</span>
                    </div>
                    <h4 class="text-2xl font-bold text-white">{{ $productsSummary['total'] }}</h4>
                    <p class="text-xs text-gray-500 mt-1">Productos registrados</p>
                </div>

                <!-- Stock Total -->
                <div class="stat-card rounded-2xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        </div>
                        <span class="text-xs font-bold bg-purple-500/10 text-purple-400 px-2 py-1 rounded">Unidades</span>
                    </div>
                    <h4 class="text-2xl font-bold text-white">{{ $productsSummary['stock_total'] }}</h4>
                    <p class="text-xs text-gray-500 mt-1">Stock físico total</p>
                </div>
            </div>
            
            <!-- Barra de Activos -->
            @php($totalP = (int)($productsSummary['total'] ?? 0))
            @php($activeP = (int)($productsSummary['active'] ?? 0))
            @php($safeTotal = $totalP > 0 ? $totalP : 1)
            @php($percentP = $safeTotal > 0 ? ($activeP / $safeTotal) * 100 : 0)
            <div class="mt-6 bg-card border border-gray-800 rounded-xl p-4 flex items-center gap-4">
                <span class="text-sm text-gray-400 whitespace-nowrap">Estado del Catálogo:</span>
                <div class="flex-grow h-2 bg-gray-800 rounded-full overflow-hidden flex">
                    <div class="h-full bg-primary shadow-neon" style="width: {{ number_format($percentP,2,'.','') }}%"></div>
                </div>
                <span class="text-sm font-bold text-primary">{{ $activeP }} Activos</span>
                <span class="text-sm text-gray-600">/ {{ $totalP }}</span>
            </div>
        </div>

        <!-- SECCIÓN SERVICIOS -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white border-l-4 border-blue-500 pl-3">Servicios Ofrecidos</h2>
                <a href="{{ route('servicios.admin') }}" class="text-xs text-blue-400 hover:underline flex items-center gap-1">
                    Ver detalles <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Valor Servicios (Suma precios base) -->
                <div class="stat-card rounded-2xl p-6 relative overflow-hidden">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gray-800 flex items-center justify-center text-white font-bold text-xl border border-gray-700">
                            $
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Suma Precios Base</p>
                            <h3 class="text-2xl font-bold text-white">$ {{ number_format($servicesSummary['valor_servicios'], 2) }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Total Servicios -->
                <div class="stat-card rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Total Servicios</p>
                            <h3 class="text-3xl font-bold text-white">{{ $servicesSummary['total'] }}</h3>
                        </div>
                        <div class="h-10 w-10 text-gray-600">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Activos -->
                <div class="stat-card rounded-2xl p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold mb-1">Disponibles</p>
                            <h3 class="text-3xl font-bold text-emerald-400">{{ $servicesSummary['active'] }}</h3>
                        </div>
                        <div class="flex items-center gap-1 bg-emerald-500/10 px-2 py-1 rounded text-xs text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            Online
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN VENTAS -->
        <div class="mt-12">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white border-l-4 border-emerald-500 pl-3">Desempeño de Ventas</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="stat-card rounded-2xl p-6">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Tickets Emitidos</p>
                    <h3 class="text-3xl font-bold text-white">{{ $ventasSummary['tickets'] }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Compras registradas</p>
                </div>
                <div class="stat-card rounded-2xl p-6">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Ingresos</p>
                    <h3 class="text-3xl font-bold text-emerald-400">$ {{ number_format($ventasSummary['ingresos'],2) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Total bruto (precio público)</p>
                </div>
                <div class="stat-card rounded-2xl p-6">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Costo</p>
                    <h3 class="text-3xl font-bold text-amber-300">$ {{ number_format($ventasSummary['costo'],2) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Costo base productos</p>
                </div>
                <div class="stat-card rounded-2xl p-6">
                    <p class="text-xs text-gray-400 uppercase font-bold mb-1">Profit</p>
                    <h3 class="text-3xl font-bold text-cyan-300">$ {{ number_format($ventasSummary['profit'],2) }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Margen: {{ number_format($ventasSummary['margin_percent'],2) }}%</p>
                </div>
            </div>
        </div>

    </main>

</body>
</html>