<!-- NAVBAR -->
<header class="bg-white shadow sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">Realstore</a>
        <nav class="space-x-6 font-medium flex items-center">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Accueil</a>
            <a href="{{ route('products') }}" class="hover:text-indigo-600 transition">Produits</a>
            <a href="{{ route('about') }}" class="hover:text-indigo-600 transition">À propos</a>

            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="/admin/dashboard" class="text-purple-600">Admin</a>
                @else
                    <a href="/client/dashboard" class="text-indigo-600">Mon espace</a>
                @endif
                <a href="/profile">Profil</a>
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-700">Déconnexion</button>
                </form>
            @else
                <a href="/login" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Connexion</a>
                <a href="/register" class="border border-indigo-600 text-indigo-600 px-4 py-2 rounded-lg hover:bg-indigo-50 transition">Inscription</a>
            @endauth
        </nav>
    </div>
</header>
