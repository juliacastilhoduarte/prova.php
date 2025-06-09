<?php
session_start();
require 'db.php';

// Verifica se usuário está logado
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clienteId = $_SESSION['cliente_id'];

    // Busca os dados do usuário antes da exclusão
    $stmt = $pdo->prepare("SELECT email, foto, documento FROM clientes WHERE id = ?");
    $stmt->execute([$clienteId]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        // Registra na tabela exclusoes_conta (sem motivo, pode ser ajustado para receber input)
        $stmtInsert = $pdo->prepare("INSERT INTO exclusoes_conta (cliente_id, email) VALUES (?, ?)");
        $stmtInsert->execute([$clienteId, $usuario['email']]);

        // Exclui os arquivos, se existirem
        if (file_exists($usuario['foto'])) {
            unlink($usuario['foto']);
        }
        if (file_exists($usuario['documento'])) {
            unlink($usuario['documento']);
        }

        // Exclui o usuário do banco
        $stmtDelete = $pdo->prepare("DELETE FROM clientes WHERE id = ?");
        $stmtDelete->execute([$clienteId]);

        // Encerra sessão e redireciona
        session_destroy();
        header('Location: index.php');
        exit;
    } else {
        $mensagem = 'Usuário não encontrado.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Excluir Conta</title>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f9f9f9;
    margin: 0;
    padding: 40px 15px;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.box {
    background: #fff;
    padding: 25px;
    max-width: 400px;
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
}

h2 {
    margin-bottom: 20px;
    color: #333;
}

button {
    background-color: #dc3545; /* Vermelho */
    color: white;
    border: none;
    padding: 12px 20px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 16px;
}

button:hover {
    background-color: #b52a37;
}

.mensagem {
    color: red;
    margin-bottom: 15px;
}
</style>
</head>
<body>
<div class="box">
    <h2>Excluir Conta</h2>
    <?php if ($mensagem): ?>
        <p class="mensagem"><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>
    <p>Tem certeza que deseja excluir sua conta? Essa ação não pode ser desfeita.</p>
    <form method="post">
        <button type="submit">Confirmar exclusão</button>
    </form>
</div>
</body>
</html>
