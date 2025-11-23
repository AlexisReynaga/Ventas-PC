<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>body{font-family:sans-serif;max-width:420px;margin:40px auto;padding:0 16px}</style>
    </head>
<body>
    <h1>Iniciar sesión</h1>

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

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" required autofocus autocomplete="email" value="{{ old('email') }}" />
        </div>
        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required autocomplete="current-password" />
        </div>
        <div>
            <label>
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Recordarme
            </label>
        </div>
        <div>
            <button type="submit">Entrar</button>
        </div>
    </form>

    <p>¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate</a></p>
</body>
</html>
