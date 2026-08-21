 <!-- Topbar -->
    <div class="mb-6 flex items-center justify-between gap-3">
      <div class="flex items-center gap-3">
        <button data-sidebar-toggle type="button" class="rounded p-2 hover:bg-gray-200 md:hidden" aria-label="Ouvrir le menu">
          <span class="material-icons">menu</span>
        </button>
        <h1 class="text-2xl font-bold sm:text-3xl">Tableau de bord</h1>
      </div>
      <div class="flex items-center space-x-2 sm:space-x-4">
        <button id="openModal" class="relative p-2 rounded hover:bg-gray-200">
          <span class="material-icons">notifications</span>
          <span id="productNumber" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">0</span>
        </button>
        <div class="flex items-center space-x-2 cursor-pointer">
          <img src="https://via.placeholder.com/40" alt="user" class="rounded-full">
          <span class="hidden font-medium sm:inline">{{ Auth::user()->name }}</span>
        </div>
      </div>
    </div>