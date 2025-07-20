<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualização do Pedido #{{ $order->order_number }}</title>
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
            background-color: #1e40af;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-confirmed { background-color: #dbeafe; color: #1e40af; }
        .status-shipped { background-color: #e9d5ff; color: #7c3aed; }
        .status-delivered { background-color: #d1fae5; color: #065f46; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
        .order-details {
            background-color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #1e40af;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Atualização do Pedido</h1>
        <p>Pedido #{{ $order->order_number }}</p>
    </div>

    <div class="content">
        <h2>Olá, {{ $order->customer_name }}!</h2>
        
        <p>O status do seu pedido foi atualizado:</p>
        
        <div class="order-details">
            <h3>Status Atualizado</h3>
            <span class="status-badge status-{{ $newStatus }}">
                {{ ucfirst($newStatus) }}
            </span>
            
            <h4>Detalhes do Pedido:</h4>
            <ul>
                <li><strong>Número do Pedido:</strong> #{{ $order->order_number }}</li>
                <li><strong>Data:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
                <li><strong>Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</li>
            </ul>
        </div>

        @if($newStatus === 'confirmed')
            <p>Seu pedido foi confirmado e está sendo preparado para envio!</p>
        @elseif($newStatus === 'shipped')
            <p>Seu pedido foi enviado e está a caminho!</p>
        @elseif($newStatus === 'delivered')
            <p>Seu pedido foi entregue! Esperamos que você esteja satisfeito com sua compra.</p>
        @endif

        <div class="order-details">
            <h4>Itens do Pedido:</h4>
            @foreach($order->items as $item)
                <div style="margin-bottom: 10px; padding: 10px; background-color: #f9fafb; border-radius: 4px;">
                    <strong>{{ $item->product->name }}</strong><br>
                    <small>Quantidade: {{ $item->quantity }} | Preço: R$ {{ number_format($item->unit_price, 2, ',', '.') }}</small>
                </div>
            @endforeach
        </div>

        <div class="order-details">
            <h4>Endereço de Entrega:</h4>
            <p>{{ $order->shipping_address }}<br>
            {{ $order->shipping_city }} - {{ $order->shipping_state }}<br>
            CEP: {{ $order->shipping_zipcode }}</p>
        </div>

        <p>Se você tiver alguma dúvida sobre seu pedido, entre em contato conosco.</p>

        <div class="footer">
            <p>Obrigado por escolher nossa loja!</p>
            <p>Montink ERP</p>
        </div>
    </div>
</body>
</html> 