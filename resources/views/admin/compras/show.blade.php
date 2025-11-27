<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Compra #{{ $purchase->id }} | Admin</title>
    
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
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col relative">

    <x-navbar />

    <main class="flex-grow p-6 max-w-[1200px] mx-auto w-full">
        
        <!-- Header y Acciones -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('admin.compras') }}" class="text-gray-500 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </a>
                    <h1 class="text-3xl font-bold text-white">Transacción #{{ $purchase->id }}</h1>
                </div>
                <p class="text-gray-400 text-sm ml-9">Detalle completo del pedido y facturación.</p>
            </div>
            
            <a href="{{ route('admin.compras.ticket.pdf', $purchase->id) }}" 
               class="bg-primary hover:bg-primaryDark text-dark font-bold py-2.5 px-6 rounded-lg shadow-neon transition-all hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Descargar Ticket PDF
            </a>
        </div>

        <!-- Info Card Principal -->
        <div class="glass-panel rounded-2xl p-6 mb-8 grid grid-cols-1 md:grid-cols-4 gap-6 border-t-4 border-t-primary">
            
            <!-- Ticket ID -->
            <div class="md:col-span-1">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Ticket ID</p>
                <div class="font-mono text-emerald-400 text-sm bg-primary/10 px-3 py-1.5 rounded w-fit border border-primary/20">
                    {{ $purchase->ticket_id }}
                </div>
            </div>

            <!-- Cliente -->
            <div class="md:col-span-1">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Cliente</p>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-gray-700 flex items-center justify-center text-[10px] text-white font-bold">
                        {{ substr(optional($purchase->user)->name ?? 'I', 0, 1) }}
                    </div>
                    <span class="text-white font-medium">{{ optional($purchase->user)->name ?? 'Usuario Invitado' }}</span>
                </div>
            </div>

            <!-- Fecha -->
            <div class="md:col-span-1">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Fecha de Compra</p>
                <p class="text-white">{{ $purchase->created_at->format('d M Y') }}</p>
                <p class="text-xs text-gray-500">{{ $purchase->created_at->format('h:i A') }}</p>
            </div>

            <!-- Total -->
            <div class="md:col-span-1 text-right">
                <p class="text-xs text-gray-500 uppercase font-bold mb-1">Total Pagado</p>
                <p class="text-3xl font-black text-white tracking-tight">${{ number_format($purchase->total, 2) }}</p>
            </div>
        </div>

        <!-- Tablas de Contenido -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- PRODUCTOS -->
            <div class="glass-panel rounded-xl overflow-hidden border border-gray-800">
                <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Productos
                    </h3>
                    <span class="text-xs bg-gray-800 text-gray-300 px-2 py-1 rounded">{{ count($purchase->items['products'] ?? []) }} Items</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-900 text-gray-500">
                            <tr>
                                <th class="px-6 py-3">Descripción</th>
                                <th class="px-6 py-3 text-center">Cant.</th>
                                <th class="px-6 py-3 text-right">Precio Unit.</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @php($totalProductos = 0)
                            @forelse($purchase->items['products'] ?? [] as $p)
                                @php($sub = ($p['price'] ?? 0) * ($p['quantity'] ?? 1))
                                @php($totalProductos += $sub)
                                <tr class="hover:bg-gray-800/30">
                                    <td class="px-6 py-4 font-medium text-white">{{ $p['name'] }}</td>
                                    <td class="px-6 py-4 text-center">{{ $p['quantity'] ?? 1 }}</td>
                                    <td class="px-6 py-4 text-right font-mono">${{ number_format($p['price'] ?? 0, 2) }}</td>
                                    <td class="px-6 py-4 text-right font-mono text-emerald-400">${{ number_format($sub, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-600 italic">No hay productos en esta compra.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if(!empty($purchase->items['products']))
                        <tfoot class="bg-gray-900/80 border-t border-gray-800">
                            <tr>
                                <td colspan="3" class="px-6 py-3 text-right font-bold text-gray-300">Total Productos:</td>
                                <td class="px-6 py-3 text-right font-bold text-white">${{ number_format($totalProductos, 2) }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

            <!-- SERVICIOS -->
            <div class="glass-panel rounded-xl overflow-hidden border border-gray-800">
                <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-800 flex justify-between items-center">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Servicios
                    </h3>
                    <span class="text-xs bg-gray-800 text-gray-300 px-2 py-1 rounded">{{ count($purchase->items['services'] ?? []) }} Servicios</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-400">
                        <thead class="text-xs uppercase bg-gray-900 text-gray-500">
                            <tr>
                                <th class="px-6 py-3">Servicio</th>
                                <th class="px-6 py-3">Fecha Agendada</th>
                                <th class="px-6 py-3 text-right">Precio</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($purchase->items['services'] ?? [] as $s)
                                <tr class="hover:bg-gray-800/30">
                                    <td class="px-6 py-4 font-medium text-white">{{ $s['name'] }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col text-xs">
                                            <span class="text-gray-300">{{ $s['scheduled_date'] ?? '-' }}</span>
                                            <span class="text-gray-500">{{ $s['scheduled_time'] ?? '-' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono text-emerald-400">${{ number_format($s['price'] ?? 0, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-600 italic">No hay servicios en esta compra.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

</body>
</html>