@extends('layouts.client')

@section('title','Checkout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif

        <div class="grid lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3 bg-white p-4 sm:p-6 rounded shadow">
        <form action="{{ route('cart.process') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Adresse de livraison</label>
                <textarea name="address" class="w-full border p-3 rounded" rows="4">{{ old('address') }}</textarea>
                @error('address')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <div class="text-lg">Total: <strong>{{ number_format($total,2) }} FC</strong></div>
                <button type="submit"
                        class="w-full sm:w-auto bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                    Payer / Créer la commande
                </button>
            </div>
                </form>
            </div>
            <aside class="lg:col-span-2 bg-white p-4 sm:p-6 rounded shadow h-fit">
                <h2 class="font-semibold text-lg mb-4">Récapitulatif</h2>
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div class="flex justify-between gap-3 border-b pb-3">
                            <div>
                                <p class="font-medium">{{ $item->product?->title ?? 'Produit supprimé' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->quantity }} x {{ number_format($item->product?->price ?? 0, 2) }} FC</p>
                            </div>
                            <strong class="whitespace-nowrap">{{ number_format(($item->product?->price ?? 0) * $item->quantity, 2) }} FC</strong>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between text-lg font-bold mt-5">
                    <span>Total</span><span>{{ number_format($total,2) }} FC</span>
                </div>
            </aside>
    </div>
</div>
@endsection
