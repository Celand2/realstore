@extends('layouts.client')

@section('title', 'Paiement validé')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="rounded-2xl bg-white p-8 text-center shadow-sm ring-1 ring-emerald-200">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-100 text-2xl text-emerald-600">
            ✓
        </div>
        <h1 class="text-3xl font-bold text-slate-900">Paiement validé</h1>
        <p class="mt-3 text-slate-600">
            Votre commande a bien été enregistrée et le paiement a été accepté.
        </p>

        @if($orderId)
            <p class="mt-4 text-sm text-slate-500">Commande #{{ $orderId }}</p>
        @endif

        <div class="mt-6">
            <a href="{{ route('client.dashboard') }}" class="inline-flex rounded-xl bg-emerald-600 px-5 py-3 font-medium text-white transition hover:bg-emerald-700">
                Retour au dashboard
            </a>
        </div>
    </div>
</div>
@endsection
