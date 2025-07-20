<?php

namespace App\Presentation\Controllers;

use App\Domain\Entities\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class WebhookController extends Controller
{
    public function updateOrderStatus(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
            'status' => 'required|string|in:pending,confirmed,shipped,delivered,cancelled'
        ]);

        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pedido não encontrado'
            ], 404);
        }

        if ($request->status === 'cancelled') {
            $this->restoreInventory($order);
            $order->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pedido cancelado e removido'
            ]);
        }

        $order->updateStatus($request->status);

        return response()->json([
            'success' => true,
            'message' => 'Status do pedido atualizado',
            'order' => $order
        ]);
    }

    private function restoreInventory(Order $order): void
    {
        foreach ($order->items as $item) {
            $inventory = $item->product->inventory()
                ->where('variation', $item->variation)
                ->first();

            if ($inventory) {
                $inventory->increaseStock($item->quantity);
            }
        }
    }
} 