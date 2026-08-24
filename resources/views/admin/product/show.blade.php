@extends('layouts.main')

@section('title', 'Détail Produit')

@section('content')

@if (session('status'))
<div class="p-4 mb-6 rounded bg-green-100 text-green-800 shadow">
    {{ session('status') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-lg mb-6 max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Détail du produit</h2>
        <a href="{{ route('get-products') }}" class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300 transition">
            ← Retour
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Image -->
        <div>
            @if ($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->title }}" class="w-full h-80 object-cover rounded-lg shadow">
            @else
                <div class="w-full h-80 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                    Aucune image
                </div>
            @endif
        </div>

        <!-- Détails -->
        <div class="space-y-4">
            <div>
                <span class="text-sm text-gray-500">Catégorie</span>
                <div class="text-lg font-medium">{{ $product->category->name ?? 'N/A' }}</div>
            </div>

            <div>
                <span class="text-sm text-gray-500">Titre</span>
                <div class="text-2xl font-bold">{{ $product->title }}</div>
            </div>

            <div>
                <span class="text-sm text-gray-500">Prix</span>
                <div class="text-2xl font-bold text-indigo-600">{{ number_format($product->price, 2) }} FC</div>
            </div>

            <div>
                <span class="text-sm text-gray-500">Statut</span>
                <div>
                    @if ($product->actif == 1)
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded text-sm font-medium">Actif</span>
                    @else
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded text-sm font-medium">Inactif</span>
                    @endif
                </div>
            </div>

            <div>
                <span class="text-sm text-gray-500">Description</span>
                <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
            </div>

            <div class="flex gap-3 pt-4">
                <a href="{{ route('edit-product', \Vinkla\Hashids\Facades\Hashids::encode($product->id)) }}" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                    Éditer
                </a>
                <form action="{{ route('delete-product', \Vinkla\Hashids\Facades\Hashids::encode($product->id)) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ? Cette action est irréversible.')">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
