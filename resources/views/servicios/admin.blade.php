<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Servicios | Valenzo's PC</title>
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
        .glass-input {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid #334155;
            color: white;
            transition: all 0.2s;
        }
        .glass-input:focus {
            border-color: #00D68F;
            background: rgba(30, 41, 59, 0.8);
            outline: none;
            box-shadow: 0 0 0 1px rgba(0, 214, 143, 0.3);
        }
        /* Ocultar scrollbar en modales */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col relative">

    <!-- NAVBAR -->
    <nav class="border-b border-gray-800 bg-dark/95 backdrop-blur sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded border border-primary flex items-center justify-center text-primary font-bold shadow-neon">V</div>
                    <span class="font-bold text-xl tracking-wide text-white">Valenzo's <span class="text-primary">PC</span></span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="hidden md:flex gap-4 text-sm font-medium text-gray-400 mr-4">
                        <a href="{{ route('productos.admin') }}" class="hover:text-primary transition-colors">Productos</a>
                        <a href="{{ route('servicios.admin') }}" class="text-primary">Servicios</a>
                    </div>
                    <div class="h-4 w-px bg-gray-700 hidden md:block"></div>
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Ir a Tienda</a>
                    <span class="text-xs font-mono text-primary bg-primary/10 px-2 py-1 rounded border border-primary/20">ADMIN</span>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-6 max-w-[1400px] mx-auto w-full">
        
        <!-- Header & Botón Crear -->
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-1">Servicios Técnicos</h1>
                <p class="text-gray-400 text-sm">Administra reparaciones, mantenimientos y garantías.</p>
            </div>
            @if($isAdmin)
            <button onclick="openModal('createModal')" 
                class="bg-primary hover:bg-primaryDark text-dark font-bold py-2.5 px-5 rounded-lg shadow-neon flex items-center gap-2 transition-transform hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Servicio
            </button>
            @endif
        </div>

        <!-- Alertas -->
        @if(session('status'))
            <div class="mb-6 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ session('status') }}
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
        @endif

        <!-- Filtros -->
        <div class="bg-card rounded-xl border border-gray-800 p-4 mb-6">
            <form method="GET" action="{{ route('servicios.admin') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <input type="text" name="search" placeholder="Buscar servicio..." value="{{ request('search') }}" class="glass-input w-full p-2 rounded-lg text-sm">
                <input type="text" name="type" placeholder="Tipo (ej. Reparación)" value="{{ request('type') }}" class="glass-input w-full p-2 rounded-lg text-sm">
                
                <select name="status" class="glass-input w-full p-2 rounded-lg text-sm">
                    <option value="" class="bg-dark text-gray-500">Estado</option>
                    <option value="active" class="bg-dark" @selected(request('status')==='active')>Activo</option>
                    <option value="inactive" class="bg-dark" @selected(request('status')==='inactive')>Inactivo</option>
                </select>

                <div class="flex gap-2 lg:col-span-1">
                    <select name="sort" class="glass-input w-full p-2 rounded-lg text-sm">
                        <option value="" class="bg-dark text-gray-500">Ordenar por</option>
                        <option value="price" class="bg-dark" @selected(request('sort')==='price')>Precio</option>
                        <option value="estimated_time" class="bg-dark" @selected(request('sort')==='estimated_time')>Tiempo</option>
                        <option value="name" class="bg-dark" @selected(request('sort')==='name')>Nombre</option>
                    </select>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex-grow text-sm transition font-medium">Filtrar</button>
                    <a href="{{ route('servicios.admin') }}" class="px-3 py-2 rounded-lg border border-gray-700 text-gray-400 hover:text-white text-sm transition flex items-center justify-center">X</a>
                </div>
            </form>
        </div>

        <!-- Tabla -->
        @php($items = $services['items'] ?? ($services['data'] ?? []))
        @php($current = $services['current_page'] ?? 1)
        @php($last = $services['last_page'] ?? 1)

        @if(isset($services['error']))
            <div class="text-center p-12 bg-card rounded-xl border border-red-900/30">
                <p class="text-red-400">{{ $services['error'] }}</p>
            </div>
        @else
        <div class="bg-card rounded-xl border border-gray-800 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-gray-900/60 text-xs uppercase font-semibold text-primary/80 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Servicio</th>
                            <th class="px-6 py-4">Precio / Tiempo</th>
                            <th class="px-6 py-4">Tipo</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($items as $s)
                        <tr class="hover:bg-gray-800/40 transition-colors group">
                            <td class="px-6 py-4 font-mono text-gray-600">#{{ $s['id'] ?? '-' }}</td>
                            
                            <!-- Info Servicio -->
                            <td class="px-6 py-4">
                                <div class="font-medium text-white text-base">{{ $s['name'] ?? $s['nombre'] ?? 'Sin Nombre' }}</div>
                                <div class="text-xs text-gray-500 truncate max-w-[250px] mt-1">{{ $s['description'] ?? '' }}</div>
                            </td>

                            <!-- Precio y Tiempo -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-emerald-400 font-mono font-bold text-base">
                                        @if(isset($s['price']) && $s['price'] > 0)
                                            ${{ number_format($s['price'], 2) }}
                                        @else
                                            <span class="text-gray-500 text-xs font-sans">A cotizar</span>
                                        @endif
                                    </span>
                                    @if(!empty($s['estimated_time']))
                                        <span class="text-xs text-gray-400 flex items-center gap-1 mt-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $s['estimated_time'] }} hrs
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="bg-gray-800 text-gray-300 px-2 py-1 rounded text-xs border border-gray-700">
                                    {{ $s['type'] ?? 'General' }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                @if(($s['status'] ?? '') === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-700 text-gray-400">
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                @if($isAdmin)
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Botón Editar (Abre Modal) -->
                                    <button 
                                        onclick="openEditModal(this)"
                                        data-id="{{ $s['id'] }}"
                                        data-name="{{ $s['name'] ?? $s['nombre'] ?? '' }}"
                                        data-price="{{ $s['price'] ?? '' }}"
                                        data-time="{{ $s['estimated_time'] ?? '' }}"
                                        data-type="{{ $s['type'] ?? '' }}"
                                        data-status="{{ $s['status'] ?? 'inactive' }}"
                                        data-description="{{ $s['description'] ?? '' }}"
                                        data-action="{{ route('servicios.admin.update', $s['id']) }}"
                                        class="p-2 rounded-lg hover:bg-blue-500/20 text-gray-500 hover:text-blue-400 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>

                                    <!-- Botón Eliminar -->
                                    <form method="POST" action="{{ route('servicios.admin.delete', $s['id']) }}" onsubmit="return confirm('¿Eliminar servicio definitivamente?');">
                                        @csrf
                                        <button type="submit" class="p-2 rounded-lg hover:bg-red-500/20 text-gray-500 hover:text-red-400 transition-colors" title="Eliminar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">No se encontraron servicios registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="bg-gray-900/40 px-6 py-4 border-t border-gray-800 flex justify-between items-center">
                <span class="text-xs text-gray-500">Pág {{ $current }} de {{ $last }}</span>
                <div class="flex gap-2">
                    @if($current > 1)
                        <a href="{{ route('servicios.admin', array_merge(request()->except('page'), ['page'=>$current-1])) }}" class="px-3 py-1 rounded bg-gray-800 hover:bg-gray-700 text-xs text-white transition">Anterior</a>
                    @endif
                    @if($current < $last)
                        <a href="{{ route('servicios.admin', array_merge(request()->except('page'), ['page'=>$current+1])) }}" class="px-3 py-1 rounded bg-gray-800 hover:bg-gray-700 text-xs text-white transition">Siguiente</a>
                    @endif
                </div>
            </div>
        </div>
        @endif

    </main>

    <!-- ================= MODAL CREAR ================= -->
    <div id="createModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('createModal')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-xl">
            <div class="bg-card border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span> Nuevo Servicio
                    </h3>
                    <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
                </div>
                <form method="POST" action="{{ route('servicios.admin.create') }}" class="p-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">Nombre del Servicio</label>
                            <input type="text" name="name" required class="glass-input w-full p-2.5 rounded-lg text-sm" placeholder="Ej: Mantenimiento Preventivo">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Precio ($)</label>
                            <input type="number" step="0.01" name="price" class="glass-input w-full p-2.5 rounded-lg text-sm" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Tiempo Est. (Horas)</label>
                            <input type="number" name="estimated_time" class="glass-input w-full p-2.5 rounded-lg text-sm" placeholder="Ej: 2">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Tipo</label>
                            <input type="text" name="type" class="glass-input w-full p-2.5 rounded-lg text-sm" placeholder="Ej: Software, Hardware...">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Estado</label>
                            <select name="status" class="glass-input w-full p-2.5 rounded-lg text-sm">
                                <option value="active" class="bg-dark">Activo</option>
                                <option value="inactive" class="bg-dark">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">Descripción</label>
                            <textarea name="description" rows="3" class="glass-input w-full p-2.5 rounded-lg text-sm resize-none" placeholder="Detalles de lo que incluye el servicio..."></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-800">
                        <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white text-sm transition">Cancelar</button>
                        <button type="submit" class="bg-primary hover:bg-primaryDark text-dark font-bold px-6 py-2 rounded-lg shadow-neon text-sm transition">Guardar Servicio</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL EDITAR ================= -->
    <div id="editModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-xl">
            <div class="bg-card border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Editar Servicio</h3>
                    <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
                </div>
                
                <form id="editForm" method="POST" action="" class="p-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">Nombre del Servicio</label>
                            <input type="text" id="edit_name" name="name" required class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Precio ($)</label>
                            <input type="number" step="0.01" id="edit_price" name="price" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Tiempo Est. (Horas)</label>
                            <input type="number" id="edit_time" name="estimated_time" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Tipo</label>
                            <input type="text" id="edit_type" name="type" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Estado</label>
                            <select id="edit_status" name="status" class="glass-input w-full p-2.5 rounded-lg text-sm">
                                <option value="active" class="bg-dark">Activo</option>
                                <option value="inactive" class="bg-dark">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">Descripción</label>
                            <textarea id="edit_description" name="description" rows="3" class="glass-input w-full p-2.5 rounded-lg text-sm resize-none"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-800">
                        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white text-sm transition">Cancelar</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-2 rounded-lg shadow-lg text-sm transition">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts Vanilla JS -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openEditModal(button) {
            const data = button.dataset;
            
            // Llenar inputs
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_price').value = data.price;
            document.getElementById('edit_time').value = data.time;
            document.getElementById('edit_type').value = data.type;
            document.getElementById('edit_status').value = data.status;
            document.getElementById('edit_description').value = data.description;

            // Actualizar action del formulario
            document.getElementById('editForm').action = data.action;

            openModal('editModal');
        }

        // Cerrar con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal('createModal');
                closeModal('editModal');
            }
        });
    </script>
</body>
</html>