@extends('layouts.main')

@section('title','Order #'.$order->id)

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h1>

    <div class="mb-4 bg-white p-4 sm:p-6 rounded shadow">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <p><strong>User:</strong> {{ $order->user?->name }}</p>
            <p><strong>Total:</strong> {{ number_format($order->total,2) }}</p>
            <p><strong>Status:</strong> {{ $order->status }}</p>
            <p class="sm:col-span-2"><strong>Address:</strong> {{ $order->address }}</p>
        </div>
    </div>

    <h2 class="text-xl font-semibold mb-2">Items</h2>
    <div class="bg-white rounded shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 border-b text-left">Product</th>
                        <th class="px-4 py-2 border-b text-center">Quantity</th>
                        <th class="px-4 py-2 border-b text-right">Price</th>
                        <th class="px-4 py-2 border-b text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->items as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $item->product?->title ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b text-center">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 border-b text-right whitespace-nowrap">{{ number_format($item->price,2) }}</td>
                            <td class="px-4 py-2 border-b text-right whitespace-nowrap">{{ number_format($item->price * $item->quantity,2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">Aucun article.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 bg-white p-4 sm:p-6 rounded shadow">
        <form method="POST" action="{{ route('admin.orders.updateStatus', \Vinkla\Hashids\Facades\Hashids::encode($order->id)) }}" class="flex flex-col sm:flex-row sm:items-center gap-3">
            @csrf
            <label for="status" class="font-medium">Status</label>
            <select name="status" id="status" class="border p-2 rounded w-full sm:w-auto">
                <option value="pending" @if($order->status=='pending') selected @endif>Pending</option>
                <option value="processing" @if($order->status=='processing') selected @endif>Processing</option>
                <option value="completed" @if($order->status=='completed') selected @endif>Completed</option>
                <option value="cancelled" @if($order->status=='cancelled') selected @endif>Cancelled</option>
            </select>
            <button type="submit"
                    class="w-full sm:w-auto bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Update
            </button>
        </form>
    </div>
</div>
@endsection
