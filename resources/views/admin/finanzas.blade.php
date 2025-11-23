<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Finanzas</title>
</head>
<body>
<h1>Finanzas</h1>
<p>Resumen inventario y servicios</p>
<h2>Productos</h2>
<ul>
    <li>Total: {{ $productsSummary['total'] }}</li>
    <li>Activos: {{ $productsSummary['active'] }}</li>
    <li>Stock total: {{ $productsSummary['stock_total'] }}</li>
    <li>Valor inventario aprox: $ {{ number_format($productsSummary['valor_inventario'],2) }}</li>
</ul>
<h2>Servicios</h2>
<ul>
    <li>Total: {{ $servicesSummary['total'] }}</li>
    <li>Activos: {{ $servicesSummary['active'] }}</li>
    <li>Suma precios: $ {{ number_format($servicesSummary['valor_servicios'],2) }}</li>
</ul>
<p><a href="{{ route('home') }}">Inicio</a></p>
</body>
</html>
