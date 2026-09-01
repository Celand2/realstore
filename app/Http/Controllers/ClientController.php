<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(protected CartService $carts) {}

    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')
            ->where('actif', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%'.$request->string('q').'%')
                        ->orWhere('description', 'like', '%'.$request->string('q').'%');
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->input('sort') === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($request->input('sort') === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when(! $request->filled('sort'), fn ($query) => $query->latest())
            ->paginate(8)
            ->withQueryString();
        $user = auth()->user();
        $carts = [];
        if ($user) {
            $carts = $user->carts()->with('products')->get();
        }

        return view('client.dashboard', compact('products', 'carts', 'categories'));
    }

    public function getProducts(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $products = Product::with('category')
            ->where('actif', true)
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%'.$request->string('q').'%')
                        ->orWhere('description', 'like', '%'.$request->string('q').'%');
                });
            })
            ->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')))
            ->when($request->input('sort') === 'price_asc', fn ($query) => $query->orderBy('price'))
            ->when($request->input('sort') === 'price_desc', fn ($query) => $query->orderByDesc('price'))
            ->when(! $request->filled('sort'), fn ($query) => $query->latest())
            ->paginate(12)
            ->withQueryString();
        $cartItems = $this->carts->getItems(auth()->id())->map(function ($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->product?->title ?? 'Produit supprimé',
                'price' => (float) ($item->product?->price ?? 0),
                'description' => $item->product?->description ?? '',
                'quantity' => $item->quantity,
            ];
        })->values();

        return view('client/product.list', compact('products', 'cartItems', 'categories'));
    }
}
