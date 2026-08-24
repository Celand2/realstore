@extends('layouts.client')

@section('title','Mes commandes')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <h1 class="text-2xl font-bold mb-6">Mes commandes</h1>

    @if(count($orders))
        <div class="bg-white rounded shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 border-b text-left">N°</th>
                            <th class="p-3 border-b text-left">Total</th>
                            <th class="p-3 border-b text-left">Statut</th>
                            <th class="p-3 border-b text-left">Date</th>
                            <th class="p-3 border-b text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="p-3 border-b font-medium whitespace-nowrap">#{{ $order->id }}</td>
                                <td class="p-3 border-b whitespace-nowrap">{{ number_format($order->total,2) }} FC</td>
                                <td class="p-3 border-b">
                                    <span class="px-2 py-1 rounded text-xs
                                        @if($order->status === 'completed') bg-green-100 text-green-800
                                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-blue-100 text-blue-800 @endif">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="p-3 border-b whitespace-nowrap">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td class="p-3 border-b text-center">
                                    <div class="flex flex-wrap items-center justify-center gap-2">
                                        <a href="{{ route('orders.show', \Vinkla\Hashids\Facades\Hashids::encode($order->id)) }}"
                                           class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                                            Voir
                                        </a>
                                        @if($order->status === 'pending')
                                            <form action="{{ route('orders.cancel', \Vinkla\Hashids\Facades\Hashids::encode($order->id)) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 transition"
                                                        onclick="return confirm('Annuler cette commande ?')">
                                                    Annuler
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p class="text-gray-600">Aucune commande pour le moment.</p>
    @endif
</div>
@endsection
