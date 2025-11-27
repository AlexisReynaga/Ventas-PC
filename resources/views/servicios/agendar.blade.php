<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agendar Servicio</title>
    
</head>
<body>
<h1>Agendar Servicio</h1>
<p><a href="{{ route('home') }}">Inicio</a> | <a href="{{ route('servicios.index') }}">Volver a servicios</a></p>
<hr>
<p>Servicio: <strong>{{ $service['name'] ?? 'Servicio' }}</strong></p>
<p>Precio: ${{ number_format($service['price'] ?? 0,2) }}</p>
<form method="POST" action="{{ route('servicios.schedule.store', $service['id'] ?? 0) }}">
    @csrf
    <label>Fecha</label>
    <input type="date" name="date" required min="{{ date('Y-m-d') }}">
    @error('date')<p style="color:red">{{ $message }}</p>@enderror
    <br>
    <label>Hora</label>
    <input type="time" name="time" required>
    @error('time')<p style="color:red">{{ $message }}</p>@enderror
    <br><br>
    <button type="submit">Agregar al Carrito</button>
    <a href="{{ route('servicios.index') }}">Cancelar</a>
</form>
</body>
</html>