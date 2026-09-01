<?php

namespace Tests\Unit\Services;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CartService;
    }

    protected function createUser(array $attributes = []): User
    {
        return User::create([
            'name' => $attributes['name'] ?? 'Test User',
            'email' => $attributes['email'] ?? fake()->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'role' => $attributes['role'] ?? 'client',
        ]);
    }

    protected function createCategory(array $attributes = []): Category
    {
        return Category::create([
            'name' => $attributes['name'] ?? 'Catégorie test',
            'description' => $attributes['description'] ?? 'Description de test',
        ]);
    }

    protected function createProduct(array $attributes = []): Product
    {
        return Product::create([
            'category_id' => $attributes['category_id'] ?? $this->createCategory()->id,
            'title' => $attributes['title'] ?? 'Produit test',
            'price' => $attributes['price'] ?? 49.99,
            'image' => $attributes['image'] ?? 'produit.jpg',
            'description' => $attributes['description'] ?? 'Produit de test',
            'actif' => $attributes['actif'] ?? true,
            'stock' => $attributes['stock'] ?? 10,
        ]);
    }

    public function test_get_or_create_cart_creates_cart_for_user(): void
    {
        $user = $this->createUser();

        $cart = $this->service->getOrCreateCart($user->id);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertSame($user->id, $cart->user_id);
        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
    }

    public function test_get_items_returns_items_with_related_product(): void
    {
        $user = $this->createUser();
        $cart = $this->service->getOrCreateCart($user->id);
        $product = $this->createProduct(['title' => 'Café premium', 'stock' => 8]);

        CartProduct::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $items = $this->service->getItems($user->id);

        $this->assertCount(1, $items);
        $this->assertSame($product->id, $items->first()->product_id);
        $this->assertSame('Café premium', $items->first()->product->title);
    }

    public function test_total_returns_sum_of_all_item_prices(): void
    {
        $user = $this->createUser();
        $cart = $this->service->getOrCreateCart($user->id);

        $productA = $this->createProduct(['title' => 'Produit A', 'price' => 10.50, 'stock' => 10]);
        $productB = $this->createProduct(['title' => 'Produit B', 'price' => 7.25, 'stock' => 10]);

        CartProduct::create(['cart_id' => $cart->id, 'product_id' => $productA->id, 'quantity' => 2]);
        CartProduct::create(['cart_id' => $cart->id, 'product_id' => $productB->id, 'quantity' => 3]);

        $items = $this->service->getItems($user->id);

        $this->assertSame(10.50 * 2 + 7.25 * 3, $this->service->total($items));
        $this->assertEquals(10.50 * 2 + 7.25 * 3, $this->service->total($items));
    }

    public function test_add_products_stores_new_items_without_overwriting_existing_ones(): void
    {
        $user = $this->createUser();
        $product = $this->createProduct(['title' => 'Produit ajouté', 'stock' => 5]);

        $cart = $this->service->addProducts($user->id, [
            ['id' => $product->id, 'quantity' => 2],
        ]);

        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertDatabaseHas('cart_products', [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $this->service->addProducts($user->id, [
            ['id' => $product->id, 'quantity' => 2],
        ]);

        $this->assertSame(4, CartProduct::where('cart_id', $cart->id)->where('product_id', $product->id)->value('quantity'));
    }

    public function test_add_products_throws_when_cumulative_quantity_exceeds_stock(): void
    {
        $user = $this->createUser();
        $product = $this->createProduct(['title' => 'Produit stock limité', 'stock' => 3]);

        $this->service->addProducts($user->id, [
            ['id' => $product->id, 'quantity' => 2],
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stock insuffisant pour « Produit stock limité ». Disponible : 3.');

        $this->service->addProducts($user->id, [
            ['id' => $product->id, 'quantity' => 2],
        ]);
    }

    public function test_add_products_sync_replaces_quantities_and_removes_missing_items(): void
    {
        $user = $this->createUser();
        $cart = $this->service->getOrCreateCart($user->id);
        $productA = $this->createProduct(['title' => 'Produit A', 'stock' => 10]);
        $productB = $this->createProduct(['title' => 'Produit B', 'stock' => 10]);
        $productC = $this->createProduct(['title' => 'Produit C', 'stock' => 10]);

        CartProduct::create(['cart_id' => $cart->id, 'product_id' => $productA->id, 'quantity' => 4]);
        CartProduct::create(['cart_id' => $cart->id, 'product_id' => $productB->id, 'quantity' => 2]);

        $updatedCart = $this->service->addProducts($user->id, [
            ['id' => $productA->id, 'quantity' => 1],
            ['id' => $productC->id, 'quantity' => 3],
        ], sync: true);

        $this->assertSame($cart->id, $updatedCart->id);
        $this->assertDatabaseHas('cart_products', ['cart_id' => $cart->id, 'product_id' => $productA->id, 'quantity' => 1]);
        $this->assertDatabaseHas('cart_products', ['cart_id' => $cart->id, 'product_id' => $productC->id, 'quantity' => 3]);
        $this->assertDatabaseMissing('cart_products', ['cart_id' => $cart->id, 'product_id' => $productB->id]);
    }

    public function test_add_products_sync_throws_when_requested_quantity_exceeds_stock(): void
    {
        $user = $this->createUser();
        $product = $this->createProduct(['title' => 'Produit impossible', 'stock' => 2]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Stock insuffisant pour « Produit impossible ». Disponible : 2.');

        $this->service->addProducts($user->id, [
            ['id' => $product->id, 'quantity' => 3],
        ], sync: true);
    }

    public function test_update_item_updates_quantity_when_stock_is_available(): void
    {
        $user = $this->createUser();
        $product = $this->createProduct(['title' => 'Produit modifiable', 'stock' => 10]);
        $cart = $this->service->getOrCreateCart($user->id);

        $item = CartProduct::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $updatedItem = $this->service->updateItem($user->id, $item->id, 4);

        $this->assertSame(4, $updatedItem->quantity);
        $this->assertSame(4, CartProduct::find($item->id)->quantity);
    }

    public function test_update_item_throws_when_requested_quantity_exceeds_stock(): void
    {
        $user = $this->createUser();
        $product = $this->createProduct(['title' => 'Produit trop demandé', 'stock' => 2]);
        $cart = $this->service->getOrCreateCart($user->id);

        $item = CartProduct::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('La quantité demandée dépasse le stock disponible.');

        $this->service->updateItem($user->id, $item->id, 3);
    }

    public function test_update_item_for_another_user_throws_not_found_exception(): void
    {
        $owner = $this->createUser(['email' => 'owner@example.com']);
        $otherUser = $this->createUser(['email' => 'other@example.com']);
        $product = $this->createProduct(['title' => 'Produit privé', 'stock' => 5]);

        $ownerCart = $this->service->getOrCreateCart($owner->id);
        $item = CartProduct::create([
            'cart_id' => $ownerCart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->expectException(ModelNotFoundException::class);

        $this->service->updateItem($otherUser->id, $item->id, 2);
    }

    public function test_remove_item_deletes_the_item_for_the_owner_user(): void
    {
        $user = $this->createUser();
        $product = $this->createProduct(['title' => 'Produit à supprimer', 'stock' => 5]);
        $cart = $this->service->getOrCreateCart($user->id);

        $item = CartProduct::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->service->removeItem($user->id, $item->id);

        $this->assertDatabaseMissing('cart_products', ['id' => $item->id]);
    }

    public function test_clear_removes_every_item_in_the_user_cart(): void
    {
        $user = $this->createUser();
        $cart = $this->service->getOrCreateCart($user->id);
        $productA = $this->createProduct(['title' => 'Produit 1', 'stock' => 10]);
        $productB = $this->createProduct(['title' => 'Produit 2', 'stock' => 10]);

        CartProduct::create(['cart_id' => $cart->id, 'product_id' => $productA->id, 'quantity' => 1]);
        CartProduct::create(['cart_id' => $cart->id, 'product_id' => $productB->id, 'quantity' => 2]);

        $this->service->clear($user->id);

        $this->assertCount(0, $this->service->getItems($user->id));
    }
}
