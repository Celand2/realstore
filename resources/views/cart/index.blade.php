@extends('layouts.client')

@section('title','Mon panier')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <h1 class="text-2xl font-bold mb-6">Mon panier</h1>

    @if(session('status'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif

    @if(count($items))
        <div class="bg-white rounded shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 border-b text-left">Produit</th>
                            <th class="p-3 border-b text-center">Quantité</th>
                            <th class="p-3 border-b text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b font-medium">
                                    {{ $item->product?->title ?? 'Produit supprimé' }}
                                </td>
                                <td class="p-3 border-b text-center">
                                    <form action="{{ route('cart.update') }}" method="POST" class="inline-flex items-center justify-center gap-2">
                                        @csrf
                                        <input type="hidden" name="cart_item_id" value="{{ $item->id }}">

                                        <div class="inline-flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                            <button type="button"
                                                    onclick="let q=this.parentNode.querySelector('input[name=quantity]'); if(parseInt(q.value)>1) q.value=parseInt(q.value)-1;"
                                                    class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition select-none">
                                                −
                                            </button>
                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                   class="w-14 text-center border-x border-gray-300 px-1 py-1 focus:outline-none focus:ring-0">
                                            <button type="button"
                                                    onclick="this.parentNode.querySelector('input[name=quantity]').value=parseInt(this.parentNode.querySelector('input[name=quantity]').value)+1;"
                                                    class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold transition select-none">
                                                +
                                            </button>
                                        </div>
                                        <button type="submit"
                                                class="bg-indigo-600 text-white px-3 py-1 rounded text-xs sm:text-sm hover:bg-indigo-700 transition whitespace-nowrap">
                                            Mettre à jour
                                        </button>
                                    </form>
                                </td>
                                <td class="p-3 border-b text-center">
                                    <div class="flex flex-wrap items-center justify-center gap-2">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <button type="submit"
                                                    class="bg-red-600 text-white px-3 py-1 rounded text-xs sm:text-sm hover:bg-red-700 transition whitespace-nowrap"
                                                    onclick="return confirm('Supprimer cet article du panier ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('cart.checkout') }}"
               class="bg-green-600 text-white px-5 py-2 rounded hover:bg-green-700 transition">
                Passer la commande
            </a>
        </div>
    @else
        <p class="text-gray-600">Votre panier est vide.</p>
    @endif
</div>
@endsection
