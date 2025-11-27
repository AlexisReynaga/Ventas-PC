<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Ticket</title></head>
<body>
<h1>Ticket de Compra</h1>
<p><a href="{{ route('home') }}">Inicio</a></p>
@if(isset($ticket))
<p>ID Ticket: {{ $ticket['ticket_id'] }}</p>
<p>Fecha: {{ $ticket['fecha'] }}</p>
<h2>Items</h2>
<h3>Productos</h3>
@if(empty($ticket['items']['products']))<p>Sin productos.</p>@else
<ul>@foreach($ticket['items']['products'] as $p)<li>{{ $p['name'] }} - {{ $p['price'] }}</li>@endforeach</ul>
@endif
<h3>Servicios</h3>
@if(empty($ticket['items']['services']))<p>Sin servicios.</p>@else
<ul>@foreach($ticket['items']['services'] as $s)<li>{{ $s['name'] }} - {{ $s['price'] }}</li>@endforeach</ul>
@endif
<h2>Total: {{ $ticket['total'] }}</h2>
@else
<p>No hay ticket generado.</p>
@endif
</body>
</html>