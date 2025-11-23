<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AuthApiController extends Controller
{
    public function showRegister()
    {
        return view('livewire.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','email','max:255'],
            'password' => ['required','string','min:6','confirmed'],
        ]);

        $base = rtrim(config('external_api.base_url'), '/');

        // 1) Registrar en la API
        try {
            $resp = Http::asJson()->post($base.'/auth/register', [
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Fallo HTTP register API', ['error' => $e->getMessage()]);
            return back()->withErrors(['email' => 'No se pudo contactar la API. Inténtalo de nuevo.'])->withInput();
        }

        if (!$resp->successful()) {
            $message = $resp->json('message') ?? $resp->json('error.message') ?? 'Registro rechazado por la API';
            return back()->withErrors(['email' => $message])->withInput();
        }

        // 2) Login inmediato contra la API para obtener token
        try {
            $login = Http::asJson()->post($base.'/auth/login', [
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Fallo HTTP login API tras registro', ['error' => $e->getMessage()]);
            return redirect()->route('login')->with('status', 'Cuenta creada. Por favor inicia sesión.');
        }

        if (!$login->successful()) {
            return redirect()->route('login')->with('status', 'Cuenta creada. Por favor inicia sesión.');
        }

        $token = $login->json('token');
        $remoteUser = $login->json('user') ?? [];

        // Refrescar datos con /auth/me (opcional)
        try {
            $me = Http::withToken($token)->get($base.'/auth/me');
            if ($me->successful()) {
                $remoteUser = $me->json();
            }
        } catch (\Throwable $e) {
            // no bloquear
        }

        if (!$token || empty($remoteUser)) {
            return redirect()->route('login')->with('status', 'Cuenta creada. Por favor inicia sesión.');
        }

        // Sincroniza usuario local (espejo) y autentica sesión web
        $local = User::updateOrCreate(
            ['email' => $remoteUser['email'] ?? $data['email']],
            [
                'name' => $remoteUser['name'] ?? $data['name'],
                'role' => $remoteUser['role'] ?? 'customer',
                'password' => bcrypt(str()->random(40)),
            ]
        );

        session([
            'api_token' => $token,
            'api_user' => $remoteUser,
            'api_user_role' => $remoteUser['role'] ?? 'customer',
        ]);

        Auth::login($local, true);

        return redirect()->intended(route('site.home'));
    }
}
