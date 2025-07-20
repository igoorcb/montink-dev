<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\UpdateOrderStatusUseCase;
use App\Application\DTOs\UpdateOrderStatusDTO;
use App\Presentation\Requests\UpdateOrderStatusRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class WebhookController extends Controller
{
    public function __construct(
        private UpdateOrderStatusUseCase $updateOrderStatusUseCase
    ) {}

    public function updateOrderStatus(UpdateOrderStatusRequest $request): JsonResponse
    {
        try {
            $orderData = UpdateOrderStatusDTO::fromArray($request->validated());
            $order = $this->updateOrderStatusUseCase->execute($orderData->orderId, $orderData->status);

            $message = $orderData->status === 'cancelled' 
                ? 'Pedido cancelado e removido' 
                : 'Status do pedido atualizado';

            return response()->json([
                'success' => true,
                'message' => $message,
                'order' => $order
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }
} 