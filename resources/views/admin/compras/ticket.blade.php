<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticket {{ $ticket['ticket_id'] }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; color:#000; }
        }
    </style>
</head>
<body class="bg-[#0B0E14] text-gray-200 min-h-screen">
<div class="max-w-xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6 no-print">
        <a href="{{ route('admin.compras.show',$purchase->id) }}" class="text-sm text-gray-400 hover:text-white">&larr; Volver</a>
        <button onclick="window.print()" class="px-4 py-2 rounded bg-emerald-500 hover:bg-emerald-600 text-[#0B0E14] font-semibold text-sm">Imprimir</button>
    </div>
    <div class="bg-[#151A23] border border-gray-800 rounded-lg p-5 mb-6">
        <h1 class="text-xl font-bold mb-2">Ticket de Compra</h1>
        <p class="text-xs text-gray-400">ID Ticket:</p>
        <p class="font-mono text-emerald-400 mb-3 break-all">{{ $ticket['ticket_id'] }}</p>
        <p class="text-xs text-gray-400">Fecha:</p>
        <p class="mb-3">{{ $ticket['fecha'] }}</p>
        <p class="text-xs text-gray-400">Usuario:</p>
        <p class="mb-3">{{ $ticket['user']->name ?? 'Invitado' }}</p>
        <p class="text-xs text-gray-400">Total:</p>
        <p class="text-2xl font-bold mb-4">${{ number_format($ticket['total'],2) }}</p>
    </div>
    <h2 class="text-lg font-semibold mb-2">Detalle Productos</h2>
    <table class="w-full text-sm mb-6 border border-gray-800 rounded">
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
        @forelse($ticket['items']['products'] ?? [] as $p)
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
        <tfoot class="bg-gray-900">
            <tr>
                <td colspan="3" class="p-2 text-right font-semibold">Total Productos</td>
                <td class="p-2 font-semibold">${{ number_format($totalProductos,2) }}</td>
            </tr>
        </tfoot>
    </table>

    <h2 class="text-lg font-semibold mb-2">Detalle Servicios</h2>
    <table class="w-full text-sm mb-6 border border-gray-800 rounded">
        <thead class="bg-gray-800 text-gray-300">
            <tr>
                <th class="p-2 text-left">Nombre</th>
                <th class="p-2 text-left">Precio</th>
                <th class="p-2 text-left">Fecha</th>
                <th class="p-2 text-left">Hora</th>
            </tr>
        </thead>
        <tbody>
        @php($totalServicios=0)
        @forelse($ticket['items']['services'] ?? [] as $s)
            @php($totalServicios += ($s['price'] ?? 0))
            <tr class="border-t border-gray-800">
                <td class="p-2">{{ $s['name'] }}</td>
                <td class="p-2">${{ number_format($s['price'] ?? 0,2) }}</td>
                <td class="p-2">{{ $s['scheduled_date'] ?? '-' }}</td>
                <td class="p-2">{{ $s['scheduled_time'] ?? '-' }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="p-3 text-center text-gray-500">Sin servicios.</td></tr>
        @endforelse
        </tbody>
        <tfoot class="bg-gray-900">
            <tr>
                <td colspan="3" class="p-2 text-right font-semibold">Total Servicios</td>
                <td class="p-2 font-semibold">${{ number_format($totalServicios,2) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="p-2 text-right font-bold">Gran Total</td>
                <td class="p-2 font-bold">${{ number_format($ticket['total'],2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="text-center text-xs text-gray-500 mt-8">
        <p>Reimpresión oficial - {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</div>
</body>
</html>