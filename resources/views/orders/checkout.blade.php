@extends('layouts.app')

@section('title', 'Checkout - Montink ERP')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
    <div class="lg:col-span-3">
        <div class="card">
            <div class="card-header">
                <h2 class="text-2xl font-bold text-gray-900">
                    <i class="bi bi-credit-card text-primary-600"></i>
                    Finalizar Compra
                </h2>
            </div>
            
            <div class="card-body">
                <form id="checkoutForm" method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="bi bi-person text-primary-600"></i>
                                    Dados Pessoais
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label">Nome Completo</label>
                                    <input type="text" class="form-input" name="customer_name" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-input" name="customer_email" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" class="form-input" name="customer_phone" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card">
                            <div class="card-header">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="bi bi-geo-alt text-primary-600"></i>
                                    Endereço de Entrega
                                </h3>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <label class="form-label">CEP</label>
                                    <div class="flex">
                                        <input type="text" class="form-input rounded-r-none" id="cep" name="cep" placeholder="00000-000" required>
                                        <button class="btn-primary rounded-l-none" type="button" onclick="searchCep()">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Endereço</label>
                                    <input type="text" class="form-input" id="address" name="address" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="form-label">Número</label>
                                        <input type="text" class="form-input" name="number" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Complemento</label>
                                        <input type="text" class="form-input" name="complement">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Bairro</label>
                                    <input type="text" class="form-input" id="neighborhood" name="neighborhood" required>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="col-span-2">
                                        <label class="form-label">Cidade</label>
                                        <input type="text" class="form-input" id="city" name="city" required>
                                    </div>
                                    <div>
                                        <label class="form-label">Estado</label>
                                        <input type="text" class="form-input" id="state" name="state" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8 text-center">
                        <button type="submit" class="btn-success mr-4">
                            <i class="bi bi-check-circle"></i>
                            Confirmar Pedido
                        </button>
                        <a href="{{ route('cart.index') }}" class="btn-secondary">
                            <i class="bi bi-arrow-left"></i>
                            Voltar ao Carrinho
                        </a>
                    </div>
                </form>
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
                @foreach($cart as $item)
                <div class="flex justify-between items-center mb-3 p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                        <p class="text-xs text-gray-500">Qtd: {{ $item['quantity'] }}</p>
                    </div>
                    <div class="price-tag">
                        R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                    </div>
                </div>
                @endforeach
                
                <hr class="my-4">
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">Subtotal:</span>
                        <span class="font-semibold text-gray-900">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-700">Frete:</span>
                        <span class="font-semibold text-gray-900">R$ {{ number_format($shippingCost, 2, ',', '.') }}</span>
                    </div>
                    @if($discount > 0)
                    <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                        <span class="text-gray-700">Desconto:</span>
                        <span class="font-semibold text-green-600">-R$ {{ number_format($discount, 2, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                
                <hr class="my-4">
                
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-bold text-gray-900">Total:</span>
                    <div class="price-tag text-lg">
                        R$ {{ number_format($total, 2, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function searchCep() {
    const cep = document.getElementById('cep').value.replace(/\D/g, '');
    
    if (cep.length !== 8) {
        alert('CEP deve ter 8 dígitos');
        return;
    }
    
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (data.erro) {
                alert('CEP não encontrado');
                return;
            }
            
            document.getElementById('address').value = data.logradouro;
            document.getElementById('neighborhood').value = data.bairro;
            document.getElementById('city').value = data.localidade;
            document.getElementById('state').value = data.uf;
        })
        .catch(error => {
            alert('Erro ao buscar CEP');
        });
}

document.getElementById('cep').addEventListener('blur', searchCep);
</script>
@endpush 