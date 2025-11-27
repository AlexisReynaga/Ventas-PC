<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detalle Compra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0B0E14] text-gray-200 min-h-screen">
<x-navbar />
<div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 mt-8 min-h-[100vh] flex flex-col">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Compra #{{ $purchase->id }}</h1>
        <div class="flex gap-3">
            <a href="{{ route('admin.compras.ticket',$purchase->id) }}" class="px-4 py-2 rounded bg-cyan-500 hover:bg-cyan-600 text-[#0B0E14] font-semibold text-sm">Ver Ticket</a>
            <button onclick="window.print()" class="px-4 py-2 rounded bg-emerald-500 hover:bg-emerald-600 text-[#0B0E14] font-semibold text-sm">Imprimir Detalle</button>
        </div>
    </div>

    <div class="bg-[#151A23] border border-gray-800 rounded-lg p-5 mb-6">
        <p class="text-sm text-gray-400">Ticket ID:</p>
        <p class="font-mono text-emerald-400 mb-4">{{ $purchase->ticket_id }}</p>
        <p class="text-sm text-gray-400">Usuario:</p>
        <p class="mb-4">{{ optional($purchase->user)->name ?? 'Invitado' }}</p>
        <p class="text-sm text-gray-400">Fecha:</p>
        <p class="mb-4">{{ $purchase->created_at->format('Y-m-d H:i:s') }}</p>
        <p class="text-sm text-gray-400">Total:</p>
        <p class="text-xl font-bold">${{ number_format($purchase->total,2) }}</p>
    </div>

    <h2 class="text-xl font-bold mb-3">Productos</h2>
    <div class="overflow-x-auto">
    <table class="min-w-full text-sm mb-8 border border-gray-800 rounded">
        <thead class="bg-gray-800 text-gray-300">
            <tr>
                <th class="p-2 text-left">Nombre</th>
                <th class="p-2 text-left">Cant.</th>
                <th class="p-2 text-left">Precio</th>
                <th class="p-2 text-left">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php($totalProductos=0)
            @forelse($purchase->items['products'] ?? [] as $p)
            @php($sub = ($p['price'] ?? 0) * ($p['quantity'] ?? 1))
            @php($totalProductos += $sub)
            <tr class="border-t border-gray-800">
                <td class="p-2">{{ $p['name'] }}</td>
                <td class="p-2">{{ $p['quantity'] ?? 1 }}</td>
                <td class="p-2">${{ number_format($p['price'] ?? 0,2) }}</td>
                <td class="p-2">${{ number_format($sub,2) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-3 text-center text-gray-500">Sin productos.</td></tr>
            @endforelse
        </tbody>
        @if(($purchase->items['products'] ?? []) )
        <tfoot class="bg-gray-900">
            <tr>
                <td colspan="3" class="p-2 text-right font-semibold">Total Productos</td>
                <td class="p-2 font-semibold">${{ number_format($totalProductos,2) }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
    </div>

    <h2 class="text-xl font-bold mb-3">Servicios</h2>
    <div class="overflow-x-auto">
    <table class="min-w-full text-sm mb-8 border border-gray-800 rounded">
        <thead class="bg-gray-800 text-gray-300">
            <tr>
                <th class="p-2 text-left">Nombre</th>
                <th class="p-2 text-left">Precio</th>
                <th class="p-2 text-left">Fecha Programada</th>
                <th class="p-2 text-left">Hora</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchase->items['services'] ?? [] as $s)
            <tr class="border-t border-gray-800">
                <td class="p-2">{{ $s['name'] }}</td>
                <td class="p-2">${{ number_format($s['price'],2) }}</td>
                <td class="p-2">{{ $s['scheduled_date'] ?? '-' }}</td>
                <td class="p-2">{{ $s['scheduled_time'] ?? '-' }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-3 text-center text-gray-500">Sin servicios.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>

    <div class="flex justify-between items-center">
        <a href="{{ route('admin.compras') }}" class="text-sm text-gray-400 hover:text-white">&larr; Volver al listado</a>
        <a href="{{ route('admin.compras.ticket',$purchase->id) }}" class="text-sm text-cyan-400 hover:text-white" title="Reimprimir Ticket">Reimprimir Ticket</a>
    </div>
</div>
</body>
</html>