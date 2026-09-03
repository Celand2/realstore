@extends('layouts.guest')

@section('title','Produits')

@section('content')

  <section class="bg-indigo-700 text-white px-4 sm:px-6 py-12 sm:py-16 text-center">
    <h1 class="text-3xl sm:text-4xl font-bold mb-4">Nos produits</h1>
    <p class="text-base sm:text-lg">Trouvez le produit qui vous convient.</p>
  </section>

  <section class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <form method="GET" action="{{ route('products') }}" class="grid sm:grid-cols-2 md:grid-cols-4 gap-3 mb-10">
      <input name="q" value="{{ request('q') }}" type="search" placeholder="Rechercher un produit" class="min-w-0 border rounded-lg px-4 py-2 sm:col-span-2 md:col-span-2">
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
      <button class="sm:col-span-2 md:col-span-4 bg-indigo-600 text-white rounded-lg px-4 py-2 hover:bg-indigo-700">Rechercher</button>
    </form>

    @if($products->isEmpty())
      <p class="text-gray-600">Aucun produit ne correspond à votre recherche.</p>
    @else
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
          <article class="bg-white rounded-2xl shadow-md overflow-hidden">
            <a href="{{ route('products.show', $product->id) }}">
              <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->title }}" class="w-full h-48 object-cover">
            </a>
            <div class="p-5">
              <p class="text-xs text-indigo-600 mb-1">{{ $product->category->name }}</p>
              <h2 class="font-semibold text-lg"><a href="{{ route('products.show', $product->id) }}">{{ $product->title }}</a></h2>
              <p class="text-indigo-600 font-bold mt-3">{{ number_format($product->price, 2) }} FC</p>
              <p class="text-sm mt-1 {{ $product->stock > 0 ? 'text-gray-500' : 'text-red-600' }}">
                {{ $product->stock > 0 ? $product->stock . ' disponible(s)' : 'Rupture de stock' }}
              </p>
              <a href="{{ route('products.show', $product->id) }}" class="block text-center mt-4 border border-indigo-600 text-indigo-600 py-2 rounded-lg">Voir le produit</a>
            </div>
          </article>
        @endforeach
      </div>
      <div class="mt-10">{{ $products->links() }}</div>
    @endif
  </section>
@endsection
