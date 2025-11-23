<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Admin Productos</title>
</head>
<body>
<h1>Admin Productos</h1>
@if(session('status'))<p>{{ session('status') }}</p>@endif
@if($errors->any())<ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<p><a href="{{ route('home') }}">Inicio</a></p>
@php($items = $products['items'] ?? ($products['data'] ?? ($products['items'] ?? [])))
@php($current = $products['current_page'] ?? ($products['current_page'] ?? 1))
@php($last = $products['last_page'] ?? ($products['last_page'] ?? 1))
<h2>Filtros</h2>
<form method="GET" action="{{ route('productos.admin') }}">
    <input type="text" name="search" placeholder="Buscar" value="{{ request('search') }}" />
    <input type="text" name="category" placeholder="Categoria" value="{{ request('category') }}" />
    <select name="status">
        <option value="">--status--</option>
        <option value="active" @selected(request('status')==='active')>active</option>
        <option value="inactive" @selected(request('status')==='inactive')>inactive</option>
    </select>
    <input type="number" step="0.01" name="min_price" placeholder="Min Precio" value="{{ request('min_price') }}" />
    <input type="number" step="0.01" name="max_price" placeholder="Max Precio" value="{{ request('max_price') }}" />
    <input type="number" name="min_stock" placeholder="Min Stock" value="{{ request('min_stock') }}" />
    <select name="sort">
        <option value="">--ordenar por--</option>
        <option value="name" @selected(request('sort')==='name')>name</option>
        <option value="price" @selected(request('sort')==='price')>price</option>
        <option value="stock" @selected(request('sort')==='stock')>stock</option>
        <option value="category" @selected(request('sort')==='category')>category</option>
    </select>
    <select name="direction">
        <option value="">--dir--</option>
        <option value="asc" @selected(request('direction')==='asc')>asc</option>
        <option value="desc" @selected(request('direction')==='desc')>desc</option>
    </select>
    <input type="number" name="per_page" placeholder="Por página" value="{{ request('per_page') }}" />
    <button type="submit">Aplicar</button>
    <a href="{{ route('productos.admin') }}">Limpiar</a>
</form>
<h2>Listado</h2>
@if(isset($products['error']))
    <p>{{ $products['error'] }}</p>
@else
<table border="1" cellpadding="4">
    <thead><tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Categoria</th><th>Status</th><th>Img URL</th><th>Acciones</th></tr></thead>
    <tbody>
    @foreach($items as $p)
        <tr>
            <td>{{ $p['id'] ?? '-' }}</td>
            <td>{{ $p['name'] ?? $p['nombre'] ?? '-' }}</td>
            <td>{{ $p['price'] ?? '' }}</td>
            <td>{{ $p['stock'] ?? '' }}</td>
            <td>{{ $p['category'] ?? '' }}</td>
            <td>{{ $p['status'] ?? '' }}</td>
            <td>{{ $p['image_url'] ?? '' }}</td>
            <td>
                @if($isAdmin)
                <form method="POST" action="{{ route('productos.admin.update', $p['id']) }}">
                    @csrf
                    <input type="text" name="name" value="{{ $p['name'] ?? '' }}" placeholder="Nombre" />
                    <input type="number" step="0.01" name="price" value="{{ $p['price'] ?? '' }}" placeholder="Precio" />
                    <input type="number" name="stock" value="{{ $p['stock'] ?? '' }}" placeholder="Stock" />
                    <input type="text" name="category" value="{{ $p['category'] ?? '' }}" placeholder="Categoria" />
                    <select name="status">
                        <option value="" @selected(empty($p['status']))>--</option>
                        <option value="active" @selected(($p['status'] ?? '')==='active')>active</option>
                        <option value="inactive" @selected(($p['status'] ?? '')==='inactive')>inactive</option>
                    </select>
                    <input type="text" name="image_url" value="{{ $p['image_url'] ?? '' }}" placeholder="Image URL" />
                    <textarea name="description" placeholder="Descripción">{{ $p['description'] ?? '' }}</textarea>
                    <button type="submit">Actualizar</button>
                </form>
                <form method="POST" action="{{ route('productos.admin.delete', $p['id']) }}" onsubmit="return confirm('Eliminar?')">
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
        <a href="{{ route('productos.admin', array_merge(request()->except('page'), ['page'=>$current-1])) }}">&lt; Anterior</a>
    @endif
    @if($current < $last)
        <a href="{{ route('productos.admin', array_merge(request()->except('page'), ['page'=>$current+1])) }}">Siguiente &gt;</a>
    @endif
</div>
@endif
@if($isAdmin)
<h2>Crear</h2>
<form method="POST" action="{{ route('productos.admin.create') }}">
    @csrf
    <input type="text" name="name" placeholder="Nombre" required />
    <input type="number" step="0.01" name="price" placeholder="Precio" required />
    <input type="number" name="stock" placeholder="Stock" />
    <input type="text" name="category" placeholder="Categoria" />
    <select name="status">
        <option value="">--status--</option>
        <option value="active">active</option>
        <option value="inactive">inactive</option>
    </select>
    <input type="text" name="image_url" placeholder="Image URL" />
    <textarea name="description" placeholder="Descripción"></textarea>
    <button type="submit">Crear</button>
</form>
@endif
</body>
</html>