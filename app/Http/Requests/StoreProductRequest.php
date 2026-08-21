<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required|numeric|exists:categories,id',
            'stock' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le titre est obligatoire.',
            'price.required' => 'Le prix est obligatoire.',
            'price.numeric' => 'Le prix doit être un nombre.',
            'description.required' => 'La description est obligatoire.',
            'image.required' => 'Une image est obligatoire.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'Formats acceptés : jpeg, png, jpg, gif, svg.',
            'category.required' => 'La catégorie est obligatoire.',
            'category.exists' => 'Cette catégorie n\'existe pas.',
            'stock.required' => 'Le stock est obligatoire.',
            'stock.integer' => 'Le stock doit être un nombre entier.',
        ];
    }
}
