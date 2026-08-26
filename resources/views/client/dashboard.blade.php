@extends('layouts.client')

@section('title', 'Espace Client')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-12">
  <div class="grid lg:grid-cols-3 gap-8">
    <div class="col-span-2">
      <h1 class="text-3xl font-bold mb-4">Bienvenue, {{ auth()->user()->name }}</h1>
      <p class="text-gray-600 mb-6">Voici un aperçu rapide de vos produits et activités récentes.</p>

      <h2 class="text-xl font-semibold mb-3">Produits</h2>
      <form method="GET" action="{{ route('client.dashboard') }}" class="grid sm:grid-cols-4 gap-3 mb-6">
        <input name="q" value="{{ request('q') }}" type="search" placeholder="Rechercher un produit" class="border rounded-lg px-4 py-2 sm:col-span-2">
        <select name="category" class="border rounded-lg px-4 py-2">
          <option value="">Toutes les catégories</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
          @endforeach
        </select>
        <select name="sort" class="border rounded-lg px-4 py-2">
          <option value="">Plus récents</option>
          <option value="price_asc" @selected(request('sort') === 'price_asc')>Prix croissant</option>
          <option value="price_desc" @selected(request('sort') === 'price_desc')>Prix décroissant</option>
        </select>
        <button type="submit" class="sm:col-span-4 bg-indigo-600 text-white rounded-lg px-4 py-2 hover:bg-indigo-700">Rechercher</button>
      </form>

      @if($products->isEmpty())
        <p class="text-gray-600">Aucun produit ne correspond à votre recherche.</p>
      @endif
      <div class="grid sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-8">
        @foreach($products as $product)
        <div class="bg-white p-4 rounded shadow">
          <img src="{{ asset('storage/'.$product->image) }}" class="h-32 w-full object-cover rounded mb-3" alt="{{ $product->title }}">
          <h3 class="font-medium"><a href="{{ route('products.show', $product->id) }}">{{ $product->title }}</a></h3>
          <p class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
          <div class="mt-3 flex flex-col items-stretch justify-between gap-3 sm:flex-row sm:items-center">

            <span class="font-bold">{{ number_format($product->price, 2) }} FC</span>


            <form action="{{ route('cart.add') }}" method="POST">
              @csrf
              <input type="hidden" name="products[0][id]" value="{{ $product->id }}">
              <input type="hidden" name="products[0][quantity]" value="1">
              <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm">Ajouter</button>
            </form>


          </div>
        </div>
        @endforeach
      </div>
      @if($products->hasPages())
        <div class="mb-8">{{ $products->links() }}</div>
      @endif
    </div>

    <aside>
      <div class="bg-white p-4 rounded shadow mb-4">
        <h3 class="font-semibold mb-2">Mon compte</h3>
        <ul class="text-sm space-y-2">
          <li><a href="/profile" class="text-indigo-600">Modifier mon profil</a></li>
          <li><a href="/client/products" class="text-indigo-600">Parcourir les produits</a></li>
          <li><a href="{{ route('cart.index') }}" class="text-indigo-600">Mon panier</a></li>
          <li><a href="/client/orders" class="text-indigo-600">Mes commandes</a></li>
        </ul>
      </div>

      <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-2">Paniers récents</h3>
        @if(count($carts))
        <ul class="text-sm space-y-2">
          @foreach($carts as $cart)
          <li class="border-b pb-2">
            <div class="flex justify-between">
              <div>
                <div class="font-medium">Panier #{{ $cart->id }}</div>
                <div class="text-gray-500 text-xs">Créé le {{ $cart->created_at->format('Y-m-d') }}</div>
              </div>
              <div class="text-sm text-gray-700">{{ $cart->products->sum('pivot.quantity') }} articles</div>
            </div>
          </li>
          @endforeach
        </ul>
        @else
        <p class="text-sm text-gray-500">Aucun panier récent.</p>
        @endif
      </div>
    </aside>
  </div>
</div>

@endsection