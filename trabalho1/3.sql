-- ============================================================================
-- SCRIPT DE CRIAÇÃO E POPULAÇÃO DO BANCO DE DADOS - ESCOLA_DB
-- Sistema de Gestão de Estoque para Equipamentos Educacionais
-- ============================================================================

-- Criar banco de dados
CREATE DATABASE IF NOT EXISTS escola_db;
USE escola_db;

-- ============================================================================
-- TABELA DE USUÁRIOS
-- ============================================================================
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(150) NOT NULL,
    telefone VARCHAR(20),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ativo BOOLEAN DEFAULT TRUE,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELA DE PRODUTOS
-- ============================================================================
CREATE TABLE produtos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) UNIQUE NOT NULL,
    descricao VARCHAR(500),
    quantidade_atual INT NOT NULL DEFAULT 0 CHECK (quantidade_atual >= 0),
    estoque_minimo INT NOT NULL DEFAULT 5 CHECK (estoque_minimo >= 0),
    preco_unitario DECIMAL(10, 2),
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    criado_por INT,
    ativo BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (criado_por) REFERENCES usuarios(id),
    INDEX idx_nome (nome),
    INDEX idx_ativo (ativo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- TABELA DE MOVIMENTAÇÕES DE ESTOQUE
-- ============================================================================
CREATE TABLE movimentacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    produto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    tipo ENUM('entrada', 'saida') NOT NULL,
    quantidade INT NOT NULL CHECK (quantidade > 0),
    data_movimentacao DATE NOT NULL,
    observacoes VARCHAR(500),
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
    INDEX idx_produto (produto_id),
    INDEX idx_usuario (usuario_id),
    INDEX idx_data (data_movimentacao),
    INDEX idx_tipo (tipo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- POPULAÇÃO DA TABELA USUARIOS
-- ============================================================================
INSERT INTO usuarios (email, senha, nome, telefone) VALUES
('admin@escola.com', SHA2('senha123', 256), 'Administrador', '(11) 98765-4321'),
('gerente@escola.com', SHA2('gerente456', 256), 'João Silva Gerente', '(11) 97654-3210'),
('estoquista@escola.com', SHA2('estoque789', 256), 'Maria Santos Estoquista', '(11) 96543-2109');

-- ============================================================================
-- POPULAÇÃO DA TABELA PRODUTOS
-- ============================================================================
INSERT INTO produtos (nome, descricao, quantidade_atual, estoque_minimo, preco_unitario, criado_por) VALUES
('Caderno 200 folhas', 'Caderno de 200 folhas, padrão A4, capa dura', 45, 10, 25.50, 1),
('Canetão Colorido', 'Kit com 12 cores de canetão permanente', 120, 20, 15.90, 1),
('Uniforme Tamanho G', 'Uniforme escolar tamanho grande, cor azul', 30, 5, 85.00, 1),
('Lápis Grafite HB', 'Caixa com 36 lápis grafite tipo HB', 80, 15, 18.50, 1),
('Borracha Branca', 'Borracha branca pequena, pacote com 10 unidades', 150, 20, 8.90, 2),
('Marcadores de Texto', 'Kit com 4 cores de marcadores fluorescentes', 60, 10, 12.50, 2),
('Caderno Desenho', 'Caderno para desenho 50 folhas, papel 180g', 35, 8, 32.00, 3),
('Apontador Metal', 'Apontador duplo metálico, caixa com 5', 95, 15, 9.80, 3),
('Tinta Guache 12 cores', 'Guache líquido em 12 cores, 15ml cada', 25, 5, 35.90, 1);

-- ============================================================================
-- POPULAÇÃO DA TABELA MOVIMENTACOES
-- ============================================================================
INSERT INTO movimentacoes (produto_id, usuario_id, tipo, quantidade, data_movimentacao, observacoes) VALUES
-- Movimentações do Caderno 200 folhas
(1, 1, 'entrada', 50, '2024-10-01', 'Entrada inicial de estoque'),
(1, 2, 'saida', 10, '2024-10-05', 'Saída para sala de aula'),
(1, 3, 'saida', 5, '2024-10-10', 'Entrega ao professor de português'),

-- Movimentações do Canetão Colorido
(2, 1, 'entrada', 100, '2024-10-02', 'Compra de fornecedor'),
(2, 2, 'saida', 20, '2024-10-08', 'Saída para atividades práticas'),
(2, 3, 'entrada', 50, '2024-10-12', 'Reposição de estoque'),

-- Movimentações do Uniforme Tamanho G
(3, 1, 'entrada', 40, '2024-10-03', 'Entrada de novo lote'),
(3, 2, 'saida', 15, '2024-10-07', 'Entrega para alunos novos'),
(3, 3, 'saida', 5, '2024-10-11', 'Reposição de trocas'),

-- Movimentações do Lápis Grafite HB
(4, 1, 'entrada', 100, '2024-10-04', 'Pedido atendido'),
(4, 2, 'saida', 30, '2024-10-09', 'Distribuição em salas'),
(4, 3, 'saida', 10, '2024-10-13', 'Uso em provas'),

-- Movimentações da Borracha Branca
(5, 1, 'entrada', 200, '2024-10-01', 'Entrada de estoque'),
(5, 2, 'saida', 25, '2024-10-06', 'Saída para alunos'),
(5, 3, 'saida', 8, '2024-10-12', 'Reposição de salas'),

-- Movimentações dos Marcadores de Texto
(6, 1, 'entrada', 50, '2024-10-05', 'Compra para ano letivo'),
(6, 2, 'saida', 15, '2024-10-10', 'Atividade de estudo'),
(6, 3, 'entrada', 20, '2024-10-14', 'Entrada extra'),

-- Movimentações do Caderno Desenho
(7, 1, 'entrada', 40, '2024-10-02', 'Pedido entregue'),
(7, 2, 'saida', 12, '2024-10-08', 'Aulas de artes'),
(7, 3, 'saida', 3, '2024-10-13', 'Reposição'),

-- Movimentações do Apontador Metal
(8, 1, 'entrada', 100, '2024-10-03', 'Entrada inicial'),
(8, 2, 'saida', 20, '2024-10-09', 'Saída para uso'),
(8, 3, 'saida', 5, '2024-10-14', 'Reposição de salas'),

-- Movimentações da Tinta Guache
(9, 1, 'entrada', 30, '2024-10-04', 'Compra de material de arte'),
(9, 2, 'saida', 8, '2024-10-11', 'Aulas práticas'),
(9, 3, 'entrada', 10, '2024-10-15', 'Reposição');

-- ============================================================================
-- VERIFICAÇÃO FINAL
-- ============================================================================
SELECT 'Usuários cadastrados:' AS info;
SELECT COUNT(*) AS total FROM usuarios;

SELECT 'Produtos cadastrados:' AS info;
SELECT COUNT(*) AS total FROM produtos;

SELECT 'Movimentações registradas:' AS info;
SELECT COUNT(*) AS total FROM movimentacoes;

-- ============================================================================
-- FIM DO SCRIPT
-- ============================================================================