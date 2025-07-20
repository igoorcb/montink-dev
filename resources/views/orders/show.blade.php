<div class="modal-header bg-white border-b border-gray-200">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
            <i class="bi bi-receipt text-xl text-white"></i>
        </div>
        <div>
            <h5 class="modal-title text-xl font-bold mb-0 text-gray-900">Pedido #{{ $order->order_number }}</h5>
            <p class="text-gray-600 text-sm mb-0">{{ $order->created_at->format('d/m/Y \à\s H:i') }}</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body bg-white text-gray-800 p-0">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-6">
        <div class="space-y-6">
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-person text-white"></i>
                    </div>
                    <h6 class="text-lg font-semibold text-gray-900 mb-0">Informações do Cliente</h6>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Nome:</span>
                        <span class="font-medium text-gray-900">{{ $order->customer_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-medium text-gray-900">{{ $order->customer_email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Telefone:</span>
                        <span class="font-medium text-gray-900">{{ $order->customer_phone ?? 'Não informado' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-geo-alt text-white"></i>
                    </div>
                    <h6 class="text-lg font-semibold text-gray-900 mb-0">Endereço de Entrega</h6>
                </div>
                <div class="space-y-2 text-gray-700">
                    <p class="font-medium">{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }} - {{ $order->shipping_state }}</p>
                    <p class="text-primary-600">CEP: {{ $order->shipping_zipcode }}</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-flag text-white"></i>
                    </div>
                    <h6 class="text-lg font-semibold text-gray-900 mb-0">Status do Pedido</h6>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-4 py-2 rounded-full text-sm font-semibold
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800 border border-yellow-300
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800 border border-blue-300
                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800 border border-purple-300
                        @elseif($order->status === 'delivered') bg-green-100 text-green-800 border border-green-300
                        @else bg-red-100 text-red-800 border border-red-300
                        @endif">
                        <i class="bi bi-circle-fill mr-2"></i>
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-calculator text-white"></i>
                    </div>
                    <h6 class="text-lg font-semibold text-gray-900 mb-0">Resumo Financeiro</h6>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium text-gray-900">R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Frete:</span>
                        <span class="font-medium text-gray-900">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Desconto:</span>
                        <span class="font-medium text-green-600">-R$ {{ number_format($order->discount, 2, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center py-3 bg-primary-50 rounded-lg px-3 border border-primary-200">
                        <span class="text-primary-900 font-semibold">Total:</span>
                        <span class="text-primary-900 font-bold text-lg">R$ {{ number_format($order->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            @if($order->coupon)
            <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center">
                        <i class="bi bi-ticket-perforated text-white"></i>
                    </div>
                    <h6 class="text-lg font-semibold text-gray-900 mb-0">Cupom Utilizado</h6>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                        <span class="text-gray-600">Código:</span>
                        <span class="font-medium text-yellow-700">{{ $order->coupon->code }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Desconto:</span>
                        <span class="font-medium text-green-600">{{ $order->coupon->discount_percentage }}%</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="bg-gray-50 mx-6 rounded-xl border border-gray-200">
        <div class="p-5">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="bi bi-box text-white"></i>
                </div>
                <h6 class="text-lg font-semibold text-gray-900 mb-0">Itens do Pedido</h6>
            </div>
            
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="bg-white rounded-lg p-4 border border-gray-200 shadow-sm">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <h6 class="font-semibold text-gray-900 mb-1">{{ $item->product->name }}</h6>
                            @if($item->variation)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-700">
                                {{ $item->variation }}
                            </span>
                            @endif
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-gray-900">R$ {{ number_format($item->total_price, 2, ',', '.') }}</div>
                            <div class="text-sm text-gray-600">R$ {{ number_format($item->unit_price, 2, ',', '.') }} cada</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 text-sm">Quantidade: {{ $item->quantity }}</span>
                        <div class="flex items-center space-x-2">
                            <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                            <span class="text-sm text-gray-600">Item processado</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal-footer bg-gray-50 border-t border-gray-200 p-4">
    <div class="flex justify-between items-center w-full">
        <div class="text-sm text-gray-600">
            <i class="bi bi-clock mr-1"></i>
            Última atualização: {{ $order->updated_at->format('d/m/Y H:i') }}
        </div>
        <div class="flex space-x-3">
            <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-circle mr-2"></i>
                Fechar
            </button>
            <button type="button" class="btn-primary" onclick="printOrder()">
                <i class="bi bi-printer mr-2"></i>
                Imprimir
            </button>
        </div>
    </div>
</div>

<script>
function printOrder() {
    const printWindow = window.open('', '_blank');
    const orderData = {
        number: '{{ $order->order_number }}',
        date: '{{ $order->created_at->format("d/m/Y H:i") }}',
        customer: '{{ $order->customer_name }}',
        email: '{{ $order->customer_email }}',
        phone: '{{ $order->customer_phone ?? "Não informado" }}',
        address: '{{ $order->shipping_address }}',
        city: '{{ $order->shipping_city }} - {{ $order->shipping_state }}',
        zipcode: '{{ $order->shipping_zipcode }}',
        status: '{{ ucfirst($order->status) }}',
        subtotal: '{{ number_format($order->subtotal, 2, ",", ".") }}',
        shipping: '{{ number_format($order->shipping_cost, 2, ",", ".") }}',
        discount: '{{ $order->discount > 0 ? number_format($order->discount, 2, ",", ".") : "0,00" }}',
        total: '{{ number_format($order->total, 2, ",", ".") }}',
        items: {!! json_encode($order->items->map(function($item) {
            return [
                'name' => $item->product->name,
                'variation' => $item->variation ?? 'N/A',
                'quantity' => $item->quantity,
                'unit_price' => number_format($item->unit_price, 2, ',', '.'),
                'total_price' => number_format($item->total_price, 2, ',', '.')
            ];
        })) !!}
    };

    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Pedido #${orderData.number}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
                .section { margin-bottom: 30px; }
                .section h3 { color: #333; border-bottom: 1px solid #ccc; padding-bottom: 5px; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f5f5f5; }
                .total { font-weight: bold; font-size: 18px; text-align: right; }
                @media print { body { margin: 0; } }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Pedido #${orderData.number}</h1>
                <p>Data: ${orderData.date}</p>
            </div>
            
            <div class="section">
                <h3>Informações do Cliente</h3>
                <p><strong>Nome:</strong> ${orderData.customer}</p>
                <p><strong>Email:</strong> ${orderData.email}</p>
                <p><strong>Telefone:</strong> ${orderData.phone}</p>
            </div>
            
            <div class="section">
                <h3>Endereço de Entrega</h3>
                <p>${orderData.address}</p>
                <p>${orderData.city}</p>
                <p>CEP: ${orderData.zipcode}</p>
            </div>
            
            <div class="section">
                <h3>Itens do Pedido</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Variação</th>
                            <th>Quantidade</th>
                            <th>Preço Unit.</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${orderData.items.map(item => `
                            <tr>
                                <td>${item.name}</td>
                                <td>${item.variation}</td>
                                <td>${item.quantity}</td>
                                <td>R$ ${item.unit_price}</td>
                                <td>R$ ${item.total_price}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
            
            <div class="section">
                <h3>Resumo Financeiro</h3>
                <p><strong>Subtotal:</strong> R$ ${orderData.subtotal}</p>
                <p><strong>Frete:</strong> R$ ${orderData.shipping}</p>
                <p><strong>Desconto:</strong> -R$ ${orderData.discount}</p>
                <div class="total">
                    <strong>Total: R$ ${orderData.total}</strong>
                </div>
            </div>
            
            <div class="section">
                <h3>Status do Pedido</h3>
                <p><strong>Status Atual:</strong> ${orderData.status}</p>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.print();
}
</script> 