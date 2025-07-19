<?php

namespace App\Presentation\Controllers;

use App\Domain\Services\CartService;
use App\Domain\Entities\Coupon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function index(): View
    {
        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->getSubtotal();
        $shippingCost = $this->cartService->getShippingCost();
        $discount = $this->cartService->getDiscount();
        $total = $this->cartService->getTotal();
        $appliedCoupon = $this->cartService->getAppliedCoupon();

        return view('cart.index', compact('cart', 'subtotal', 'shippingCost', 'discount', 'total', 'appliedCoupon'));
    }

    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'variation' => 'nullable|string'
        ]);

        $product = \App\Domain\Entities\Product::findOrFail($request->product_id);
        
        $this->cartService->addItem(
            $product,
            $request->quantity,
            $request->variation
        );

        return response()->json([
            'success' => true,
            'message' => 'Produto adicionado ao carrinho',
            'cart' => $this->cartService->getCart()
        ]);
    }

    public function updateQuantity(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
            'variation' => 'nullable|string'
        ]);

        $this->cartService->updateQuantity(
            $request->product_id,
            $request->quantity,
            $request->variation
        );

        return response()->json([
            'success' => true,
            'cart' => $this->cartService->getCart(),
            'subtotal' => $this->cartService->getSubtotal(),
            'shipping_cost' => $this->cartService->getShippingCost(),
            'discount' => $this->cartService->getDiscount(),
            'total' => $this->cartService->getTotal()
        ]);
    }

    public function removeItem(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'variation' => 'nullable|string'
        ]);

        $this->cartService->removeItem($request->product_id, $request->variation);

        return response()->json([
            'success' => true,
            'cart' => $this->cartService->getCart(),
            'subtotal' => $this->cartService->getSubtotal(),
            'shipping_cost' => $this->cartService->getShippingCost(),
            'discount' => $this->cartService->getDiscount(),
            'total' => $this->cartService->getTotal()
        ]);
    }

    public function applyCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Cupom não encontrado'
            ], 404);
        }

        if (!$this->cartService->applyCoupon($coupon)) {
            return response()->json([
                'success' => false,
                'message' => 'Cupom não pode ser aplicado'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'coupon' => $coupon,
            'discount' => $this->cartService->getDiscount(),
            'total' => $this->cartService->getTotal()
        ]);
    }

    public function removeCoupon(): JsonResponse
    {
        $this->cartService->removeCoupon();

        return response()->json([
            'success' => true,
            'discount' => $this->cartService->getDiscount(),
            'total' => $this->cartService->getTotal()
        ]);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clearCart();

        return response()->json([
            'success' => true,
            'cart' => [],
            'subtotal' => 0,
            'shipping_cost' => 0,
            'discount' => 0,
            'total' => 0
        ]);
    }
} 