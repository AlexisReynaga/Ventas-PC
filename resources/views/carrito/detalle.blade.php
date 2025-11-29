<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Pedido | Ventas PC</title>
    
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
                        'neon': '0 0 20px rgba(0, 214, 143, 0.25)',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0B0E14; color: #e2e8f0; font-family: 'Inter', sans-serif; }
        .glass-receipt {
            background: rgba(21, 26, 35, 0.8);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            background-image: radial-gradient(circle at top right, rgba(0, 214, 143, 0.05), transparent 40%);
        }
        .dashed-line {
            background-image: linear-gradient(to right, #334155 50%, transparent 50%);
            background-position: bottom;
            background-size: 10px 1px;
            background-repeat: repeat-x;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col relative bg-dark">

    <x-navbar />

    <main class="flex-grow flex items-center justify-center p-6 relative overflow-hidden">
        
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="glass-receipt w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden relative z-10 animate-[fadeIn_0.5s_ease-out]">
            
            <div class="p-8 text-center border-b border-gray-800">
                <div class="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4 text-primary">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Confirmar Compra</h1>
                <p class="text-gray-400 text-sm">Revisa los detalles antes de finalizar.</p>
            </div>

            @php($cart = $cart ?? ['products'=>[], 'services'=>[], 'total'=>0])

            <div class="p-8 space-y-6">
                
                @if(!empty($cart['products']))
                <div>
                    <h3 class="text-xs font-bold text-primary uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Productos
                    </h3>
                    <ul class="space-y-3 text-sm">
                        @foreach($cart['products'] as $p)
                        <li class="flex justify-between items-start">
                            <span class="text-gray-300">
                                <span class="font-bold text-white">{{ $p['quantity'] ?? 1 }}x</span> {{ $p['name'] }}
                            </span>
                            <span class="font-mono text-gray-400">
                                ${{ number_format(($p['price'] ?? 0) * ($p['quantity'] ?? 1), 2) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($cart['products']) && !empty($cart['services']))
                    <div class="h-px bg-gray-800 dashed-line"></div>
                @endif

                @if(!empty($cart['services']))
                <div>
                    <h3 class="text-xs font-bold text-blue-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Servicios
                    </h3>
                    <ul class="space-y-3 text-sm">
                        @foreach($cart['services'] as $s)
                        <li class="flex justify-between items-start">
                            <div class="flex flex-col">
                                <span class="text-gray-300">{{ $s['name'] }}</span>
                                <span class="text-[10px] text-gray-500">
                                    Cita: {{ $s['scheduled_date'] ?? 'Pendiente' }} {{ $s['scheduled_time'] ?? '' }}
                                </span>
                            </div>
                            <span class="font-mono text-gray-400">
                                ${{ number_format($s['price'] ?? 0, 2) }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(empty($cart['products']) && empty($cart['services']))
                    <p class="text-center text-gray-500 py-4">No hay items en el resumen.</p>
                @endif

            </div>

            <div class="bg-gray-900/50 p-8 border-t border-gray-800">
                <div class="flex justify-between items-end mb-8">
                    <span class="text-gray-400 font-medium">Total a Pagar</span>
                    <span class="text-4xl font-black text-primary tracking-tight">
                        ${{ number_format($cart['total'], 2) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('carrito.ticket') }}">
                    @csrf 
                    <button type="submit" class="w-full bg-primary hover:bg-primaryDark text-dark font-bold py-4 rounded-xl shadow-neon transition-all hover:-translate-y-1 active:translate-y-0 flex items-center justify-center gap-3 text-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Finalizar y Descargar Ticket
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('carrito.index') }}" class="text-sm text-gray-500 hover:text-white transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Volver al carrito para editar
                    </a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>