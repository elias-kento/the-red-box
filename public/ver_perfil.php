<?php
session_start();
if (empty($_SESSION['id'])) {
  header('Location: entrar.php');
  exit;
}

// Conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "redbox";
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

$user_id = intval($_SESSION['id']);
$stmt = $conn->prepare("SELECT u.nome, u.email, u.tipo AS tipo_usuario, p.* 
                        FROM usuarios u 
                        LEFT JOIN perfil_usuarios p ON u.id = p.user_id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$perfil = $result->fetch_assoc() ?: [];
$stmt->close();

function e($v){ return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>The Red Box — Meu Perfil</title>
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
      margin-bottom: 24px; border-bottom: 2px solid var(--line); width: 100%; max-width: 800px;
      padding-bottom: 10px;
    }

    .profile-card {
      background-color: var(--panel2);
      padding: 32px; border-radius: 12px;
      width: 100%; max-width: 800px;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.05);
    }

    .profile-grid {
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 10px 20px;
      margin-top: 10px;
    }

    .label {
      font-weight: 600;
      color: var(--teal);
      text-align: right;
      padding-right: 8px;
    }

    .value {
      color: var(--white);
      border-bottom: 1px solid var(--line);
      padding-bottom: 6px;
      word-break: break-word;
    }

    .server-msg {
      text-align: center; color: #5f5;
      margin-bottom: 16px; font-weight: bold;
    }

    .actions {
      display: flex; justify-content: center; gap: 14px;
      margin-top: 24px; flex-wrap: wrap;
    }

    .actions {
  display: flex;
  justify-content: center;
  gap: 14px;
  margin-top: 24px;
  flex-wrap: wrap;
}

.actions {
  display: flex;
  justify-content: center;
  gap: 14px;
  margin-top: 24px;
  flex-wrap: wrap;
}

.actions a,
.actions button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: var(--red);
  color: #fff;
  border: none;
  border-radius: 6px;
  width: 140px;              /* largura fixa para todos */
  height: 44px;              /* altura fixa */
  text-decoration: none;
  cursor: pointer;
  font-weight: 600;
  font-size: 15px;
  line-height: 1;
  transition: background 0.2s;
  text-align: center;
}

.actions a:hover,
.actions button:hover {
  background: #ff324f;
}

.delete-btn {
  background: #700;
}

.delete-btn:hover {
  background: #a00;
}

form.inline {
  display: inline;
}


    @media (max-width: 700px) {
      .profile-grid { grid-template-columns: 1fr; }
      .label { text-align: left; padding: 0; }
    }
  </style>
</head>
<body>
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">
      <svg viewBox="0 0 24 24" fill="none">
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
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <h1 class="page-title">Meu Perfil</h1>

    <?php if (!empty($_SESSION['profile_msg'])): ?>
      <p class="server-msg"><?= e($_SESSION['profile_msg']); unset($_SESSION['profile_msg']); ?></p>
    <?php endif; ?>

    <div class="profile-card">
      <div class="profile-grid">
        <div class="label">Nome:</div> <div class="value"><?= e($perfil['nome']) ?></div>
        <div class="label">E-mail:</div> <div class="value"><?= e($perfil['email']) ?></div>
        <div class="label">Tipo:</div> <div class="value"><?= e($perfil['tipo_usuario']) ?></div>
        <div class="label">CPF:</div> <div class="value"><?= e($perfil['cpf']) ?></div>
        <div class="label">CNPJ:</div> <div class="value"><?= e($perfil['cnpj']) ?></div>
        <div class="label">Empresa:</div> <div class="value"><?= e($perfil['empresa']) ?></div>
        <div class="label">Cidade:</div> <div class="value"><?= e($perfil['cidade']) ?></div>
        <div class="label">Telefone:</div> <div class="value"><?= e($perfil['telefone']) ?></div>
        <div class="label">Habilidades:</div> <div class="value"><?= e($perfil['habilidades']) ?></div>

        <div class="label">Site/Portfólio:</div>
        <div class="value">
          <?php if (!empty(trim($perfil['site_portfolio'] ?? ''))): ?>
            <a href="<?= e($perfil['site_portfolio']); ?>" target="_blank" style="color:var(--teal); text-decoration:none;">
              <?= e($perfil['site_portfolio']); ?>
            </a>
          <?php endif; ?>
        </div>

        <div class="label">Bio:</div>
        <div class="value"><?= nl2br(e($perfil['bio'])) ?></div>
      </div>

      <div class="actions">
        <?php if (($perfil['tipo_usuario'] ?? '') === 'HACKER'): ?>
          <a href="perfil_hacker.php">✏️ Editar</a>
        <?php else: ?>
          <a href="perfil_cliente.php">✏️ Editar</a>
        <?php endif; ?>

        <form action="excluir_perfil.php" method="POST" class="inline" onsubmit="return confirm('Deseja realmente excluir seu perfil e conta?')">
          <button type="submit" class="delete-btn">🗑️ Excluir</button>
        </form>

        <a href="logout.php">🚪 Sair</a>
      </div>
    </div>
  </main>
</body>
</html>
