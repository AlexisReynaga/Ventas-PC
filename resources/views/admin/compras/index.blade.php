<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras | Valenzo's PC</title>
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
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col relative">

    <x-navbar />

    <main class="flex-grow p-6 max-w-[1400px] mx-auto w-full">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-1 flex items-center gap-3">
                    <span class="p-2 bg-primary/10 rounded-lg text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </span>
                    Compras Realizadas
                </h1>
                <p class="text-gray-400 text-sm">Registro histórico de transacciones y tickets generados.</p>
            </div>
        </div>

        <!-- Tabla Estilizada -->
        <div class="bg-card rounded-xl border border-gray-800 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-gray-900/60 text-xs uppercase font-semibold text-primary/80 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">ID / Ticket</th>
                            <th class="px-6 py-4">Usuario</th>
                            <th class="px-6 py-4 text-center">Items</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Fecha</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($purchases as $purchase)
                        <tr class="hover:bg-gray-800/40 transition-colors group">
                            
                            <!-- ID y Ticket -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-500">ID: {{ $purchase->id }}</span>
                                    <span class="font-mono text-white font-medium text-xs bg-gray-900 px-2 py-1 rounded w-fit mt-1 border border-gray-700 group-hover:border-primary/50 transition-colors">
                                        {{ $purchase->ticket_id }}
                                    </span>
                                </div>
                            </td>

                            <!-- Usuario -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 border border-gray-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ substr(optional($purchase->user)->name ?? 'I', 0, 1) }}
                                    </div>
                                    <span class="text-gray-300 font-medium">
                                        {{ optional($purchase->user)->name ?? 'Usuario Invitado' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Items Count -->
                            <td class="px-6 py-4 text-center">
                                <span class="bg-gray-800 text-gray-300 px-2.5 py-0.5 rounded-full text-xs font-bold border border-gray-700">
                                    {{ count($purchase->items['products'] ?? []) + count($purchase->items['services'] ?? []) }}
                                </span>
                            </td>

                            <!-- Total -->
                            <td class="px-6 py-4">
                                <span class="text-emerald-400 font-mono font-bold text-base">
                                    ${{ number_format($purchase->total, 2) }}
                                </span>
                            </td>

                            <!-- Fecha -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col text-xs">
                                    <span class="text-gray-300">{{ $purchase->created_at->format('d M Y') }}</span>
                                    <span class="text-gray-500">{{ $purchase->created_at->format('h:i A') }}</span>
                                </div>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.compras.show', $purchase->id) }}" 
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-primary hover:text-dark text-gray-300 transition-all text-xs font-bold border border-gray-700 hover:border-primary shadow-sm hover:shadow-neon">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    Ver Detalle
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mb-4 text-gray-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <p class="text-lg font-medium text-gray-400">No hay compras registradas</p>
                                    <p class="text-sm">Las transacciones aparecerán aquí.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="bg-gray-900/40 px-6 py-4 border-t border-gray-800">
                {{ $purchases->links() }}
            </div>
        </div>

    </main>

</body>
</html>