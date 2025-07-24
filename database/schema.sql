-- mini-erp-dev ERP Database Schema
-- MySQL 8.0+

CREATE DATABASE IF NOT EXISTS mini-erp-dev CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mini-erp-dev;

-- Tabela de produtos
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT NULL,
    variations JSON NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Tabela de estoque
CREATE TABLE inventory (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    variation VARCHAR(255) NULL,
    quantity INT NOT NULL DEFAULT 0,
    min_quantity INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tabela de cupons
CREATE TABLE coupons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL UNIQUE,
    description VARCHAR(255) NOT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    min_amount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    max_uses INT NULL,
    used_count INT NOT NULL DEFAULT 0,
    valid_from DATE NOT NULL,
    valid_until DATE NOT NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Tabela de pedidos
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(255) NOT NULL UNIQUE,
    status ENUM('pending', 'confirmed', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
    subtotal DECIMAL(10, 2) NOT NULL,
    shipping_cost DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    discount DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    total DECIMAL(10, 2) NOT NULL,
    coupon_id BIGINT UNSIGNED NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(255) NULL,
    shipping_address VARCHAR(255) NOT NULL,
    shipping_city VARCHAR(255) NOT NULL,
    shipping_state VARCHAR(255) NOT NULL,
    shipping_zipcode VARCHAR(255) NOT NULL,
    shipping_country VARCHAR(255) NOT NULL DEFAULT 'Brasil',
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (coupon_id) REFERENCES coupons(id) ON DELETE SET NULL
);

-- Tabela de itens do pedido
CREATE TABLE order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    variation VARCHAR(255) NULL,
    quantity INT NOT NULL,
    unit_price DECIMAL(10, 2) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Índices para melhor performance
CREATE INDEX idx_products_active ON products(active);
CREATE INDEX idx_inventory_product_variation ON inventory(product_id, variation);
CREATE INDEX idx_coupons_code_active ON coupons(code, active);
CREATE INDEX idx_coupons_valid_dates ON coupons(valid_from, valid_until);
CREATE INDEX idx_orders_status ON orders(status);
CREATE INDEX idx_orders_customer_email ON orders(customer_email);
CREATE INDEX idx_order_items_order_id ON order_items(order_id);
CREATE INDEX idx_order_items_product_id ON order_items(product_id);

-- Inserir dados de exemplo
INSERT INTO products (name, price, description, variations, active, created_at, updated_at) VALUES
('Smartphone Galaxy S23', 2999.99, 'Smartphone Samsung Galaxy S23 com 128GB', '["Preto", "Branco", "Verde"]', TRUE, NOW(), NOW()),
('Notebook Dell Inspiron', 4599.99, 'Notebook Dell Inspiron 15" Intel i5 8GB 256GB SSD', '["Intel i5", "Intel i7"]', TRUE, NOW(), NOW()),
('Fone de Ouvido Bluetooth', 299.99, 'Fone de ouvido sem fio com cancelamento de ruído', NULL, TRUE, NOW(), NOW()),
('Smart TV 55" 4K', 2899.99, 'Smart TV Samsung 55" 4K Ultra HD', NULL, TRUE, NOW(), NOW()),
('Mouse Gamer RGB', 199.99, 'Mouse gamer com RGB e 6 botões programáveis', '["Preto", "Branco"]', TRUE, NOW(), NOW());

INSERT INTO inventory (product_id, variation, quantity, min_quantity, created_at, updated_at) VALUES
(1, 'Preto', 50, 5, NOW(), NOW()),
(1, 'Branco', 30, 5, NOW(), NOW()),
(1, 'Verde', 25, 5, NOW(), NOW()),
(2, 'Intel i5', 20, 3, NOW(), NOW()),
(2, 'Intel i7', 15, 3, NOW(), NOW()),
(3, NULL, 100, 10, NOW(), NOW()),
(4, NULL, 25, 3, NOW(), NOW()),
(5, 'Preto', 80, 10, NOW(), NOW()),
(5, 'Branco', 60, 10, NOW(), NOW());

INSERT INTO coupons (code, description, type, value, min_amount, max_uses, valid_from, valid_until, created_at, updated_at) VALUES
('DESCONTO10', 'Desconto de 10% em compras acima de R$ 100', 'percentage', 10.00, 100.00, 100, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 3 MONTH), NOW(), NOW()),
('FRETE0', 'Frete grátis em compras acima de R$ 150', 'fixed', 20.00, 150.00, 50, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 2 MONTH), NOW(), NOW()),
('MEGA50', 'Desconto de R$ 50 em compras acima de R$ 500', 'fixed', 50.00, 500.00, 25, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 MONTH), NOW(), NOW());
