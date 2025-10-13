<?php
session_start();
if (empty($_SESSION['id']) || strtoupper(($_SESSION['tipo'] ?? '')) !== 'ADMIN') {
  header('Location: entrar.php');
  exit;
}

// Conexão com o banco (mesma configuração do seu projeto)
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "redbox";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

// helper de escape (igual ao ver_perfil.php)
function e($v){ return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8'); }

// CSRF token para ações administrativas
if (empty($_SESSION['admin_csrf'])) {
  $_SESSION['admin_csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['admin_csrf'];

// busca todos os usuários (clientes e hackers) e exibe
$stmt = $conn->prepare("SELECT id, nome, email, tipo FROM usuarios ORDER BY tipo, nome");
$stmt->execute();
$result = $stmt->get_result();
$users = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$self_id = (int)($_SESSION['id'] ?? 0);
$self_name = $_SESSION['nome'] ?? '(admin)';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>The Red Box — Painel Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root {
      --bg:#000; --panel:#0D0D0F; --panel2:#141418;
      --red:#E4002B; --white:#EDEDED; --muted:#9A9A9A;
      --teal:#56C2C2; --line:#2A2A2A;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }
    body {
      background: var(--bg); color: var(--white);
      font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      display: flex; min-height: 100vh; overflow-x: hidden;
    }

    /* SIDEBAR */
    .sidebar {
      width: 230px; background: #0A0A0C; padding: 22px 18px;
      display: flex; flex-direction: column; align-items: center; gap: 26px;
      border-right: 2px solid var(--line);
    }
    .logo { width: 64px; height: 64px; display: grid; place-items: center; }
    .menu { width: 100%; display: flex; flex-direction: column; gap: 22px; }
    .item {
      display: flex; align-items: center; gap: 14px;
      color: var(--white); text-decoration: none;
      padding: 6px 10px; border-radius: 6px; transition: all 0.3s ease;
    }
    .item:hover { background-color: #1a1a1a; color: var(--red); }
    .item:hover .icon { color: var(--white); }
    .item .icon { width: 28px; height: 28px; display: grid; place-items: center; color: var(--red); }
    .sep { width: 80%; height: 2px; background: var(--line); margin: 6px auto; border-radius: 2px; opacity: .75; }

    /* MAIN */
    .main {
      flex: 1; display: flex; flex-direction: column;
      padding: 32px; align-items: center; justify-content: flex-start;
    }

    .page-title {
      font-size: 32px; font-weight: 700; color: var(--red);
      margin-bottom: 24px; border-bottom: 2px solid var(--line); width: 100%; max-width: 1000px;
      padding-bottom: 10px;
    }

    .profile-card {
      background-color: var(--panel2);
      padding: 24px; border-radius: 12px;
      width: 100%; max-width: 1000px;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.03);
    }

    .users-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    .users-table th, .users-table td {
      padding: 12px 10px;
      border-bottom: 1px solid var(--line);
      text-align: left;
      font-size: 15px;
    }
    .users-table thead th {
      color: var(--teal);
      font-weight: 700;
      border-bottom: 2px solid var(--line);
    }

    .actions {
      display: flex; justify-content: center; gap: 14px;
      margin-top: 18px; flex-wrap: wrap;
    }

    .actions a,
    .actions button {
      display: inline-flex; align-items: center; justify-content: center;
      background: var(--red); color: #fff; border: none; border-radius: 6px;
      padding: 10px 14px; text-decoration: none; cursor: pointer; font-weight: 600;
    }
    .delete-btn { background: #700; }
    .delete-btn:hover { background: #a00; }

    .muted { color: var(--muted); font-size: 14px; }

    @media (max-width: 900px) {
      .page-title { font-size: 26px; }
      .profile-card { padding: 16px; }
      .users-table th, .users-table td { padding: 8px; font-size: 14px; }
    }
  </style>
</head>
<body>
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">
      <svg viewBox="0 0 24 24" fill="none" width="42" height="42">
        <rect x="1.5" y="3" width="21" height="18" rx="2" stroke="#E4002B" stroke-width="1.7"/>
        <path d="M2 8H22" stroke="#E4002B" stroke-width="1.7"/>
        <path d="M8 3V8" stroke="#E4002B" stroke-width="1.7"/>
      </svg>
    </div>

    <nav class="menu">
      <a class="item" href="index.php"><span class="icon">🏠</span><span>Início</span></a>
      <div class="sep"></div>
      <a class="item" href="sobre.php"><span class="icon">🧠</span><span>Quem Somos</span></a>
      <div class="sep"></div>
      <a class="item" href="cadastro.php"><span class="icon">👤</span><span>Cadastrar</span></a>
      <div class="sep"></div>
      <a class="item" href="entrar.php"><span class="icon">🔑</span><span>Login</span></a>
      <div class="sep"></div>
      <a class="item" href="admin.php"><span class="icon">🛠️</span><span>Painel Admin</span></a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <h1 class="page-title">Painel Administrativo</h1>

    <div class="profile-card">
      <p class="muted">Logado como: <strong><?= e($self_name) ?></strong></p>

      <?php if (!empty($_SESSION['success'])): ?>
        <p style="color:#5f5; font-weight:700; text-align:center;"><?= e($_SESSION['success']); unset($_SESSION['success']); ?></p>
      <?php endif; ?>
      <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:#f66; font-weight:700; text-align:center;"><?= e($_SESSION['error']); unset($_SESSION['error']); ?></p>
      <?php endif; ?>

      <div style="overflow:auto;">
        <table class="users-table" role="table" aria-label="Lista de usuários">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>E-mail</th>
              <th>Tipo</th>
              <th style="width:160px;">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($users)): ?>
              <tr><td colspan="5" style="text-align:center; padding:18px;">Nenhum usuário cadastrado.</td></tr>
            <?php else: foreach ($users as $u): ?>
              <tr>
                <td><?= (int)$u['id'] ?></td>
                <td><?= e($u['nome']) ?></td>
                <td><?= e($u['email'] ?? '') ?></td>
                <td><?= e($u['tipo'] ?? '') ?></td>
                <td>
                  <?php if ($self_id === (int)$u['id']): ?>
                    <span class="muted">Conta própria</span>
                  <?php else: ?>
                    <form method="post" action="delete_user.php" style="display:inline" onsubmit="return confirm('Confirma exclusão do usuário <?= addslashes(e($u['nome'])) ?>? Esta ação é irreversível.');">
                      <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                      <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                      <button type="submit" class="delete-btn">🗑️ Excluir</button>
                    </form>
                    <a href="ver_perfil.php?user_id=<?= (int)$u['id'] ?>" style="margin-left:8px; text-decoration:none; padding:8px 10px; background:#333; border-radius:6px; color:#fff; font-weight:600;">🔍 Ver</a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>

      <div class="actions" style="margin-top:18px;">
        <a href="index.php">← Voltar</a>
        <a href="logout.php">🚪 Sair</a>
      </div>
    </div>
  </main>
</body>
</html>
