<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seguridad | Valenzo's PC</title>
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
        body { background-color: #0B0E14; font-family: 'Inter', sans-serif; }
        .glass-panel {
            background: rgba(21, 26, 35, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-input {
            background: rgba(11, 14, 20, 0.6);
            border: 1px solid #334155;
            color: white;
            transition: all 0.3s ease;
        }
        .glass-input:focus {
            border-color: #00D68F;
            background: rgba(11, 14, 20, 0.9);
            box-shadow: 0 0 15px rgba(0, 214, 143, 0.1);
            outline: none;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col relative overflow-x-hidden">

    <!-- Navbar Universal -->
    <x-navbar />

    <!-- Fondo decorativo -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px] pointer-events-none z-0"></div>

    <main class="flex-grow flex items-center justify-center p-6 relative z-10">
        <div class="glass-panel w-full max-w-lg rounded-2xl p-8 shadow-2xl border border-gray-800/50">
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-700 text-primary shadow-neon">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Cambiar Contraseña</h1>
                <p class="text-gray-400 text-sm mt-1">Actualiza tus credenciales de seguridad</p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 text-sm">
                    <div class="flex items-center gap-2 mb-2 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Algo salió mal:
                    </div>
                    <ul class="list-disc list-inside opacity-80 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.change.update') }}" class="space-y-6">
                @csrf
                @method('PATCH')

                <div>
                    <label for="current_password" class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Contraseña Actual</label>
                    <div class="relative">
                        <input id="current_password" name="current_password" type="password" required 
                            class="glass-input w-full p-3 pl-4 rounded-xl text-sm focus:ring-0" 
                            placeholder="••••••••" />
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Nueva Contraseña</label>
                    <div class="relative">
                        <input id="new_password" name="new_password" type="password" required 
                            class="glass-input w-full p-3 pl-4 rounded-xl text-sm focus:ring-0 border-primary/30" 
                            placeholder="Mínimo 8 caracteres" />
                    </div>
                    <p class="text-[10px] text-gray-500 mt-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Usa mayúsculas y números para mayor seguridad.
                    </p>
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-white transition-colors">Cancelar</a>
                    <button type="submit" class="bg-primary hover:bg-primaryDark text-dark font-bold py-3 px-8 rounded-xl shadow-neon transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>