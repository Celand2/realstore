<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orders) {}

    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $id = $this->decodeId($id);
        $order = Order::with('items.product', 'user')->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        try {
            $this->orders->updateStatus($this->decodeId($id), $data['status']);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'Statut de la commande mis à jour.');
    }
}
