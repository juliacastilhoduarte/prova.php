<?php
session_start();

// Verifica se o pedido foi confirmado com um ID válido
if (!isset($_GET['pedido'])) {
    header('Location: index.php');
    exit;
}

$pedidoId = (int) $_GET['pedido'];
$nomeCliente = $_SESSION['cliente_nome'] ?? 'Cliente';
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
