<?php

namespace App\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|integer|min:1',
            'status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled'
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'O ID do pedido é obrigatório',
            'order_id.integer' => 'O ID do pedido deve ser um número inteiro',
            'order_id.min' => 'O ID do pedido deve ser maior que zero',
            'status.required' => 'O status é obrigatório',
            'status.in' => 'Status inválido. Valores permitidos: pending, confirmed, shipped, delivered, cancelled'
        ];
    }
} 