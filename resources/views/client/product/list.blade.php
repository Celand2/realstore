@extends('layouts.client')

@section('title', 'Produits')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')

@if(session('status'))
    <div class="mb-4 p-3 rounded bg-green-100 text-green-800 shadow">{{ session('status') }}</div>
@endif

<form method="GET" action="{{ route('client-get-product') }}" class="grid md:grid-cols-4 gap-3 mb-8">
    <input name="q" value="{{ request('q') }}" type="search" placeholder="Rechercher un produit" class="border rounded-lg px-4 py-2 md:col-span-2">
    <select name="category" class="border rounded-lg px-4 py-2">
        <option value="">Toutes les catégories</option>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
        @endforeach
    </select>
    <select name="sort" class="border rounded-lg px-4 py-2">
        <option value="">Plus récents</option>
        <option value="price_asc" @selected(request('sort') === 'price_asc')>Prix croissant</option>
        <option value="price_desc" @selected(request('sort') === 'price_desc')>Prix décroissant</option>
    </select>
    <button type="submit" class="md:col-span-4 bg-indigo-600 text-white rounded-lg px-4 py-2 hover:bg-indigo-700">Rechercher</button>
</form>

<!-- Grid -->
@if($products->isEmpty())
    <p class="text-gray-600">Aucun produit ne correspond à votre recherche.</p>
@else
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
    @foreach ($products as $product)
    <!-- Card Produit -->
    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">

        <!-- Image -->
        <div class="relative overflow-hidden">
            <img src="{{asset('storage/'.$product->image)}}"
                 alt="Nom du produit"
                 class="w-full h-40 sm:h-48 md:h-56 object-cover group-hover:scale-105 transition duration-500">

            <!-- Badge catégorie -->
            <span class="absolute top-3 left-3 bg-indigo-600 text-white text-xs px-3 py-1 rounded-full">
                {{ $product->category->name }}
            </span>
        </div>

        <!-- Contenu -->
        <div class="p-4 sm:p-5 flex flex-col justify-between">

            <div>
                <!-- Nom -->
                <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-1 line-clamp-2">
                    {{ $product->title }}
                </h3>
            </div>

            <!-- Prix + Boutons -->
            <div class="mt-4">

                <div class="flex items-center justify-between mb-3">
                    <span class="text-lg sm:text-xl font-bold text-indigo-600">
                        {{ number_format($product->price, 2) }} FC
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row gap-2">
                    <!-- Bouton détails -->
                    <button onclick='showDetails({{ json_encode($product) }})' class="w-full sm:w-1/2 border border-indigo-600 text-indigo-600 py-2 rounded-lg text-sm font-medium hover:bg-indigo-50 transition">
                        Voir détails
                    </button>

                    <!-- Bouton Add to cart -->
                    <button onclick="addTocart({{ json_encode($product) }})" class="w-full sm:w-1/2 bg-indigo-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                        Add to cart
                    </button>
                </div>

            </div>

        </div>
    </div>
    <!-- Fin Card -->
    @endforeach

</div>
<div class="mt-8">{{ $products->links() }}</div>
@endif

{{-- Modal --}}
<div id="modalOverlay"
    class="fixed inset-0 bg-black/60 backdrop-blur-md hidden items-center justify-center z-50 p-2 sm:p-4">

    <!-- Modal -->
    <div id="modalContent"
        class="bg-white w-full max-w-4xl h-[90vh] max-h-[95vh] mx-auto rounded-2xl sm:rounded-3xl shadow-2xl transform scale-100 opacity-100 transition-all duration-300 flex flex-col overflow-hidden">

        <!-- Header -->
        <div class="flex justify-between items-center border-b px-4 sm:px-10 py-4 sm:py-6 bg-gray-50">
            <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
                Mon Panier
            </h2>
            <button id="closeModal"
                class="text-gray-400 hover:text-gray-700 text-3xl leading-none">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div id="cartItems" class="flex-1 min-h-0 p-4 sm:p-8 overflow-y-auto space-y-4 sm:space-y-6 bg-white"></div>

        <!-- START LOOP PRODUCT -->
        <template id="cartItemTemplate">
            <div class="grid grid-cols-12 items-center gap-3 sm:gap-6 border rounded-xl sm:rounded-2xl p-3 sm:p-6 hover:shadow-lg transition">

                <!-- Product Info -->
                <div class="col-span-12 sm:col-span-5 space-y-1">
                    <h3 class="productName text-base sm:text-lg font-semibold text-gray-800"></h3>
                    <p class="productDescription text-xs sm:text-sm text-gray-500 line-clamp-2"></p>
                    <p class="productPrice text-sm font-medium text-indigo-600"></p>
                </div>

                <!-- Quantity -->
                <div class="col-span-8 sm:col-span-4 flex items-center justify-start sm:justify-center gap-2 sm:gap-3">
                    <button type="button" class="decreaseBtn w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg sm:rounded-xl bg-gray-200 hover:bg-gray-300 transition text-base sm:text-lg font-bold">−</button>
                    <span class="productQuantity w-10 sm:w-12 text-center text-base sm:text-lg font-semibold"></span>
                    <button type="button" class="increaseBtn w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-lg sm:rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition text-base sm:text-lg font-bold">+</button>
                </div>

                <!-- Remove -->
                <div class="col-span-4 sm:col-span-3 flex items-center justify-end">
                    <span class="totalPrice mr-3 text-sm sm:text-base font-bold text-gray-800"></span>
                    <button class="removeBtn text-red-500 hover:text-red-700 text-sm font-medium">Supprimer</button>
                </div>
            </div>
        </template>

        <!-- END LOOP PRODUCT -->

        <!-- Footer -->
        <div class="border-t bg-gray-50 px-4 sm:px-10 py-4 sm:py-6 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
                <button id="cancelBtn"
                    class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-3 bg-gray-200 rounded-lg sm:rounded-xl hover:bg-gray-300 transition font-medium text-sm sm:text-base">
                    Annuler
                </button>
                <button id="btnSave"
                    class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-3 bg-indigo-600 text-white rounded-lg sm:rounded-xl hover:bg-indigo-700 transition font-medium text-sm sm:text-base">
                    Confirmer
                </button>
            </div>

            <div class="text-right">
                <span class="text-base sm:text-xl font-semibold text-gray-700">Total :</span>
                <span id='total' class="text-xl sm:text-2xl font-bold text-indigo-600 ml-2">00 FC</span>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div id="productModalOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-md hidden items-center justify-center z-50 p-4">
    <div id="productModalContent" class="bg-white w-full max-w-3xl rounded-2xl sm:rounded-3xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
        <div class="p-4 sm:p-6">
            <div class="flex justify-between items-start gap-3">
                <div class="min-w-0">
                    <h2 id="pd_name" class="text-xl sm:text-2xl font-bold"></h2>
                    <p id="pd_category" class="text-sm text-gray-500"></p>
                </div>
                <button id="pd_close" class="text-3xl leading-none shrink-0">&times;</button>
            </div>
            <div class="mt-4 flex flex-col sm:flex-row gap-4 sm:gap-6">
                <img id="pd_image" src="" alt="" class="w-full sm:w-48 h-48 object-cover rounded">
                <div class="min-w-0">
                    <p id="pd_description" class="text-gray-700 text-sm sm:text-base"></p>
                    <p class="mt-4 text-xl font-bold text-indigo-600" id="pd_price"></p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
