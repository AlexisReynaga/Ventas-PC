<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendar Cita | Ventas PC</title>
    
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
        .glass-panel {
            background: rgba(21, 26, 35, 0.7);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-input {
            background: rgba(11, 14, 20, 0.6);
            border: 1px solid #334155;
            color: white;
            transition: all 0.3s ease;
            color-scheme: dark; 
        }
        .glass-input:focus {
            border-color: #00D68F;
            background: rgba(11, 14, 20, 0.9);
            box-shadow: 0 0 15px rgba(0, 214, 143, 0.1);
            outline: none;
        }
        ::-webkit-calendar-picker-indicator {
            filter: invert(1);
            opacity: 0.6;
            cursor: pointer;
        }
        ::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col relative bg-dark">

    <x-navbar />

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <main class="flex-grow flex items-center justify-center p-6 relative z-10">
        
        <div class="glass-panel w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border-t-4 border-t-primary">
            
            <div class="p-8">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-white mb-2">Agendar Servicio</h1>
                    <p class="text-gray-400 text-sm">Selecciona la fecha ideal para atender tu equipo.</p>
                </div>

                <div class="bg-gray-800/50 rounded-xl p-4 mb-8 flex items-center justify-between border border-gray-700">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white text-sm">{{ $service['name'] ?? 'Servicio General' }}</h3>
                            <span class="text-xs text-gray-400">Servicio Técnico Especializado</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="block text-xs text-gray-500">Costo Estimado</span>
                        <span class="font-mono font-bold text-xl text-primary">${{ number_format($service['price'] ?? 0, 2) }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('servicios.schedule.store', $service['id'] ?? 0) }}" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Fecha de Cita</label>
                            <div class="relative">
                                <input type="date" name="date" required min="{{ date('Y-m-d') }}"
                                    class="glass-input w-full p-3 rounded-xl text-sm focus:ring-0 appearance-none">
                            </div>
                            @error('date')
                                <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Hora Preferida</label>
                            <div class="relative">
                                <input type="time" name="time" required
                                    class="glass-input w-full p-3 rounded-xl text-sm focus:ring-0 appearance-none">
                            </div>
                            @error('time')
                                <p class="text-red-400 text-xs mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-800 flex items-center justify-between gap-4">
                        <a href="{{ route('servicios.index') }}" class="text-sm text-gray-500 hover:text-white transition-colors px-4 py-2">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-primary hover:bg-primaryDark text-dark font-bold py-3 px-8 rounded-xl shadow-neon transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                            <span>Confirmar Cita</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>

</body>
</html>