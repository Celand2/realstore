@extends('layouts.client')

@section('title', 'Paiement')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-medium uppercase tracking-[0.2em] text-indigo-600">Paiement</p>
                <h1 class="mt-2 text-2xl font-bold text-slate-900">Finaliser la commande</h1>
            </div>
            <span class="rounded-full bg-indigo-50 px-3 py-1 text-sm font-medium text-indigo-700">
                Commande #{{ $orderId ?? '—' }}
            </span>
        </div>

        <div class="space-y-6">
            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Paiement sécurisé</p>
                <p class="mt-1 text-lg font-semibold text-slate-900">Carte bancaire via Stripe</p>
            </div>

            <form id="payment-form" class="space-y-5">
                @csrf
                <input type="hidden" name="orderId" value="{{ $orderId ?? '' }}">

                <div>
                    <label for="amount" class="mb-2 block text-sm font-medium text-slate-700">Montant</label>
                    <input id="amount" name="amount" type="number" min="100" step="1" value="1000" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>

                <div>
                    <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Description</label>
                    <input id="description" name="description" type="text" placeholder="Commande de produits" value="Commande RealStore" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                </div>

                <div id="card-element" class="rounded-xl border border-slate-300 bg-white p-3"></div>

                <div id="payment-message" class="hidden rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-700"></div>

                <button id="submit-button" type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-3 font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 disabled:cursor-not-allowed disabled:bg-indigo-300">
                    Payer maintenant
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ $stripePublicKey ?? '' }}');
const elements = stripe.elements();
const card = elements.create('card');
card.mount('#card-element');

const form = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-button');
const paymentMessage = document.getElementById('payment-message');

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    paymentMessage.classList.add('hidden');
    paymentMessage.textContent = '';
    submitButton.disabled = true;
    submitButton.textContent = 'Traitement...';

    try {
        const payload = {
            amount: Number(document.getElementById('amount').value),
            description: document.getElementById('description').value,
            orderId: Number(document.querySelector('input[name="orderId"]').value || 0) || null,
        };

        const response = await fetch('{{ route('payment.create-intent') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Le paiement n’a pas pu être initialisé.');
        }

        const { error, paymentIntent } = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: {
                card,
                billing_details: {
                    name: '{{ auth()->user()->name ?? 'Client' }}',
                },
            },
        });

        if (error) {
            throw new Error(error.message || 'Erreur de paiement.');
        }

        const confirmResponse = await fetch('{{ route('payment.confirm') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ paymentIntentId: paymentIntent.id }),
        });

        const confirmData = await confirmResponse.json();

        if (!confirmResponse.ok) {
            throw new Error(confirmData.error || 'Le paiement a échoué.');
        }

        window.location.href = '{{ route('payment.success', ['orderId' => $orderId ?? 0]) }}';
    } catch (error) {
        paymentMessage.textContent = error.message || 'Une erreur est survenue.';
        paymentMessage.classList.remove('hidden');
    } finally {
        submitButton.disabled = false;
        submitButton.textContent = 'Payer maintenant';
    }
});
</script>
@endsection
