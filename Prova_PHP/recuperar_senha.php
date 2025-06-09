<?php
session_start();
require 'db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (!$email) {
        $mensagem = 'Informe seu e-mail.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        $cliente = $stmt->fetch();

        if ($cliente) {
            // Simula envio de e-mail (em um sistema real, envie um e-mail com link)
            $token = bin2hex(random_bytes(16));
            $link = "https://seudominio.com/nova_senha.php?token=$token";

            // Armazena o token no banco ou sessão temporária (aqui vamos simular com $_SESSION)
            $_SESSION['recuperacao_token'] = $token;
            $_SESSION['recuperacao_email'] = $email;

            $mensagem = "Um link de recuperação foi enviado para <strong>$email</strong>. <br><br>
                         (Simulado: <a href='nova_senha.php?token=$token'>Clique aqui para redefinir</a>)";
        } else {
            $mensagem = 'E-mail não encontrado.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <style>
        body {
            font-family: Arial;
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        .mensagem {
            margin-bottom: 15px;
            text-align: center;
            color: #333;
        }
        .voltar {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="box">
    <h2>Recuperar Senha</h2>

    <?php if ($mensagem): ?>
        <div class="mensagem"><?= $mensagem ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Digite seu e-mail" required>
        <button type="submit">Enviar link de recuperação</button>
    </form>
    <a href="login.php" class="voltar">← Voltar ao login</a>
</div>
</body>
</html>
