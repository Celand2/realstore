@extends('layouts.main')

@section('title', 'Gestion des utilisateurs')

@section('content')

@if (session('status'))
<div class="p-4 mb-6 rounded bg-green-100 text-green-800 shadow">
    {{ session('status') }}
</div>
@endif

@if (session('error'))
<div class="p-4 mb-6 rounded bg-red-100 text-red-800 shadow">
    {{ session('error') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center pb-4">
        <h2 class="text-xl font-bold">Liste des utilisateurs</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition">
            + Ajouter
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-3 border-b text-left">#</th>
                    <th class="p-3 border-b text-left">Nom</th>
                    <th class="p-3 border-b text-left">Email</th>
                    <th class="p-3 border-b text-left">Rôle</th>
                    <th class="p-3 border-b text-left">Créé le</th>
                    <th class="p-3 border-b text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-100">
                        <td class="p-3 border-b">{{ $user->id }}</td>
                        <td class="p-3 border-b">{{ $user->name }}</td>
                        <td class="p-3 border-b">{{ $user->email }}</td>
                        <td class="p-3 border-b">
                            @if($user->role === 'admin')
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">Admin</span>
                            @else
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">Client</span>
                            @endif
                        </td>
                        <td class="p-3 border-b">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                        <td class="p-3 border-b flex space-x-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600 transition">
                                Éditer
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition">
                                        Supprimer
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-6 text-gray-500">
                            Aucun utilisateur trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>

@endsection
