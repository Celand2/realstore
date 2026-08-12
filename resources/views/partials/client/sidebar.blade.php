<!-- Client Sidebar -->
<aside class="w-72 bg-white shadow-lg flex flex-col h-screen sticky top-0">
    <div class="p-6 text-2xl font-bold border-b">Espace Client</div>
    <nav class="flex-1 mt-6 px-4 space-y-2 overflow-y-auto">
        <a href="/client/dashboard" class="flex items-center p-3 rounded hover:bg-gray-100">
            <span class="material-icons mr-3">home</span> Accueil
        </a>
        <a href="/client/products" class="flex items-center p-3 rounded hover:bg-gray-100">
            <span class="material-icons mr-3">shopping_bag</span> Produits
        </a>
        <a href="/cart" class="flex items-center p-3 rounded hover:bg-gray-100">
            <span class="material-icons mr-3">shopping_cart</span> Mon panier
        </a>
        <a href="/orders" class="flex items-center p-3 rounded hover:bg-gray-100">
            <span class="material-icons mr-3">receipt_long</span> Mes commandes
        </a>
        <a href="/profile" class="flex items-center p-3 rounded hover:bg-gray-100">
            <span class="material-icons mr-3">person</span> Mon profil
        </a>
    </nav>
    <div class="p-6 border-t">
        <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded transition">Logout</button>
        </form>
    </div>
</aside>
