<!-- NAVBAR -->
<header class="bg-white shadow sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <span class="text-2xl font-bold text-indigo-600">FintechX</span>
    <nav class="space-x-8 font-medium">
      <a href="/">Home</a>
      <a href="/products" class="text-indigo-600 font-medium">Solutions</a>
      <a href="/about">Company</a>
      @auth
        <a href="/profile">Profile</a>
        <form method="POST" action="/logout" class="inline">
            @csrf
            <button type="submit" class="text-gray-700">Logout</button>
        </form>
      @else
        <a href="/login">Login</a>
      @endauth
    </nav>
  </div>
</header>