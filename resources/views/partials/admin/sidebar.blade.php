  <!-- Sidebar statique -->
  <aside class="w-72 bg-white shadow-lg flex flex-col h-screen sticky top-0">

      @if (Auth::user()->role === 'admin')
          <div class="p-6 text-2xl font-bold border-b">Dashboard Admin</div>
          <nav class="flex-1 mt-6 px-4 space-y-2 overflow-y-auto">
              <a href="#" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">dashboard</span> Accueil
              </a>
              <a href="#" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">people</span> Utilisateurs
              </a>
              <a href="/admin/categories" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">bar_chart</span> Products Categories
              </a>
              <a href="/admin/products" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">bar_chart</span> Products List
              </a>
              <a href="/admin/orders" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">receipt_long</span> Orders
              </a>
              <a href="#" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">settings</span> Paramètres
              </a>
          </nav>
      @elseif (Auth::user()->role === 'client')
          <div class="p-6 text-2xl font-bold border-b">Dashboard Client</div>
          <nav class="flex-1 mt-6 px-4 space-y-2 overflow-y-auto">
              <a href="#" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">dashboard</span> Accueil
              </a>
              <a href="#" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">people</span> Utilisateurs
              </a>
              <a href="{{ route('client-get-product')}}" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">bar_chart</span> Products
              </a>
              <a href="#" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">settings</span> Paramètres
              </a>
          </nav>
      @endif
      <div class="p-6 border-t">
          <form action="/logout" method="POST">
              @csrf
              <button type="submit"
                  class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded transition">Logout</button>
          </form>
      </div>
  </aside>
