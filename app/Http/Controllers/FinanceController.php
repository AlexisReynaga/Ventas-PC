<?php

namespace App\Http\Controllers;

use App\Services\ExternalApi\ExternalApiClient;
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
            'valor_inventario' => 0.0,
        ];
        $servicesSummary = [
            'total' => 0,
            'active' => 0,
            'valor_servicios' => 0.0,
        ];
        try {
            $productsResp = $this->client->products();
            $products = $productsResp['data'] ?? $productsResp;
            foreach ($products as $p) {
                $productsSummary['total']++;
                if (($p['status'] ?? null) === 'active') { $productsSummary['active']++; }
                $stock = (int) ($p['stock'] ?? 0);
                $price = (float) ($p['price'] ?? 0);
                $productsSummary['stock_total'] += $stock;
                $productsSummary['valor_inventario'] += $stock * $price;
            }
            $servicesResp = $this->client->services();
            $services = $servicesResp['data'] ?? $servicesResp;
            foreach ($services as $s) {
                $servicesSummary['total']++;
                if (($s['status'] ?? null) === 'active') { $servicesSummary['active']++; }
                $servicesSummary['valor_servicios'] += (float) ($s['price'] ?? 0);
            }
        } catch (Throwable $e) {
            // Mantener valores en cero
        }
        return view('admin.finanzas', compact('productsSummary','servicesSummary'));
    }
}
