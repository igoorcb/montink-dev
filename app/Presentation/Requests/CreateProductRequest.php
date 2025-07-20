<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock.quantity' => 'required|integer|min:0',
            'stock.variation' => 'nullable|string',
            'stock.min_quantity' => 'nullable|integer|min:0',
            'variations' => 'nullable|array',
            'variations.*' => 'string'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome do produto é obrigatório',
            'price.required' => 'O preço é obrigatório',
            'price.numeric' => 'O preço deve ser um número',
            'price.min' => 'O preço deve ser maior que zero',
            'stock.quantity.required' => 'A quantidade em estoque é obrigatória',
            'stock.quantity.integer' => 'A quantidade deve ser um número inteiro',
            'stock.quantity.min' => 'A quantidade deve ser maior ou igual a zero'
        ];
    }
} 