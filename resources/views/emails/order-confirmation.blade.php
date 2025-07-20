<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmação do Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .order-details {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .item {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid #0d6efd;
        }
        .footer {
            background-color: #6c757d;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 0 0 5px 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Confirmação do Pedido</h1>
        <p>Pedido #{{ $order->order_number }}</p>
    </div>
    
    <div class="content">
        <p>Olá <strong>{{ $order->customer_name }}</strong>,</p>
        
        <p>Seu pedido foi recebido e está sendo processado. Abaixo estão os detalhes:</p>
        
        <div class="order-details">
            <h3>Detalhes do Pedido</h3>
            <p><strong>Número do Pedido:</strong> {{ $order->order_number }}</p>
            <p><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
            
            <h4>Itens do Pedido:</h4>
            @foreach($order->items as $item)
            <div class="item">
                <span>{{ $item->product->name }} @if($item->variation)({{ $item->variation }})@endif x {{ $item->quantity }}</span>
                <span>R$ {{ number_format($item->total_price, 2, ',', '.') }}</span>
            </div>
            @endforeach
            
            <div class="total">
                <div>Subtotal: R$ {{ number_format($order->subtotal, 2, ',', '.') }}</div>
                <div>Frete: R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</div>
                @if($order->discount > 0)
                <div>Desconto: -R$ {{ number_format($order->discount, 2, ',', '.') }}</div>
                @endif
                <div>Total: R$ {{ number_format($order->total, 2, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="order-details">
            <h4>Endereço de Entrega:</h4>
            <p>
                {{ $order->customer_name }}<br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }} - {{ $order->shipping_state }}<br>
                CEP: {{ $order->shipping_zipcode }}<br>
                {{ $order->shipping_country }}
            </p>
        </div>
        
        <p>Você receberá atualizações sobre o status do seu pedido por e-mail.</p>
        
        <p>Obrigado por escolher nossos produtos!</p>
    </div>
    
    <div class="footer">
        <p>Montink ERP - Sistema de Gestão de Pedidos</p>
        <p>Este é um e-mail automático, não responda a esta mensagem.</p>
    </div>
</body>
</html> 