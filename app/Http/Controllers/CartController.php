<?php

namespace App\Http\Controllers;

use App\Services\ExternalApi\ExternalApiClient;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected ExternalApiClient $client;

    public function __construct(ExternalApiClient $client)
    {
        $this->client = $client;
    }

    private function getCart(): array
    {
        return session('cart', [
            'products' => [],
            'services' => [],
            'total' => 0,
        ]);
    }

    private function saveCart(array $cart): void
    {
        // Recalcular total
        $total = 0;
        foreach ($cart['products'] as $p) { $total += (float)($p['price'] ?? 0) * (int)($p['quantity'] ?? 1); }
        foreach ($cart['services'] as $s) { $total += (float)($s['price'] ?? 0); }
        $cart['total'] = $total;
        session(['cart' => $cart]);
    }

    public function index()
    {
        $cart = $this->getCart();
        // Vista simple del carrito
        return view('carrito.index', compact('cart'));
    }

    public function addProduct(int $id)
    {
        $cart = $this->getCart();
        try {
            $data = $this->client->product($id);
            $item = $data['data'] ?? $data; // flexible
            
            // Si ya existe en carrito, incrementa cantidad
            $existingIndex = collect($cart['products'])->search(fn($p) => ($p['id'] ?? null) == ($item['id'] ?? $id));
            
            if ($existingIndex !== false) {
                $cart['products'][$existingIndex]['quantity'] = (int)($cart['products'][$existingIndex]['quantity'] ?? 1) + 1;
            } else {
                $cart['products'][] = [
                    'id' => $item['id'] ?? $id,
                    'name' => $item['name'] ?? 'Producto',
                    'cost_price' => $item['cost_price'] ?? 0,
                    'price' => $item['price'] ?? 0,
                    'quantity' => 1,
                    // CORRECCIÓN: Agregamos la imagen al array de sesión
                    'image_url' => $item['image_url'] ?? '', 
                ];
            }
            
            $this->saveCart($cart);
            
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Producto agregado', 'cart' => $cart]);
            }
            return redirect()->route('carrito.index')->with('status', 'Producto agregado al carrito');
        } catch (\Throwable $e) {
            return response()->json(['error' => 'No se pudo agregar producto'], 422);
        }
    }

    // Incrementar cantidad de un producto
    public function incrementProduct(int $id)
    {
        $cart = $this->getCart();
        $index = collect($cart['products'])->search(fn($p) => ($p['id'] ?? null) == $id);
        if ($index === false) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Producto no encontrado en carrito'], 404);
            }
            return redirect()->route('carrito.index')->withErrors(['cart' => 'Producto no encontrado en carrito']);
        }
        $cart['products'][$index]['quantity'] = (int)($cart['products'][$index]['quantity'] ?? 1) + 1;
        $this->saveCart($cart);
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Cantidad incrementada', 'cart' => $cart]);
        }
        return redirect()->route('carrito.index')->with('status', 'Cantidad incrementada');
    }

    // Disminuir cantidad de un producto (eliminar si llega a 0)
    public function decrementProduct(int $id)
    {
        $cart = $this->getCart();
        $index = collect($cart['products'])->search(fn($p) => ($p['id'] ?? null) == $id);
        if ($index === false) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Producto no encontrado en carrito'], 404);
            }
            return redirect()->route('carrito.index')->withErrors(['cart' => 'Producto no encontrado en carrito']);
        }
        $qty = (int)($cart['products'][$index]['quantity'] ?? 1) - 1;
        if ($qty <= 0) {
            array_splice($cart['products'], $index, 1);
        } else {
            $cart['products'][$index]['quantity'] = $qty;
        }
        $this->saveCart($cart);
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Cantidad actualizada', 'cart' => $cart]);
        }
        return redirect()->route('carrito.index')->with('status', 'Cantidad actualizada');
    }

    // Eliminar producto del carrito
    public function removeProduct(int $id)
    {
        $cart = $this->getCart();
        $cart['products'] = array_values(array_filter($cart['products'], fn($p) => ($p['id'] ?? null) != $id));
        $this->saveCart($cart);
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Producto eliminado', 'cart' => $cart]);
        }
        return redirect()->route('carrito.index')->with('status', 'Producto eliminado');
    }

    // Vaciar carrito
    public function clear()
    {
        session()->forget('cart');
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Carrito vaciado', 'cart' => $this->getCart()]);
        }
        return redirect()->route('carrito.index')->with('status', 'Carrito vaciado');
    }

    // Eliminar servicio del carrito
    public function removeService(int $id)
    {
        $cart = $this->getCart();
        $cart['services'] = array_values(array_filter($cart['services'], fn($s) => ($s['id'] ?? null) != $id));
        $this->saveCart($cart);
        if (request()->wantsJson()) {
            return response()->json(['message' => 'Servicio eliminado', 'cart' => $cart]);
        }
        return redirect()->route('carrito.index')->with('status', 'Servicio eliminado');
    }

    public function addService(int $id)
    {
        $cart = $this->getCart();
        try {
            $data = $this->client->service($id);
            $item = $data['data'] ?? $data;
            $cart['services'][] = [
                'id' => $item['id'] ?? $id,
                'name' => $item['name'] ?? 'Servicio',
                'price' => $item['price'] ?? 0,
            ];
            $this->saveCart($cart);
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Servicio agregado', 'cart' => $cart]);
            }
            return redirect()->route('carrito.index')->with('status', 'Servicio agregado al carrito');
        } catch (\Throwable $e) {
            return response()->json(['error' => 'No se pudo agregar servicio'], 422);
        }
    }

    public function checkout()
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            session(['intended_after_login' => 'carrito.checkout']);
            return redirect()->route('login')->with('status', 'Inicia sesión para continuar con la compra');
        }
        $cart = $this->getCart();
        if (empty($cart['products']) && empty($cart['services'])) {
            return redirect()->route('carrito.index')->with('status', 'El carrito está vacío');
        }
        return view('carrito.detalle', compact('cart'));
    }

    public function generateTicket()
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            session(['intended_after_login' => 'carrito.ticket']);
            return redirect()->route('login')->with('status', 'Inicia sesión para generar el ticket');
        }
        $cart = $this->getCart();
        if (empty($cart['products']) && empty($cart['services'])) {
            return redirect()->route('carrito.index')->with('status', 'El carrito está vacío');
        }
        $ticketId = uniqid('TCK-');
        // Persistencia de la compra
        // Asegurar que cada producto tenga cost_price (fallback API si falta)
        foreach ($cart['products'] as &$p) {
            if (!isset($p['cost_price'])) {
                try {
                    $apiData = $this->client->product($p['id']);
                    $apiItem = $apiData['data'] ?? $apiData;
                    $p['cost_price'] = $apiItem['cost_price'] ?? 0;
                } catch (\Throwable $e) {
                    $p['cost_price'] = 0; // fallback
                }
            }
        }
        unset($p);
        $purchase = Purchase::create([
            'user_id' => Auth::id(),
            'ticket_id' => $ticketId,
            'items' => [
                'products' => $cart['products'],
                'services' => $cart['services'],
            ],
            'total' => $cart['total'],
            'status' => 'completed',
        ]);
        session()->forget('cart');
        $ticket = [
            'ticket_id' => $purchase->ticket_id,
            'fecha' => $purchase->created_at->format('Y-m-d H:i:s'),
            'items' => $purchase->items,
            'total' => $purchase->total,
            'user' => optional($purchase->user),
        ];
        // Generar PDF directo (descarga) usando DOMPDF
        $html = view('pdf.ticket', compact('ticket'))->render();
        try {
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $purchaseId = isset($purchase) ? $purchase->id : (isset($data['purchase']['id']) ? $data['purchase']['id'] : 'NA');
            $dateStr = now()->format('Ymd');
            $userSegment = Auth::check() ? Str::slug((Auth::user()->name ?? Auth::user()->email ?? 'usuario'), '_') : 'invitado';
            $filename = "ticket_{$purchaseId}_{$dateStr}_{$userSegment}.pdf";

            return response($dompdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename=' . $filename);
        } catch (\Throwable $e) {
            return redirect()->route('carrito.index')->withErrors(['pdf' => 'Error generando PDF']);
        }
    }

    // Formulario para agendar servicio (vista con fecha/hora)
    public function scheduleForm(int $id)
    {
        try {
            $data = $this->client->service($id);
            $service = $data['data'] ?? $data;
        } catch (\Throwable $e) {
            return redirect()->route('servicios.index')->withErrors(['schedule' => 'Servicio no disponible']);
        }
        return view('servicios.agendar', compact('service'));
    }

    // Guardar servicio agendado en carrito
    public function scheduleStore(Request $request, int $id)
    {
        $payload = $request->validate([
            'date' => ['required','date','after_or_equal:today'],
            'time' => ['required','date_format:H:i'],
        ]);
        // Validación adicional de horario: entre 08:00 y 20:00
        try {
            [$hour, $min] = array_map('intval', explode(':', $payload['time']));
            $minutes = $hour * 60 + $min;
            $minAllowed = 8 * 60;   // 08:00
            $maxAllowed = 20 * 60;  // 20:00
            if ($minutes < $minAllowed || $minutes > $maxAllowed) {
                return back()->withErrors(['time' => 'La hora debe estar entre 08:00 y 20:00'])->withInput();
            }
        } catch (\Throwable $e) {
            return back()->withErrors(['time' => 'Hora inválida'])->withInput();
        }
        $cart = $this->getCart();
        try {
            $data = $this->client->service($id);
            $service = $data['data'] ?? $data;
            $cart['services'][] = [
                'id' => $service['id'] ?? $id,
                'name' => $service['name'] ?? 'Servicio',
                'price' => $service['price'] ?? 0,
                'scheduled_date' => $payload['date'],
                'scheduled_time' => $payload['time'],
            ];
            $this->saveCart($cart);
            return redirect()->route('carrito.index')->with('status', 'Servicio agendado añadido al carrito');
        } catch (\Throwable $e) {
            return redirect()->route('servicios.index')->withErrors(['schedule' => 'Error al agendar servicio']);
        }
    }
}