<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Logout;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
        $this->configureApiAuthenticationBridge();
        $this->configureApiLogoutBridge();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn () => view('livewire.auth.login'));
        Fortify::verifyEmailView(fn () => view('livewire.auth.verify-email'));
        Fortify::twoFactorChallengeView(fn () => view('livewire.auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn () => view('livewire.auth.confirm-password'));
        Fortify::registerView(fn () => view('livewire.auth.register'));
        Fortify::resetPasswordView(fn () => view('livewire.auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn () => view('livewire.auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }

    /**
     * Puente para autenticar contra la API externa en lugar de la base local.
     */
    private function configureApiAuthenticationBridge(): void
    {
        Fortify::authenticateUsing(function (Request $request) {
            $email = $request->input('email');
            $password = $request->input('password');

            if (!$email || !$password) {
                return null;
            }

            $base = rtrim(config('external_api.base_url'), '/');
            try {
                $loginResp = Http::asJson()->post($base.'/auth/login', [
                    'email' => $email,
                    'password' => $password,
                ]);
            } catch (\Throwable $e) {
                Log::error('Fallo HTTP login API', ['error' => $e->getMessage()]);
                return null;
            }

            if (!$loginResp->successful()) {
                return null; // credenciales inválidas
            }

            $token = $loginResp->json('token');
            $remoteUser = $loginResp->json('user') ?? [];

            // El login no retorna el rol actualmente, lo obtenemos con /auth/me
            try {
                $meResp = Http::withToken($token)->get($base.'/auth/me');
                if ($meResp->successful()) {
                    $remoteUser = $meResp->json();
                }
            } catch (\Throwable $e) {
                Log::warning('No se pudo obtener /auth/me', ['error' => $e->getMessage()]);
            }

            if (!$token || empty($remoteUser)) {
                return null;
            }

            // Sincronizamos/creamos el usuario local (solo espejo) para que Auth::user() funcione.
            $local = User::updateOrCreate(
                ['email' => $remoteUser['email'] ?? $email],
                [
                    'name' => $remoteUser['name'] ?? $email,
                    'role' => $remoteUser['role'] ?? 'customer',
                    // Password aleatorio, nunca se usa localmente.
                    'password' => bcrypt(str()->random(40)),
                ]
            );

            // Guardamos token y datos en sesión para consumo posterior.
            session([
                'api_token' => $token,
                'api_user' => $remoteUser,
                'api_user_role' => $remoteUser['role'] ?? 'customer',
            ]);

            return $local;
        });
    }

    /**
     * Puente para hacer logout también en la API y limpiar sesión.
     */
    private function configureApiLogoutBridge(): void
    {
        Event::listen(Logout::class, function () {
            $token = session('api_token');
            if ($token) {
                $base = rtrim(config('external_api.base_url'), '/');
                try {
                    Http::withToken($token)->post($base.'/auth/logout');
                } catch (\Throwable $e) {
                    Log::warning('No se pudo hacer logout en API', ['error' => $e->getMessage()]);
                }
            }
            session()->forget(['api_token','api_user','api_user_role']);
        });
    }
}
