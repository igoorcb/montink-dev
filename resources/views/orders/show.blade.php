<div class="modal-header">
    <h5 class="modal-title">Detalhes do Pedido #{{ $order->id }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <h6>Informações do Cliente</h6>
            <p><strong>Nome:</strong> {{ $order->customer_name }}</p>
            <p><strong>Email:</strong> {{ $order->customer_email }}</p>
            <p><strong>Telefone:</strong> {{ $order->customer_phone ?? 'Não informado' }}</p>
        </div>
        <div class="col-md-6">
            <h6>Endereço de Entrega</h6>
            <p>{{ $order->address }}, {{ $order->number }}</p>
            <p>{{ $order->neighborhood }}, {{ $order->city }} - {{ $order->state }}</p>
            <p>CEP: {{ $order->cep }}</p>
            @if($order->complement)
                <p>Complemento: {{ $order->complement }}</p>
            @endif
        </div>
    </div>
    
    <hr>
    
    <h6>Itens do Pedido</h6>
    <div class="table-responsive">
        <table class="table table-sm">
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
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->variation ?? 'N/A' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>R$ {{ number_format($item->price, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($item->price * $item->quantity, 2, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <hr>
    
    <div class="row">
        <div class="col-md-6">
            <h6>Status do Pedido</h6>
            <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'completed' ? 'success' : 'danger') }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <div class="col-md-6">
            <h6>Resumo Financeiro</h6>
            <p><strong>Subtotal:</strong> R$ {{ number_format($order->subtotal, 2, ',', '.') }}</p>
            <p><strong>Frete:</strong> R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</p>
            @if($order->discount > 0)
                <p><strong>Desconto:</strong> -R$ {{ number_format($order->discount, 2, ',', '.') }}</p>
            @endif
            <p><strong>Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</p>
        </div>
    </div>
    
    @if($order->coupon)
    <hr>
    <div class="row">
        <div class="col-12">
            <h6>Cupom Utilizado</h6>
            <p><strong>Código:</strong> {{ $order->coupon->code }}</p>
            <p><strong>Desconto:</strong> {{ $order->coupon->discount_percentage }}%</p>
        </div>
    </div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
</div> 