<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="min-h-screen bg-gray-100 font-sans md:flex">
    <!-- Sidebar (client partial) -->
    @include('partials.client.sidebar')
    <div data-sidebar-overlay class="fixed inset-0 z-40 hidden bg-black/50 md:hidden" aria-hidden="true"></div>

    <!-- Main Content -->
    <main class="min-w-0 flex-1 p-4 overflow-y-auto sm:p-6">

        <!-- Topbar (shared with admin partials) -->
        @include('partials.client.topbar')

        @yield('content')

    </main>
    @stack('scripts')
</body>

</html>