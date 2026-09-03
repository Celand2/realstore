@extends('layouts.guest')

@section('title','Digital Financial Infrastructure')

@section('content')
  <!-- HERO -->
  <section class="bg-indigo-700 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-24 lg:py-32 grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
      <div>
        <h1 class="text-4xl sm:text-5xl font-bold leading-tight mb-6 sm:mb-8">
          Financial Infrastructure for the Next Generation of Businesses
        </h1>
        <p class="text-lg sm:text-xl mb-8 sm:mb-10 text-indigo-100">
          We provide secure payment systems, virtual cards and financial APIs
          powering fintech innovation across emerging markets.
        </p>
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
          <a href="{{ route('products') }}" class="text-center bg-white text-indigo-700 px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold">
            Our Solutions
          </a>
          <a href="{{ route('about') }}" class="text-center border border-white px-6 sm:px-8 py-3 sm:py-4 rounded-lg font-semibold">
            About FintechX
          </a>
        </div>
      </div>

        <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d"
          class="w-full rounded-2xl shadow-2xl">
    </div>
  </section>

  <!-- TRUST -->
  <section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
      <h2 class="text-3xl font-bold mb-12">Trusted by growing businesses</h2>
      <div class="grid md:grid-cols-4 gap-10 text-gray-500 font-semibold">
        <div>Startups</div>
        <div>Banks</div>
        <div>Merchants</div>
        <div>NGOs</div>
      </div>
    </div>
  </section>

  <!-- VALUE PROPOSITION -->
  <section class="py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <h2 class="text-4xl font-bold text-center mb-20">
        Why Companies Choose FintechX
      </h2>

      <div class="grid lg:grid-cols-3 gap-8 lg:gap-14">
        <div class="bg-white p-6 sm:p-10 rounded-2xl shadow">
          <h3 class="text-2xl font-bold mb-4">Enterprise Security</h3>
          <p>PCI-DSS standards, encryption and fraud monitoring built-in.</p>
        </div>
        <div class="bg-white p-6 sm:p-10 rounded-2xl shadow">
          <h3 class="text-2xl font-bold mb-4">Developer First</h3>
          <p>Robust APIs, documentation and sandbox environment.</p>
        </div>
        <div class="bg-white p-6 sm:p-10 rounded-2xl shadow">
          <h3 class="text-2xl font-bold mb-4">Pan-African Reach</h3>
          <p>Mobile money, cards and bank rails in one platform.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="bg-indigo-700 text-white py-24">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center">
      <h2 class="text-4xl font-bold mb-6">
        Ready to build on top of FintechX?
      </h2>
      <p class="text-lg mb-10">
        Join companies transforming digital finance.
      </p>
      <a href="{{ route('about') }}" class="bg-white text-indigo-700 px-10 py-4 rounded-lg font-semibold">
        Contact Sales
      </a>
    </div>
  </section>
@endsection
