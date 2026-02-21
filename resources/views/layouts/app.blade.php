<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>FintechX – @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    <!-- NAVBAR -->
    @include('partials.guest.header')

    @yield('content')

<footer class="bg-gray-900 text-gray-400 py-10 text-center">
  © 2026 FintechX — Financial Technology Company
</footer>
 
</body>
</html>