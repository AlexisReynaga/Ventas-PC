<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseAdminController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();
        $purchases = Purchase::latest()->paginate(20);
        return view('admin.compras.index', compact('purchases'));
    }

    public function show(int $id)
    {
        $this->authorizeAdmin();
        $purchase = Purchase::findOrFail($id);
        return view('admin.compras.show', compact('purchase'));
    }

    public function ticket(int $id)
    {
        $this->authorizeAdmin();
        $purchase = Purchase::findOrFail($id);
        // Construir estructura similar a la vista de ticket público
        $ticket = [
            'ticket_id' => $purchase->ticket_id,
            'fecha' => $purchase->created_at->format('Y-m-d H:i:s'),
            'items' => $purchase->items,
            'total' => $purchase->total,
            'user' => optional($purchase->user),
        ];
        return view('admin.compras.ticket', compact('ticket','purchase'));
    }

    private function authorizeAdmin(): void
    {
        if (session('api_user_role') !== 'admin') {
            abort(403, 'No autorizado');
        }
    }
}
