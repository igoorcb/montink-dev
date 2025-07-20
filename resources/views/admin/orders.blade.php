@extends('layouts.app')

@section('title', 'Administração - Montink ERP')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="card">
            <div class="card-header">
                <h2 class="text-2xl font-bold text-gray-900">
                    <i class="bi bi-list-ul text-primary-600"></i>
                    Pedidos
                </h2>
            </div>
            
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pedido
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cliente
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            #{{ $order->id }} - {{ $order->order_number }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $order->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $order->customer_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        R$ {{ number_format($order->total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="viewOrder({{ $order->id }})" class="text-primary-600 hover:text-primary-900 mr-2">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button onclick="testWebhook({{ $order->id }})" class="text-green-600 hover:text-green-900">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="bi bi-inbox text-4xl text-gray-400 mb-4"></i>
                        <p class="text-gray-500">Nenhum pedido encontrado</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="lg:col-span-1">
        <div class="card sticky top-24">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="bi bi-gear text-primary-600"></i>
                    Teste de Webhook
                </h3>
            </div>
            
            <div class="card-body">
                <div class="space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-3">
                            <i class="bi bi-hash text-primary-600"></i>
                            <label class="form-label mb-0">ID do Pedido</label>
                        </div>
                        <input type="number" id="webhookOrderId" class="form-input" placeholder="Ex: 1" min="1">
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center space-x-2 mb-3">
                            <i class="bi bi-flag text-primary-600"></i>
                            <label class="form-label mb-0">Novo Status</label>
                        </div>
                        <select id="webhookStatus" class="form-input">
                            <option value="pending">Pendente</option>
                            <option value="confirmed">Confirmado</option>
                            <option value="shipped">Enviado</option>
                            <option value="delivered">Entregue</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                </div>
                
                <button onclick="testWebhookManual()" class="btn-primary w-full mt-6">
                    <i class="bi bi-arrow-clockwise mr-2"></i>
                    Testar Webhook
                </button>
                
                <div id="webhookResult" class="mt-4 p-4 rounded-lg hidden border">
                    <pre id="webhookResponse" class="text-sm"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-white border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                        <i class="bi bi-receipt text-xl text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-xl font-bold mb-0 text-gray-900">Detalhes do Pedido</h5>
                        <p class="text-gray-600 text-sm mb-0">Visualização completa dos dados</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-white text-gray-800 p-0" id="orderDetailsContent">
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-white border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                        <i class="bi bi-arrow-clockwise text-xl text-white"></i>
                    </div>
                    <div>
                        <h5 class="modal-title text-xl font-bold mb-0 text-gray-900">Atualizar Status</h5>
                        <p class="text-gray-600 text-sm mb-0">Alterar status do pedido</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-white text-gray-800 p-6">
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                <i class="bi bi-hash text-white"></i>
                            </div>
                            <h6 class="text-lg font-semibold text-gray-900 mb-0">Número do Pedido</h6>
                        </div>
                        <input type="text" id="editOrderId" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 font-mono text-lg" readonly>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-yellow-600 rounded-lg flex items-center justify-center">
                                <i class="bi bi-flag text-white"></i>
                            </div>
                            <h6 class="text-lg font-semibold text-gray-900 mb-0">Status Atual</h6>
                        </div>
                        <input type="text" id="currentStatus" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 font-medium" readonly>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-5 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                                <i class="bi bi-arrow-up-circle text-white"></i>
                            </div>
                            <h6 class="text-lg font-semibold text-gray-900 mb-0">Novo Status</h6>
                        </div>
                        <select id="newStatus" class="w-full bg-white border border-gray-300 rounded-lg px-4 py-3 text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="pending">Pendente</option>
                            <option value="confirmed">Confirmado</option>
                            <option value="shipped">Enviado</option>
                            <option value="delivered">Entregue</option>
                            <option value="cancelled">Cancelado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-gray-50 border-t border-gray-200 p-4">
                <div class="flex justify-end space-x-3">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle mr-2"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn-primary" onclick="updateOrderStatus()">
                        <i class="bi bi-check-circle mr-2"></i>
                        Atualizar Status
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentOrderId = null;

function viewOrder(orderId) {
    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    const contentDiv = $('#orderDetailsContent');
    
    contentDiv.html(`
        <div class="flex items-center justify-center p-12">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-500 mx-auto mb-4"></div>
                <p class="text-gray-400">Carregando detalhes do pedido...</p>
            </div>
        </div>
    `);
    
    modal.show();
    
    $.get(`/orders/${orderId}`)
    .done(function(response) {
        if (response.success && response.html) {
            contentDiv.html(response.html);
        } else {
            contentDiv.html(`
                <div class="flex items-center justify-center p-12">
                    <div class="text-center">
                        <i class="bi bi-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                        <p class="text-red-400">Erro ao carregar detalhes do pedido</p>
                        <p class="text-gray-500 text-sm">${response.message || 'Resposta inválida'}</p>
                    </div>
                </div>
            `);
        }
    })
    .fail(function(xhr) {
        console.error('Erro na requisição:', xhr);
        contentDiv.html(`
            <div class="flex items-center justify-center p-12">
                <div class="text-center">
                    <i class="bi bi-wifi-off text-red-500 text-4xl mb-4"></i>
                    <p class="text-red-400">Erro de conexão</p>
                    <p class="text-gray-500 text-sm">${xhr.responseJSON?.message || 'Não foi possível conectar ao servidor'}</p>
                </div>
            </div>
        `);
    });
}

