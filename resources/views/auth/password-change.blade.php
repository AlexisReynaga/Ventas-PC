<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar contraseña</title>
    <style>body{font-family:sans-serif;max-width:480px;margin:40px auto;padding:0 16px}</style>
</head>
<body>
    <h1>Cambiar contraseña</h1>
    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif
    @if ($errors->any())
        <div>
            <strong>Errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PATCH')
        <div>
            <label for="current_password">Contraseña actual</label>
            <input id="current_password" name="current_password" type="password" required />
        </div>
        <div>
            <label for="new_password">Nueva contraseña</label>
            <input id="new_password" name="new_password" type="password" required />
        </div>
        <div style="margin-top:12px;">
            <button type="submit">Guardar</button>
        </div>
    </form>
    <p><a href="{{ route('home') }}">Volver al inicio</a></p>
</body>
</html>
