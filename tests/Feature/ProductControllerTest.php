<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_listing_displays_active_products(): void
    {
        $category = Category::create(['name' => 'Books']);
        Product::create($this->productAttributes($category, ['title' => 'Visible book']));
        Product::create($this->productAttributes($category, ['title' => 'Hidden book', 'actif' => false]));

        $response = $this->get(route('products'));

        $response->assertOk()
            ->assertViewIs('products')
            ->assertSee('Visible book')
            ->assertDontSee('Hidden book');
    }

    public function test_product_listing_filters_by_search_query(): void
    {
        $category = Category::create(['name' => 'Books']);
        Product::create($this->productAttributes($category, ['title' => 'Laravel Guide']));
        Product::create($this->productAttributes($category, ['title' => 'PHP Patterns']));

        $response = $this->get(route('products', ['q' => 'Laravel']));

        $response->assertOk()
            ->assertSee('Laravel Guide')
            ->assertDontSee('PHP Patterns');
    }

    public function test_product_detail_displays_product_and_related_products(): void
    {
        $category = Category::create(['name' => 'Books']);
        $product = Product::create($this->productAttributes($category, ['title' => 'Laravel Guide']));
        Product::create($this->productAttributes($category, ['title' => 'PHP Patterns']));

        $response = $this->get(route('products.show', $product));

        $response->assertOk()
            ->assertViewIs('products.show')
            ->assertSee('Laravel Guide')
            ->assertSee('PHP Patterns');
    }

    public function test_missing_product_returns_not_found(): void
    {
        $response = $this->get(route('products.show', 999));

        $response->assertNotFound();
    }

    /** @return array<string, mixed> */
    private function productAttributes(Category $category, array $overrides = []): array
    {
        return array_merge([
            'category_id' => $category->id,
            'title' => 'Product',
            'price' => 25.50,
            'image' => 'products/example.jpg',
            'description' => 'Product description',
            'actif' => true,
            'stock' => 10,
        ], $overrides);
    }
}
