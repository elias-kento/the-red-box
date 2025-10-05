<?php
// inc/render.php - layout helpers
function rb_header($title='The Red Box'){
  if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }

  echo "<!DOCTYPE html>
<html lang='pt-BR'>
<head>
  <meta charset='UTF-8'>
  <title>{$title}</title>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <link rel='stylesheet' href='assets/styles.css'>
</head>
<body>
  <!-- SIDEBAR -->
  <aside class='sidebar'>
    <div class='logo'>
      <svg viewBox='0 0 24 24' fill='none'>
        <rect x='1.5' y='3' width='21' height='18' rx='2' stroke='#E4002B' stroke-width='1.7'/>
        <path d='M2 8H22' stroke='#E4002B' stroke-width='1.7'/>
        <path d='M8 3V8' stroke='#E4002B' stroke-width='1.7'/>
      </svg>
    </div>
    <nav class='menu'>
      <a class='item' href='index.php'><span class='icon'>🏠</span><span>Início</span></a>
      <div class='sep'></div>
      <a class='item' href='sobre.php'><span class='icon'>🧠</span><span>Quem Somos</span></a>
      <div class='sep'></div>
      <a class='item' href='cadastro.php'><span class='icon'>👤</span><span>Cadastrar</span></a>
      <div class='sep'></div>
      <a class='item' href='login.php'><span class='icon'>🔑</span><span>Login</span></a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class='main'>
    <div class='topbar'>";
    
  // se logado → mostra nome, perfil e sair
  if (!empty($_SESSION['user_id'])) {
    $nome = htmlspecialchars($_SESSION['user_name'] ?? 'usuário', ENT_QUOTES, 'UTF-8');
    $tipo = strtoupper(trim($_SESSION['user_tipo'] ?? ''));
    $perfilLink = ($tipo === 'HACKER') ? 'perfil_hacker.php' : 'perfil_cliente.php';
    $perfilTxt  = ($tipo === 'HACKER') ? 'Meu Perfil (Hacker)' : 'Meu Perfil (Cliente)';

    echo "<div class='auth'>
            <span class='user'>Olá, {$nome}</span>
            • <a class='profile' href='{$perfilLink}'>{$perfilTxt}</a>
            • <a class='logout' href='logout.php'>Sair</a>
          </div>";
  } else {
    // se não logado → formulário de login rápido
    echo "<form method='post' action='login.php' class='login-topbar'>
            <input type='text' name='login' placeholder='Usuário' required>
            <input type='password' name='senha' placeholder='Senha' required>
            <button type='submit'>Entrar</button>
          </form>";
  }

  echo "</div>
    <section class='content'>
";
}

function rb_footer(){
  echo "    </section>
  </main>
</body>
</html>";
}
