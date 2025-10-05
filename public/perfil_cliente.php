<?php
session_start();
if (empty($_SESSION['id'])) {
    header('Location: entrar.php');
    exit;
}

// conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "redbox";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// busca dados do perfil (se existirem)
$user_id = intval($_SESSION['id']);
$stmt = $conn->prepare("SELECT * FROM perfil_usuarios WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$perfil = $result->fetch_assoc() ?: [];
$stmt->close();

function e($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>The Red Box — Perfil (Cliente)</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{
      --bg:#000; --panel:#0D0D0F; --panel2:#141418;
      --red:#E4002B; --white:#EDEDED; --muted:#9A9A9A; --teal:#56C2C2;
      --line:#D9D9D9;
    }
    *{box-sizing:border-box}
    html,body{height:100%}
    body{
      margin:0; background:var(--bg); color:var(--white);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      display:flex; min-height:100vh; overflow:auto;
    }

    .sidebar{
      width:230px; background:#0A0A0C; padding:22px 18px;
      display:flex; flex-direction:column; align-items:center; gap:26px;
      border-right:2px solid var(--line);
    }
    .logo{width:64px; height:64px; display:grid; place-items:center}
    .menu{width:100%; display:flex; flex-direction:column; gap:22px}
    .item{
      display:flex; align-items:center; gap:14px;
      color:var(--white); text-decoration:none;
      padding:6px 10px; border-radius:6px;
      transition: all 0.3s ease;
    }
    .item .icon{width:28px; height:28px; display:grid; place-items:center; color:var(--red)}
    .item span{font-weight:600}
    .item:hover{background-color:#1a1a1a; color:var(--red);}
    .item:hover .icon{color:var(--white);}
    .sep{width:80%; height:2px; background:var(--line); margin:6px auto; border-radius:2px; opacity:.75}

    .main{flex:1; display:flex; flex-direction:column; padding:18px 28px;}

    .page-title{
      font-size:32px; font-weight:700; color:var(--red);
      margin:0 0 20px 0; padding-bottom:10px; border-bottom:2px solid var(--line);
    }

    .form-card{
      background-color:var(--panel2);
      padding:32px;
      border-radius:12px;
      width:100%;
      max-width:800px;
      box-shadow:0 0 10px rgba(255,255,255,0.03);
      margin:24px auto;
    }
    .grid{display:grid; grid-template-columns:1fr 1fr; gap:12px;}
    .field{display:flex; flex-direction:column; gap:6px; margin-bottom:12px;}
    input, textarea{
      padding:10px; border-radius:6px; border:1px solid #333;
      background:#0C0C0F; color:var(--white);
    }
    .actions{display:flex; gap:12px; align-items:center; margin-top:8px;}
    button.primary{
      background:var(--red); color:#fff; padding:10px 14px; border:none;
      border-radius:6px; cursor:pointer; font-weight:700;
    }
    .server-msg{color:#f88; text-align:center; margin-bottom:12px;}

    @media (max-width:900px){
      .sidebar{width:84px; padding:18px 10px;}
      .item span{display:none;}
      .sep{width:60%;}
      .form-card{padding:20px; max-width:95%;}
      .grid{grid-template-columns:1fr;}
    }
  </style>
</head>
<body>
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

  <main class="main">
    <div class="form-card">
      <h1 class="page-title">Completar Perfil - Cliente</h1>

      <?php if (!empty($_SESSION['profile_msg'])): ?>
        <div class="server-msg"><?= e($_SESSION['profile_msg']); unset($_SESSION['profile_msg']); ?></div>
      <?php endif; ?>

      <form method="POST" action="save_profile.php" novalidate>
        <input type="hidden" name="tipo" value="CLIENTE">

        <div class="grid">
          <div class="field">
            <label for="cnpj">CNPJ</label>
            <input id="cnpj" name="cnpj" type="text" value="<?= e($perfil['cnpj'] ?? '') ?>">
          </div>

          <div class="field">
            <label for="empresa">Nome da Empresa</label>
            <input id="empresa" name="empresa" type="text" value="<?= e($perfil['empresa'] ?? '') ?>">
          </div>

          <div class="field">
            <label for="cidade">Cidade</label>
            <input id="cidade" name="cidade" type="text" value="<?= e($perfil['cidade'] ?? '') ?>">
          </div>

          <div class="field">
            <label for="telefone">Telefone</label>
            <input id="telefone" name="telefone" type="text" value="<?= e($perfil['telefone'] ?? '') ?>">
          </div>
        </div>

        <div class="field">
          <label for="site_portfolio">Site da Empresa</label>
          <input id="site_portfolio" name="site_portfolio" type="url" value="<?= e($perfil['site_portfolio'] ?? '') ?>">
        </div>

        <div class="field">
          <label for="bio">Descrição / Observações</label>
          <textarea id="bio" name="bio" rows="4"><?= e($perfil['bio'] ?? '') ?></textarea>
        </div>

        <div class="actions">
          <button type="submit" class="primary">Salvar Perfil</button>
          <a href="ver_perfil.php" style="
            color:var(--white);
            text-decoration:none;
            padding:8px 12px;
            border-radius:6px;
            border:1px solid #333;
          ">Cancelar</a>
        </div>
      </form>
    </div>
  </main>
</body>
</html>
