-- ====================================================
-- carbonburguer.sql - Script de criação do banco de dados
-- Estrutura completa do banco para o sistema CarbonBurguer
-- ====================================================

-- ===============================
-- CRIAÇÃO DO BANCO DE DADOS
-- ===============================
DROP DATABASE IF EXISTS carbonburguer;
CREATE DATABASE carbonburguer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE carbonburguer;

-- ===============================
-- TABELA DE USUÁRIOS
-- ===============================
-- Armazena dados dos usuários do sistema
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 0,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_admin (is_admin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- TABELA DE PRODUTOS
-- ===============================
-- Armazena todos os produtos do cardápio
CREATE TABLE produtos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(200) NOT NULL,
  descricao TEXT,
  preco DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  categoria VARCHAR(80) DEFAULT 'hamburguer',
  imagem VARCHAR(255),
  ativo TINYINT(1) DEFAULT 1,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_categoria (categoria),
  INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- TABELA DE PEDIDOS
-- ===============================
-- Armazena os pedidos realizados pelos clientes
CREATE TABLE pedidos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT,
  nome VARCHAR(150),
  endereco VARCHAR(255),
  obs TEXT,
  total DECIMAL(10,2) DEFAULT 0.00,
  status VARCHAR(50) DEFAULT 'pendente',
  data_pedido TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
  INDEX idx_usuario (usuario_id),
  INDEX idx_status (status),
  INDEX idx_data (data_pedido)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- TABELA DE ITENS DO PEDIDO
-- ===============================
-- Armazena os produtos de cada pedido
CREATE TABLE itens_pedido (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pedido_id INT NOT NULL,
  produto_id INT,
  quantidade INT DEFAULT 1,
  preco DECIMAL(10,2) DEFAULT 0.00,
  subtotal DECIMAL(10,2) DEFAULT 0.00,
  FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
  FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE SET NULL,
  INDEX idx_pedido (pedido_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===============================
-- USUÁRIO ADMIN PADRÃO
-- ===============================
-- Inserir usuário admin padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, is_admin) VALUES
('Administrador', 'admin@carbonburguer.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Inserir produtos exemplo
INSERT INTO produtos (nome, descricao, preco, categoria, imagem, ativo) VALUES
('Carbon Burger', 'Pão brioche artesanal, 2x150g de carne angus, queijo cheddar derretido, bacon caramelizado e maionese especial da casa', 34.90, 'hamburguer', 'assets/img/burger1.jpg', 1),
('Smoky Bacon', 'Hambúrguer 180g de carne premium, queijo provolone flamejado, bacon crocante e molho barbecue defumado', 36.90, 'hamburguer', 'assets/img/burger2.jpg', 1),
('Carbon Combo', 'Burger Carbon + Porção média de batata rústica + Refrigerante 350ml', 49.00, 'combo', 'assets/img/combo1.jpg', 1),
('Veggie Burger', 'Hambúrguer vegetariano de grão de bico e quinoa, alface, tomate, cebola caramelizada e molho especial', 32.90, 'hamburguer', 'assets/img/burger3.jpg', 1),
('Batata Rústica', 'Porção generosa de batata rústica com ervas finas e parmesão ralado', 14.50, 'acompanhamento', 'assets/img/batata.jpg', 1),
('Onion Rings', 'Anéis de cebola empanados e crocantes com molho ranch', 12.00, 'acompanhamento', 'assets/img/onion.jpg', 1),
('Brownie Carbon', 'Brownie artesanal de chocolate belga com calda quente e sorvete de baunilha', 12.00, 'sobremesa', 'assets/img/brownie.jpg', 1),
('Milkshake', 'Milkshake cremoso nos sabores: chocolate, baunilha ou morango', 15.00, 'bebida', 'assets/img/milkshake.jpg', 1),
('Coca-Cola 350ml', 'Refrigerante Coca-Cola lata 350ml gelada', 5.00, 'bebida', 'assets/img/coca.jpg', 1),
('Suco Natural', 'Suco natural de laranja, limão ou maracujá (300ml)', 8.00, 'bebida', 'assets/img/suco.jpg', 1);