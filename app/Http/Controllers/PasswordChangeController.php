<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PasswordChangeController extends Controller
{
    public function show()
    {
        return view('auth.password-change');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required','string'],
            'new_password' => ['required','string','min:6'],
        ]);

        $token = session('api_token');
        if (!$token) {
            return redirect()->route('login')->withErrors(['current_password' => 'Sesión expirada']);
        }

        $base = rtrim(config('external_api.base_url'), '/');
        try {
            $resp = Http::withToken($token)->patch($base.'/auth/password', [
                'current_password' => $data['current_password'],
                'new_password' => $data['new_password'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Fallo HTTP cambio password', ['error' => $e->getMessage()]);
            return back()->withErrors(['current_password' => 'No se pudo contactar la API'])->withInput();
        }

        if (!$resp->successful()) {
            $message = $resp->json('message') ?? 'Error al cambiar la contraseña';
            return back()->withErrors(['current_password' => $message])->withInput();
        }

        return redirect()->route('site.home')->with('status', 'Contraseña actualizada');
    }
}
