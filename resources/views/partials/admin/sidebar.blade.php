  <!-- Sidebar responsive -->
  <aside data-sidebar class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-white shadow-lg transition-transform duration-200 md:sticky md:top-0 md:h-screen md:translate-x-0">

      <div class="flex items-center justify-between border-b p-6">
          <div class="text-xl font-bold sm:text-2xl">Dashboard Admin</div>
          <button data-sidebar-close type="button" class="rounded p-2 text-gray-500 hover:bg-gray-100 md:hidden" aria-label="Fermer le menu">
              <span class="material-icons">close</span>
          </button>
      </div>

      @if (Auth::user()->role === 'admin')
          <nav class="flex-1 mt-6 px-4 space-y-2 overflow-y-auto">
              <a href="#" class="flex items-center p-3 rounded hover:bg-gray-100">
                  <span class="material-icons mr-3">dashboard</span> Accueil
              </a>
              <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 rounded hover:bg-gray-100">
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
