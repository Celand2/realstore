<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderService
{
    public function __construct(protected CartService $carts) {}

    /**
     * Crée une commande à partir du panier actuel d'un utilisateur,
     * décrémente le stock, puis vide le panier. Le tout dans une transaction.
     *
     * @return Order La commande créée
     * @throws RuntimeException Si le panier est vide ou si le stock est insuffisant
     */
    public function checkout(int $userId, string $address): Order
    {
        $items = $this->carts->getItems($userId);

        if ($items->isEmpty()) {
            throw new RuntimeException('Votre panier est vide.');
        }

        // Vérification globale du stock avant de créer la commande
        foreach ($items as $item) {
            if (!$item->product || $item->quantity > $item->product->stock) {
                throw new RuntimeException('Le stock de l\'un des produits a changé.');
            }
        }

        $total = $this->carts->total($items);
        $cart = $this->carts->getOrCreateCart($userId);

        return DB::transaction(function () use ($items, $userId, $address, $total, $cart) {
            $order = Order::create([
                'user_id' => $userId,
                'total' => $total,
                'status' => 'pending',
                'address' => $address,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // Vider le panier après la création réussie
            $cart->products()->detach();

            return $order;
        });
    }

    /**
     * Annule une commande appartenant à l'utilisateur, et seulement si elle est
     * encore en statut "pending". Le stock est recrédité.
     *
     * @throws RuntimeException Si la commande n'est pas annulable
     */
    public function cancel(int $userId, int $orderId): Order
    {
        $order = Order::with('items')->findOrFail($orderId);

        if ($order->user_id !== $userId) {
            throw new RuntimeException('Cette commande ne vous appartient pas.');
        }

        if ($order->status !== 'pending') {
            throw new RuntimeException('Cette commande ne peut plus être annulée.');
        }

        return DB::transaction(function () use ($order) {
            // Recréditer le stock
            foreach ($order->items as $item) {
                $item->product()->increment('stock', $item->quantity);
            }

            $order->status = 'cancelled';
            $order->save();

            return $order;
        });
    }
}
