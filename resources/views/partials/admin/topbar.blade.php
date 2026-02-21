 <!-- Topbar -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold">Tableau de bord</h1>
      <div class="flex items-center space-x-4">
        <button id="openModal" class="relative p-2 rounded hover:bg-gray-200">
          <span class="material-icons">notifications</span>
          <span id="productNumber" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">0</span>
        </button>
        <div class="flex items-center space-x-2 cursor-pointer">
          <img src="https://via.placeholder.com/40" alt="user" class="rounded-full">
          <span class="font-medium">{{ Auth::user()->name }}</span>
        </div>
      </div>
    </div>