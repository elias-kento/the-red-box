<?php
session_start();
?>

<?php /* index.php - The Red Box (mock da home) */ ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>The Red Box</title>
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
      display:flex; min-height:100vh; overflow:hidden;
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
      flex:1; padding:24px 6px; /* vazio por enquanto (aqui entrarão os cards) */
    }

    .login-topbar {
      margin-left: auto;          /* joga para a direita */
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

    .hero {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 20px;
    }

    .hero svg {
      width: 220px;
      height: 220px;
    }

    .hero h1 {
      font-size: 64px;
      color: var(--red);
      margin: 0;
      font-weight: 800;
      letter-spacing: 2px;
    }


    /* responsivo leve */
    @media (max-width: 900px){
      .sidebar{ width: 84px; padding:18px 10px }
      .item span{ display:none }
      .sep{ width:60% }
      .site-title{ display:none }
    }
  </style>
</head>
<body>
  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">
      <!-- ícone da caixa -->
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
    <div class="topbar">
      <?php if (isset($_SESSION['nome'])): ?>
        <div class="auth">
          Olá, <strong><?= $_SESSION['nome']; ?></strong> (<?= $_SESSION['tipo']; ?>) |
          <form action="logout.php" method="post" class="login-topbar" style="display:inline;">
            <button type="submit">Sair</button>
          </form>
        </div>

      <?php else: ?>
        <form method="post" action="validar_login.php" class="login-topbar">
          <input type="email" name="email" placeholder="E-mail" required>
          <input type="password" name="senha" placeholder="Senha" required>
          <button type="submit">Entrar</button>
        </form>

        <?php if (isset($_SESSION['erro_login'])): ?>
          <p style="color: #f88; font-size: 14px; margin-top: 6px;">
            <?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?>
          </p>
        <?php endif; ?>
      <?php endif; ?>
    </div>


    <section class="content">
       <div class="hero">
         <svg viewBox="0 0 24 24" fill="none">
           <rect x="1.5" y="3" width="21" height="18" rx="2"
                 stroke="#E4002B" stroke-width="2.5"/>
           <path d="M2 8H22" stroke="#E4002B" stroke-width="2.5"/>
           <path d="M8 3V8" stroke="#E4002B" stroke-width="2.5"/>
         </svg>
         <h1>The Red Box</h1>
        </div>
    </section>

  </main>
</body>
</html>

