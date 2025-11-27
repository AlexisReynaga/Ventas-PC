<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use Illuminate\Support\Str;
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

    public function ticketPdf(int $id)
    {
        $this->authorizeAdmin();
        $purchase = Purchase::findOrFail($id);
        $ticket = [
            'ticket_id' => $purchase->ticket_id,
            'fecha' => $purchase->created_at->format('Y-m-d H:i:s'),
            'items' => $purchase->items,
            'total' => $purchase->total,
            'user' => optional($purchase->user),
        ];
        $html = view('pdf.ticket', compact('ticket'))->render();
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dateStr = now()->format('Ymd');
        $userSegment = Str::slug(($purchase->user->name ?? $purchase->user->email ?? 'usuario'), '_');
        $filename = "ticket_{$purchase->id}_{$dateStr}_{$userSegment}.pdf";

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename=' . $filename);
    }

    private function authorizeAdmin(): void
    {
        if (session('api_user_role') !== 'admin') {
            abort(403, 'No autorizado');
        }
    }
}
