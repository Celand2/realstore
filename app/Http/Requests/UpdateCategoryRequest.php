<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Vinkla\Hashids\Facades\Hashids;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $decoded = Hashids::decode((string) $this->route('id'));
        $categoryId = $decoded[0] ?? 0;

        return [
            'name' => "required|string|max:255|unique:categories,name,{$categoryId}",
            'description' => 'nullable|string',
        ];
    }
}
