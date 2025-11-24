<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | Valenzo's PC</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                        'neon': '0 0 20px rgba(0, 214, 143, 0.4)',
                        'input': '0 0 0 1px rgba(0, 214, 143, 0.3)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0B0E14; font-family: 'Inter', sans-serif; }
        .glass-card {
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
        /* Checkbox personalizado */
        .custom-checkbox:checked {
            background-color: #00D68F;
            border-color: #00D68F;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center relative overflow-hidden">

    <!-- Elementos de fondo decorativos -->
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-primary/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-4 animate-fade-in relative z-10">
        
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                <div class="w-10 h-10 rounded border border-primary flex items-center justify-center text-primary font-bold shadow-neon group-hover:scale-105 transition-transform">V</div>
                <span class="font-bold text-2xl tracking-wide text-white">Valenzo's <span class="text-primary">PC</span></span>
            </a>
        </div>

        <div class="glass-card rounded-2xl p-8 shadow-2xl">
            <h2 class="text-2xl font-bold text-white mb-2 text-center">Bienvenido de nuevo</h2>
            <p class="text-gray-400 text-sm text-center mb-8">Ingresa tus credenciales para acceder</p>

            <!-- Errores -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm flex gap-3 items-start">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-1.5 ml-1">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" required autofocus autocomplete="email" value="{{ old('email') }}" 
                            class="glass-input w-full pl-10 pr-4 py-3 rounded-xl text-sm placeholder-gray-600 focus:ring-0" 
                            placeholder="tu@email.com" />
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5 ml-1">
                        <label for="password" class="block text-sm font-medium text-gray-400">Contraseña</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-primary hover:text-white transition-colors">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="glass-input w-full pl-10 pr-4 py-3 rounded-xl text-sm placeholder-gray-600 focus:ring-0" 
                            placeholder="••••••••" />
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                        class="w-4 h-4 rounded bg-gray-800 border-gray-600 text-primary focus:ring-primary focus:ring-offset-gray-900 custom-checkbox transition">
                    <label for="remember" class="ml-2 block text-sm text-gray-400 select-none cursor-pointer">
                        Mantener sesión iniciada
                    </label>
                </div>

                <button type="submit" class="w-full bg-primary hover:bg-primaryDark text-dark font-bold py-3.5 rounded-xl shadow-neon transition-all hover:-translate-y-0.5 active:translate-y-0 text-sm uppercase tracking-wide">
                    Entrar al Sistema
                </button>
            </form>

            <div class="mt-8 text-center border-t border-gray-800 pt-6">
                <p class="text-gray-400 text-sm">
                    ¿Aún no tienes cuenta? 
                    <a href="{{ route('register') }}" class="text-white font-semibold hover:text-primary transition-colors ml-1">
                        Crear cuenta gratis
                    </a>
                </p>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-white text-sm transition-colors flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al inicio
            </a>
        </div>
    </div>
</body>
</html>