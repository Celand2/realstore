<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orders) {}

    public function index(Request $request)
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('client.orders.index', compact('orders'));
    }

    public function show($id, Request $request)
    {
        $id = $this->decodeId($id);
        $order = Order::with('items.product')->findOrFail($id);

        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('client.orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address' => 'required|string',
        ]);

        try {
            $order = $this->orders->checkout(
                $request->user()->id,
                $data['address'],
            );
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('orders.show', \Vinkla\Hashids\Facades\Hashids::encode($order->id))->with('status', 'Commande créée');
    }

    public function cancel($id, Request $request)
    {
        $id = $this->decodeId($id);
        try {
            $this->orders->cancel($request->user()->id, (int) $id);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('orders.index')->with('status', 'Commande annulée');
    }
}
