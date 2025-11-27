<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Detalle Compra</title></head>
<body>
<h1>Detalle de la Compra</h1>
<p><a href="{{ route('carrito.index') }}">Volver al carrito</a></p>
@php($cart = $cart ?? ['products'=>[], 'services'=>[], 'total'=>0])
<h2>Resumen</h2>
<h3>Productos</h3>
@if(empty($cart['products']))<p>Sin productos.</p>@else
<ul>@foreach($cart['products'] as $p)<li>{{ $p['name'] }} - {{ $p['price'] }}</li>@endforeach</ul>
@endif
<h3>Servicios</h3>
@if(empty($cart['services']))<p>Sin servicios.</p>@else
<ul>@foreach($cart['services'] as $s)<li>{{ $s['name'] }} - {{ $s['price'] }}</li>@endforeach</ul>
@endif
<h2>Total: {{ $cart['total'] }}</h2>
<form method="POST" action="{{ route('carrito.ticket') }}">@csrf <button type="submit">Generar Ticket (PDF)</button></form>
</body>
</html>