<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | Ventas PC</title>
    
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
                        secondary: '#1E293B',
                    },
                    boxShadow: {
                        'neon': '0 0 20px rgba(0, 214, 143, 0.25)',
                        'glow': '0 0 10px rgba(0, 214, 143, 0.5)',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0B0E14; color: #e2e8f0; font-family: 'Inter', sans-serif; }
        .dropdown-enter {
            animation: slideDown 0.2s ease-out forwards;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-content {
            transition: opacity 0.5s ease-in-out, transform 0.5s ease-in-out;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
</head>
<body class="antialiased selection:bg-primary selection:text-dark">

    <x-navbar />

    @php($isAdmin = Auth::check() && session('api_user_role') === 'admin')

    <section class="relative overflow-hidden bg-dark min-h-[calc(100vh-80px)] flex items-center">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[500px] bg-primary/20 rounded-full blur-[120px] opacity-20 pointer-events-none"></div>
        
        <div class="w-full max-w-[1400px] mx-auto px-4 py-12 md:py-20 flex flex-col md:flex-row items-center gap-16 relative z-10">
            
            <div class="w-full md:w-1/2 space-y-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-primary/30 bg-primary/5 text-primary text-xs font-bold tracking-wider uppercase">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    Venta y Reparación
                </div>
                
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-[1.1]">
                    Tecnología que <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-emerald-600 drop-shadow-lg">Impulsa tu Mundo</span>
                </h1>
                
                <p class="text-gray-400 text-lg md:text-xl max-w-xl leading-relaxed">
                    Computadoras industriales seminuevas y componentes de alto rendimiento en San Luis Potosí. Calidad premium garantizada.
                </p>

                <div class="flex flex-wrap gap-4 pt-4">
                    @if($isAdmin)
                         <a href="{{ route('productos.admin') }}" class="px-8 py-4 rounded-xl bg-primary hover:bg-primaryDark text-dark font-bold text-lg shadow-neon hover:shadow-glow transition-all transform hover:-translate-y-1">
                            Gestionar Inventario
                        </a>
                    @else
                        <a href="{{ route('productos.index') }}" class="px-8 py-4 rounded-xl bg-primary hover:bg-primaryDark text-dark font-bold text-lg shadow-neon hover:shadow-glow transition-all transform hover:-translate-y-1">
                            Ver Catálogo
                        </a>
                        <a href="{{ route('servicios.index') }}" class="px-8 py-4 rounded-xl border border-gray-700 hover:border-primary text-gray-300 hover:text-primary font-bold text-lg hover:bg-primary/5 transition-all">
                            Servicios
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-6 pt-6 text-sm text-gray-500 font-medium">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Garantía en equipos
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Atención en SLP
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/2 flex justify-center relative">
                <div class="absolute inset-0 bg-gradient-to-r from-primary to-blue-500 rounded-[3rem] blur-3xl opacity-20 -z-10 transform rotate-6 scale-90"></div>
                
                <div class="bg-card/80 backdrop-blur-xl border border-white/10 p-2 rounded-[2.5rem] shadow-2xl w-full max-w-md transform rotate-[-3deg] hover:rotate-0 transition-transform duration-500">
                    <div class="bg-dark rounded-[2rem] overflow-hidden relative min-h-[400px] flex items-center justify-center border border-gray-800 group">
                        
                        <div id="slide-0" class="slide-content absolute inset-0 flex flex-col items-center justify-center p-8 opacity-100 pointer-events-auto">
                            <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-t from-gray-800 to-gray-700 flex items-center justify-center border-2 border-primary shadow-[0_0_30px_rgba(0,214,143,0.3)] transform group-hover:scale-110 transition-transform duration-500">
                                <span class="text-5xl">🎮</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Setup Gamer</h3>
                            <p class="text-gray-400 text-sm text-center">Rendimiento extremo para tus juegos favoritos. FPS altos y gráficos ultra.</p>
                        </div>

                        <div id="slide-1" class="slide-content absolute inset-0 flex flex-col items-center justify-center p-8 opacity-0 pointer-events-none transform translate-x-10">
                            <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-t from-gray-800 to-gray-700 flex items-center justify-center border-2 border-blue-500 shadow-[0_0_30px_rgba(59,130,246,0.3)] transform group-hover:scale-110 transition-transform duration-500">
                                <span class="text-5xl">🚀</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Workstation</h3>
                            <p class="text-gray-400 text-sm text-center">Potencia para renderizado, edición de video y diseño 3D profesional.</p>
                        </div>

                        <div id="slide-2" class="slide-content absolute inset-0 flex flex-col items-center justify-center p-8 opacity-0 pointer-events-none transform translate-x-10">
                            <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-gradient-to-t from-gray-800 to-gray-700 flex items-center justify-center border-2 border-purple-500 shadow-[0_0_30px_rgba(168,85,247,0.3)] transform group-hover:scale-110 transition-transform duration-500">
                                <span class="text-5xl">🎙️</span>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Streaming</h3>
                            <p class="text-gray-400 text-sm text-center">Transmite en alta calidad sin lag. Configuración optimizada para creadores.</p>
                        </div>
                        
                        <div class="absolute bottom-8 left-0 right-0 flex justify-center gap-3 z-20">
                            <button onclick="manualSlide(0)" id="dot-0" class="w-8 h-2 rounded-full bg-primary transition-all duration-300 hover:bg-white"></button>
                            <button onclick="manualSlide(1)" id="dot-1" class="w-2 h-2 rounded-full bg-gray-600 transition-all duration-300 hover:bg-white"></button>
                            <button onclick="manualSlide(2)" id="dot-2" class="w-2 h-2 rounded-full bg-gray-600 transition-all duration-300 hover:bg-white"></button>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

    <script>
        let currentSlide = 0;
        const totalSlides = 3;
        let slideInterval;

        function showSlide(index) {
            // Ocultar todos
            for (let i = 0; i < totalSlides; i++) {
                const slide = document.getElementById(`slide-${i}`);
                const dot = document.getElementById(`dot-${i}`);
                
                if (slide) {
                    slide.classList.remove('opacity-100', 'pointer-events-auto', 'translate-x-0');
                    slide.classList.add('opacity-0', 'pointer-events-none', 'translate-x-10'); // Efecto salida
                }
                
                if (dot) {
                    dot.classList.remove('w-8', 'bg-primary');
                    dot.classList.add('w-2', 'bg-gray-600');
                }
            }

            const activeSlide = document.getElementById(`slide-${index}`);
            const activeDot = document.getElementById(`dot-${index}`);

            if (activeSlide) {
                activeSlide.classList.remove('opacity-0', 'pointer-events-none', 'translate-x-10');
                activeSlide.classList.add('opacity-100', 'pointer-events-auto', 'translate-x-0');
            }

            if (activeDot) {
                activeDot.classList.remove('w-2', 'bg-gray-600');
                activeDot.classList.add('w-8', 'bg-primary');
            }

            currentSlide = index;
        }

        function nextSlide() {
            let next = (currentSlide + 1) % totalSlides;
            showSlide(next);
        }

        function manualSlide(index) {
            clearInterval(slideInterval); 
            showSlide(index);
            startAutoPlay(); 
        }

        function startAutoPlay() {
            slideInterval = setInterval(nextSlide, 4000); 
        }

        // Iniciar al cargar
        document.addEventListener('DOMContentLoaded', () => {
            startAutoPlay();
        });
    </script>

</body>
</html>