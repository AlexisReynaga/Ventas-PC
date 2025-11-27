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

    private function authorizeAdmin(): void
    {
        if (session('api_user_role') !== 'admin') {
            abort(403, 'No autorizado');
        }
    }
}
