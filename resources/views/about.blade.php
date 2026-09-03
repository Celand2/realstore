@extends('layouts.guest')

@section('title','Company')

@section('content')

  <section class="bg-indigo-700 text-white px-4 sm:px-6 py-16 sm:py-24 text-center">
    <h1 class="text-4xl sm:text-5xl font-bold">About FintechX</h1>
  </section>

  <section class="max-w-7xl mx-auto px-4 sm:px-6 py-16 sm:py-24 lg:py-28 grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
    <div>
      <h2 class="text-3xl font-bold mb-6">Our Vision</h2>
      <p class="mb-6">
        We believe financial infrastructure should be accessible, secure
        and adaptable for emerging economies.
      </p>
      <p>
        FintechX bridges traditional finance and modern digital platforms.
      </p>
    </div>
    <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216"
      class="w-full rounded-2xl shadow">
  </section>

  <section class="bg-white py-28">
    <div class="max-w-xl mx-auto px-4 sm:px-6">
      <h2 class="text-3xl font-bold text-center mb-10">Contact Us</h2>
      <form class="space-y-6">
        <input class="w-full border p-4 rounded" placeholder="Full Name">
        <input class="w-full border p-4 rounded" placeholder="Email Address">
        <textarea class="w-full border p-4 rounded" rows="5" placeholder="Message"></textarea>
        <button class="w-full bg-indigo-700 text-white py-4 rounded font-semibold">
          Submit
        </button>
      </form>
    </div>
  </section>

@endsection

