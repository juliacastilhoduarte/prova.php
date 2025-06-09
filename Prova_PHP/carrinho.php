<?php
// Simula produtos como no index.php (em um sistema real, você consultaria do banco de dados)
$produtos = [
    1 => ['nome' => 'Camiseta Masculina', 'preco' => 49.90],
    2 => ['nome' => 'Tênis Esportivo', 'preco' => 199.90],
    3 => ['nome' => 'Mochila Escolar', 'preco' => 89.90],
    4 => ['nome' => 'skateboard', 'preco' => 199.90],
    5 => ['nome' => 'bolas de volêi', 'preco' => 89.90]

];

// Recupera o carrinho do cookie
$carrinho = json_decode($_COOKIE['carrinho'] ?? '{}', true);

// Manipulações do carrinho
if (isset($_GET['remover'])) {
    $id = $_GET['remover'];
    unset($carrinho[$id]);
    setcookie('carrinho', json_encode($carrinho), time() + 3600, '/');
    header('Location: carrinho.php');
    exit;
}

if (isset($_GET['limpar'])) {
    setcookie('carrinho', '', time() - 3600, '/');
    header('Location: carrinho.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho de Compras</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 20px; }
        .carrinho-box { max-width: 700px; margin: auto; background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 0 10px #ccc; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .total { font-weight: bold; font-size: 18px; text-align: right; }
        .btn { padding: 8px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px; display: inline-block; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #bd2130; }
    </style>
</head>
<body>
    <div class="carrinho-box">
        <h2>Carrinho de Compras</h2>

        <?php if (empty($carrinho)): ?>
            <p>Seu carrinho está vazio.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Total</th>
                        <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($carrinho as $id => $qtd): ?>
                        <?php if (isset($produtos[$id])): ?>
                            <?php
                                $produto = $produtos[$id];
                                $subtotal = $qtd * $produto['preco'];
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($produto['nome']) ?></td>
                                <td><?= $qtd ?></td>
                                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                                <td>
                                    <a href="?remover=<?= $id ?>" class="btn btn-danger">Remover</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="total">Total: R$ <?= number_format($total, 2, ',', '.') ?></div>
            <a href="index.php" class="btn">Continuar comprando</a>
            <a href="?limpar=1" class="btn btn-danger">Limpar carrinho</a>
            <a href="pedido_confirmado.php?pedido=<?= rand(1000,9999) ?>" class="btn" style="background: #28a745;">Finalizar Compra</a>
        <?php endif; ?>
    </div>
</body>
</html>
