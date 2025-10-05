<?php
// entrar.php — página de login
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <title>Entrar — The Red Box</title>
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
    .item{
      display:flex; align-items:center; gap:14px;
      color:var(--white); text-decoration:none;
      padding:6px 10px; border-radius:6px;
      transition: all 0.3s ease;
    }
    .item .icon{ width:28px; height:28px; display:grid; place-items:center; color:var(--red) }
    .item span{ font-weight:600 }

    .item:hover {
      background-color:#1a1a1a;
      color:var(--red);
    }
    .item:hover .icon { color:var(--white); }

    .sep{ width:80%; height:2px; background:var(--line); margin:6px auto; border-radius:2px; opacity:.75 }

    /* MAIN */
    .main{ flex:1; display:flex; flex-direction:column; padding:18px 28px; }

    .page-title{
      font-size:32px;
      font-weight:700;
      color:var(--red);
      margin:0 0 20px 0;
      padding-bottom:10px;
      border-bottom:2px solid var(--line);
      text-align:left;
    }

    /* FORM */
    .content{
      display:flex;
      justify-content:center;
      align-items:center;
      padding:40px 20px;
      min-height:calc(100vh - 100px);
    }
    form.login-form{
      background-color:var(--panel2);
      padding:32px;
      border-radius:12px;
      width:100%;
      max-width:480px;
      box-shadow:0 0 10px rgba(255,255,255,0.05);
      display:flex;
      flex-direction:column;
      gap:20px;
    }
    form.login-form input{
      padding:8px;
      border:1px solid #444;
      border-radius:4px;
      width:100%;
      background:#0C0C0F;
      color:#fff;
    }
    form.login-form button{
      padding:10px;
      background:#E4002B;
      color:#fff;
      border:none;
      border-radius:4px;
      cursor:pointer;
    }

    /* Responsivo leve */
    @media (max-width:900px){
      .sidebar{ width:84px; padding:18px 10px }
      .item span{ display:none }
      .sep{ width:60% }
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
    <section class="content">
      <form method="POST" action="validar_login.php" class="login-form">
        <h1 class="page-title">Entrar</h1>

        <label>
          E-mail:
          <input type="email" name="email" placeholder="E-mail" required autocomplete="email">
        </label>

        <label>
          Senha:
          <input type="password" name="senha" placeholder="Senha" required autocomplete="current-password">
        </label>

        <input type="hidden" name="origem" value="<?php echo basename($_SERVER['PHP_SELF']); ?>">

        <button type="submit">Entrar</button>

        <?php if (isset($_SESSION['erro_login'])): ?>
          <p style="color: #f88; font-size: 14px; text-align:center;">
            <?= $_SESSION['erro_login']; unset($_SESSION['erro_login']); ?>
          </p>
        <?php endif; ?>
      </form>
    </section>
  </main>
</body>
</html>
