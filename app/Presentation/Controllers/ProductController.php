<?php

namespace App\Presentation\Controllers;

use App\Application\UseCases\CreateProductUseCase;
use App\Domain\Repositories\ProductRepositoryInterface;
use App\Domain\Services\CartService;
use App\Domain\Entities\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CreateProductUseCase $createProductUseCase,
        private CartService $cartService
    ) {}

    public function index(): View
    {
        $products = $this->productRepository->findActive();
        $cart = $this->cartService->getCart();
        
        return view('products.index', compact('products', 'cart'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'stock.quantity' => 'required|integer|min:0',
            'stock.variation' => 'nullable|string',
            'stock.min_quantity' => 'nullable|integer|min:0'
        ]);

        $product = $this->createProductUseCase->execute($request->all());

        return response()->json([
            'success' => true,
            'product' => $product->load('inventory')
        ]);
    }

    public function edit(Product $product): JsonResponse
    {
        return response()->json([
            'name' => $product->name,
            'price' => $product->price,
            'description' => $product->description,
            'variations' => $product->variations,
            'stock' => $product->total_stock
        ]);
    }

    public function update(Request $request, Product $product): JsonResponse
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string',
        'stock.quantity' => 'required|numeric|min:0',
        'stock.variation' => 'nullable|string',
        'stock.min_quantity' => 'nullable|numeric|min:0'
    ]);

    $product = $this->productRepository->update($product, $request->except('stock'));

    if (isset($request->stock)) {
        $this->updateInventory($product, $request->stock);
    }

    return response()->json([
        'success' => true,
        'product' => $product->load('inventory')
    ]);
}

    public function addToCart(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'variation' => 'nullable|string'
        ]);

        if (!$product->hasStock($request->quantity, $request->variation)) {
            return response()->json([
                'success' => false,
                'message' => 'Estoque insuficiente'
            ], 400);
        }

        $this->cartService->addItem($product, $request->quantity, $request->variation);

        return response()->json([
            'success' => true,
            'cart' => $this->cartService->getCart(),
            'subtotal' => $this->cartService->getSubtotal(),
            'shipping_cost' => $this->cartService->getShippingCost(),
            'total' => $this->cartService->getTotal()
        ]);
    }

    private function updateInventory(Product $product, array $stockData): void
{
    $inventory = $product->inventory()
        ->where('variation', $stockData['variation'] ?? null)
        ->first();

    if ($inventory) {
        $inventory->update([
            'quantity' => (int) $stockData['quantity'],
            'min_quantity' => (int) ($stockData['min_quantity'] ?? 0)
        ]);
    } else {
        $product->inventory()->create([
            'variation' => $stockData['variation'] ?? null,
            'quantity' => (int) $stockData['quantity'],
            'min_quantity' => (int) ($stockData['min_quantity'] ?? 0)
        ]);
    }
}
} 