<?php

namespace App\Services\ExternalApi;

use App\Services\ExternalApi\Exceptions\ExternalApiException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ExternalApiClient
{
    protected string $baseUrl;
    protected ?string $email;
    protected ?string $password;
    protected int $cacheTtl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('external_api.base_url'), '/');
        $this->email = config('external_api.admin_email');
        $this->password = config('external_api.admin_password');
        $this->cacheTtl = (int) config('external_api.cache_ttl', 3600);
    }

    public function products(array $filters = []): array
    {
        return $this->request('GET', '/products', ['query' => $filters], false);
    }

    public function product(int $id): array
    {
        return $this->request('GET', '/products/' . $id, [], false);
    }

    public function createProduct(array $data): array
    {
        return $this->request('POST', '/products', ['json' => $data]);
    }

    public function updateProduct(int $id, array $data): array
    {
        return $this->request('PUT', '/products/' . $id, ['json' => $data]);
    }

    public function deleteProduct(int $id): bool
    {
        $this->request('DELETE', '/products/' . $id);
        return true;
    }

    public function services(array $filters = []): array
    {
        return $this->request('GET', '/services', ['query' => $filters], false);
    }

    public function service(int $id): array
    {
        return $this->request('GET', '/services/' . $id, [], false);
    }

    public function createService(array $data): array
    {
        return $this->request('POST', '/services', ['json' => $data]);
    }

    public function updateService(int $id, array $data): array
    {
        return $this->request('PUT', '/services/' . $id, ['json' => $data]);
    }

    public function deleteService(int $id): bool
    {
        $this->request('DELETE', '/services/' . $id);
        return true;
    }

    public function users(array $filters = []): array
    {
        return $this->request('GET', '/users', ['query' => $filters]);
    }

    public function updateUserRole(int $id, string $role): array
    {
        return $this->request('PATCH', '/users/' . $id . '/role', ['json' => ['role' => $role]]);
    }

    public function registerUser(array $data): array
    {
        return $this->request('POST', '/auth/register', ['json' => $data], false);
    }

    protected function request(string $method, string $path, array $options = [], bool $auth = true): array
    {
        $url = $this->baseUrl . $path;
        // Construimos query string manualmente (Laravel Http PendingRequest no tiene ->query())
        if (isset($options['query']) && is_array($options['query']) && $options['query']) {
            $queryString = http_build_query($options['query']);
            $url .= (str_contains($url, '?') ? '&' : '?') . $queryString;
        }
        $headers = ['Accept' => 'application/json'];
        if ($auth) {
            // Si el usuario autenticó contra la API usamos su token, sino token admin por defecto.
            $userToken = session('api_token');
            $headers['Authorization'] = 'Bearer ' . ($userToken ?: $this->getToken());
        }

        $req = Http::withHeaders($headers);

        if (isset($options['json'])) {
            $req = $req->asJson()->withBody(json_encode($options['json']), 'application/json');
        }

        $response = $req->send($method, $url);

        if ($response->status() === 401 && $auth) {
            // Intentamos refrescar credenciales de admin solo si NO hay token de usuario.
            if (!session('api_token')) {
                $this->refreshToken();
                $headers['Authorization'] = 'Bearer ' . $this->getToken();
            } else {
                // Token de usuario inválido: limpiar sesión para forzar re-login.
                session()->forget(['api_token','api_user','api_user_role']);
                throw new ExternalApiException('Token de usuario expirado o inválido', 401);
            }
            $req = Http::withHeaders($headers);
            if (isset($options['json'])) {
                $req = $req->asJson()->withBody(json_encode($options['json']), 'application/json');
            }
            $response = $req->send($method, $url);
        }

        if (!$response->successful()) {
            $data = $response->json() ?? [];
            $message = $data['error']['message'] ?? $response->body();
            throw new ExternalApiException($message, $response->status(), $data);
        }

        return $response->json() ?? [];
    }

    protected function getToken(): string
    {
        return Cache::remember('external_api_token', $this->cacheTtl, function () {
            return $this->login();
        });
    }

    protected function refreshToken(): void
    {
        Cache::forget('external_api_token');
    }

    protected function login(): string
    {
        if (!$this->email || !$this->password) {
            throw new ExternalApiException('Credenciales de API no configuradas');
        }

        $url = $this->baseUrl . '/auth/login';
        $response = Http::asJson()->post($url, [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (!$response->successful()) {
            $data = $response->json() ?? [];
            $message = $data['error']['message'] ?? 'Error autenticando contra API externa';
            throw new ExternalApiException($message, $response->status(), $data);
        }

        $token = $response->json()['token'] ?? null;
        if (!$token) {
            throw new ExternalApiException('Token no recibido desde API externa');
        }
        return $token;
    }
}
