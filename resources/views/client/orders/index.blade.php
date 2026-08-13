@extends('layouts.client')

@section('title','Mes commandes')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Mes commandes</h1>

    @if(count($orders))
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                    <div>
                        <div class="font-medium">Commande #{{ $order->id }}</div>
                        <div class="text-sm text-gray-500">Total: {{ number_format($order->total,2) }} FC — {{ $order->status }}</div>
                    </div>
                    <div>
                        <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600">Voir</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">Aucune commande pour le moment.</p>
    @endif
</div>
@endsection
