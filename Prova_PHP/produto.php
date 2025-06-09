<?php
// Simulação de base de dados de produtos
$produtos = [
    1 => [
        'nome' => 'Camiseta Masculina',
        'preco' => 49.90,
        'imagem' => 'https://img.irroba.com.br/fit-in/600x600/filters:fill(fff):quality(80)/gugicalc/catalog/tiny/camiseta-basica-masculina-301-100-algodao-cm01-512-preta-5.jpg',
        'descricao' => 'Camiseta em algodão confortável, perfeita para o dia a dia.'
    ],
    2 => [
        'nome' => 'Tênis Esportivo',
        'preco' => 199.90,
        'imagem' => 'https://sc04.alicdn.com/kf/H942dd6c4ad3d42da9e2cc529013ded54r.jpg',
        'descricao' => 'Tênis esportivo com amortecimento e design moderno.'
    ],
    3 => [
        'nome' => 'Mochila Escolar',
        'preco' => 89.90,
        'imagem' => 'https://media.istockphoto.com/id/1339055637/pt/foto/back-to-school-background-stationery-supplies-in-the-school-bag-banner-design-education-on.jpg?s=612x612&w=0&k=20&c=MChMZ933AFFQ5uqK66h0VwFMX-UZ03XAdk977ZdROpo=',
        'descricao' => 'Mochila resistente com vários compartimentos.'
    ],
    4 => [
       'id' => 4,
      'nome' => 'skateboard',
       'preco' => 199.90,
       'imagem' => 'data:image/webp;base64,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',
       'descricao' => 'Explore sua liberdade com nossos skateboards de alta qualidade, perfeitos para manobras, passeios urbanos e aventuras no parque.'
    ],
    5 => [
       'id' => 5,
       'nome' => 'bolas de vôlei',
      'preco' => 89.90,
     'imagem' => 'https://th.bing.com/th/id/OIP.SeKtUEaCSWjmsALIojvxOwHaE7?w=228&h=180&c=7&r=0&o=7&pid=1.7&rm=3',
     'descricao' => 'Domine a quadra com nossas bolas de vôlei profissionais, projetadas para oferecer controle, leveza e resistência.'
    ]

];

// Obtém ID do produto
$id = $_GET['id'] ?? null;

if (!$id || !isset($produtos[$id])) {
    echo "Produto não encontrado.";
    exit;
}

$produto = $produtos[$id];

// Adicionar ao carrinho
if (isset($_GET['adicionar'])) {
    $carrinho = json_decode($_COOKIE['carrinho'] ?? '{}', true);
    $carrinho[$id] = ($carrinho[$id] ?? 0) + 1;
    setcookie('carrinho', json_encode($carrinho), time() + 3600, '/');
    header("Location: carrinho.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($produto['nome']) ?> - Detalhes do Produto</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; padding: 30px; }
        .produto-box {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
            text-align: center;
        }
        .produto-box img {
            max-width: 100%;
            border-radius: 5px;
        }
        .produto-box h1 {
            font-size: 24px;
            margin-top: 20px;
        }
        .produto-box p {
            font-size: 16px;
            color: #444;
        }
        .preco {
            font-size: 20px;
            color: green;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn:hover {
            background: #218838;
        }
        .voltar {
            margin-top: 20px;
            display: block;
            text-align: center;
            color: #007bff;
            text-decoration: none;
        }
        .voltar:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="produto-box">
        <img src="<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
        <h1><?= htmlspecialchars($produto['nome']) ?></h1>
        <div class="preco">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></div>
        <p><?= htmlspecialchars($produto['descricao']) ?></p>
        <a href="?id=<?= $id ?>&adicionar=1" class="btn">Adicionar ao Carrinho</a>
        <a href="index.php" class="voltar">← Voltar à loja</a>
    </div>
</body>
</html>
