<?php

namespace App\Http\Controllers;

use App\Services\ExternalApi\ExternalApiClient;
use App\Models\Purchase;
use Illuminate\View\View;
use Throwable;

class FinanceController extends Controller
{
    protected ExternalApiClient $client;

    public function __construct(ExternalApiClient $client)
    {
        $this->client = $client;
    }

    public function index(): View
    {
        $productsSummary = [
            'total' => 0,
            'active' => 0,
            'stock_total' => 0,
            'valor_inventario_publico' => 0.0,
            'valor_inventario_costo' => 0.0,
            'margen_potencial_inventario' => 0.0,
            'costo_mayor_precio_count' => 0,
        ];
        $servicesSummary = [
            'total' => 0,
            'active' => 0,
            'valor_servicios' => 0.0,
        ];
        $ventasSummary = [
            'tickets' => 0,
            'ingresos' => 0.0,
            'costo' => 0.0,
            'profit' => 0.0,
            'margin_percent' => 0.0,
        ];
        try {
            $productsResp = $this->client->products();
            $products = $productsResp['data'] ?? $productsResp;
            foreach ($products as $p) {
                $productsSummary['total']++;
                if (($p['status'] ?? null) === 'active') { $productsSummary['active']++; }
                $stock = (int) ($p['stock'] ?? 0);
                $price = (float) ($p['price'] ?? 0);
                $cost = (float) ($p['cost_price'] ?? 0);
                $productsSummary['stock_total'] += $stock;
                $productsSummary['valor_inventario_publico'] += $stock * $price;
                $productsSummary['valor_inventario_costo'] += $stock * $cost;
                if ($cost > $price && $price > 0) {
                    $productsSummary['costo_mayor_precio_count']++;
                }
            }
            $productsSummary['margen_potencial_inventario'] = $productsSummary['valor_inventario_publico'] - $productsSummary['valor_inventario_costo'];
            $servicesResp = $this->client->services();
            $services = $servicesResp['data'] ?? $servicesResp;
            foreach ($services as $s) {
                $servicesSummary['total']++;
                if (($s['status'] ?? null) === 'active') { $servicesSummary['active']++; }
                $servicesSummary['valor_servicios'] += (float) ($s['price'] ?? 0);
            }
            // Ventas (Purchases persistidas)
            $purchases = Purchase::query()->latest()->get();
            $ventasSummary['tickets'] = $purchases->count();
            foreach ($purchases as $purchase) {
                $items = $purchase->items;
                foreach (($items['products'] ?? []) as $pr) {
                    $cantidad = (int)($pr['quantity'] ?? 1);
                    $precio = (float)($pr['price'] ?? 0);
                    $costo = (float)($pr['cost_price'] ?? 0);
                    $ventasSummary['ingresos'] += $precio * $cantidad;
                    $ventasSummary['costo'] += $costo * $cantidad;
                }
                foreach (($items['services'] ?? []) as $sv) {
                    $ventasSummary['ingresos'] += (float)($sv['price'] ?? 0);
                }
            }
            $ventasSummary['profit'] = $ventasSummary['ingresos'] - $ventasSummary['costo'];
            if ($ventasSummary['ingresos'] > 0) {
                $ventasSummary['margin_percent'] = round(($ventasSummary['profit'] / $ventasSummary['ingresos']) * 100, 2);
            }
        } catch (Throwable $e) {
            // Mantener valores en cero
        }
        return view('admin.finanzas', compact('productsSummary','servicesSummary','ventasSummary'));
    }
}
