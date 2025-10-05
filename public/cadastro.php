<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>The Red Box — Cadastro</title>
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

    /* SIDEBAR */
    .sidebar{
      width: 230px; background:#0A0A0C; padding:22px 18px;
      display:flex; flex-direction:column; align-items:center; gap:26px;
      border-right: 2px solid var(--line);
    }
    .logo{ width:64px; height:64px; display:grid; place-items:center }
    .logo svg{ width:100%; height:100% }

    .menu{ width:100%; display:flex; flex-direction:column; gap:22px }
    .item{ display:flex; align-items:center; gap:14px; color:var(--white); text-decoration:none; }
    .item .icon{ width:28px; height:28px; display:grid; place-items:center; color:var(--red) }
    .item span{ font-weight:600 }

    .item {
      display:flex;
      align-items:center;
      gap:14px;
      color:var(--white);
      text-decoration:none;
      padding:6px 10px;
      border-radius:6px;
      transition: all 0.3s ease;
    }

    .item:hover {
      background-color:#1a1a1a;  /* fundo escuro no hover */
      color:var(--red);          /* texto vermelho */
    }

    .item:hover .icon {
      color:var(--white);        /* ícone branco no hover */
    }

    .sep{ width:80%; height:2px; background:var(--line); margin:6px auto; border-radius:2px; opacity:.75 }

    .bottom-gear{ margin-top:auto; display:flex; align-items:center; gap:10px; color:var(--teal); opacity:.8 }

    /* MAIN */
    .main{
      flex:1; display:flex; flex-direction:column; padding:18px 28px;
    }

    .page-title{
      font-size:32px;
      font-weight:700;
      color:var(--red);
      margin:0 0 20px 0;
      padding-bottom:10px;
      border-bottom:2px solid var(--line);
      text-align:left;
    }

    .topbar{
      display:grid; grid-template-columns: 1fr auto auto;
      align-items:center; gap:24px; padding:8px 0 16px 0;
      border-bottom:1px solid #141414;
    }
    .search{
      display:flex; align-items:center; gap:10px; max-width:720px; width:100%;
      background:#0C0C0F; border:1px solid #2A2A2A; border-radius:999px; padding:10px 14px;
    }
    .search .magnify{ color:var(--red) }
    .search input{
      flex:1; border:none; outline:none; background:transparent; color:var(--white);
      font-size:16px;
    }
    .search .mic{ color:#6f6f6f }
    .filter{
      display:flex; align-items:center; gap:10px; color:var(--white); font-size:24px; font-weight:600;
    }
    .filter svg{ color:var(--red); width:28px; height:28px }
    .auth{
      margin-left:auto; display:flex; align-items:center; gap:10px; color:#b9b9b9; text-decoration:none;
    }
    .auth .avatar{ color:var(--red) }

    .content{
      flex:1; padding:24px 6px;
    }

    .login-topbar {
      margin-left: auto;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .login-topbar input {
      padding: 6px 8px;
      border: 1px solid #2A2A2A;
      border-radius: 4px;
      background: #0C0C0F;
      color: var(--white);
    }

    .login-topbar button {
      padding: 6px 12px;
      background: var(--red);
      color: var(--white);
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    /* Form visual */
    .form-card {
      background-color: var(--panel2);
      padding: 32px;
      border-radius: 12px;
      width: 100%;
      max-width: 480px;
      box-shadow: 0 0 10px rgba(255,255,255,0.03);
    }
    .field { display:flex; flex-direction:column; gap:6px; margin-bottom:12px; }
    .field input, .field select {
      padding:10px; border-radius:6px; border:1px solid #333; background:#0C0C0F; color:var(--white);
    }
    .server-msg { color: #f88; text-align:center; margin-bottom:12px; }

    /* responsivo leve */
    @media (max-width: 900px){
      .sidebar{ width: 84px; padding:18px 10px }
      .item span{ display:none }
      .sep{ width:60% }
      .site-title{ display:none }
      .form-card { padding: 20px; }
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
      <a class="item" href="index.php">
        <span class="icon">🏠</span><span>Início</span>
      </a>
      <div class="sep"></div>

      <a class="item" href="sobre.php">
        <span class="icon">🧠</span><span>Quem Somos</span>
      </a>
      <div class="sep"></div>

      <a class="item" href="cadastro.php">
        <span class="icon">👤</span><span>Cadastrar</span>
      </a>
      <div class="sep"></div>

      <a class="item" href="entrar.php">
        <span class="icon">🔑</span><span>Login</span>
      </a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <div class="topbar"></div>

    <section class="content" style="display:flex; justify-content:center; align-items:center; padding:40px 20px; min-height:calc(100vh - 100px);">
      <div class="form-card" role="region" aria-label="Formulário de cadastro">
        <h1 class="page-title">Criar nova conta</h1>

        <?php if (!empty($_SESSION['cad_msg'])): ?>
          <div class="server-msg"><?= htmlspecialchars($_SESSION['cad_msg'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['cad_msg']); ?></div>
        <?php endif; ?>

        <form method="POST" action="salvar_cadastro.php" novalidate>
          <div class="field">
            <label for="nome" style="font-size:14px; color:var(--muted);">Nome completo</label>
            <input type="text" id="nome" name="nome" placeholder="Digite seu nome completo" required autocomplete="name">
          </div>

          <div class="field">
            <label for="email" style="font-size:14px; color:var(--muted);">E-mail</label>
            <input type="email" id="email" name="email" placeholder="exemplo@email.com" required autocomplete="email">
          </div>

          <div class="field">
            <label for="senha" style="font-size:14px; color:var(--muted);">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="********" required autocomplete="new-password">
          </div>

          <div class="field">
            <label for="tipo" style="font-size:14px; color:var(--muted);">Tipo de usuário</label>
            <select id="tipo" name="tipo" required>
              <option value="">Selecione...</option>
              <option value="CLIENTE">Cliente</option>
              <option value="HACKER">Hacker</option>
            </select>
          </div>

          <button type="submit" style="background-color:var(--red); border:none; padding:12px; border-radius:6px; color:var(--white); font-weight:bold; font-size:16px; cursor:pointer;">
            Cadastrar
          </button>
        </form>
      </div>
    </section>
  </main>
</body>
</html>
