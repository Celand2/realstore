@extends('layouts.main')

@section('title','Orders')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Orders</h1>

    <div class="bg-white rounded shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 border-b text-left">#</th>
                        <th class="px-4 py-2 border-b text-left">User</th>
                        <th class="px-4 py-2 border-b text-right">Total</th>
                        <th class="px-4 py-2 border-b text-left">Status</th>
                        <th class="px-4 py-2 border-b text-left">Created</th>
                        <th class="px-4 py-2 border-b text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $order->id }}</td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">{{ $order->user?->name }}</td>
                            <td class="px-4 py-2 border-b text-right whitespace-nowrap">{{ number_format($order->total,2) }}</td>
                            <td class="px-4 py-2 border-b">
                                <span class="px-2 py-1 rounded text-xs
                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border-b whitespace-nowrap">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 border-b text-center">
                                <a href="{{ route('admin.orders.show', \Vinkla\Hashids\Facades\Hashids::encode($order->id)) }}"
                                   class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Aucune commande.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
