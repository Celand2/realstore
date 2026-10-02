<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_form_is_accessible_to_authenticated_users(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/payment/42');

        $response->assertOk();
        $response->assertViewHasAll([
            'stripePublicKey' => config('services.stripe.key'),
            'orderId' => 42,
        ]);
    }

    public function test_webhook_rejects_invalid_payload(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withHeader('Stripe-Signature', 'invalid-signature')
            ->post('/payment/webhook');

        $response->assertStatus(400);
        $this->assertStringContainsString('Invalid payload', $response->getContent());
    }
}
