<?php
session_start();
if (empty($_SESSION['id'])) {
  header('Location: entrar.php');
  exit;
}

$nome = htmlspecialchars($_SESSION['nome'] ?? '', ENT_QUOTES, 'UTF-8');
$tipo = strtoupper($_SESSION['tipo'] ?? '');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Perfil salvo com sucesso</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      background-color: #000;
      color: #EDEDED;
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      text-align: center;
    }
    .card {
      background: #141418;
      padding: 40px 60px;
      border-radius: 12px;
      border: 1px solid #E4002B;
      box-shadow: 0 0 16px rgba(255, 0, 0, 0.1);
    }
    h1 {
      color: #E4002B;
      margin-bottom: 20px;
    }
    p {
      font-size: 18px;
      margin-bottom: 30px;
    }
    a {
      background: #E4002B;
      color: #fff;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 6px;
      font-weight: bold;
    }
    a:hover {
      background: #b80022;
    }
  </style>
</head>
<body>
  <div class="card">
    <h1>Perfil salvo com sucesso!</h1>
    <p>Parabéns, <?= $nome ?>! Suas informações foram registradas corretamente.</p>

    <?php if ($tipo === 'HACKER'): ?>
      <a href="perfil_hacker.php">Ver meu perfil</a>
    <?php else: ?>
      <a href="perfil_cliente.php">Ver meu perfil</a>
    <?php endif; ?>
  </div>
</body>
</html>
