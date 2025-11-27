<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Compras | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0B0E14] text-gray-200 min-h-screen">
<x-navbar />
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 mt-8 min-h-[100vh] flex flex-col">
    <h1 class="text-3xl font-bold mb-6">Compras Realizadas</h1>
    <div class="overflow-x-auto">
    <table class="min-w-full text-sm border border-gray-800 rounded">
        <thead class="bg-gray-800 text-gray-300">
            <tr>
                <th class="p-3 text-left">ID</th>
                <th class="p-3 text-left">Ticket</th>
                <th class="p-3 text-left">Usuario</th>
                <th class="p-3 text-left">Items</th>
                <th class="p-3 text-left">Total</th>
                <th class="p-3 text-left">Fecha</th>
                <th class="p-3 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchases as $purchase)
            <tr class="border-t border-gray-800 hover:bg-gray-900/60">
                <td class="p-3 font-mono">{{ $purchase->id }}</td>
                <td class="p-3 font-mono text-emerald-400 break-all">{{ $purchase->ticket_id }}</td>
                <td class="p-3">{{ optional($purchase->user)->name ?? 'Invitado' }}</td>
                <td class="p-3">{{ count($purchase->items['products'] ?? []) + count($purchase->items['services'] ?? []) }}</td>
                <td class="p-3 font-semibold">${{ number_format($purchase->total,2) }}</td>
                <td class="p-3 text-xs text-gray-400 whitespace-nowrap">{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                <td class="p-3 space-x-3">
                    <a href="{{ route('admin.compras.show', $purchase->id) }}" class="text-emerald-400 hover:underline">Ver</a>
                    <a href="{{ route('admin.compras.ticket', $purchase->id) }}" class="text-cyan-400 hover:underline" title="Reimprimir Ticket">Ticket</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="p-4 text-center text-gray-500">Sin compras registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="mt-4">
        {{ $purchases->links() }}
    </div>
</div>
</body>
</html>