<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelamento do Pedido #{{ $order->order_number }}</title>
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
            background-color: #dc2626;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #fef2f2;
            padding: 20px;
            border-radius: 0 0 8px 8px;
        }
        .cancellation-notice {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
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
            background-color: #dc2626;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Pedido Cancelado</h1>
        <p>Pedido #{{ $order->order_number }}</p>
    </div>

    <div class="content">
        <h2>Olá, {{ $order->customer_name }}!</h2>

        <div class="cancellation-notice">
            <h3>⚠️ Seu pedido foi cancelado</h3>
            <p>Infelizmente, seu pedido foi cancelado. Qualquer valor pago será reembolsado conforme nossa política de reembolso.</p>
        </div>

        <div class="order-details">
            <h4>Detalhes do Pedido Cancelado:</h4>
            <ul>
                <li><strong>Número do Pedido:</strong> #{{ $order->order_number }}</li>
                <li><strong>Data do Pedido:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</li>
                <li><strong>Total:</strong> R$ {{ number_format($order->total, 2, ',', '.') }}</li>
            </ul>
        </div>

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
            <h4>Informações de Reembolso:</h4>
            <ul>
                <li>O reembolso será processado em até 5 dias úteis</li>
                <li>O valor será creditado na mesma forma de pagamento utilizada</li>
                <li>Você receberá uma confirmação por email quando o reembolso for processado</li>
            </ul>
        </div>

        <p>Se você tiver alguma dúvida sobre o cancelamento ou reembolso, entre em contato conosco.</p>

        <p>Lamentamos qualquer inconveniente causado.</p>

        <div class="footer">
            <p>Obrigado por escolher nossa loja!</p>
            <p>Mini-erp-dev</p>
        </div>
    </div>
</body>
</html>
