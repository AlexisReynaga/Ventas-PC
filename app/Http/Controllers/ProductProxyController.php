<?php

namespace App\Http\Controllers;

use App\Services\ExternalApi\ExternalApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductProxyController extends Controller
{
    protected ExternalApiClient $client;

    public function __construct(ExternalApiClient $client)
    {
        $this->client = $client;
    }

    // Lista pública (invitados / clientes / admin)
    public function index(Request $request)
    {
        $filters = $request->only([
            'search','category','status','min_price','max_price','min_stock','sort','direction','per_page'
        ]);
        try {
            $data = $this->client->products(array_filter($filters, fn ($v) => $v !== null && $v !== ''));
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            Log::error('Error listando productos API', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Fallo al listar productos'], 502);
        }
    }

    // Mostrar producto (público)
    public function show(int $id)
    {
        try {
            $data = $this->client->product($id);
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 502;
            return response()->json(['error' => 'No se pudo obtener el producto'], $status);
        }
    }

    // Crear producto (solo admin)
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
            'price' => ['required','numeric','min:0'],
            'stock' => ['nullable','integer','min:0'],
            'category' => ['nullable','string','max:100'],
            'status' => ['nullable','in:active,inactive'],
            'image_url' => ['nullable','string','max:255'],
        ]);
        $payload = array_merge($request->except(['_token']), $data);
        try {
            $created = $this->client->createProduct($payload);
            return response()->json($created, 201);
        } catch (\Throwable $e) {
            Log::error('Error creando producto API', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'No se pudo crear el producto'], 422);
        }
    }

    // Actualizar producto (solo admin)
    public function update(Request $request, int $id)
    {
        $payload = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'description' => ['sometimes','nullable','string','max:1000'],
            'price' => ['sometimes','numeric','min:0'],
            'stock' => ['sometimes','nullable','integer','min:0'],
            'category' => ['sometimes','nullable','string','max:100'],
            'status' => ['sometimes','nullable','in:active,inactive'],
            'image_url' => ['sometimes','nullable','string','max:255'],
        ]) + $request->except(['_token']);
        try {
            $updated = $this->client->updateProduct($id, $payload);
            return response()->json($updated, 200);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => 'No se pudo actualizar el producto'], $status);
        }
    }

    // Eliminar producto (solo admin)
    public function destroy(int $id)
    {
        try {
            $this->client->deleteProduct($id);
            return response()->json([], 204);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => 'No se pudo eliminar el producto'], $status);
        }
    }

    private function isAdmin(): bool
    {
        return session('api_user_role') === 'admin';
    }

    // Vista HTML admin (CRUD básico)
    public function admin(Request $request)
    {
        $isAdmin = $this->isAdmin();
        $filters = $request->only([
            'search','category','status','min_price','max_price','min_stock','sort','direction','per_page','page'
        ]);
        $products = [];
        $pagination = [
            'current_page' => 1,
            'last_page' => 1,
            'per_page' => null,
            'total' => null,
        ];
        try {
            $raw = $this->client->products(array_filter($filters, fn ($v) => $v !== null && $v !== ''));
            // Normalizar estructura flexible
            $items = $raw['data'] ?? $raw['items'] ?? (is_array($raw) && isset($raw[0]) ? $raw : []);
            $pagination['current_page'] = $raw['current_page'] ?? ($raw['meta']['current_page'] ?? 1);
            $pagination['last_page']    = $raw['last_page'] ?? ($raw['meta']['last_page'] ?? 1);
            $pagination['per_page']     = $raw['per_page'] ?? ($raw['meta']['per_page'] ?? count($items));
            $pagination['total']        = $raw['total'] ?? ($raw['meta']['total'] ?? count($items));
            $products = ['items' => $items] + $pagination;
        } catch (\Throwable $e) {
            $products = ['error' => 'No se pudo cargar listado', 'items' => []];
        }
        return view('productos.admin', compact('products', 'isAdmin', 'filters', 'pagination'));
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
            'price' => ['required','numeric','min:0'],
            'stock' => ['nullable','integer','min:0'],
            'category' => ['nullable','string','max:100'],
            'status' => ['nullable','in:active,inactive'],
            'image_url' => ['nullable','string','max:255'],
        ]);
        try {
            $this->client->createProduct($data + $request->except(['_token']));
            return redirect()->route('productos.admin')->with('status', 'Producto creado');
        } catch (\Throwable $e) {
            return redirect()->route('productos.admin')->withErrors(['create' => 'Error creando: '.$e->getMessage()]);
        }
    }

    public function adminUpdate(Request $request, int $id)
    {
        $payload = $request->validate([
            'name' => ['sometimes','string','max:255'],
            'description' => ['sometimes','nullable','string','max:1000'],
            'price' => ['sometimes','numeric','min:0'],
            'stock' => ['sometimes','nullable','integer','min:0'],
            'category' => ['sometimes','nullable','string','max:100'],
            'status' => ['sometimes','nullable','in:active,inactive'],
            'image_url' => ['sometimes','nullable','string','max:255'],
        ]) + $request->except(['_token']);
        try {
            $this->client->updateProduct($id, $payload);
            return redirect()->route('productos.admin')->with('status', 'Producto actualizado');
        } catch (\Throwable $e) {
            return redirect()->route('productos.admin')->withErrors(['update' => 'Error: '.$e->getMessage()]);
        }
    }

    public function adminDestroy(int $id)
    {
        try {
            $this->client->deleteProduct($id);
            return redirect()->route('productos.admin')->with('status', 'Producto eliminado');
        } catch (\Throwable $e) {
            return redirect()->route('productos.admin')->withErrors(['delete' => 'Error: '.$e->getMessage()]);
        }
    }
    public function catalogo(Request $request)
    {
        // 1. Recogemos los filtros de la URL (igual que en tu vista blade)
        $filters = $request->only([
            'search','category','min_price','max_price','sort','direction','page','status'
        ]);
        // Por defecto mostrar solo activos para clientes/invitados
        if (!$request->has('status')) {
            $filters['status'] = 'active';
        }

        // 2. Preparamos estructura vacía por si falla la API
        $products = [
            'items' => [],
            'current_page' => 1,
            'last_page' => 1
        ];
        $categories = [];

        try {
            // 3. Llamamos a tu cliente API (reutilizando tu lógica existente)
            $raw = $this->client->products(array_filter($filters, fn ($v) => $v !== null && $v !== ''));
            
            // 4. Normalizamos los datos (igual que hiciste en el admin)
            $items = $raw['data'] ?? $raw['items'] ?? (is_array($raw) && isset($raw[0]) ? $raw : []);
            
            $products = [
                'items' => $items,
                'current_page' => $raw['current_page'] ?? ($raw['meta']['current_page'] ?? 1),
                'last_page'    => $raw['last_page'] ?? ($raw['meta']['last_page'] ?? 1),
                'total'        => $raw['total'] ?? ($raw['meta']['total'] ?? count($items)),
            ];

            // Construir lista de categorías desde los items actuales
            $categories = array_values(array_unique(array_filter(array_map(function ($it) {
                return $it['category'] ?? null;
            }, $items))));

            // Intentar ampliar categorías con una consulta más amplia (fallback silencioso)
            try {
                $catRaw = $this->client->products(array_filter([
                    'status' => $filters['status'] ?? 'active',
                    'per_page' => 200,
                ], fn ($v) => $v !== null && $v !== ''));
                $catItems = $catRaw['data'] ?? $catRaw['items'] ?? (is_array($catRaw) && isset($catRaw[0]) ? $catRaw : []);
                $moreCats = array_values(array_unique(array_filter(array_map(function ($it) {
                    return $it['category'] ?? null;
                }, $catItems))));
                $categories = array_values(array_unique(array_merge($categories, $moreCats)));
            } catch (\Throwable $e) {
                // ignorar, nos quedamos con las categorías de la página actual
            }

        } catch (\Throwable $e) {
            // Si falla, mandamos lista vacía pero no rompemos la página
            Log::error('Error cargando catálogo', ['e' => $e->getMessage()]);
        }

        // 5. Pasar también categorías para el filtro dinámico
        return view('productos.userproduct', compact('products','categories'));
    }
}
