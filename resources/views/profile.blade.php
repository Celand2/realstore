@extends('layouts.guest')

@section('title','Profile')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <div class="bg-white p-5 sm:p-8 rounded shadow">
        <h2 class="text-2xl font-bold mb-6">Your profile</h2>

        @if(session('status'))
            <div class="mb-4 text-green-600">{{ session('status') }}</div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border px-3 py-2 rounded" />
                @error('name')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border px-3 py-2 rounded" />
                @error('email')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password (optional)</label>
                <input type="password" name="password" class="w-full border px-3 py-2 rounded" />
                @error('password')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full border px-3 py-2 rounded" />
            </div>

            <button class="bg-indigo-600 text-white px-4 py-2 rounded">Update profile</button>
        </form>
    </div>
</div>

@endsection
