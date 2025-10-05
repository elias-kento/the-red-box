<?php
session_start();

// Se não estiver logado, volta para index
if (!isset($_SESSION['id'])) {
  header("Location: index.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Perfil do Usuário</title>
  <style>
    body {
      background: #000;
      color: #EDEDED;
      font-family: system-ui, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .perfil-box {
      background: #141418;
      padding: 32px;
      border-radius: 12px;
      border: 1px solid #E4002B;
      text-align: center;
      max-width: 400px;
      box-shadow: 0 0 12px rgba(255,255,255,0.05);
    }
    h1 { color: #E4002B; margin-bottom: 20px; }
    p { margin: 8px 0; }
    .actions { margin-top: 20px; display: flex; flex-direction: column; gap: 10px; }
    .actions a, .actions form button {
      display: inline-block;
      padding: 10px 16px;
      border-radius: 6px;
      background: var(--red, #E4002B);
      color: #fff;
      font-weight: bold;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: background 0.2s ease;
    }
    .actions a:hover, .actions form button:hover {
      background: #b30020;
    }
  </style>
</head>
<body>
  <div class="perfil-box">
    <h1>Perfil do Usuário</h1>
    <p><strong>Nome:</strong> <?= $_SESSION['nome']; ?></p>
    <p><strong>Email:</strong> <?= $_SESSION['email']; ?></p>
    <p><strong>Tipo:</strong> <?= $_SESSION['tipo']; ?></p>

    <div class="actions">
      <a href="editar.php">✏️ Editar Dados</a>
      <form action="excluir.php" method="post" onsubmit="return confirm('Tem certeza que deseja excluir sua conta?');">
        <button type="submit">🗑️ Excluir Conta</button>
      </form>
      <form action="logout.php" method="post">
        <button type="submit">🚪 Sair</button>
      </form>
    </div>
  </div>
</body>
</html>
