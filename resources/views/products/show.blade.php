@extends('layouts.guest')

@section('title', $product->title)

@section('content')
<main class="max-w-6xl mx-auto px-6 py-12">
    <a href="{{ route('products') }}" class="text-indigo-600">Retour aux produits</a>
    <div class="grid md:grid-cols-2 gap-10 mt-6 bg-white rounded-2xl shadow p-6">
        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->title }}" class="w-full aspect-square object-cover rounded-xl">
        <div>
            <p class="text-sm text-indigo-600">{{ $product->category->name }}</p>
            <h1 class="text-3xl font-bold mt-2">{{ $product->title }}</h1>
            <p class="text-2xl font-bold text-indigo-600 mt-5">{{ number_format($product->price, 2) }} FC</p>
            <p class="text-gray-600 mt-6 whitespace-pre-line">{{ $product->description }}</p>
            <p class="mt-5 {{ $product->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ $product->stock > 0 ? $product->stock . ' article(s) disponible(s)' : 'Produit épuisé' }}
            </p>
            @auth
                @if(auth()->user()->role === 'client' && $product->stock > 0)
                    <form action="{{ route('cart.add') }}" method="POST" class="flex gap-3 mt-6">
                        @csrf
                        <input type="hidden" name="products[0][id]" value="{{ $product->id }}">
                        <input type="number" name="products[0][quantity]" value="1" min="1" max="{{ $product->stock }}" class="w-24 border rounded-lg px-3 py-2">
                        <button class="flex-1 bg-indigo-600 text-white rounded-lg px-4 py-2 hover:bg-indigo-700">Ajouter au panier</button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="block text-center mt-6 bg-indigo-600 text-white rounded-lg px-4 py-2">Connectez-vous pour commander</a>
            @endauth
        </div>
    </div>

    @if($relatedProducts->isNotEmpty())
        <h2 class="text-2xl font-bold mt-12 mb-5">Produits similaires</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($relatedProducts as $relatedProduct)
                <a href="{{ route('products.show', $relatedProduct->id) }}" class="bg-white rounded-xl shadow p-4">
                    <img src="{{ asset('storage/'.$relatedProduct->image) }}" alt="{{ $relatedProduct->title }}" class="w-full h-36 object-cover rounded-lg">
                    <h3 class="font-semibold mt-3">{{ $relatedProduct->title }}</h3>
                    <p class="text-indigo-600 font-bold mt-1">{{ number_format($relatedProduct->price, 2) }} FC</p>
                </a>
            @endforeach
        </div>
    @endif
</main>
@endsection