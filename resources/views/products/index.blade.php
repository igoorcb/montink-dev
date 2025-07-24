@extends('layouts.app')

@section('title', 'Produtos - Mini ERP')

@section('content')
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
        <div class="lg:col-span-3">
            <div class="card">
                <div class="card-header">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">
                            <i class="bi bi-box-seam text-primary-600"></i>
                            Catálogo de Produtos
                        </h2>
                        <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#newProductModal">
                            <i class="bi bi-plus-circle"></i>
                            Novo Produto
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3" id="products-container">
                        @foreach ($products as $product)
                            <div class="product-card">
                                <div class="flex h-full flex-col p-6">
                                    <div class="mb-4 text-center">
                                        <i class="bi bi-box-seam text-4xl text-primary-500"></i>
                                    </div>

                                    <div class="flex-1">
                                        <h3 class="mb-2 text-center text-lg font-semibold text-gray-900">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="mb-4 text-center text-sm text-gray-600">
                                            {{ $product->description }}
                                        </p>

                                        <div class="mb-4 flex items-center justify-between">
                                            <div class="price-tag">
                                                R$ {{ number_format($product->price, 2, ',', '.') }}
                                            </div>
                                            <div class="stock-badge">
                                                <i class="bi bi-box"></i>
                                                {{ $product->total_stock }}
                                            </div>
                                        </div>

                                        @if ($product->variations)
                                            <div class="mb-4">
                                                <p class="mb-2 text-center text-xs text-gray-500">Variações disponíveis:</p>
                                                <div class="flex flex-wrap justify-center gap-1">
                                                    @foreach ($product->variations as $variation)
                                                        <span
                                                            class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-800">
                                                            {{ $variation }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="space-y-2">
                                        <button class="btn-success w-full"
                                            onclick="openAddToCartModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ json_encode($product->variations ?? []) }}')">
                                            <i class="bi bi-cart-plus"></i>
                                            Adicionar ao Carrinho
                                        </button>
                                        <button class="btn-secondary w-full" onclick="editProduct({{ $product->id }})">
                                            <i class="bi bi-pencil-square"></i>
                                            Editar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="card sticky top-24">
                <div class="card-header">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="bi bi-cart3 text-primary-600"></i>
                        Seu Carrinho
                    </h3>
                </div>

                <div class="card-body">
                    <div id="cart-items">
                        @if (count($cart) > 0)
                            @foreach ($cart as $item)
                                <div class="mb-3 flex items-center justify-between rounded-lg bg-gray-50 p-3">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                                        <p class="text-xs text-gray-500">Qtd: {{ $item['quantity'] }}</p>
                                    </div>
                                    <div class="price-tag">
                                        R$ {{ number_format($item['price'] * $item['quantity'], 2, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            <hr class="my-4">

                            <div class="mb-4 flex items-center justify-between">
                                <span class="font-semibold text-gray-900">Total:</span>
                                <div class="price-tag">
                                    R$
                                    {{ number_format(array_sum(array_map(function ($item) {return $item['price'] * $item['quantity'];}, $cart)),2,',','.') }}
                                </div>
                            </div>

                            <a href="{{ route('cart.index') }}" class="btn-primary w-full text-center">
                                <i class="bi bi-arrow-right"></i>
                                Ver Carrinho Completo
                            </a>
                        @else
                            <div class="py-8 text-center">
                                <i class="bi bi-cart-x mb-4 text-4xl text-gray-400"></i>
                                <p class="mb-2 text-gray-500">Carrinho vazio</p>
                                <p class="text-sm text-gray-400">Adicione produtos para começar</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Novo Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="newProductForm">
                    <div class="modal-body">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="form-label">Nome do Produto</label>
                                <input type="text" class="form-input" name="name" required>
                            </div>
                            <div>
                                <label class="form-label">Preço</label>
                                <input type="number" class="form-input" name="price" step="0.01" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-input" name="description" rows="3"></textarea>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Variações (separadas por vírgula)</label>
                            <input type="text" class="form-input" name="variations" placeholder="Pequeno, Médio, Grande">
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Estoque Inicial</label>
                            <input type="number" class="form-input" name="stock" value="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addToCartModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adicionar ao Carrinho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addToCartForm">
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6 class="font-semibold text-gray-900" id="productName"></h6>
                            <p class="font-medium text-primary-600" id="productPrice"></p>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Quantidade</label>
                            <input type="number" class="form-input" name="quantity" value="1" min="1"
                                required>
                        </div>

                        <div class="mb-4" id="variationSection" style="display: none;">
                            <label class="form-label">Variação</label>
                            <select class="form-input" name="variation">
                                <option value="">Selecione uma variação</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-success">Adicionar ao Carrinho</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Produto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editProductForm">
                    <div class="modal-body">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label class="form-label">Nome do Produto</label>
                                <input type="text" class="form-input" name="name" required>
                            </div>
                            <div>
                                <label class="form-label">Preço</label>
                                <input type="number" class="form-input" name="price" step="0.01" required>
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Descrição</label>
                            <textarea class="form-input" name="description" rows="3"></textarea>
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Variações (separadas por vírgula)</label>
                            <input type="text" class="form-input" name="variations"
                                placeholder="Pequeno, Médio, Grande">
                        </div>
                        <div class="mt-4">
                            <label class="form-label">Estoque Inicial</label>
                            <input type="number" class="form-input" name="stock" value="0" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentProductId = null;

        function openAddToCartModal(productId, productName, productPrice, variationsJson) {
            currentProductId = productId;
            document.getElementById('productName').textContent = productName;
            document.getElementById('productPrice').textContent = `R$ ${productPrice.toFixed(2).replace('.', ',')}`;

            const variationSelect = document.querySelector('#addToCartModal select[name="variation"]');
            const variationSection = document.getElementById('variationSection');

            try {
                const variations = JSON.parse(variationsJson);

                if (variations && variations.length > 0) {
                    variationSelect.innerHTML = '<option value="">Selecione uma variação</option>';
                    variations.forEach(variation => {
                        const option = document.createElement('option');
                        option.value = variation;
                        option.textContent = variation;
                        variationSelect.appendChild(option);
                    });
                    variationSection.style.display = 'block';
                } else {
                    variationSection.style.display = 'none';
                }
            } catch (e) {
                variationSection.style.display = 'none';
            }

            const modalElement = document.getElementById('addToCartModal');

            if (typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            } else {
                modalElement.style.display = 'block';
                modalElement.classList.add('show');
            }
        }

        function editProduct(productId) {
            $.get(`/products/${productId}/edit`)
                .done(function(response) {
                    $('#editProductModal input[name="name"]').val(response.name);
                    $('#editProductModal input[name="price"]').val(response.price);
                    $('#editProductModal textarea[name="description"]').val(response.description);
                    $('#editProductModal input[name="variations"]').val(response.variations ? response.variations.join(
                        ', ') : '');
                    $('#editProductModal input[name="stock"]').val(response.stock);
                    $('#editProductForm').attr('action', `/products/${productId}`);
                    new bootstrap.Modal(document.getElementById('editProductModal')).show();
                })
                .fail(function() {
                    alert('Erro ao carregar produto');
                });
        }

        $('#newProductForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            const name = formData.get('name');
            const price = formData.get('price');
            const description = formData.get('description');
            const stock = formData.get('stock');
            const variations = formData.get('variations');

            const newFormData = new FormData();
            newFormData.append('name', name);
            newFormData.append('price', price);
            newFormData.append('description', description);
            newFormData.append('stock[quantity]', stock);
            newFormData.append('stock[variation]', '');
            newFormData.append('stock[min_quantity]', '0');

            if (variations && variations.trim()) {
                const variationsArray = variations.split(',').map(v => v.trim()).filter(v => v);
                variationsArray.forEach((variation, index) => {
                    newFormData.append(`variations[${index}]`, variation);
                });
            }

            $.ajax({
                    url: '{{ route('products.store') }}',
                    method: 'POST',
                    data: newFormData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                })
                .fail(function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert('Erro: ' + xhr.responseJSON.message);
                    } else {
                        alert('Erro ao salvar produto');
                    }
                });
        });

        $('#addToCartForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('product_id', currentProductId);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                    url: '{{ route('cart.add') }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false
                })
                .done(function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                })
                .fail(function() {
                    alert('Erro ao adicionar ao carrinho');
                });
        });

        $('#editProductForm').on('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const url = $(this).attr('action');

            const name = formData.get('name');
            const price = formData.get('price');
            const description = formData.get('description');
            const stock = formData.get('stock');
            const variations = formData.get('variations');

            const newFormData = new FormData();
            newFormData.append('_method', 'PATCH');
            newFormData.append('name', name);
            newFormData.append('price', price);
            newFormData.append('description', description);
            newFormData.append('stock[quantity]', stock);
            newFormData.append('stock[variation]', '');
            newFormData.append('stock[min_quantity]', '0');

            if (variations && variations.trim()) {
                const variationsArray = variations.split(',').map(v => v.trim()).filter(v => v);
                variationsArray.forEach((variation, index) => {
                    newFormData.append(`variations[${index}]`, variation);
                });
            }

            $.ajax({
                    url: url,
                    method: 'POST',
                    data: newFormData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                })
                .fail(function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        alert('Erro: ' + xhr.responseJSON.message);
                    } else {
                        alert('Erro ao salvar produto');
                    }
                });
        });

        $('#newProductModal').on('hidden.bs.modal', function() {
            $('#newProductForm')[0].reset();
        });

        $('#editProductModal').on('hidden.bs.modal', function() {
            $('#editProductForm').attr('action', '');
            $('#editProductForm')[0].reset();
        });

        $('#addToCartModal').on('hidden.bs.modal', function() {
            currentProductId = null;
            $('#addToCartForm')[0].reset();
        });
    </script>
@endpush
