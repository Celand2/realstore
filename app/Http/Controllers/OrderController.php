<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Liste des commandes pour le client connecté
    public function index(Request $request)
    {
        $user = $request->user();
        $orders = [];
        if ($user) {
            $orders = Order::where('user_id', $user->id)->get();
        }

        return view('client.orders.index', compact('orders'));
    }

    // Vue détail d'une commande
    public function show($id, Request $request)
    {
        $order = Order::with('items.product')->findOrFail($id);

        // Simple check: only owner can view
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('client.orders.show', compact('order'));
    }

    // Créer une commande à partir d'un panier basique (simplifié)
    public function store(Request $request)
    {
        $data = $request->validate([
            'address' => 'required|string',
            'total' => 'required|numeric',
        ]);

        $order = Order::create([
            'user_id' => $request->user()->id,
            'address' => $data['address'],
            'total' => $data['total'],
            'status' => 'pending',
        ]);

        return redirect()->route('orders.show', $order->id)->with('status', 'Commande créée');
    }

    // Annuler une commande (uniquement par son propriétaire, et uniquement si 'pending')
    public function cancel($id, Request $request)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Cette commande ne peut plus être annulée.');
        }

        $order->status = 'cancelled';
        $order->save();

        return redirect()->route('orders.index')->with('status', 'Commande annulée');
    }
}
