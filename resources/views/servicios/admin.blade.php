<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Servicios</title>
</head>
<body>
<h1>Admin Servicios</h1>
@if(session('status'))<p>{{ session('status') }}</p>@endif
@if($errors->any())<ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<p><a href="{{ route('home') }}">Inicio</a></p>
@php($items = $services['items'] ?? ($services['data'] ?? []))
@php($current = $services['current_page'] ?? 1)
@php($last = $services['last_page'] ?? 1)
<h2>Filtros</h2>
<form method="GET" action="{{ route('servicios.admin') }}">
    <input type="text" name="search" placeholder="Buscar" value="{{ request('search') }}" />
    <input type="text" name="type" placeholder="Tipo" value="{{ request('type') }}" />
    <select name="status">
        <option value="">--status--</option>
        <option value="active" @selected(request('status')==='active')>active</option>
        <option value="inactive" @selected(request('status')==='inactive')>inactive</option>
    </select>
    <select name="sort">
        <option value="">--ordenar por--</option>
        <option value="name" @selected(request('sort')==='name')>name</option>
        <option value="price" @selected(request('sort')==='price')>price</option>
        <option value="estimated_time" @selected(request('sort')==='estimated_time')>estimated_time</option>
        <option value="type" @selected(request('sort')==='type')>type</option>
    </select>
    <select name="direction">
        <option value="">--dir--</option>
        <option value="asc" @selected(request('direction')==='asc')>asc</option>
        <option value="desc" @selected(request('direction')==='desc')>desc</option>
    </select>
    <input type="number" name="per_page" placeholder="Por página" value="{{ request('per_page') }}" />
    <button type="submit">Aplicar</button>
    <a href="{{ route('servicios.admin') }}">Limpiar</a>
</form>
<h2>Listado</h2>
@if(isset($services['error']))
    <p>{{ $services['error'] }}</p>
@else
<table border="1" cellpadding="4">
    <thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Tiempo</th><th>Tipo</th><th>Status</th><th>Acciones</th></tr></thead>
    <tbody>
    @foreach($items as $s)
        <tr>
            <td>{{ $s['id'] ?? '-' }}</td>
            <td>{{ $s['name'] ?? $s['nombre'] ?? '-' }}</td>
            <td>{{ $s['price'] ?? '' }}</td>
            <td>{{ $s['estimated_time'] ?? '' }}</td>
            <td>{{ $s['type'] ?? '' }}</td>
            <td>{{ $s['status'] ?? '' }}</td>
            <td>
                @if($isAdmin)
                <form method="POST" action="{{ route('servicios.admin.update', $s['id']) }}">
                    @csrf
                    <input type="text" name="name" value="{{ $s['name'] ?? '' }}" placeholder="Nombre" />
                    <input type="number" step="0.01" name="price" value="{{ $s['price'] ?? '' }}" placeholder="Precio" />
                    <input type="number" name="estimated_time" value="{{ $s['estimated_time'] ?? '' }}" placeholder="Tiempo" />
                    <input type="text" name="type" value="{{ $s['type'] ?? '' }}" placeholder="Tipo" />
                    <select name="status">
                        <option value="" @selected(empty($s['status']))>--</option>
                        <option value="active" @selected(($s['status'] ?? '')==='active')>active</option>
                        <option value="inactive" @selected(($s['status'] ?? '')==='inactive')>inactive</option>
                    </select>
                    <textarea name="description" placeholder="Descripción">{{ $s['description'] ?? '' }}</textarea>
                    <button type="submit">Actualizar</button>
                </form>
                <form method="POST" action="{{ route('servicios.admin.delete', $s['id']) }}" onsubmit="return confirm('Eliminar?')">
                    @csrf
                    <button type="submit">Eliminar</button>
                </form>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
<p>Página {{ $current }} de {{ $last }}</p>
<div>
    @if($current>1)
        <a href="{{ route('servicios.admin', array_merge(request()->except('page'), ['page'=>$current-1])) }}">&lt; Anterior</a>
    @endif
    @if($current < $last)
        <a href="{{ route('servicios.admin', array_merge(request()->except('page'), ['page'=>$current+1])) }}">Siguiente &gt;</a>
    @endif
</div>
@endif
@if($isAdmin)
<h2>Crear</h2>
<form method="POST" action="{{ route('servicios.admin.create') }}">
    @csrf
    <input type="text" name="name" placeholder="Nombre" required />
    <input type="number" step="0.01" name="price" placeholder="Precio" />
    <input type="number" name="estimated_time" placeholder="Tiempo" />
    <input type="text" name="type" placeholder="Tipo" />
    <select name="status">
        <option value="">--status--</option>
        <option value="active">active</option>
        <option value="inactive">inactive</option>
    </select>
    <textarea name="description" placeholder="Descripción"></textarea>
    <button type="submit">Crear</button>
</form>
@endif
</body>
</html>