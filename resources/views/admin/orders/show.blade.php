@extends('layouts.main')

@section('title','Order #'.$order->id)

@section('content')
<div class="container mx-auto">
    <h1 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h1>

    <div class="mb-4">
        <strong>User:</strong> {{ $order->user?->name }}<br>
        <strong>Total:</strong> {{ number_format($order->total,2) }}<br>
        <strong>Status:</strong> {{ $order->status }}<br>
        <strong>Address:</strong> {{ $order->address }}
    </div>

    <h2 class="text-xl font-semibold mb-2">Items</h2>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">Product</th>
                <th class="px-4 py-2">Quantity</th>
                <th class="px-4 py-2">Price</th>
                <th class="px-4 py-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $item->product?->title ?? 'N/A' }}</td>
                <td class="px-4 py-2">{{ $item->quantity }}</td>
                <td class="px-4 py-2">{{ number_format($item->price,2) }}</td>
                <td class="px-4 py-2">{{ number_format($item->price * $item->quantity,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
            @csrf
            <label for="status">Status</label>
            <select name="status" id="status" class="border p-2">
                <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
                <option value="processing" @if($order->status=='processing') selected @endif>Processing</option>
                <option value="completed" @if($order->status=='completed') selected @endif>Completed</option>
                <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
            </select>
            <button class="ml-2 px-4 py-2 bg-blue-600 text-white">Update</button>
        </form>
    </div>
</div>
@endsection
