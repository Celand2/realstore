<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Reset password</title>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-semibold text-gray-800 text-center mb-6">Reset password</h1>
        <p class="text-sm text-gray-600 text-center mb-6">Enter your email address and we will send you a reset link.</p>

        <form action="/forgot-password" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input type="email" name="email" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition font-medium">
                Send reset link
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            <a href="/login" class="text-indigo-600 hover:underline">Back to login</a>
        </p>
    </div>
</body>
</html>
