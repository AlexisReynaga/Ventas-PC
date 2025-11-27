<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Ticket {{ $ticket['ticket_id'] }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; margin: 20px; }
        h1 { margin: 0 0 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #444; padding: 5px; text-align: left; }
        th { background: #eee; }
        .totales td { font-weight: bold; }
        .right { text-align: right; }
        .small { font-size: 11px; color: #555; }
    </style>
</head>
<body>
    <h1>Ticket de Compra</h1>
    <p><strong>ID Ticket:</strong> {{ $ticket['ticket_id'] }}<br>
       <strong>Fecha:</strong> {{ $ticket['fecha'] }}<br>
       <strong>Usuario:</strong> {{ $ticket['user']->name ?? 'Invitado' }}
    </p>

    <h2>Productos</h2>
    @php($totalProductos = 0)
    @if(empty($ticket['items']['products']))
        <p class="small">Sin productos.</p>
    @else
    <table>
        <thead><tr><th>Nombre</th><th>Cant.</th><th>Precio</th><th>Subtotal</th></tr></thead>
        <tbody>
        @foreach($ticket['items']['products'] as $p)
            @php($sub = ($p['price'] ?? 0) * ($p['quantity'] ?? 1))
            @php($totalProductos += $sub)
            <tr>
                <td>{{ $p['name'] }}</td>
                <td>{{ $p['quantity'] ?? 1 }}</td>
                <td class="right">$ {{ number_format($p['price'] ?? 0,2) }}</td>
                <td class="right">$ {{ number_format($sub,2) }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot><tr class="totales"><td colspan="3" class="right">Total Productos</td><td class="right">$ {{ number_format($totalProductos,2) }}</td></tr></tfoot>
    </table>
    @endif

    <h2>Servicios</h2>
    @php($totalServicios = 0)
    @if(empty($ticket['items']['services']))
        <p class="small">Sin servicios.</p>
    @else
    <table>
        <thead><tr><th>Nombre</th><th>Precio</th><th>Fecha</th><th>Hora</th></tr></thead>
        <tbody>
        @foreach($ticket['items']['services'] as $s)
            @php($totalServicios += ($s['price'] ?? 0))
            <tr>
                <td>{{ $s['name'] }}</td>
                <td class="right">$ {{ number_format($s['price'] ?? 0,2) }}</td>
                <td>{{ $s['scheduled_date'] ?? '-' }}</td>
                <td>{{ $s['scheduled_time'] ?? '-' }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot><tr class="totales"><td colspan="3" class="right">Total Servicios</td><td class="right">$ {{ number_format($totalServicios,2) }}</td></tr></tfoot>
    </table>
    @endif

    @php($granTotal = $ticket['total'])
    <table>
        <tr class="totales"><td class="right">Gran Total</td><td class="right">$ {{ number_format($granTotal,2) }}</td></tr>
    </table>
    <p class="small">Generado automáticamente | {{ date('Y-m-d H:i:s') }}</p>
</body>
</html>