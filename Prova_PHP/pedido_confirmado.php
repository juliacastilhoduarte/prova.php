<?php
session_start();
require 'db.php'; // conexão PDO

// Verifica se usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

$cliente_id = $_SESSION['cliente_id'];
$nomeCliente = $_SESSION['cliente_nome'] ?? 'Cliente';

// Recupera carrinho do cookie
$carrinho = json_decode($_COOKIE['carrinho'] ?? '{}', true);

if (empty($carrinho)) {
    // Redireciona se carrinho vazio
    header('Location: index.php');
    exit;
}

// Produtos fixos (idealmente buscados do banco)
$produto = [
    1 => ['nome' => 'Camiseta Masculina', 'preco' => 49.90],
    2 => ['nome' => 'Tênis Esportivo', 'preco' => 199.90],
    3 => ['nome' => 'Mochila Escolar', 'preco' => 89.90],
    4 => ['nome' => 'skateboard', 'preco' => 199.90],
    5 => ['nome' => 'bolas de volêi', 'preco' => 89.90]
];

// Calcula total do pedido
$total = 0;
foreach ($carrinho as $id => $qtd) {
    if (isset($produtos[$id])) {
        $total += $produtos[$id]['preco'] * $qtd;
    }
}

try {
    $pdo->beginTransaction();

    // Insere pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, total, criado_em) VALUES (?, ?, NOW())");
    $stmt->execute([$cliente_id, $total]);
    $pedidoId = $pdo->lastInsertId();

    // Insere itens do pedido
    $stmtItem = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
    foreach ($carrinho as $id => $qtd) {
        if (isset($produtos[$id])) {
            $preco = $produtos[$id]['preco'];
            $stmtItem->execute([$pedidoId, $id, $qtd, $preco]);
        }
    }

    $pdo->commit();

    // Limpa o carrinho
    setcookie('carrinho', '', time() - 3600, '/');

} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro ao processar pedido: " . $e->getMessage());
}

// Agora mostra a página de confirmação abaixo:
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            text-align: center;
            padding: 50px;
        }
        .box {
            background: white;
            display: inline-block;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h1 {
            color: #28a745;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            color: #333;
        }
        .btn {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="box">
    <h1>Compra Finalizada!</h1>
    <p>Obrigado, <strong><?= htmlspecialchars($nomeCliente) ?></strong>. Seu pedido foi confirmado com sucesso.</p>
    <p><strong>Número do Pedido:</strong> <?= $pedidoId ?></p>

    <a href="index.php" class="btn">Voltar para a Loja</a>
</div>

</body>
</html>
