@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')

    <!-- Alert Boxes -->
    <div class="space-y-3 mb-6">
      <div class="p-4 rounded bg-green-100 text-green-800 shadow">Succès : l'utilisateur a été ajouté avec succès !</div>
      <div class="p-4 rounded bg-red-100 text-red-800 shadow">Erreur : impossible de supprimer cet utilisateur.</div>
      <div class="p-4 rounded bg-yellow-100 text-yellow-800 shadow">Attention : certains champs sont manquants.</div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
          <p class="text-gray-500">Total Utilisateurs</p>
          <h2 class="text-2xl font-bold">128</h2>
        </div>
        <span class="material-icons text-4xl text-blue-600">people</span>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
          <p class="text-gray-500">Nouvelles Inscriptions</p>
          <h2 class="text-2xl font-bold">24</h2>
        </div>
        <span class="material-icons text-4xl text-green-600">person_add</span>
      </div>
      <div class="bg-white p-6 rounded-lg shadow-lg flex items-center justify-between">
        <div>
          <p class="text-gray-500">Messages</p>
          <h2 class="text-2xl font-bold">12</h2>
        </div>
        <span class="material-icons text-4xl text-yellow-600">message</span>
      </div>
    </div>

    <!-- Formulaire Utilisateur Avancé -->
    <div class="bg-white p-6 rounded-lg shadow-lg mb-6">
      <h2 class="text-xl font-bold mb-4">Ajouter un utilisateur</h2>
      <form class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="text" placeholder="Nom" class="p-3 border border-gray-300 rounded-lg">
        <input type="text" placeholder="Prénom" class="p-3 border border-gray-300 rounded-lg">
        <input type="email" placeholder="Email" class="p-3 border border-gray-300 rounded-lg">
        <input type="tel" placeholder="Téléphone" class="p-3 border border-gray-300 rounded-lg">
        <select class="p-3 border border-gray-300 rounded-lg">
          <option>Sexe</option>
          <option>Homme</option>
          <option>Femme</option>
        </select>
        <input type="date" class="p-3 border border-gray-300 rounded-lg">
        <textarea placeholder="Adresse" class="p-3 border border-gray-300 rounded-lg col-span-2"></textarea>
        <input type="file" class="col-span-2">
        <button class="bg-blue-600 text-white py-3 rounded-lg col-span-2 hover:bg-blue-700 transition">Ajouter</button>
      </form>
    </div>

    <!-- Tableau Utilisateurs Avancé avec boutons -->
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <h2 class="text-xl font-bold mb-4">Liste des utilisateurs</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="p-3 border-b">Nom</th>
              <th class="p-3 border-b">Email</th>
              <th class="p-3 border-b">Téléphone</th>
              <th class="p-3 border-b">Sexe</th>
              <th class="p-3 border-b">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr class="hover:bg-gray-100">
              <td class="p-3 border-b">Daniel Sivyolo</td>
              <td class="p-3 border-b">daniel@example.com</td>
              <td class="p-3 border-b">+257 123 456</td>
              <td class="p-3 border-b">Homme</td>
              <td class="p-3 border-b flex space-x-2">
                <button class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">Editer</button>
                <button class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 transition">Voir</button>
                <button class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">Supprimer</button>
              </td>
            </tr>
            <tr class="hover:bg-gray-100">
              <td class="p-3 border-b">Marie Dupont</td>
              <td class="p-3 border-b">marie@example.com</td>
              <td class="p-3 border-b">+257 987 654</td>
              <td class="p-3 border-b">Femme</td>
              <td class="p-3 border-b flex space-x-2">
                <button class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">Editer</button>
                <button class="bg-green-500 text-white py-1 px-3 rounded hover:bg-green-600 transition">Voir</button>
                <button class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">Supprimer</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

@endsection
