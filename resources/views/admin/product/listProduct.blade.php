@extends('layouts.main')

@section('title', 'Product List')

@section('content')

@if (session('status'))
<div class="space-y-3 mb-6">
    <div class="p-4 rounded bg-green-100 text-green-800 shadow">
        {{ session('status') }}
    </div>
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-lg">

    <div class="w-full flex justify-between items-center pb-4">
        <h2 class="text-xl font-bold">Liste des Produits</h2>

        <a href="{{ route('add-product') }}"
           class="bg-green-500 text-white text-center py-2 px-4 rounded hover:bg-green-600 transition">
            Ajouter Produit
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 border-b text-left">#</th>
                    <th class="p-3 border-b text-left">Category</th>
                    <th class="p-3 border-b text-left">Title</th>
                    <th class="p-3 border-b text-left">Price</th>
                    <th class="p-3 border-b text-left">Stock</th>
                    <th class="p-3 border-b text-left">Status</th>
                    <th class="p-3 border-b text-left">Image</th>
                    <th class="p-3 border-b text-left">Actions</th>
                </tr>
            </thead>

            <tbody>
                @php $num = 1; @endphp

                @forelse ($products as $product)
                <tr class="hover:bg-gray-100">

                    <td class="p-3 border-b">{{ $num++ }}</td>

                    <td class="p-3 border-b">
                        {{ $product->category->name ?? 'N/A' }}
                    </td>

                    <td class="p-3 border-b">
                        {{ $product->title }}
                    </td>

                    <td class="p-3 border-b">
                        {{ $product->price }} $
                    </td>

                    <td class="p-3 border-b">
                        @if (($product->stock ?? 0) > 0)
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">{{ $product->stock }}</span>
                        @else
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">Rupture</span>
                        @endif
                    </td>

                    <td class="p-3 border-b">
                        @if ($product->actif == 1)
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">
                                Actif
                            </span>
                        @else
                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">
                                Inactif
                            </span>
                        @endif
                    </td>

                    <td class="p-3 border-b">
                        @if ($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                 alt="{{ $product->title }}"
                                 class="w-16 h-16 object-cover rounded">
                        @else
                            <span class="text-gray-500">Aucune image</span>
                        @endif
                    </td>

                    <td class="p-8 border-b flex space-x-2">

                        <a href="{{ route('edit-product', $product->id) }}"
                           class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">
                            Editer
                        </a>

                        <a href="{{ route('show-product', $product->id) }}"
                           class="bg-orange-500 text-white py-1 px-3 rounded hover:bg-orange-600 transition">
                            Voir
                        </a>

                        <form action="{{ route('delete-product', $product->id) }}" method="POST" onsubmit="return confirm('Supprimer ce produit ?')">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">
                                Supprimer
                            </button>
                        </form>

                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-center p-6 text-gray-500">
                        Aucun produit trouvé.
                    </td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

@endsection
