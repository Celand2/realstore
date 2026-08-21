@extends('layouts.guest')

@section('title','Solutions')

@section('content')

  <section class="bg-indigo-700 text-white py-24 text-center">
    <h1 class="text-5xl font-bold mb-6">Our Financial Solutions</h1>
    <p class="text-xl max-w-3xl mx-auto">
      Modular fintech products designed to scale with your business.
    </p>
  </section>

  <section class="max-w-7xl mx-auto px-6 py-28 space-y-24">

  <div class="grid lg:grid-cols-2 gap-16 items-center">
    <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3" class="rounded-2xl shadow">
    <div>
      <h2 class="text-3xl font-bold mb-6">Payment Gateway</h2>
      <p class="mb-6">
        Unified payments supporting mobile money, cards and bank transfers.
      </p>
      <ul class="list-disc ml-6 space-y-2">
        <li>Multi-currency support</li>
        <li>Instant settlement</li>
        <li>Fraud protection</li>
      </ul>
    </div>
  </div>

  <div class="grid lg:grid-cols-2 gap-16 items-center">
    <div>
      <h2 class="text-3xl font-bold mb-6">Virtual Cards Issuance</h2>
      <p class="mb-6">
        Create, manage and control virtual cards programmatically.
      </p>
      <ul class="list-disc ml-6 space-y-2">
        <li>Spending limits</li>
        <li>Real-time monitoring</li>
        <li>Merchant controls</li>
      </ul>
    </div>
    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c" class="rounded-2xl shadow">
  </div>

  <div class="grid lg:grid-cols-2 gap-16 items-center">
    <img src="https://images.unsplash.com/photo-1605902711622-cfb43c44367f" class="rounded-2xl shadow">
    <div>
      <h2 class="text-3xl font-bold mb-6">Merchant Dashboard</h2>
      <p class="mb-6">
        Centralized control panel for operations and analytics.
      </p>
      <ul class="list-disc ml-6 space-y-2">
        <li>Transaction analytics</li>
        <li>User management</li>
        <li>Settlement reports</li>
      </ul>
    </div>
  </div>

</section>
@endsection
