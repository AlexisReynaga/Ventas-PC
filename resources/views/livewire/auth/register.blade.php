<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>body{font-family:sans-serif;max-width:420px;margin:40px auto;padding:0 16px}</style>
</head>
<body>
    <h1>Crear cuenta</h1>

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

    <form method="POST" action="{{ route('register.store') }}">
        @csrf
        <div>
            <label for="name">Nombre</label>
            <input id="name" name="name" type="text" required autofocus autocomplete="name" value="{{ old('name') }}" />
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required autocomplete="email" value="{{ old('email') }}" />
        </div>
        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" />
        </div>
        <div>
            <label for="password_confirmation">Confirmar Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />
        </div>
        <div>
            <button type="submit">Crear cuenta</button>
        </div>
    </form>

    <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión</a></p>
</body>
</html>
