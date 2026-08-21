<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $categoryId = $this->route('id');
        return [
            'name' => "required|string|max:255|unique:categories,name,{$categoryId}",
            'description' => 'nullable|string',
        ];
    }
}
