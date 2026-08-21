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
                    <div class="flex items-center gap-4">
                        <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600">Voir</a>
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.cancel', $order->id) }}" method="POST" onsubmit="return confirm('Annuler cette commande ?')">
                                @csrf
                                <button class="text-red-600 hover:underline">Annuler</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">Aucune commande pour le moment.</p>
    @endif
</div>
@endsection
