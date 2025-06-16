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
        background: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    header {
        background: #fff;
        border-bottom: 1px solid #ddd;
        padding: 15px 20px;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar {
        max-width: 1200px;
        margin: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo h1 {
        margin: 0;
        font-size: 24px;
        color: #333;
    }

    .nav-links a {
        background: #007bff;
        color: white;
        padding: 8px 15px;
        border-radius: 6px;
        text-decoration: none;
        margin-left: 10px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .nav-links a:hover {
        background: #0056b3;
    }

    .form-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px);
        padding: 20px;
    }

    .form-box {
        background: white;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
        box-sizing: border-box;
    }

    h2 {
        margin-bottom: 25px;
        color: #333;
        text-align: center;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 18px;
        border: 1.8px solid #ccc;
        border-radius: 8px;
        font-size: 15px;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
    }

    input:focus {
        border-color: #007bff;
        outline: none;
    }

    label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #555;
        display: block;
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
        color: #007bff;
        text-decoration: none;
        font-weight: 500;
    }

    a:hover {
        text-decoration: underline;
    }

    @media (max-width: 600px) {
        .navbar {
            flex-direction: column;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .nav-links a {
            width: 90%;
            text-align: center;
            margin: 5px 0;
        }
    }
</style>
</head>
<body>

<header>
    <div class="navbar">
        <div class="logo">
            <h1>🛒 Minha Loja Online</h1>
        </div>
    </div>
</header>

<div class="form-container">
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
</div>

</body>
</html>
