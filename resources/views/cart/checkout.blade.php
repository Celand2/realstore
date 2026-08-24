@extends('layouts.client')

@section('title','Checkout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="bg-white p-4 sm:p-6 rounded shadow">
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
</div>
@endsection
