<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\CreateOrderUseCase;
use App\Application\DTOs\CreateOrderDTO;
use App\Domain\Entities\Order;
use App\Domain\Services\AddressServiceInterface;
use App\Domain\Services\CartService;
use App\Presentation\Requests\CreateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function __construct(
        private CreateOrderUseCase $createOrderUseCase,
        private AddressServiceInterface $addressService,
        private CartService $cartService
    ) {}

    public function checkout(): View
    {
        $cart = $this->cartService->getCart();
        
        if (empty($cart)) {
            return redirect()->route('home')->with('error', 'Carrinho vazio. Adicione produtos antes de finalizar a compra.');
        }
        
        $subtotal = $this->cartService->getSubtotal();
        $shippingCost = $this->cartService->getShippingCost();
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal();

        return view('orders.checkout', compact('cart', 'subtotal', 'shippingCost', 'discount', 'total'));
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $orderData = CreateOrderDTO::fromArray($request->validated());
        $order = $this->createOrderUseCase->execute($orderData);

        return response()->json([
            'success' => true,
            'order' => $order,
            'message' => 'Pedido criado com sucesso!'
        ]);
    }

    public function show(Order $order): JsonResponse
    {
        $order->load('items.product', 'coupon');
        
        $html = view('orders.show', compact('order'))->render();
        
        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function getAddressByCep(Request $request): JsonResponse
    {
        $request->validate([
            'cep' => 'required|string'
        ]);

        $address = $this->addressService->getAddressByCep($request->cep);

        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'CEP não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }
} 