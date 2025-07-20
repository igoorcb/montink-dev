<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'cep' => 'required|string',
            'number' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'O nome do cliente é obrigatório',
            'customer_email.required' => 'O email do cliente é obrigatório',
            'customer_email.email' => 'O email deve ser válido',
            'address.required' => 'O endereço é obrigatório',
            'city.required' => 'A cidade é obrigatória',
            'state.required' => 'O estado é obrigatório',
            'cep.required' => 'O CEP é obrigatório',
            'number.required' => 'O número é obrigatório',
            'neighborhood.required' => 'O bairro é obrigatório'
        ];
    }
} 