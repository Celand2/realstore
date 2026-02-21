<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> @yield('title') </title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body class="min-h-screen flex bg-gray-100 font-sans">
    <!-- Sidebar-->
    @include('partials.admin.sidebar')

  <!-- Main Content -->
  <main class="flex-1 p-6 overflow-y-auto">

    <!-- Topbar-->
   @include('partials.admin.topbar')

    @yield('content')

  </main>
  @stack('scripts')
</body>
</html>