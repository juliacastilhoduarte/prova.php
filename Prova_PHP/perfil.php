<?php
session_start();
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit;
}

require 'db.php';

$stmt = $pdo->prepare("SELECT nome, email, foto FROM clientes WHERE id = ?");
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
body { font-family: Arial; background: #f9f9f9; padding: 40px; }
.container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 6px; box-shadow: 0 0 10px #ccc; text-align: center; }
img { max-width: 150px; border-radius: 50%; margin-bottom: 20px; }
a.logout { display: inline-block; margin-top: 20px; background: #dc3545; color: #fff; padding: 10px 15px; text-decoration: none; border-radius: 4px; }
a.logout:hover { background: #c82333; }
</style>
</head>
<body>
<div class="container">
    <h1>Olá, <?= htmlspecialchars($cliente['nome']) ?></h1>
    <img src="<?= htmlspecialchars($cliente['foto']) ?>" alt="Foto de perfil" />
    <p><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></p>
    <a href="logout.php" class="logout">Sair</a>
</div>

<form action="exclui_conta.php" method="get">
    <button type="submit" style="background-color:#dc3545; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
        Excluir Conta
    </button>
</form>

</body>
</html>
