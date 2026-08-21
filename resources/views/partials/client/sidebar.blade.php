<!-- Client Sidebar -->
<aside data-sidebar class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-white shadow-lg transition-transform duration-200 md:sticky md:top-0 md:h-screen md:translate-x-0">
    <div class="flex items-center justify-between border-b p-6">
        <div class="text-xl font-bold sm:text-2xl">Espace Client</div>
        <button data-sidebar-close type="button" class="rounded p-2 text-gray-500 hover:bg-gray-100 md:hidden" aria-label="Fermer le menu">
            <span class="material-icons">close</span>
        </button>
    </div>
    <nav class="flex-1 mt-6 px-4 space-y-2 overflow-y-auto">
        <a href="/client/dashboard" class="flex items-center p-3 rounded hover:bg-gray-100">
            <span class="material-icons mr-3">home</span> Accueil
        </a>
        <a href="/client/products" class="flex items-center p-3 rounded hover:bg-gray-100">
            <span class="material-icons mr-3">shopping_bag</span> Produits
        </a>
        <a href="{{ route('cart.index') }}" class="flex items-center p-3 rounded hover:bg-gray-100">
            <span class="material-icons mr-3">shopping_cart</span> Mon panier
        </a>
        <a href="/client/orders" class="flex items-center p-3 rounded hover:bg-gray-100">
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
