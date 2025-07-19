#!/bin/bash

echo "🚀 Iniciando Montink ERP..."

echo "📦 Iniciando containers Docker..."
docker-compose up -d

echo "⏳ Aguardando containers..."
sleep 10

echo "🔧 Instalando dependências..."
docker-compose exec app composer install

echo "🗄️ Configurando banco de dados..."
docker-compose exec app php artisan migrate --force

echo "🌱 Populando dados de exemplo..."
docker-compose exec app php artisan db:seed --class=ProductSeeder --force

echo "🔑 Gerando chave da aplicação..."
docker-compose exec app php artisan key:generate --force

echo "📁 Configurando permissões..."
docker-compose exec app chmod -R 777 storage bootstrap/cache

echo "✅ Montink ERP inciado com sucesso!"
echo ""
echo "🌐 Acessos:"
echo "   Aplicação: http://localhost:8000"
echo "   Mailpit:   http://localhost:8025"
echo "   MySQL:     localhost:3306"
echo ""
echo "🎯 Cupons de teste:"
echo "   DESCONTO10 - 10% de desconto (mín. R$ 100)"
echo "   FRETE0     - Frete grátis (mín. R$ 150)"
echo "   MEGA50     - R$ 50 de desconto (mín. R$ 500)"
echo ""
echo "📚 Para mais informações, consulte o README.md" 