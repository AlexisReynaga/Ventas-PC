<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Productos | Valenzo's PC</title>
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
        /* Clases utilitarias para inputs estilo glass */
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

<x-navbar />

    <main class="flex-grow p-6 max-w-[1400px] mx-auto w-full">
        
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-1">Inventario</h1>
                <p class="text-gray-400 text-sm">Gestión completa de productos</p>
            </div>
            @if($isAdmin)
            <button onclick="openModal('createModal')" 
                class="bg-primary hover:bg-primaryDark text-dark font-bold py-2.5 px-5 rounded-lg shadow-neon flex items-center gap-2 transition-transform hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Producto
            </button>
            @endif
        </div>

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

        <div class="bg-card rounded-xl border border-gray-800 p-4 mb-6">
            <form method="GET" action="{{ route('productos.admin') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" class="glass-input w-full p-2 rounded-lg text-sm">
                <input type="text" name="category" placeholder="Categoría" value="{{ request('category') }}" class="glass-input w-full p-2 rounded-lg text-sm">
                <select name="status" class="glass-input w-full p-2 rounded-lg text-sm">
                    <option value="" class="bg-dark text-gray-500">Estado</option>
                    <option value="active" class="bg-dark" @selected(request('status')==='active')>Activo</option>
                    <option value="inactive" class="bg-dark" @selected(request('status')==='inactive')>Inactivo</option>
                </select>
                <select name="sort" class="glass-input w-full p-2 rounded-lg text-sm">
                    <option value="" class="bg-dark text-gray-500">Ordenar por</option>
                    <option value="price" class="bg-dark" @selected(request('sort')==='price')>Precio</option>
                    <option value="stock" class="bg-dark" @selected(request('sort')==='stock')>Stock</option>
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg flex-grow text-sm transition">Filtrar</button>
                    <a href="{{ route('productos.admin') }}" class="px-3 py-2 rounded-lg border border-gray-700 text-gray-400 hover:text-white text-sm transition flex items-center justify-center">X</a>
                </div>
            </form>
        </div>

        @php($items = $products['items'] ?? ($products['data'] ?? ($products['items'] ?? [])))
        @php($current = $products['current_page'] ?? 1)
        @php($last = $products['last_page'] ?? 1)

        <div class="bg-card rounded-xl border border-gray-800 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-gray-900/60 text-xs uppercase font-semibold text-primary/80 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Producto</th>
                            <th class="px-6 py-4">Categoría</th>
                            <th class="px-6 py-4">Costo</th>
                            <th class="px-6 py-4">Precio Público</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($items as $p)
                        <tr class="hover:bg-gray-800/40 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded bg-gray-800 flex-shrink-0 overflow-hidden border border-gray-700">
                                        @if(!empty($p['image_url']))
                                            <img src="{{ $p['image_url'] }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            <div class="flex items-center justify-center h-full text-xs text-gray-600">N/A</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $p['name'] ?? $p['nombre'] ?? 'Sin Nombre' }}</div>
                                        <div class="text-xs text-gray-500 truncate max-w-[200px]">{{ $p['description'] ?? '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">{{ $p['category'] ?? '-' }}</td>
                            @php($costo = (float)($p['cost_price'] ?? 0))
                            @php($publico = (float)($p['price'] ?? 0))
                            <td class="px-6 py-4 font-mono">
                                @if(!isset($p['cost_price']))
                                    <span class="text-gray-500 italic">N/D</span>
                                @else
                                    <span class="{{ $costo <= 0 ? 'text-gray-500' : 'text-gray-300' }}">${{ number_format($costo,2) }}</span>
                                    @if($costo > $publico && $publico > 0)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold bg-red-500/10 text-red-400 border border-red-500/30" title="El costo supera el precio público">Alerta</span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 font-mono font-medium">
                                <span class="text-emerald-400">${{ number_format($publico,2) }}</span>
                                @if($publico > 0 && $costo > 0 && $publico > $costo)
                                    @php($margenUnit = $publico - $costo)
                                    <span class="block text-[11px] text-cyan-300 mt-0.5" title="Margen unitario">+ ${{ number_format($margenUnit,2) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="{{ ($p['stock'] ?? 0) < 5 ? 'text-red-400' : 'text-gray-300' }}">
                                    {{ $p['stock'] ?? 0 }} u.
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if(($p['status'] ?? '') === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        Activo
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
                                    <button 
                                        onclick="openEditModal(this)"
                                        data-id="{{ $p['id'] }}"
                                        data-name="{{ $p['name'] ?? $p['nombre'] ?? '' }}"
                                        data-cost_price="{{ $p['cost_price'] ?? '' }}"
                                        data-price="{{ $p['price'] ?? '' }}"
                                        data-stock="{{ $p['stock'] ?? '' }}"
                                        data-category="{{ $p['category'] ?? '' }}"
                                        data-status="{{ $p['status'] ?? 'inactive' }}"
                                        data-image="{{ $p['image_url'] ?? '' }}"
                                        data-description="{{ $p['description'] ?? '' }}"
                                        data-action="{{ route('productos.admin.update', $p['id']) }}"
                                        class="p-2 rounded-lg hover:bg-blue-500/20 text-gray-500 hover:text-emerald-400 transition-colors" title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>

                                    <form method="POST" action="{{ route('productos.admin.delete', $p['id']) }}" onsubmit="return confirm('¿Eliminar producto definitivamente?');">
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
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">No se encontraron productos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-900/40 px-6 py-4 border-t border-gray-800 flex justify-between items-center">
                <span class="text-xs text-gray-500">Pág {{ $current }} de {{ $last }}</span>
                <div class="flex gap-2">
                    @if($current > 1)
                        <a href="{{ route('productos.admin', array_merge(request()->except('page'), ['page'=>$current-1])) }}" class="px-3 py-1 rounded bg-gray-800 hover:bg-gray-700 text-xs text-white transition">Anterior</a>
                    @endif
                    @if($current < $last)
                        <a href="{{ route('productos.admin', array_merge(request()->except('page'), ['page'=>$current+1])) }}" class="px-3 py-1 rounded bg-gray-800 hover:bg-gray-700 text-xs text-white transition">Siguiente</a>
                    @endif
                </div>
            </div>
        </div>

    </main>

    <div id="createModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('createModal')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl">
            <div class="bg-card border border-gray-700 rounded-xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Nuevo Producto</h3>
                    <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-white">&times;</button>
                </div>
                <form method="POST" action="{{ route('productos.admin.create') }}" class="p-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">Nombre</label>
                            <input type="text" name="name" required class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Precio de Costo ($)</label>
                            <input type="number" step="0.01" name="cost_price" required class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Precio Público ($)</label>
                            <input type="number" step="0.01" name="price" required class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Stock</label>
                            <input type="number" name="stock" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Categoría</label>
                            <input type="text" name="category" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Estado</label>
                            <select name="status" class="glass-input w-full p-2.5 rounded-lg text-sm">
                                <option value="active" class="bg-dark">Activo</option>
                                <option value="inactive" class="bg-dark">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">URL Imagen</label>
                            <input type="text" name="image_url" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">Descripción</label>
                            <textarea name="description" rows="3" class="glass-input w-full p-2.5 rounded-lg text-sm resize-none"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white text-sm transition">Cancelar</button>
                        <button type="submit" class="bg-primary hover:bg-primaryDark text-dark font-bold px-6 py-2 rounded-lg shadow-neon text-sm transition">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('editModal')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl">
            <div class="bg-card border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Editar Producto</h3>
                    <button onclick="closeModal('editModal')" class="text-gray-400 hover:text-white">&times;</button>
                </div>
                <form id="editForm" method="POST" action="" class="p-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">Nombre</label>
                            <input type="text" id="edit_name" name="name" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Precio de Costo ($)</label>
                            <input type="number" step="0.01" id="edit_cost_price" name="cost_price" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Precio Público ($)</label>
                            <input type="number" step="0.01" id="edit_price" name="price" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Stock</label>
                            <input type="number" id="edit_stock" name="stock" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Categoría</label>
                            <input type="text" id="edit_category" name="category" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 mb-1">Estado</label>
                            <select id="edit_status" name="status" class="glass-input w-full p-2.5 rounded-lg text-sm">
                                <option value="active" class="bg-dark">Activo</option>
                                <option value="inactive" class="bg-dark">Inactivo</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">URL Imagen</label>
                            <input type="text" id="edit_image" name="image_url" class="glass-input w-full p-2.5 rounded-lg text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-400 mb-1">Descripción</label>
                            <textarea id="edit_description" name="description" rows="3" class="glass-input w-full p-2.5 rounded-lg text-sm resize-none"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal('editModal')" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white text-sm transition">Cancelar</button>
                        <button type="submit" class="bg-emerald-400 hover:bg-emerald-500 text-white font-bold px-6 py-2 rounded-lg shadow-lg text-sm transition">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Función para poblar el modal de edición
        function openEditModal(button) {
            // Leer datos del botón (data attributes)
            const data = button.dataset;
            
            // Llenar inputs
            document.getElementById('edit_name').value = data.name;
            document.getElementById('edit_cost_price').value = data.cost_price;
            document.getElementById('edit_price').value = data.price;
            document.getElementById('edit_stock').value = data.stock;
            document.getElementById('edit_category').value = data.category;
            document.getElementById('edit_status').value = data.status;
            document.getElementById('edit_image').value = data.image;
            document.getElementById('edit_description').value = data.description;

            // Actualizar action del formulario
            document.getElementById('editForm').action = data.action;

            // Mostrar modal
            openModal('editModal');
        }

        // Cerrar modales con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal('createModal');
                closeModal('editModal');
            }
        });
    </script>
</body>
</html>