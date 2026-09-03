<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClientErrorController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
            'stack' => ['nullable', 'string', 'max:10000'],
            'context' => ['nullable', 'array'],
        ]);

        Log::warning('Client-side error', [
            'message' => $validated['message'],
            'stack' => $validated['stack'] ?? null,
            'context' => $validated['context'] ?? [],
            'user_id' => $request->user()?->id,
        ]);

        return response()->json(['status' => 'logged']);
    }
}
