@extends('layouts.main')

@section('title', 'Products Category')

@section('content')

    <!-- Alert Boxes -->
    @if (session('status'))
        <div class="space-y-3 mb-6">
        <div  class="p-4 rounded bg-green-100 text-green-800 shadow">Success: {{ session('status') }}</div>
    </div>
    @endif
  
    {{-- <div class="w-full flex justify-between "> --}}
        <!-- Formulaire Utilisateur Avancé -->
        <div class="bg-white p-6 rounded-lg shadow-lg mb-6 ">
            <h2 class="text-xl font-bold mb-4">Ajouter une categorie</h2>
            <form action="{{ route('add-category') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
              @csrf
                <input type="text" placeholder="categorie" name="name" class="p-3 border border-gray-300 rounded-lg col-span-2">
                @error('name')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit"
                    class="bg-blue-600 text-white py-3 rounded-lg col-span-2 hover:bg-blue-700 transition">Ajouter</button>
            </form>
        </div>

        <!-- Tableau Utilisateurs Avancé avec boutons -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-4">Liste des Categories</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 border-b">N°</th>
                            <th class="p-3 border-b">Categorie</th>
                            <th class="p-3 border-b">Date</th>
                            <th class="p-3 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                      @php $numero = 1; @endphp
                      @foreach ($categories as $category)
                          <tr class="hover:bg-gray-100" flex justify-between text-center>
                            <td class="p-3 border-b" text-center>{{ $numero++ }}</td>
                            <td class="p-3 border-b" text-center>{{ $category->name }}</td>
                            <td class="p-3 border-b" text-center>{{ $category->created_at }}</td>
                            <td class="p-3 border-b flex space-x-2 justify-center">
                                <a href="{{ route('edit-category', $category->id) }}">
                                    <button type="button" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">Editer</button>
                                </a>
                                <a href="{{ route('delete-category', $category->id) }}" onclick="return confirm('Supprimer cette catégorie ?')">
                                    <button type="button" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">Supprimer</button>
                                </a>
                            </td>
                        </tr>
                      @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>
    {{-- </div> --}}

@endsection