function testWebhook(orderId) {
    currentOrderId = orderId;
    
    $.get(`/orders/${orderId}`)
    .done(function(response) {
        if (response.success) {
            const statusMatch = response.html.match(/Status.*?<span[^>]*class="[^"]*badge[^"]*"[^>]*>([^<]+)<\/span>/i);
            const currentStatus = statusMatch ? statusMatch[1].toLowerCase() : 'pending';
            
            $('#editOrderId').val(orderId);
            $('#currentStatus').val(currentStatus);
            $('#newStatus').val(currentStatus);
            
            new bootstrap.Modal(document.getElementById('editStatusModal')).show();
        } else {
            alert('Erro ao carregar dados do pedido');
        }
    })
    .fail(function() {
        alert('Erro ao carregar dados do pedido');
    });
}

function updateOrderStatus() {
    const orderId = $('#editOrderId').val();
    const status = $('#newStatus').val();
    
    if (!orderId || !status) {
        alert('Dados inválidos');
        return;
    }
    
    sendWebhook(orderId, status);
    
    // Fechar o modal
    bootstrap.Modal.getInstance(document.getElementById('editStatusModal')).hide();
}

function testWebhookManual() {
    const orderId = $('#webhookOrderId').val();
    const status = $('#webhookStatus').val();
    
    if (!orderId) {
        alert('Digite o ID do pedido');
        return;
    }
    
    if (!Number.isInteger(parseInt(orderId)) || parseInt(orderId) <= 0) {
        alert('O ID do pedido deve ser um número inteiro positivo');
        return;
    }
    
    sendWebhook(parseInt(orderId), status);
}

function sendWebhook(orderId, status) {
    const resultDiv = $('#webhookResult');
    const responseDiv = $('#webhookResponse');
    
    resultDiv.removeClass('hidden bg-green-100 bg-red-100 border-green-500 border-red-500');
    resultDiv.addClass('bg-gray-100 border border-gray-300');
    responseDiv.html(`
        <div class="flex items-center justify-center py-4">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary-500 mr-3"></div>
            <span class="text-gray-600">Enviando webhook...</span>
        </div>
    `);
    
    $.ajax({
        url: '/webhook/update-order-status',
        method: 'POST',
        data: {
            order_id: orderId,
            status: status
        },
        headers: {
            'Accept': 'application/json'
        }
    })
    .done(function(response) {
        if (response.success) {
            resultDiv.removeClass('bg-gray-100 border-gray-300');
            resultDiv.addClass('bg-green-100 border-green-500');
            responseDiv.html(`
                <div class="flex items-center mb-3">
                    <i class="bi bi-check-circle text-green-600 text-xl mr-2"></i>
                    <span class="text-green-800 font-semibold">Webhook enviado com sucesso!</span>
                </div>
                <div class="bg-white p-3 rounded border">
                    <pre class="text-sm text-gray-700">${JSON.stringify(response, null, 2)}</pre>
                </div>
                <div class="mt-3 text-sm text-green-700">
                    <i class="bi bi-arrow-clockwise mr-1"></i>
                    A página será recarregada em 3 segundos...
                </div>
            `);
            
            setTimeout(() => {
                location.reload();
            }, 3000);
        } else {
            resultDiv.removeClass('bg-gray-100 border-gray-300');
            resultDiv.addClass('bg-red-100 border-red-500');
            responseDiv.html(`
                <div class="flex items-center mb-3">
                    <i class="bi bi-exclamation-triangle text-red-600 text-xl mr-2"></i>
                    <span class="text-red-800 font-semibold">Erro no webhook</span>
                </div>
                <div class="bg-white p-3 rounded border">
                    <pre class="text-sm text-gray-700">${JSON.stringify(response, null, 2)}</pre>
                </div>
            `);
        }
    })
    .fail(function(xhr) {
        resultDiv.removeClass('bg-gray-100 border-gray-300');
        resultDiv.addClass('bg-red-100 border-red-500');
        responseDiv.html(`
            <div class="flex items-center mb-3">
                <i class="bi bi-x-circle text-red-600 text-xl mr-2"></i>
                <span class="text-red-800 font-semibold">Falha na requisição</span>
            </div>
            <div class="bg-white p-3 rounded border">
                <pre class="text-sm text-gray-700">${JSON.stringify(xhr.responseJSON || {error: 'Erro na requisição'}, null, 2)}</pre>
            </div>
        `);
    });
}
</script>
@endpush 