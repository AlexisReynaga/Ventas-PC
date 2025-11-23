<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión Usuarios</title>
</head>
<body>
<h1>Gestión de Usuarios</h1>
@if(session('status'))<p>{{ session('status') }}</p>@endif
@if($errors->any())<ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
<p><a href="{{ route('home') }}">Inicio</a></p>
<h2>Crear usuario</h2>
<form method="POST" action="{{ route('admin.users.create') }}">
    @csrf
    <label>Nombre <input name="name" required /></label><br>
    <label>Email <input type="email" name="email" required /></label><br>
    <label>Password <input type="password" name="password" required /></label><br>
    <label>Rol 
        <select name="role">
            <option value="customer">customer</option>
            <option value="admin">admin</option>
        </select>
    </label><br>
    <button type="submit">Crear</button>
</form>
<h2>Listado</h2>
<table border="1" cellpadding="4">
    <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Acciones</th></tr></thead>
    <tbody>
    @forelse($users as $u)
        <tr>
            <td>{{ $u['id'] ?? '' }}</td>
            <td>{{ $u['name'] ?? '' }}</td>
            <td>{{ $u['email'] ?? '' }}</td>
            <td>{{ $u['role'] ?? '' }}</td>
            <td>
                <form method="POST" action="{{ route('admin.users.role', $u['id']) }}">
                    @csrf
                    <select name="role">
                        <option value="customer" @selected(($u['role'] ?? '')==='customer')>customer</option>
                        <option value="admin" @selected(($u['role'] ?? '')==='admin')>admin</option>
                    </select>
                    <button type="submit">Actualizar</button>
                </form>
            </td>
        </tr>
    @empty
        <tr><td colspan="5">Sin usuarios</td></tr>
    @endforelse
    </tbody>
</table>
</body>
</html>
