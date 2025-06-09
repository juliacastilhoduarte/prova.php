<?php
session_start();
require 'db.php';

$mensagem = '';

// Inicializa a contagem de tentativas
if (!isset($_SESSION['tentativas_login'])) {
    $_SESSION['tentativas_login'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$email || !$senha) {
        $mensagem = 'Preencha todos os campos.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM clientes WHERE email = ?");
        $stmt->execute([$email]);
        $cliente = $stmt->fetch();

        if ($cliente && password_verify($senha, $cliente['senha'])) {
            $_SESSION['cliente_id'] = $cliente['id'];
            $_SESSION['cliente_nome'] = $cliente['nome'];
            $_SESSION['tentativas_login'] = 0; // zera as tentativas
            header('Location: perfil.php');
            exit;
        } else {
            $_SESSION['tentativas_login']++;
            $mensagem = 'E-mail ou senha inválidos.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Login</title>
<style>
  body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f2f5;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
  }

  .form-box {
    background: white;
    padding: 30px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    width: 320px;
    box-sizing: border-box;
  }

  h2 {
    margin-bottom: 25px;
    color: #333;
    text-align: center;
  }

  input[type="email"],
  input[type="password"] {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0 20px;
    border: 1.8px solid #ccc;
    border-radius: 8px;
    font-size: 15px;
    transition: border-color 0.3s ease;
    box-sizing: border-box;
  }

  input[type="email"]:focus,
  input[type="password"]:focus {
    border-color: #007bff;
    outline: none;
  }

  button {
    width: 100%;
    background: linear-gradient(135deg, #4a90e2, #357abd);
    border: none;
    padding: 14px 0;
    border-radius: 10px;
    color: white;
    font-weight: 600;
    font-size: 17px;
    cursor: pointer;
    box-shadow: 0 6px 12px rgba(53, 122, 189, 0.5);
    transition: background 0.3s ease, box-shadow 0.3s ease;
  }

  button:hover {
    background: linear-gradient(135deg, #357abd, #2c5da8);
    box-shadow: 0 8px 20px rgba(44, 93, 168, 0.7);
  }

  .mensagem {
    color: red;
    margin-bottom: 10px;
    text-align: center;
  }

  a {
    display: block;
    text-align: center;
    margin-top: 18px;
    font-size: 14px;
    color: #666;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }

  .recuperar {
    margin-top: 10px;
    color: #007bff;
  }

  .recuperar:hover {
    text-decoration: underline;
  }
</style>
</head>
<body>
<div class="form-box">
    <h2>Login</h2>
    <?php if ($mensagem): ?>
        <div class="mensagem"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>
    <form method="post">
        <input type="email" name="email" placeholder="E-mail" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
        <input type="password" name="senha" placeholder="Senha" required />
        <button type="submit">Entrar</button>
    </form>

    <a href="cadastro.php">Não tem uma conta? Cadastre-se</a>
    <a href="exclui_conta.php" style="color:#dc3545;">Excluir conta</a>

    <?php if ($_SESSION['tentativas_login'] >= 3): ?>
        <a class="recuperar" href="recuperar_senha.php">Esqueceu a senha?</a>
    <?php endif; ?>
</div>
</body>
</html>
