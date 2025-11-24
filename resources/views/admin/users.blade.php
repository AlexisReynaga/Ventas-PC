<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Usuarios | Valenzo's PC</title>
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
            background: rgba(11, 14, 20, 0.9);
            outline: none;
            box-shadow: 0 0 0 1px rgba(0, 214, 143, 0.3);
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased min-h-screen flex flex-col relative">

    <!-- NAVBAR -->
<x-navbar />

    <main class="flex-grow p-6 max-w-[1200px] mx-auto w-full">
        
        <!-- Header & Botón Crear -->
        <div class="flex flex-col sm:flex-row justify-between items-end mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-1">Usuarios del Sistema</h1>
                <p class="text-gray-400 text-sm">Gestiona accesos y roles de administradores y clientes.</p>
            </div>
            <button onclick="openModal('createModal')" 
                class="bg-primary hover:bg-primaryDark text-dark font-bold py-2.5 px-5 rounded-lg shadow-neon flex items-center gap-2 transition-transform hover:-translate-y-0.5 active:translate-y-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Crear Usuario
            </button>
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

        <!-- Tabla de Usuarios -->
        <div class="bg-card rounded-xl border border-gray-800 overflow-hidden shadow-xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-400">
                    <thead class="bg-gray-900/60 text-xs uppercase font-semibold text-primary/80 border-b border-gray-800">
                        <tr>
                            <th class="px-6 py-4">Usuario</th>
                            <th class="px-6 py-4">Rol Actual</th>
                            <th class="px-6 py-4">ID Sistema</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @forelse($users as $u)
                        <tr class="hover:bg-gray-800/40 transition-colors group">
                            <!-- Info Usuario -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 border border-gray-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($u['name'] ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-white text-base">{{ $u['name'] ?? 'Sin Nombre' }}</div>
                                        <div class="text-xs text-gray-500">{{ $u['email'] ?? 'No Email' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Rol -->
                            <td class="px-6 py-4">
                                @if(($u['role'] ?? '') === 'admin')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-bold bg-primary/10 text-primary border border-primary/20 uppercase tracking-wide">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-800 text-gray-400 border border-gray-700 uppercase tracking-wide">
                                        Customer
                                    </span>
                                @endif
                            </td>

                            <!-- ID -->
                            <td class="px-6 py-4 font-mono text-xs">
                                <span class="bg-gray-900 px-2 py-1 rounded text-gray-500">ID: {{ $u['id'] ?? '-' }}</span>
                            </td>

                            <!-- Acciones -->
                            <td class="px-6 py-4 text-right">
                                <button 
                                    onclick="openRoleModal('{{ $u['id'] }}', '{{ $u['name'] }}', '{{ $u['role'] }}', '{{ route('admin.users.role', $u['id']) }}')"
                                    class="text-gray-400 hover:text-white hover:bg-gray-700 px-3 py-1.5 rounded-lg text-xs transition-colors flex items-center gap-2 ml-auto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Editar Rol
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p>No se encontraron usuarios.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- ================= MODAL CREAR USUARIO ================= -->
    <div id="createModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('createModal')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
            <div class="bg-card border border-gray-700 rounded-xl shadow-2xl overflow-hidden animate-fade-in-up">
                <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span> Nuevo Usuario
                    </h3>
                    <button onclick="closeModal('createModal')" class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
                </div>
                <form method="POST" action="{{ route('admin.users.create') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Nombre Completo</label>
                        <input type="text" name="name" required class="glass-input w-full p-2.5 rounded-lg text-sm" placeholder="Ej: Juan Pérez">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Correo Electrónico</label>
                        <input type="email" name="email" required class="glass-input w-full p-2.5 rounded-lg text-sm" placeholder="usuario@email.com">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Contraseña</label>
                        <input type="password" name="password" required class="glass-input w-full p-2.5 rounded-lg text-sm" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Rol Inicial</label>
                        <select name="role" class="glass-input w-full p-2.5 rounded-lg text-sm">
                            <option value="customer" class="bg-dark">Customer (Cliente)</option>
                            <option value="admin" class="bg-dark">Admin</option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-gray-800 mt-2">
                        <button type="button" onclick="closeModal('createModal')" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white text-sm transition">Cancelar</button>
                        <button type="submit" class="bg-primary hover:bg-primaryDark text-dark font-bold px-6 py-2 rounded-lg shadow-neon text-sm transition">Crear Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ================= MODAL EDITAR ROL ================= -->
    <div id="roleModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('roleModal')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-sm">
            <div class="bg-card border border-gray-700 rounded-xl shadow-2xl overflow-hidden">
                <div class="bg-gray-900/50 px-6 py-4 border-b border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-white">Cambiar Rol</h3>
                    <button onclick="closeModal('roleModal')" class="text-gray-400 hover:text-white text-2xl leading-none">&times;</button>
                </div>
                
                <form id="roleForm" method="POST" action="" class="p-6">
                    @csrf
                    <p class="text-gray-400 text-sm mb-4">Editando permisos para: <span id="roleUserName" class="text-white font-bold"></span></p>
                    
                    <div class="mb-6">
                        <label class="block text-xs text-gray-400 mb-1">Nuevo Rol</label>
                        <select name="role" id="roleSelect" class="glass-input w-full p-3 rounded-lg text-sm">
                            <option value="customer" class="bg-dark">Customer (Cliente)</option>
                            <option value="admin" class="bg-dark">Admin</option>
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('roleModal')" class="px-4 py-2 rounded-lg text-gray-400 hover:text-white text-sm transition">Cancelar</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold px-6 py-2 rounded-lg shadow-lg text-sm transition">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openRoleModal(userId, userName, currentRole, actionUrl) {
            document.getElementById('roleUserName').innerText = userName;
            document.getElementById('roleSelect').value = currentRole;
            document.getElementById('roleForm').action = actionUrl;
            openModal('roleModal');
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal('createModal');
                closeModal('roleModal');
            }
        });
    </script>
</body>
</html>