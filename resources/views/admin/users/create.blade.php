@extends('layouts.main')

@section('title', 'Créer un utilisateur')

@section('content')

<div class="bg-white p-6 rounded-lg shadow-lg mb-6 max-w-2xl">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Créer un utilisateur</h2>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-800 py-2 px-4 rounded hover:bg-gray-300 transition">
            ← Retour
        </a>
    </div>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full p-3 border border-gray-300 rounded-lg">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full p-3 border border-gray-300 rounded-lg">
            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
            <select name="role" class="w-full p-3 border border-gray-300 rounded-lg">
                <option value="client" {{ old('role') === 'client' ? 'selected' : '' }}>Client</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input type="password" name="password" class="w-full p-3 border border-gray-300 rounded-lg">
            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full p-3 border border-gray-300 rounded-lg">
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                Créer l'utilisateur
            </button>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-800 py-3 px-6 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
        </div>
    </form>
</div>

@endsection
