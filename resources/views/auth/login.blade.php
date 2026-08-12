<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

  <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
    <h1 class="text-2xl font-semibold text-gray-800 text-center mb-6">
      Login 
    </h1>

    <form action="/login" method="POST" class="space-y-5">
      @csrf

      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
          Email Address
        </label>
        <input
          type="email" name="email"
          class="w-full rounded-md border  px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          placeholder="andre@gmail.com"
        />
        @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Mot de passe -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Password
        </label>
        <input
          type="password" name="password"
          class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          placeholder="••••••••"
        />
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Options -->
      <div class="flex items-center justify-between text-sm">
        <label class="flex items-center gap-2">
          <input type="checkbox" class="rounded border-gray-300 text-indigo-600">
          <span class="text-gray-600">Remember me</span>
        </label>
        <a href="/forgot-password" class="text-indigo-600 hover:underline">
          forgot password?
        </a>
      </div>

      <!-- Bouton -->
      <button
        type="submit"
        class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition font-medium"
      >
        Login
      </button>
    </form>
    <span>
        <p class="mt-4 text-center text-sm text-gray-600">
            Do you not have an account ? 
            <a href="/register" class="text-indigo-600 hover:underline"> Create account</a>
        </p>
    </span>

  </div>

</body>
</html>
