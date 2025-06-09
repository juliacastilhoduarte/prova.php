<?php
session_start();
require 'db.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (!$nome || !$email || !$senha) {
        $mensagem = 'Preencha todos os campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'Email inválido.';
    } elseif (!isset($_FILES['foto']) || !isset($_FILES['documento'])) {
        $mensagem = 'Envie a foto e o documento obrigatórios.';
    } else {
        $foto = $_FILES['foto'];
        $documento = $_FILES['documento'];

        $extFoto = strtolower(pathinfo($foto['name'], PATHINFO_EXTENSION));
        $extDoc = strtolower(pathinfo($documento['name'], PATHINFO_EXTENSION));

        if (!in_array($extFoto, ['jpg', 'jpeg', 'png', 'gif'])) {
            $mensagem = 'Formato de foto inválido. Use jpg, jpeg, png ou gif.';
        } elseif ($extDoc !== 'pdf') {
            $mensagem = 'Documento deve ser um arquivo PDF.';
        } else {
            $pastaFotos = 'uploads/fotos/';
            $pastaDocs = 'uploads/documentos/';
            if (!is_dir($pastaFotos)) mkdir($pastaFotos, 0755, true);
            if (!is_dir($pastaDocs)) mkdir($pastaDocs, 0755, true);

            $nomeFoto = uniqid() . '.' . $extFoto;
            $nomeDoc = uniqid() . '.' . $extDoc;

            $destinoFoto = $pastaFotos . $nomeFoto;
            $destinoDoc = $pastaDocs . $nomeDoc;

            if (move_uploaded_file($foto['tmp_name'], $destinoFoto) &&
                move_uploaded_file($documento['tmp_name'], $destinoDoc)) {

                $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetch()) {
                    $mensagem = 'Email já cadastrado.';
                    unlink($destinoFoto);
                    unlink($destinoDoc);
                } else {
                    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("INSERT INTO clientes (nome, email, senha, foto, documento) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([$nome, $email, $senhaHash, $destinoFoto, $destinoDoc]);
                    $_SESSION['cliente_id'] = $pdo->lastInsertId();
                    $_SESSION['cliente_nome'] = $nome;

                    // Redireciona para index.php após cadastro com sucesso
                    header('Location: index.php');
                    exit;
                }
            } else {
                $mensagem = 'Erro no upload dos arquivos.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8" />
<title>Cadastro</title>
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

.form-box {
    background: #ffffff;
    padding: 30px 25px;
    max-width: 400px;
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
}

h2 {
    margin-bottom: 25px;
    font-weight: 600;
    color: #333;
    text-align: center;
}

input[type="text"],
input[type="email"],
input[type="password"],
input[type="file"] {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 18px;
    border: 1.5px solid #ccc;
    border-radius: 6px;
    font-size: 15px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus,
input[type="file"]:focus {
    border-color: #4caf50;
    outline: none;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #555;
}

button {
    background-color: #007bff; /* Azul */
    color: white;
    border: none;
    padding: 10px;
    font-weight: bold;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    width: 100%;
    margin-bottom: 15px;
}

button:hover {
    background-color: #0056b3; /* Azul escuro */
}

.mensagem {
    background-color: #f8d7da;
    color: #721c24;
    padding: 12px;
    border-radius: 6px;
    margin-bottom: 20px;
    text-align: center;
}

a {
    display: block;
    text-align: center;
    margin-top: 15px;
    color: #4caf50;
    text-decoration: none;
    font-weight: 500;
}

a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
<div class="form-box">
    <h2>Cadastro</h2>
    <?php if ($mensagem): ?>
        <div class="mensagem"><?= htmlspecialchars($mensagem) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="nome" placeholder="Nome completo" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" />
        <input type="email" name="email" placeholder="E-mail" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />
        <input type="password" name="senha" placeholder="Senha" required />
        
        <label for="foto">Foto de perfil (jpg, png, gif)</label>
        <input type="file" name="foto" id="foto" accept="image/*" required />
        
        <label for="documento">Documento PDF</label>
        <input type="file" name="documento" id="documento" accept="application/pdf" required />
        
        <button type="submit">Cadastrar</button>
    </form>
    <a href="login.php">Já tem uma conta? Faça login</a>
</div>
</body>
</html>
