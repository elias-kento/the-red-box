<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>The Red Box — Cadastro Concluído</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{
      --bg:#000; --panel:#0D0D0F; --red:#E4002B; --white:#EDEDED; --line:#D9D9D9;
    }
    body{
      margin:0; background:var(--bg); color:var(--white);
      font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;
      display:flex; flex-direction:column; align-items:center; justify-content:center;
      min-height:100vh;
    }
    .card{
      background:var(--panel); padding:40px; border-radius:12px;
      border:1px solid var(--line); text-align:center; max-width:500px;
      box-shadow:0 0 10px rgba(255,255,255,0.05);
    }
    h1{ color:var(--red); margin-bottom:20px; }
    p{ color:var(--white); margin-bottom:30px; }
    .btns{ display:flex; justify-content:center; gap:16px; }
    a{
      background:var(--red); color:#fff; padding:10px 18px;
      border-radius:6px; text-decoration:none; font-weight:bold;
      transition:0.2s ease;
    }
    a:hover{ background:#b30021; }
  </style>
</head>
<body>
  <div class="card">
    <h1>Cadastro concluído!</h1>
    <p>Agora você pode entrar na sua conta ou voltar para a página inicial.</p>
    <div class="btns">
      <a href="entrar.php">Fazer login</a>
      <a href="index.php" style="background:#444;">Voltar ao início</a>
    </div>
  </div>
</body>
</html>
