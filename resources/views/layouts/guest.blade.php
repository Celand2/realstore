<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Realstore — @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    @include('partials.guest.header')

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-400 py-8 text-center">
        © {{ date('Y') }} Realstore — Tous droits réservés
    </footer>

</body>
</html>
