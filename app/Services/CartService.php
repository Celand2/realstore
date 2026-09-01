<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Support\Collection;
use RuntimeException;

class CartService
{
    /**
     * Récupère (ou crée) le panier actif de l'utilisateur.
     */
    public function getOrCreateCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Retourne les items (avec produit) du panier actif d'un utilisateur.
     */
    public function getItems(int $userId): Collection
    {
        $cart = $this->getOrCreateCart($userId);

        return CartProduct::where('cart_id', $cart->id)
            ->with('product')
            ->get();
    }

    /**
     * Calcule le total d'un ensemble d'items du panier.
     */
    public function total(Collection $items): float
    {
        return (float) $items->reduce(
            fn ($carry, $item) => $carry + ($item->product->price * $item->quantity),
            0
        );
    }

    /**
     * Ajoute une liste de produits au panier, en gérant le stock.
     *
     * @param  array<int, array{id: int, quantity: int}>  $products
     *
     * @throws RuntimeException
     */
    public function addProducts(int $userId, array $products, bool $sync = false): Cart
    {
        $cart = $this->getOrCreateCart($userId);

        if ($sync) {
            $requestedQuantities = collect($products)->pluck('quantity', 'id');

            foreach ($products as $productData) {
                $product = Product::findOrFail($productData['id']);
                $quantity = (int) $productData['quantity'];

                if ($quantity > $product->stock) {
                    throw new RuntimeException(
                        "Stock insuffisant pour « {$product->title} ». Disponible : {$product->stock}."
                    );
                }

                CartProduct::updateOrCreate(
                    ['cart_id' => $cart->id, 'product_id' => $product->id],
                    ['quantity' => $quantity],
                );
            }

            CartProduct::where('cart_id', $cart->id)
                ->whereNotIn('product_id', $requestedQuantities->keys())
                ->delete();

            return $cart;
        }

        foreach ($products as $productData) {
            $product = Product::findOrFail($productData['id']);
            $existing = CartProduct::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            $newQuantity = ($existing?->quantity ?? 0) + $productData['quantity'];

            if ($newQuantity > $product->stock) {
                throw new RuntimeException(
                    "Stock insuffisant pour « {$product->title} ». Disponible : {$product->stock}."
                );
            }

            if ($existing) {
                $existing->quantity = $newQuantity;
                $existing->save();
            } else {
                CartProduct::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'quantity' => $productData['quantity'],
                ]);
            }
        }

        return $cart;
    }

    /**
     * Met à jour la quantité d'un item, après vérification de propriété et de stock.
     */
    public function updateItem(int $userId, int $cartItemId, int $quantity): CartProduct
    {
        $item = $this->findItemForUser($userId, $cartItemId);

        if ($quantity > $item->product->stock) {
            throw new RuntimeException('La quantité demandée dépasse le stock disponible.');
        }

        $item->quantity = $quantity;
        $item->save();

        return $item;
    }

    /**
     * Supprime un item du panier, après vérification de propriété.
     */
    public function removeItem(int $userId, int $cartItemId): void
    {
        $item = $this->findItemForUser($userId, $cartItemId);
        $item->delete();
    }

    /**
     * Vide complètement le panier d'un utilisateur.
     */
    public function clear(int $userId): void
    {
        $cart = $this->getOrCreateCart($userId);
        CartProduct::where('cart_id', $cart->id)->delete();
    }

    /**
     * Récupère un item tout en s'assurant qu'il appartient bien à l'utilisateur.
     */
    protected function findItemForUser(int $userId, int $cartItemId): CartProduct
    {
        return CartProduct::whereHas('cart', fn ($q) => $q->where('user_id', $userId))
            ->with('product')
            ->findOrFail($cartItemId);
    }
}
