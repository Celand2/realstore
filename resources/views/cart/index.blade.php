@extends('layouts.client')

@section('title','Mon panier')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Mon panier</h1>

    @if(session('status'))
        <div class="mb-4 text-green-600">{{ session('status') }}</div>
    @endif

    @if(count($items))
        <div class="space-y-4">
            @foreach($items as $item)
                <div class="bg-white p-4 rounded shadow flex items-center justify-between">
                    <div>
                        <div class="font-medium">{{ $item->product->title ?? 'Produit supprimé' }}</div>
                        <div class="text-sm text-gray-500">{{ $item->product->description ?? '' }}</div>
                    </div>
                    <div class="flex items-center gap-4">
                        <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-20 border px-2 py-1 rounded">
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded">Mettre à jour</button>
                        </form>

                        <form action="{{ route('cart.remove') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <button class="bg-red-600 text-white px-3 py-1 rounded">Supprimer</button>
                        </form>
                    </div>
                </div>
            @endforeach

            <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                <div class="font-bold">Total</div>
                <div class="text-xl font-bold">
                    {{ number_format($items->reduce(function($carry,$i){ return $carry + ($i->product->price * $i->quantity); },0),2) }} FC
                </div>
            </div>

            <div>
                <a href="{{ route('cart.checkout') }}" class="bg-green-600 text-white px-4 py-2 rounded">Passer la commande</a>
            </div>
        </div>
    @else
        <p class="text-gray-600">Votre panier est vide.</p>
    @endif
</div>
@endsection
