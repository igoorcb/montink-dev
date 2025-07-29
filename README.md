# Mini Sistema de Gestão

Um mini ERP completo para controle de Pedidos, Produtos, Cupons e Estoque desenvolvido com Laravel 11, seguindo os princípios SOLID e DDD.

## 🚀 Tecnologias Utilizadas

- **Backend**: Laravel 11
- **Banco de Dados**: MySQL 8.0
- **Frontend**: Bootstrap 5
- **Containerização**: Docker
- **Email**: Mailpit
- **Arquitetura**: SOLID, DDD (Domain-Driven Design)
- **Cálculo de Frete Inteligente**: Regras de negócio flexíveis e automáticas.
- **Webhook para Atualização de Status de Pedido**: Integração com sistemas externos, incluindo lógica de estorno de estoque para cancelamentos.

## 📋 Funcionalidades

### ✅ Implementadas
- ✅ Cadastro e edição de produtos com variações
- ✅ Controle de estoque por produto e variação
- ✅ Carrinho de compras em sessão
- ✅ Sistema de cupons com validação
- ✅ Cálculo automático de frete
- ✅ Consulta de CEP via ViaCEP
- ✅ Finalização de pedidos
- ✅ Envio de email de confirmação
- ✅ Webhook para atualização de status
- ✅ Interface responsiva com tema dark

### 🎯 Regras de Negócio
- **Frete**:
  - R$ 15,00 para subtotal entre R$ 52,00 e R$ 166,59
  - Grátis para subtotal acima de R$ 200,00
  - R$ 20,00 para outros valores
- **Cupons**: Validação por data, valor mínimo e limite de uso
- **Estoque**: Controle automático ao finalizar pedidos

## 🛠️ Instalação e Configuração

### Pré-requisitos
- Docker e Docker Compose
- Git

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd mini-erp-dev
```

### 2. Configure o ambiente
```bash
cp .env.example .env
```

### 3. Inicie os containers
```bash
docker-compose up -d
```

### 4. Instale as dependências
```bash
docker-compose exec app composer install
```

### 5. Configure o banco de dados
```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed --class=ProductSeeder
```

### 6. Gere a chave da aplicação
```bash
docker-compose exec app php artisan key:generate
```

### 7. Configure as permissões
```bash
docker-compose exec app chmod -R 777 storage bootstrap/cache
```

## 🌐 Acessos

- **Aplicação**: http://localhost:8000
- **Mailpit**: http://localhost:8025
- **MySQL**: localhost:3306

## 📊 Estrutura do Banco de Dados

### Tabelas Principais
- `products`: Produtos com variações
- `inventory`: Controle de estoque
- `coupons`: Cupons de desconto
- `orders`: Pedidos
- `order_items`: Itens dos pedidos

### Dados de Exemplo
O sistema já vem com produtos e cupons de exemplo:
- 5 produtos com variações
- 3 cupons de desconto
- Estoque configurado para todos os produtos

## 🔧 Endpoints da API

### Produtos
- `GET /` - Lista de produtos
- `POST /products` - Criar produto
- `PUT /products/{id}` - Atualizar produto
- `POST /products/{id}/add-to-cart` - Adicionar ao carrinho

### Carrinho
- `GET /cart` - Visualizar carrinho
- `PUT /cart/update-quantity` - Atualizar quantidade
- `DELETE /cart/remove-item` - Remover item
- `POST /cart/apply-coupon` - Aplicar cupom
- `DELETE /cart/remove-coupon` - Remover cupom
- `DELETE /cart/clear` - Limpar carrinho

### Pedidos
- `GET /orders/checkout` - Página de checkout
- `POST /orders` - Criar pedido
- `GET /orders/{id}` - Visualizar pedido
- `POST /orders/get-address-by-cep` - Consultar CEP

### Webhook
- `POST /webhook/update-order-status` - Atualizar status do pedido

## 🎨 Interface

### Tema Dark
- Interface moderna com tema escuro
- Bootstrap 5 responsivo
- Ícones Bootstrap Icons
- Modais para formulários

### Funcionalidades da Interface
- Cadastro/edição de produtos via modal
- Carrinho em tempo real
- Aplicação de cupons
- Checkout com validação de CEP
- Confirmação de pedido

## 📧 Sistema de Email

### Mailpit
- Interface web para visualizar emails
- Acesso: http://localhost:8025
- Emails de confirmação de pedido

### Template de Email
- Design responsivo
- Detalhes completos do pedido
- Endereço de entrega
- Valores e descontos

## 🔄 Webhook

### Atualização de Status
```json
POST /webhook/update-order-status
{
    "order_id": 1,
    "status": "confirmed|shipped|delivered|cancelled"
}
```

### Comportamentos
- **Cancelado**: Remove pedido e restaura estoque
- **Outros status**: Atualiza status do pedido

## 🏗️ Arquitetura DDD

### Estrutura de Pastas
```
app/
├── Domain/
│   ├── Entities/          # Entidades do domínio
│   ├── Repositories/      # Interfaces dos repositórios
│   └── Services/          # Serviços do domínio
├── Application/
│   ├── UseCases/          # Casos de uso
│   └── DTOs/              # Objetos de transferência
├── Infrastructure/
│   ├── Repositories/      # Implementação dos repositórios
│   └── Services/          # Serviços externos
└── Presentation/
    ├── Controllers/       # Controladores
    └── Views/             # Views
```

### Princípios SOLID
- **S**: Responsabilidade única em cada classe
- **O**: Extensível sem modificação
- **L**: Substituição de Liskov
- **I**: Interfaces específicas
- **D**: Inversão de dependência

## 🧪 Testando o Sistema

### 1. Acesse a aplicação
```bash
http://localhost:8000
```

### 2. Teste os cupons
- `DESCONTO10`: 10% de desconto (mín. R$ 100)
- `FRETE0`: Frete grátis (mín. R$ 150)
- `MEGA50`: R$ 50 de desconto (mín. R$ 500)

### 3. Teste o webhook
```bash
curl -X POST http://localhost:8000/webhook/update-order-status \
  -H "Content-Type: application/json" \
  -d '{"order_id": 1, "status": "confirmed"}'
```

## 📝 Script SQL

O arquivo `database/schema.sql` contém:
- Criação do banco de dados
- Estrutura das tabelas
- Índices para performance
- Dados de exemplo

## 🚀 Deploy

### Produção
1. Configure variáveis de ambiente
2. Execute migrations
3. Configure web server (Nginx/Apache)
4. Configure SSL
5. Configure email real

### Docker
```bash
docker-compose -f docker-compose.prod.yml up -d
```

---

**Nota**: Este é um sistema completo e funcional que demonstra boas práticas de desenvolvimento com Laravel, DDD e SOLID.


image.png
