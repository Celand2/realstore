@extends('layouts.main')

@section('title', 'Modifier Produit')

@section('content')

@if (session('status'))
<div class="p-4 mb-6 rounded bg-green-100 text-green-800 shadow">
    {{ session('status') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-lg mb-6">
    <h2 class="text-xl font-bold mb-4">Modifier un Produit</h2>

    <form action="{{ route('update-product', $product->id) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="grid grid-cols-1 md:grid-cols-2 gap-4">

        @csrf
        @method('PUT')

        <div class="col-span-2">
            <select name="category" class="p-3 border border-gray-300 rounded-lg w-full">
                <option value="">-- Category ---</option>
                @foreach ($categories as $category) 
                    <option value="{{ $category->id }}" 
                        {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option> 
                @endforeach
            </select>
        </div>

        <div class="col-span-2">
            <input type="text" 
                   name="name" 
                   value="{{ $product->title }}"
                   placeholder="Title"
                   class="p-3 border border-gray-300 rounded-lg w-full">
        </div>

        <div class="col-span-2">
            <input type="number" 
                   name="price"
                   value="{{ $product->price }}"
                   placeholder="Price"
                   class="p-3 border border-gray-300 rounded-lg w-full">
        </div>

        <div class="col-span-2">
            <textarea name="description"
                      placeholder="Description"
                      class="p-3 border border-gray-300 rounded-lg w-full">{{ $product->description }}</textarea>
        </div>

        <div class="col-span-2">
            @if ($product->image)
                <img src="{{ Storage::url($product->image) }}" 
                     alt="{{ $product->title }}" 
                     class="w-20 h-20 object-cover rounded mb-2">
            @endif

            <input type="file" 
                   name="image" 
                   accept="image/*"
                   class="p-3 border border-gray-300 rounded-lg w-full">
        </div>

        <button class="bg-blue-600 text-white py-3 rounded-lg col-span-2 hover:bg-blue-700 transition">
            Modifier
        </button>

    </form>
</div>

@endsection
