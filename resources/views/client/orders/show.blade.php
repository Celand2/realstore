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
</div>
@endsection
