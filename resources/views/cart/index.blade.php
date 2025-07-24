@extends('layouts.app')

@section('title', 'Carrinho - mini-erp-dev')

@section('content')
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
        <div class="lg:col-span-3">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="bi bi-cart3 text-primary-600"></i>
                            Seu Carrinho de Compras
                        </h2>
                        @if (count($cart) > 0)
                            <button class="btn-danger" onclick="clearCart()">
                                <i class="bi bi-trash3"></i>
                                Limpar Carrinho
                            </button>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    @if (count($cart) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="px-4 py-3 text-left font-semibold text-gray-900">Produto</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-900">Preço Unit.</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-900">Quantidade</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-900">Total</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-900">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cart as $key => $item)
                                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                                            <td class="px-4 py-4">
                                                <div class="flex items-center">
                                                    <div class="mr-3">
                                                        <i class="bi bi-box-seam text-2xl text-primary-500"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                                        @if ($item['variation'])
                                                            <p class="text-sm text-gray-500">Variação:
                                                                {{ $item['variation'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="price-tag">
                                                    R$ {{ number_format($item['price'], 2, ',', '.') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <input type="number" class="form-input w-20"
                                                    value="{{ $item['quantity'] }}" min="1"
                                                    onchange="updateQuantity('{{ $key }}', this.value)">
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="price-tag">
                                                    R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">
                                                <button class="btn-danger" onclick="removeItem('{{ $key }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <i class="bi bi-cart-x mb-6 text-6xl text-gray-400"></i>
                            <h3 class="mb-2 text-xl font-semibold text-gray-900">Carrinho vazio</h3>
                            <p class="mb-6 text-gray-500">Adicione produtos ao seu carrinho para continuar</p>
                            <a href="{{ route('home') }}" class="btn-primary">
                                <i class="bi bi-arrow-left"></i>
                                Voltar aos Produtos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card sticky top-24">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="bi bi-receipt text-primary-600"></i>
                        Resumo do Pedido
                    </h3>
                </div>

                <div class="card-body">
                    @if (count($cart) > 0)
                        <div class="space-y-4">
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                                <span class="text-gray-700">Subtotal:</span>
                                <span class="font-semibold text-gray-900">R$
                                    {{ number_format($subtotal, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between rounded-lg bg-gray-50 p-3">
                                <span class="text-gray-700">Frete:</span>
                                <span class="font-semibold text-gray-900">R$
                                    {{ number_format($shippingCost, 2, ',', '.') }}</span>
                            </div>
                            @if ($discount > 0)
                                <div class="flex items-center justify-between rounded-lg bg-green-50 p-3">
                                    <span class="text-gray-700">Desconto:</span>
                                    <span class="font-semibold text-green-600">-R$
                                        {{ number_format($discount, 2, ',', '.') }}</span>
                                </div>
                            @endif

                            <hr class="my-4">

                            <div class="mb-6 flex items-center justify-between">
                                <span class="text-lg font-bold text-gray-900">Total:</span>
                                <div class="price-tag text-lg">
                                    R$ {{ number_format($total, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="form-label">Cupom de Desconto</label>
                            <div class="flex">
                                <input type="text" class="form-input rounded-r-none" id="couponCode"
                                    placeholder="Digite o código">
                                <button class="btn-primary rounded-l-none" type="button" onclick="applyCoupon()">
                                    <i class="bi bi-check-circle"></i>
                                </button>
                            </div>
                            @if ($appliedCoupon)
                                <div class="mt-3 rounded-lg border border-green-200 bg-green-50 p-3">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <p class="font-medium text-green-800">Cupom aplicado:
                                                {{ $appliedCoupon->code }}</p>
                                            <p class="text-sm text-green-600">Desconto:
                                                {{ $appliedCoupon->type === 'percentage' ? $appliedCoupon->value . '%' : 'R$ ' . number_format($appliedCoupon->value, 2, ',', '.') }}
                                            </p>
                                        </div>
                                        <button class="btn-danger" onclick="removeCoupon()">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <a href="{{ route('orders.checkout') }}" class="btn-success w-full text-center">
                            <i class="bi bi-check-circle"></i>
                            Finalizar Compra
                        </a>
                    @else
                        <div class="py-8 text-center">
                            <i class="bi bi-receipt mb-4 text-4xl text-gray-400"></i>
                            <p class="text-gray-500">Nenhum produto no carrinho</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function updateQuantity(itemKey, quantity) {
            const item = @json($cart);
            const itemData = item[itemKey];

            $.ajax({
                    url: '{{ route('cart.update-quantity') }}',
                    method: 'PUT',
                    data: {
                        product_id: itemData.product_id,
                        quantity: quantity,
                        variation: itemData.variation,
                        _token: '{{ csrf_token() }}'
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        location.reload();
                    }
                })
                .fail(function() {
                    alert('Erro ao atualizar quantidade');
                });
        }

        function removeItem(itemKey) {
            const item = @json($cart);
            const itemData = item[itemKey];

            $.ajax({
                    url: '{{ route('cart.remove-item') }}',
                    method: 'DELETE',
                    data: {
                        product_id: itemData.product_id,
                        variation: itemData.variation,
                        _token: '{{ csrf_token() }}'
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        location.reload();
                    }
                })
                .fail(function() {
                    alert('Erro ao remover item');
                });
        }

        function clearCart() {
            if (confirm('Tem certeza que deseja limpar o carrinho?')) {
                $.ajax({
                        url: '{{ route('cart.clear') }}',
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        }
                    })
                    .done(function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    })
                    .fail(function() {
                        alert('Erro ao limpar carrinho');
                    });
            }
        }

        function applyCoupon() {
            const code = $('#couponCode').val();

            if (!code) {
                alert('Digite um código de cupom');
                return;
            }

            $.ajax({
                    url: '{{ route('cart.apply-coupon') }}',
                    method: 'POST',
                    data: {
                        code: code,
                        _token: '{{ csrf_token() }}'
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                })
                .fail(function() {
                    alert('Erro ao aplicar cupom');
                });
        }

        function removeCoupon() {
            $.ajax({
                    url: '{{ route('cart.remove-coupon') }}',
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        location.reload();
                    }
                })
                .fail(function() {
                    alert('Erro ao remover cupom');
                });
        }
    </script>
@endpush
