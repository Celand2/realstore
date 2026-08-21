@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')

    @if (session('status'))
        <div class="space-y-3 mb-6">
        <div  class="p-4 rounded bg-green-100 text-green-800 shadow">Success: {{ session('status') }}</div>
    </div>
    @endif


    <!-- Formulaire Utilisateur Avancé -->
    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
        <h2 class="text-xl font-bold mb-4">Ajouter un Produit</h2>
        <form action="{{ route('store-product') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div class="col-span-2">
                <select name="category"  class="p-3 border border-gray-300 rounded-lg w-full">
                    <option value="">-- Category ---</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-span-2">
                <input type="text" placeholder="Title" name="name" class="p-3 border border-gray-300 rounded-lg w-full">
            </div>
            @error('name')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
            @enderror
            <div class="col-span-2">
                <input type="number" placeholder="price" name="price"
                    class="p-3 border border-gray-300 rounded-lg w-full">
            </div>
                @error('price')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            
            <div class="col-span-2">
                <textarea placeholder="Description" name="description" class="p-3 border border-gray-300 rounded-lg w-full"></textarea>
            </div>
                @error('description')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            <div class="col-span-2">
                <input type="number" placeholder="Stock" name="stock" min="0" value="{{ old('stock', 0) }}" class="p-3 border border-gray-300 rounded-lg w-full">
            </div>
                @error('stock')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            <div class="col-span-2">
                <input type="file" name="image" accept="image/" class=" p-3 border border-gray-300 rounded-lg w-full">
            </div>
                @error('image')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            <button class="bg-blue-600 text-white py-3 rounded-lg col-span-2 hover:bg-blue-700 transition">Ajouter</button>
        </form>
    </div>



@endsection
