<?php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

$stmt = $pdo->prepare("SELECT nome, email, foto, documento FROM clientes WHERE id = ?");
$stmt->execute([$_SESSION['cliente_id']]);
$cliente = $stmt->fetch();

if (!$cliente) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Perfil do Cliente</title>
<style>
body {
    font-family: Arial;
    background: #f9f9f9;
    padding: 40px;
}
.container {
    max-width: 600px;
    margin: auto;
    background: #fff;
    padding: 20px;
    border-radius: 6px;
    box-shadow: 0 0 10px #ccc;
    text-align: center;
}
img {
    max-width: 150px;
    border-radius: 50%;
    margin-bottom: 20px;
    border: 3px solid #28a745;
}
a.button, button {
    padding: 10px 20px;
    margin: 10px 5px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    font-weight: bold;
    text-decoration: none;
}
a.button-home {
    background-color: #28a745;
}
a.button-home:hover {
    background-color: #218838;
}
a.button-pdf {
    background-color: #007bff;
}
a.button-pdf:hover {
    background-color: #0056b3;
}
button.excluir {
    background-color: #dc3545;
}
button.excluir:hover {
    background-color: #c82333;
}
</style>
</head>
<body>
<div class="container">
    <h1>Olá, <?= htmlspecialchars($cliente['nome']) ?></h1>
    <img src="<?= htmlspecialchars($cliente['foto']) ?>" alt="Foto de perfil" />
    <p><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></p>

    <!-- Link para visualizar documento PDF -->
    <p>
        <a href="<?= htmlspecialchars($cliente['documento']) ?>" target="_blank" class="button button-pdf">Ver Documento PDF</a>
    </p>

    <a href="index.php" class="button button-home">Home</a>

    <form action="exclui_conta.php" method="get" style="display:inline;">
        <button type="submit" class="excluir">Excluir Conta</button>
    </form>
</div>
</body>
</html>
