@extends('layouts.main')

@section('title','Orders')

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Orders</h1>

    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">#</th>
                <th class="px-4 py-2">User</th>
                <th class="px-4 py-2">Total</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Created</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $order->id }}</td>
                <td class="px-4 py-2">{{ $order->user?->name }}</td>
                <td class="px-4 py-2">{{ number_format($order->total,2) }}</td>
                <td class="px-4 py-2">{{ $order->status }}</td>
                <td class="px-4 py-2">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="text-blue-600">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
