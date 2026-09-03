<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ClientErrorTest extends TestCase
{
    public function test_client_error_endpoint_logs_error_details(): void
    {
        Log::shouldReceive('warning')
            ->once()
            ->withArgs(function (string $message, array $context): bool {
                return $message === 'Client-side error'
                    && $context['message'] === 'Cart failed'
                    && $context['context']['operation'] === 'save_cart';
            });

        $response = $this->postJson('/client/log', [
            'message' => 'Cart failed',
            'stack' => 'Error: Cart failed',
            'context' => ['operation' => 'save_cart'],
        ]);

        $response->assertOk()->assertJson(['status' => 'logged']);
    }
}
