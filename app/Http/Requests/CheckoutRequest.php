<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'address' => 'required|string|min:5',
        ];
    }

    public function messages(): array
    {
        return [
            'address.required' => 'L\'adresse de livraison est obligatoire.',
        ];
    }
}
