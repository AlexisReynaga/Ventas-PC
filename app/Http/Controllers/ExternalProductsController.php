<?php

namespace App\Http\Controllers;

use App\Services\ExternalApi\ExternalApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExternalProductsController extends Controller
{
    public function index(Request $request, ExternalApiClient $client)
    {
        $filters = $request->only([
            'search','category','status','min_price','max_price','min_stock','sort','direction','per_page'
        ]);
        try {
            $data = $client->products(array_filter($filters, fn ($v) => $v !== null && $v !== ''));
            return response()->json($data);
        } catch (\Throwable $e) {
            Log::error('Error consumiendo API externa productos', [ 'error' => $e->getMessage() ]);
            return response()->json([
                'error' => [ 'message' => 'Fallo al consultar productos externos' ],
            ], 502);
        }
    }
}
