@extends('layouts.client')

@section('title','Checkout')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if(session('error'))
        <div class="mb-4 text-red-600">{{ session('error') }}</div>
    @endif

    <div class="bg-white p-6 rounded shadow">
        <form action="{{ route('cart.process') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Adresse de livraison</label>
                <textarea name="address" class="w-full border p-3 rounded" rows="4">{{ old('address') }}</textarea>
                @error('address')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-between items-center">
                <div class="text-lg">Total: <strong>{{ number_format($total,2) }} FC</strong></div>
                <button class="bg-indigo-600 text-white px-4 py-2 rounded">Payer / Créer la commande</button>
            </div>
        </form>
    </div>
</div>
@endsection
