@extends('layouts.client')

@section('title', 'Espace Client')

@section('content')

<div class="max-w-7xl mx-auto px-6 py-12">
  <div class="grid lg:grid-cols-3 gap-8">
    <div class="col-span-2">
      <h1 class="text-3xl font-bold mb-4">Bienvenue, {{ auth()->user()->name }}</h1>
      <p class="text-gray-600 mb-6">Voici un aperçu rapide de vos produits et activités récentes.</p>

      <h2 class="text-xl font-semibold mb-3">Produits recommandés</h2>
      <div class="grid sm:grid-cols-2 lg:grid-cols-2 gap-4 mb-8">
        @foreach($products as $product)
        <div class="bg-white p-4 rounded shadow">
          <img src="{{ asset('storage/'.$product->image) }}" class="h-32 w-full object-cover rounded mb-3" alt="{{ $product->title }}">
          <h3 class="font-medium">{{ $product->title }}</h3>
          <p class="text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($product->description, 60) }}</p>
          <div class="mt-3 flex items-center justify-between ">

            <span class="font-bold">{{ number_format($product->price, 2) }} FC</span>


            <form action="/client/add-cart" method="POST">
              @csrf
              <input type="hidden" name="userID" value="{{ auth()->id() }}">
              <input type="hidden" name="products[0][id]" value="{{ $product->id }}">
              <input type="hidden" name="products[0][quantity]" value="1">
              <input type="hidden" name="products[0][price]" value="{{ $product->price }}">
              <button class="bg-indigo-600 text-white px-3 py-1 rounded text-sm">Ajouter</button>
            </form>


          </div>
        </div>
        @endforeach
      </div>
    </div>

    <aside>
      <div class="bg-white p-4 rounded shadow mb-4">
        <h3 class="font-semibold mb-2">Mon compte</h3>
        <ul class="text-sm space-y-2">
          <li><a href="/profile" class="text-indigo-600">Modifier mon profil</a></li>
          <li><a href="/client/products" class="text-indigo-600">Parcourir les produits</a></li>
          <li><a href="/cart" class="text-indigo-600">Mon panier</a></li>
          <li><a href="/orders" class="text-indigo-600">Mes commandes</a></li>
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