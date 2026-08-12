@extends('layouts.client')

@section('title', 'Produits')

@section('content')

<div class="space-y-3 mb-6">
      <div class="p-4 rounded bg-green-100 text-green-800 shadow">Succès : l'utilisateur a été ajouté avec succès !</div>

    <!-- Grid -->
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach ($products as $product)
            <!-- Card Produit -->
            <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group">
                
                <!-- Image -->
                <div class="relative overflow-hidden">
                    <img src="{{asset('storage/'.$product->image)}}"
                         alt="Nom du produit"
                         class="w-full h-56 object-cover group-hover:scale-105 transition duration-500">

                    <!-- Badge catégorie -->
                    <span class="absolute top-3 left-3 bg-indigo-600 text-white text-xs px-3 py-1 rounded-full">
                        {{ $product->category->name }}
                    </span>
                </div>

                <!-- Contenu -->
                <div class="p-5 flex flex-col justify-between h-[250px]">

                    <div>
                        <!-- Nom -->
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ $product->name }}
                        </h3>

                        <!-- Description -->
                        <p class="text-gray-500 text-sm line-clamp-3">
                            {{ $product->description }}
                        </p>
                    </div>

                    <!-- Prix + Boutons -->
                    <div class="mt-5">
                        
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xl font-bold text-indigo-600">
                                ${{ $product->price }}
                            </span>
                        </div>

                        <div class="flex gap-2">
                            <!-- Bouton détails -->
                            <button class="w-1/2 border border-indigo-600 text-indigo-600 py-2 rounded-lg text-sm font-medium hover:bg-indigo-50 transition">
                                Voir détails
                            </button>

                            <!-- Bouton Add to cart -->
                            <button onclick="addTocart({{ json_encode($product) }})" class="w-1/2 bg-indigo-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                                Add to cart
                            </button>
                        </div>

                    </div>

                </div>
            </div>
            <!-- Fin Card -->
            @endforeach

        </div>
        {{-- Modal --}}
<div id="modalOverlay"
    class="fixed inset-0 bg-black/60 backdrop-blur-md hidden items-center justify-center z-50">

    <!-- Modal -->
    <div id="modalContent"
        class="bg-white w-full max-w-6xl h-[90vh] mx-6 rounded-3xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 flex flex-col overflow-hidden">

        <!-- Header -->
        <div class="flex justify-between items-center border-b px-10 py-6 bg-gray-50">
            <h2 class="text-2xl font-bold text-gray-800">
                Mon Panier
            </h2>
            <button id="closeModal"
                class="text-gray-400 hover:text-gray-700 text-3xl leading-none">
                &times;
            </button>
        </div>

        <!-- Body -->
        <div id="cartItems" class="flex-1 p-8 overflow-y-auto space-y-6 bg-white"></div>

        <!-- START LOOP PRODUCT -->
        <template id="cartItemTemplate">
            <div class="grid grid-cols-12 items-center gap-6 border rounded-2xl p-6 hover:shadow-lg transition">

                <!-- Product Info -->
                <div class="col-span-4 space-y-2">
                    <h3 class="productName text-xl font-semibold text-gray-800"></h3>
                    <p class="productPrice text-gray-500"> </p>
                    <p class="productDescription text-sm text-gray-400"></p>
                </div>

                <!-- Quantity -->
                <div class="col-span-3 flex items-center justify-center gap-4">
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-200 hover:bg-gray-300 transition text-lg"> -</button>
                    <span class="productQuantity w-12 text-center text-lg font-semibold"></span>
                    <button class="w-10 h-10 flex items-center justify-center rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition text-lg"> + </button>
                </div>

                <!-- Total + Remove --> 
                <div class="col-span-3 flex flex-col items-end gap-4">
                    <span class="totalPrice text-xl font-bold text-gray-800"></span>
                    <button class="text-red-500 hover:text-red-700 font-medium">Supprimer</button>
                </div>
            </div>
        </template>
    
        <!-- END LOOP PRODUCT -->

        <!-- Footer -->
        <div class="border-t bg-gray-50 px-10 py-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex gap-3">
                <button id="cancelBtn"
                    class="px-6 py-3 bg-gray-200 rounded-xl hover:bg-gray-300 transition font-medium">
                    Annuler
                </button>
                <button id="btnSave"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-medium">
                    Confirmer
                </button>
            </div>

            <div class="text-right mt-4 md:mt-0">
                <span id='productNumber' class="text-xl font-semibold text-gray-700">Total :</span>
                <span id='total' class="text-2xl font-bold text-indigo-600 ml-2">00  BIF</span>
            </div>
        </div>
    </div>
</div>

        
@endsection

        @push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function(){

    let cart = {
        userID: {{ Auth::id() }},
        products: []
    };

    const openBtn = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelBtn');
    const overlay = document.getElementById('modalOverlay');
    const modal = document.getElementById('modalContent');
    const cartItemsContainer = document.getElementById('cartItems');
    const cartItemTemplate = document.getElementById('cartItemTemplate');
    const productNumber = document.getElementById('productNumber');
    const btnSave = document.getElementById('btnSave');

    function addTocart(product){

        let existing = cart.products.find(p => p.id === product.id);

        if(existing){
            existing.quantity += 1;
        }else{
            cart.products.push({
                id: product.id,
                name: product.name,
                price: product.price,
                description: product.description,
                quantity: 1
            });
        }

        productNumber.textContent = cart.products.length;
        updateTotal();
    }

    window.addTocart = addTocart;

    function saveCart(){
        axios.post('/client/add-cart', cart)
            .then(response => {
                console.log(response.data);
                closeModal();
            })
            .catch(error => {
                console.error('Error saving cart:', error);
            });
    }

    function viewCart(){

        cartItemsContainer.innerHTML = '';

        cart.products.forEach(product => {

            const clone = cartItemTemplate.content.cloneNode(true);

            clone.querySelector('.productName').textContent = product.name;
            clone.querySelector('.productDescription').textContent = product.description;
            clone.querySelector('.productPrice').textContent = product.price + " BIF";
            clone.querySelector('.productQuantity').textContent = product.quantity;
            clone.querySelector('.totalPrice').textContent = (product.price * product.quantity) + " BIF";

            cartItemsContainer.appendChild(clone);
        });

        updateTotal();
    }

    function calculateTotal(){
        return cart.products.reduce((sum, product) => {
            return sum + (product.price * product.quantity);
        }, 0);
    }

    function updateTotal(){
        document.getElementById('total').textContent = calculateTotal() + " BIF";
    }

    function openModal(){
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');

        viewCart();

        setTimeout(() => {
            modal.classList.remove('scale-95','opacity-0');
            modal.classList.add('scale-100','opacity-100');
        },10);
    }

    function closeModal(){
        modal.classList.remove('scale-100','opacity-100');
        modal.classList.add('scale-95','opacity-0');

        setTimeout(()=>{
            overlay.classList.add('hidden');
            overlay.classList.remove('flex');
        },200);
    }

    openBtn.addEventListener('click', openModal);
    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    btnSave.addEventListener('click', saveCart);

});


</script>

@endpush
