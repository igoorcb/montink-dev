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
                                            {{ $order->order_number }}
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
                <div class="mb-4">
                    <label class="form-label">ID do Pedido</label>
                    <input type="text" id="webhookOrderId" class="form-input" placeholder="Ex: 1">
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Novo Status</label>
                    <select id="webhookStatus" class="form-input">
                        <option value="pending">Pendente</option>
                        <option value="confirmed">Confirmado</option>
                        <option value="shipped">Enviado</option>
                        <option value="delivered">Entregue</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
                
                <button onclick="testWebhookManual()" class="btn-primary w-full">
                    <i class="bi bi-arrow-clockwise"></i>
                    Testar Webhook
                </button>
                
                <div id="webhookResult" class="mt-4 p-3 rounded-lg hidden">
                    <pre id="webhookResponse" class="text-sm"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes do Pedido -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalhes do Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Conteúdo será carregado via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de Edição de Status -->
<div class="modal fade" id="editStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Status do Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label">Pedido</label>
                    <input type="text" id="editOrderId" class="form-input" readonly>
                </div>
                <div class="mb-4">
                    <label class="form-label">Status Atual</label>
                    <input type="text" id="currentStatus" class="form-input" readonly>
                </div>
                <div class="mb-4">
                    <label class="form-label">Novo Status</label>
                    <select id="newStatus" class="form-input">
                        <option value="pending">Pendente</option>
                        <option value="confirmed">Confirmado</option>
                        <option value="shipped">Enviado</option>
                        <option value="delivered">Entregue</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-primary" onclick="updateOrderStatus()">
                    <i class="bi bi-check-circle"></i>
                    Atualizar Status
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentOrderId = null;

function viewOrder(orderId) {
    $.get(`/orders/${orderId}`)
    .done(function(response) {
        if (response.success) {
            $('#orderDetailsContent').html(response.html);
            new bootstrap.Modal(document.getElementById('orderDetailsModal')).show();
        } else {
            alert('Erro ao carregar detalhes do pedido');
        }
    })
    .fail(function() {
        alert('Erro ao carregar detalhes do pedido');
    });
}

function testWebhook(orderId) {
    currentOrderId = orderId;
    
    // Buscar o status atual do pedido
    $.get(`/orders/${orderId}`)
    .done(function(response) {
        if (response.success) {
            // Extrair o status atual do HTML
            const statusMatch = response.html.match(/Status.*?<span[^>]*>([^<]+)<\/span>/i);
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
    
    sendWebhook(orderId, status);
}

function sendWebhook(orderId, status) {
    $.ajax({
        url: '/webhook/update-order-status',
        method: 'POST',
        data: {
            order_id: orderId,
            status: status
        },
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .done(function(response) {
        $('#webhookResult').removeClass('hidden');
        $('#webhookResponse').html(JSON.stringify(response, null, 2));
        
        if (response.success) {
            setTimeout(() => {
                location.reload();
            }, 2000);
        }
    })
    .fail(function(xhr) {
        $('#webhookResult').removeClass('hidden');
        $('#webhookResponse').html(JSON.stringify(xhr.responseJSON || {error: 'Erro na requisição'}, null, 2));
    });
}
</script>
@endpush 