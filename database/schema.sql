-- SisTerMa - Sistema de Gestão de Condomínios
-- Schema Multi-Tenant

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Planos de assinatura
CREATE TABLE IF NOT EXISTS planos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    tipo ENUM('mensal', 'anual') DEFAULT 'mensal',
    max_unidades INT UNSIGNED DEFAULT 0 COMMENT '0 = ilimitado',
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Condomínios (tenants)
CREATE TABLE IF NOT EXISTS condominios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    plano_id INT UNSIGNED NOT NULL,
    nome VARCHAR(255) NOT NULL,
    cnpj VARCHAR(18) NULL,
    endereco VARCHAR(255) NULL,
    cidade VARCHAR(100) NULL,
    estado VARCHAR(2) NULL,
    cep VARCHAR(10) NULL,
    telefone VARCHAR(20) NULL,
    status ENUM('ativo', 'bloqueado', 'suspenso') DEFAULT 'ativo',
    pagamento_status ENUM('pendente', 'pago', 'vencido', 'bloqueado') DEFAULT 'pendente',
    pagamento_vencimento DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (plano_id) REFERENCES planos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usuários (Admin Master, Síndicos)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    condominio_id INT UNSIGNED NULL COMMENT 'NULL = Admin Master',
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    role ENUM('admin_master', 'sindico', 'morador') NOT NULL,
    ativo TINYINT(1) DEFAULT 1,
    ultimo_acesso DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Moradores
CREATE TABLE IF NOT EXISTS moradores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    condominio_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NULL COMMENT 'Vinculado ao login quando existir',
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NULL,
    cpf VARCHAR(14) NULL,
    telefone VARCHAR(20) NULL,
    bloco VARCHAR(20) NULL,
    unidade VARCHAR(20) NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Áreas comuns para reserva
CREATE TABLE IF NOT EXISTS areas_comuns (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    condominio_id INT UNSIGNED NOT NULL,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT NULL,
    ativo TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Pagamentos (assinatura do condomínio)
CREATE TABLE IF NOT EXISTS pagamentos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    condominio_id INT UNSIGNED NOT NULL,
    plano_id INT UNSIGNED NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    vencimento DATE NOT NULL,
    status ENUM('pendente', 'pago', 'vencido', 'cancelado') DEFAULT 'pendente',
    picpay_charge_id VARCHAR(100) NULL,
    picpay_reference_id VARCHAR(100) NULL,
    qr_code TEXT NULL,
    pago_em DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE CASCADE,
    FOREIGN KEY (plano_id) REFERENCES planos(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Avisos
CREATE TABLE IF NOT EXISTS avisos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    condominio_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    conteudo TEXT NOT NULL,
    tipo ENUM('geral', 'urgente', 'manutencao', 'reuniao') DEFAULT 'geral',
    publicado TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reservas de áreas comuns
CREATE TABLE IF NOT EXISTS reservas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    condominio_id INT UNSIGNED NOT NULL,
    area_comum_id INT UNSIGNED NOT NULL,
    morador_id INT UNSIGNED NOT NULL,
    data_reserva DATE NOT NULL,
    hora_inicio TIME NOT NULL,
    hora_fim TIME NOT NULL,
    status ENUM('pendente', 'confirmada', 'cancelada') DEFAULT 'confirmada',
    observacao TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE CASCADE,
    FOREIGN KEY (area_comum_id) REFERENCES areas_comuns(id) ON DELETE CASCADE,
    FOREIGN KEY (morador_id) REFERENCES moradores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ocorrências (registradas por moradores)
CREATE TABLE IF NOT EXISTS ocorrencias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    condominio_id INT UNSIGNED NOT NULL,
    morador_id INT UNSIGNED NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    tipo ENUM('reclamacao', 'sugestao', 'elogio', 'manutencao') DEFAULT 'reclamacao',
    status ENUM('aberta', 'em_andamento', 'resolvida') DEFAULT 'aberta',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE CASCADE,
    FOREIGN KEY (morador_id) REFERENCES moradores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Boletos (para moradores - simplificado)
CREATE TABLE IF NOT EXISTS boletos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    condominio_id INT UNSIGNED NOT NULL,
    morador_id INT UNSIGNED NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    valor DECIMAL(10,2) NOT NULL,
    vencimento DATE NOT NULL,
    status ENUM('pendente', 'pago', 'vencido') DEFAULT 'pendente',
    pago_em DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE CASCADE,
    FOREIGN KEY (morador_id) REFERENCES moradores(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Logs de acesso
CREATE TABLE IF NOT EXISTS logs_acesso (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NULL,
    morador_id INT UNSIGNED NULL,
    condominio_id INT UNSIGNED NULL,
    acao VARCHAR(100) NOT NULL,
    ip VARCHAR(45) NULL,
    user_agent TEXT NULL,
    dados JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL,
    FOREIGN KEY (morador_id) REFERENCES moradores(id) ON DELETE SET NULL,
    FOREIGN KEY (condominio_id) REFERENCES condominios(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Dados iniciais
INSERT INTO planos (nome, valor, tipo, max_unidades) VALUES
('Básico', 99.90, 'mensal', 20),
('Profissional', 199.90, 'mensal', 50),
('Premium', 299.90, 'mensal', 0);

-- Admin Master padrão (senha: admin123 - altere após primeiro acesso)
INSERT INTO usuarios (email, senha, nome, role) VALUES
('admin@sisterma.com.br', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'Administrador Master', 'admin_master');
