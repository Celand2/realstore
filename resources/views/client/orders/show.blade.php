@extends('layouts.client')

@section('title','Détail commande')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Commande #{{ $order->id }}</h1>

    <div class="bg-white p-6 rounded shadow">
        <p><strong>Statut :</strong> {{ $order->status }}</p>
        <p><strong>Total :</strong> {{ number_format($order->total,2) }} FC</p>
        <p><strong>Adresse :</strong> {{ $order->address }}</p>
        <p class="text-sm text-gray-500 mt-4">Créée le {{ $order->created_at->format('Y-m-d H:i') }}</p>
    </div>
    
    <div class="bg-white p-6 rounded shadow mt-6">
        <h2 class="text-lg font-semibold mb-3">Articles</h2>
        @if($order->items && $order->items->count())
            <table class="min-w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Produit</th>
                        <th class="px-4 py-2">Quantité</th>
                        <th class="px-4 py-2">Prix</th>
                        <th class="px-4 py-2">Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $item->product?->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $item->quantity }}</td>
                        <td class="px-4 py-2">{{ number_format($item->price,2) }} FC</td>
                        <td class="px-4 py-2">{{ number_format($item->price * $item->quantity,2) }} FC</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-600">Aucun article trouvé pour cette commande.</p>
        @endif
    </div>
</div>
@endsection
