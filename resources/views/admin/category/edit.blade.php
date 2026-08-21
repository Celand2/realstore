@extends('layouts.main')

@section('title', 'Modifier la Catégorie')

@section('content')

@if (session('status'))
<div class="p-4 mb-6 rounded bg-green-100 text-green-800 shadow">
    {{ session('status') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-lg mb-6 max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Modifier la catégorie</h2>
        <a href="{{ route('list-categories') }}" class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300 transition">
            ← Retour
        </a>
    </div>

    <form action="{{ route('update-category', $category->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom de la catégorie</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="w-full p-3 border border-gray-300 rounded-lg">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description (optionnel)</label>
            <textarea name="description" rows="4" class="w-full p-3 border border-gray-300 rounded-lg">{{ old('description', $category->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                Enregistrer les modifications
            </button>
            <a href="{{ route('list-categories') }}" class="bg-gray-200 text-gray-800 py-3 px-6 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
        </div>
    </form>
</div>

@endsection
