<?php

namespace App\Http\Controllers;

use App\Services\ExternalApi\ExternalApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceProxyController extends Controller
{
    protected ExternalApiClient $client;

    public function __construct(ExternalApiClient $client)
    {
        $this->client = $client;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search','status','sort','direction','per_page']);
        try {
            $data = $this->client->services(array_filter($filters, fn ($v) => $v !== null && $v !== ''));
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            Log::error('Error listando servicios API', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Fallo al listar servicios'], 502);
        }
    }

    public function show(int $id)
    {
        try {
            $data = $this->client->service($id);
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 404;
            return response()->json(['error' => 'Servicio no encontrado'], $status);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
            'price' => ['nullable','numeric','min:0'],
            'estimated_time' => ['nullable','integer','min:0'],
            'type' => ['nullable','string','max:100'],
            'status' => ['nullable','in:active,inactive'],
        ]);
        $payload = array_merge($request->except(['_token']), $data);
        try {
            $created = $this->client->createService($payload);
            return response()->json($created, 201);
        } catch (\Throwable $e) {
            Log::error('Error creando servicio API', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo crear el servicio'], 422);
        }
    }

    public function update(Request $request, int $id)
    {
        $payload = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'description' => ['sometimes','nullable','string','max:1000'],
            'price' => ['sometimes','nullable','numeric','min:0'],
            'estimated_time' => ['sometimes','nullable','integer','min:0'],
            'type' => ['sometimes','nullable','string','max:100'],
            'status' => ['sometimes','nullable','in:active,inactive'],
        ]) + $request->except(['_token']);
        try {
            $updated = $this->client->updateService($id, $payload);
            return response()->json($updated, 200);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => 'No se pudo actualizar el servicio'], $status);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->client->deleteService($id);
            return response()->json([], 204);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => 'No se pudo eliminar el servicio'], $status);
        }
    }

    private function isAdmin(): bool
    {
        return session('api_user_role') === 'admin';
    }

    public function admin(Request $request)
    {
        $isAdmin = $this->isAdmin();
        $filters = $request->only(['search','status','sort','direction','per_page','page','type']);
        $services = [];
        $pagination = [
            'current_page' => 1,
            'last_page' => 1,
            'per_page' => null,
            'total' => null,
        ];
        try {
            $raw = $this->client->services(array_filter($filters, fn ($v) => $v !== null && $v !== ''));
            $items = $raw['data'] ?? $raw['items'] ?? (is_array($raw) && isset($raw[0]) ? $raw : []);
            $pagination['current_page'] = $raw['current_page'] ?? ($raw['meta']['current_page'] ?? 1);
            $pagination['last_page']    = $raw['last_page'] ?? ($raw['meta']['last_page'] ?? 1);
            $pagination['per_page']     = $raw['per_page'] ?? ($raw['meta']['per_page'] ?? count($items));
            $pagination['total']        = $raw['total'] ?? ($raw['meta']['total'] ?? count($items));
            $services = ['items' => $items] + $pagination;
        } catch (\Throwable $e) {
            $services = ['error' => 'No se pudo cargar listado', 'items' => []];
        }
        return view('servicios.admin', compact('services','isAdmin','filters','pagination'));
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
            'price' => ['nullable','numeric','min:0'],
            'estimated_time' => ['nullable','integer','min:0'],
            'type' => ['nullable','string','max:100'],
            'status' => ['nullable','in:active,inactive'],
        ]);
        try {
            $this->client->createService($data + $request->except(['_token']));
            return redirect()->route('servicios.admin')->with('status', 'Servicio creado');
        } catch (\Throwable $e) {
            return redirect()->route('servicios.admin')->withErrors(['create' => 'Error: '.$e->getMessage()]);
        }
    }

    public function adminUpdate(Request $request, int $id)
    {
        $payload = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'description' => ['sometimes','nullable','string','max:1000'],
            'price' => ['sometimes','nullable','numeric','min:0'],
            'estimated_time' => ['sometimes','nullable','integer','min:0'],
            'type' => ['sometimes','nullable','string','max:100'],
            'status' => ['sometimes','nullable','in:active,inactive'],
        ]) + $request->except(['_token']);
        try {
            $this->client->updateService($id, $payload);
            return redirect()->route('servicios.admin')->with('status', 'Servicio actualizado');
        } catch (\Throwable $e) {
            return redirect()->route('servicios.admin')->withErrors(['update' => 'Error: '.$e->getMessage()]);
        }
    }

    public function adminDestroy(int $id)
    {
        try {
            $this->client->deleteService($id);
            return redirect()->route('servicios.admin')->with('status', 'Servicio eliminado');
        } catch (\Throwable $e) {
            return redirect()->route('servicios.admin')->withErrors(['delete' => 'Error: '.$e->getMessage()]);
        }
    }
}
