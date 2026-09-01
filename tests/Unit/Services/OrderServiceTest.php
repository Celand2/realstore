<?php

namespace Tests\Unit\Services;

use App\Mail\OrderCreatedMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Tests\TestCase;

class OrderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected OrderService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new OrderService(new CartService);
        Mail::fake();
    }

    protected function createUser(array $attributes = []): User
    {
        return User::create([
            'name' => $attributes['name'] ?? 'Client test',
            'email' => $attributes['email'] ?? fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => $attributes['role'] ?? 'client',
        ]);
    }

    protected function createCategory(array $attributes = []): Category
    {
        return Category::create([
            'name' => $attributes['name'] ?? 'Catégorie commande',
            'description' => $attributes['description'] ?? 'Description',
        ]);
    }

    protected function createProduct(array $attributes = []): Product
    {
        return Product::create([
            'category_id' => $attributes['category_id'] ?? $this->createCategory()->id,
            'title' => $attributes['title'] ?? 'Produit commande',
            'price' => $attributes['price'] ?? 89.90,
            'image' => $attributes['image'] ?? 'commande.jpg',
            'description' => $attributes['description'] ?? 'Produit pour commande',
            'actif' => $attributes['actif'] ?? true,
            'stock' => $attributes['stock'] ?? 10,
        ]);
    }

    protected function addProductToCart(int $userId, int $productId, int $quantity): Cart
    {
        $cartService = new CartService;
        $cartService->addProducts($userId, [['id' => $productId, 'quantity' => $quantity]]);

        return $cartService->getOrCreateCart($userId);
    }

    public function test_checkout_creates_order_updates_stock_and_clears_cart(): void
    {
        $user = $this->createUser(['email' => 'checkout@example.com']);
        $product = $this->createProduct(['title' => 'Laptop', 'price' => 1200.00, 'stock' => 5]);
        $this->addProductToCart($user->id, $product->id, 2);

        $order = $this->service->checkout($user->id, '12 rue des Tests');

        $this->assertInstanceOf(Order::class, $order);
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'pending',
            'address' => '12 rue des Tests',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => '1200.00',
        ]);
        $this->assertSame(3, $product->fresh()->stock);
        $this->assertCount(0, (new CartService)->getItems($user->id));

        Mail::assertSent(OrderCreatedMail::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id;
        });
    }

    public function test_checkout_throws_when_cart_is_empty(): void
    {
        $user = $this->createUser(['email' => 'empty-cart@example.com']);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Votre panier est vide.');

        $this->service->checkout($user->id, '1 avenue Vide');
    }

    public function test_checkout_throws_when_stock_is_insufficient(): void
    {
        $user = $this->createUser(['email' => 'stock@example.com']);
        $product = $this->createProduct(['title' => 'Stock limité', 'stock' => 2]);
        $cart = (new CartService)->getOrCreateCart($user->id);

        CartProduct::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Le stock de l\'un des produits a changé.');

        $this->service->checkout($user->id, 'Rue du Stock');
    }

    public function test_cancel_marks_order_as_cancelled_and_restores_stock(): void
    {
        $user = $this->createUser(['email' => 'cancel@example.com']);
        $product = $this->createProduct(['title' => 'Produit annulé', 'price' => 45.00, 'stock' => 10]);
        $this->addProductToCart($user->id, $product->id, 3);
        $order = $this->service->checkout($user->id, '22 rue Annulation');

        $cancelled = $this->service->cancel($user->id, $order->id);

        $this->assertSame('cancelled', $cancelled->status);
        $this->assertSame(10, $product->fresh()->stock);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);

        Mail::assertSent(OrderStatusUpdatedMail::class, function ($mail) use ($cancelled) {
            return $mail->order->id === $cancelled->id;
        });
    }

    public function test_cancel_throws_for_non_pending_order_or_wrong_user(): void
    {
        $owner = $this->createUser(['email' => 'owner@example.com']);
        $other = $this->createUser(['email' => 'other@example.com']);
        $product = $this->createProduct(['title' => 'Produit privé', 'stock' => 5, 'price' => 25.00]);
        $this->addProductToCart($owner->id, $product->id, 1);
        $order = $this->service->checkout($owner->id, 'Adresse');

        $this->service->updateStatus($order->id, 'confirmed');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cette commande ne vous appartient pas.');
        $this->service->cancel($other->id, $order->id);
    }

    public function test_update_status_accepts_valid_status_and_restores_stock_when_cancelled(): void
    {
        $user = $this->createUser(['email' => 'status@example.com']);
        $product = $this->createProduct(['title' => 'Produit status', 'price' => 99.99, 'stock' => 6]);
        $this->addProductToCart($user->id, $product->id, 2);
        $order = $this->service->checkout($user->id, 'Rue du statut');

        $updated = $this->service->updateStatus($order->id, 'cancelled');

        $this->assertSame('cancelled', $updated->status);
        $this->assertSame(6, $product->fresh()->stock);

        Mail::assertSent(OrderStatusUpdatedMail::class, function ($mail) use ($updated) {
            return $mail->order->id === $updated->id;
        });
    }

    public function test_update_status_rejects_invalid_status(): void
    {
        $user = $this->createUser(['email' => 'invalid-status@example.com']);
        $product = $this->createProduct(['title' => 'Produit invalide', 'stock' => 3]);
        $this->addProductToCart($user->id, $product->id, 1);
        $order = $this->service->checkout($user->id, 'Rue invalide');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Statut de commande invalide.');

        $this->service->updateStatus($order->id, 'unknown_status');
    }
}
