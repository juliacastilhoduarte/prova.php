-- Banco de dados: `loja`

CREATE TABLE `clientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `documento` varchar(255) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `exclusoes_conta` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `motivo` text DEFAULT NULL,
  `data_exclusao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `exclusoes_conta_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `produto_id` (`produto_id`),
  CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `cliente_id` (`cliente_id`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)

  INSERT INTO produtos (nome, descricao, preco, imagem) VALUES
('Camiseta Masculina', 'Camiseta em algodão confortável, perfeita para o dia a dia.', 49.90, 'https://img.irroba.com.br/fit-in/600x600/filters:fill(fff):quality(80)/gugicalc/catalog/tiny/camiseta-basica-masculina-301-100-algodao-cm01-512-preta-5.jpg'),
('Tênis Esportivo', 'Tênis esportivo com amortecimento e design moderno.', 199.90, 'https://sc04.alicdn.com/kf/H942dd6c4ad3d42da9e2cc529013ded54r.jpg'),
('Mochila Escolar', 'Mochila resistente com vários compartimentos.', 89.90, 'https://media.istockphoto.com/id/1339055637/pt/foto/back-to-school-background-stationery-supplies-in-the-school-bag-banner-design-education-on.jpg?s=612x612&w=0&k=20&c=MChMZ933AFFQ5uqK66h0VwFMX-UZ03XAdk977ZdROpo='),
('Skateboard', 'Explore sua liberdade com nossos skateboards de alta qualidade, perfeitos para manobras, passeios urbanos e aventuras no parque.', 199.90, 'https://via.placeholder.com/600x400?text=Imagem+Skateboard'), -- Usei uma imagem genérica pois a original está em base64
('Bolas de Vôlei', 'Domine a quadra com nossas bolas de vôlei profissionais, projetadas para oferecer controle, leveza e resistência.', 89.90, 'https://th.bing.com/th/id/OIP.SeKtUEaCSWjmsALIojvxOwHaE7?w=228&h=180&c=7&r=0&o=7&pid=1.7&rm=3');

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
