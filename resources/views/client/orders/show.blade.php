@extends('layouts.client')

@section('title','Détail commande')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <h1 class="text-2xl font-bold mb-6">Commande #{{ $order->id }}</h1>

    <div class="bg-white p-4 sm:p-6 rounded shadow mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <p><strong>Statut :</strong> {{ ['pending' => 'En attente', 'confirmed' => 'Confirmée', 'processing' => 'En préparation', 'shipped' => 'Expédiée', 'delivered' => 'Livrée', 'cancelled' => 'Annulée'][$order->status] ?? $order->status }}</p>
            <p><strong>Total :</strong> {{ number_format($order->total,2) }} FC</p>
            <p class="sm:col-span-2"><strong>Adresse :</strong> {{ $order->address }}</p>
        </div>
        <p class="text-sm text-gray-500 mt-4">Créée le {{ $order->created_at->format('Y-m-d H:i') }}</p>
    </div>

    <div class="bg-white p-4 sm:p-6 rounded shadow">
        <h2 class="text-lg font-semibold mb-3">Articles</h2>
        @if($order->items && $order->items->count())
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 border-b text-left">Produit</th>
                            <th class="p-3 border-b text-center">Quantité</th>
                            <th class="p-3 border-b text-right">Prix</th>
                            <th class="p-3 border-b text-right">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b">{{ $item->product?->title ?? 'N/A' }}</td>
                                <td class="p-3 border-b text-center">{{ $item->quantity }}</td>
                                <td class="p-3 border-b text-right whitespace-nowrap">{{ number_format($item->price,2) }} FC</td>
                                <td class="p-3 border-b text-right whitespace-nowrap">{{ number_format($item->price * $item->quantity,2) }} FC</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600">Aucun article trouvé pour cette commande.</p>
        @endif
    </div>
</div>
@endsection
