<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Exception\SignatureVerificationException;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;
use UnexpectedValueException;

class PaymentController extends Controller
{
    public function __construct()
    {
        $secretKey = config('services.stripe.secret');

        if (is_string($secretKey) && $secretKey !== '') {
            Stripe::setApiKey($secretKey);
        }
    }

    public function show($orderId = null)
    {
        return view('payment.form', [
            'stripePublicKey' => config('services.stripe.key'),
            'orderId' => $orderId,
        ]);
    }

    public function success($orderId = null)
    {
        return view('payment.success', [
            'orderId' => $orderId,
        ]);
    }

    public function createPaymentIntent(Request $request)
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:100'],
            'description' => ['nullable', 'string'],
            'orderId' => ['nullable', 'integer'],
        ]);

        if (empty(config('services.stripe.secret'))) {
            return response()->json(['error' => 'Le paiement Stripe n\'est pas configuré.'], 500);
        }

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) $validated['amount'],
                'currency' => 'eur',
                'description' => $validated['description'] ?? null,
                'metadata' => [
                    'user_id' => Auth::id(),
                    'order_id' => $validated['orderId'] ?? null,
                ],
            ]);

            Payment::create([
                'user_id' => Auth::id(),
                'stripe_payment_id' => $paymentIntent->id,
                'amount' => (int) $validated['amount'],
                'currency' => 'eur',
                'status' => 'pending',
                'description' => $validated['description'] ?? null,
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function confirm(Request $request)
    {
        $validated = $request->validate([
            'paymentIntentId' => ['required', 'string'],
        ]);

        try {
            $paymentIntent = PaymentIntent::retrieve($validated['paymentIntentId']);

            if ($paymentIntent->status === 'succeeded') {
                $payment = Payment::where('stripe_payment_id', $paymentIntent->id)->first();

                if ($payment) {
                    $payment->update(['status' => 'succeeded']);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Paiement reçu avec succès!',
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Paiement échoué',
            ], 400);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        if ($payload === '' || empty($sigHeader) || empty($endpointSecret)) {
            return response('Invalid payload', 400);
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (UnexpectedValueException|SignatureVerificationException $e) {
            return response('Invalid payload', 400);
        }

        if ($event->type === 'payment_intent.succeeded') {
            $paymentIntent = $event->data->object;

            $payment = Payment::where('stripe_payment_id', $paymentIntent->id)->first();
            if ($payment && $payment->status !== 'succeeded') {
                $payment->update(['status' => 'succeeded']);
            }
        }

        return response('Webhook received', 200);
    }
}
