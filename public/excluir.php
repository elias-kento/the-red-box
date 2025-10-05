<?php
session_start();
if (!isset($_SESSION['id'])) { header("Location: index.php"); exit; }

$host = "localhost"; 
$usuario = "root"; 
$senha = ""; 
$banco = "redbox";

$conn = new mysqli($host, $usuario, $senha, $banco);

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
$stmt->bind_param("i", $_SESSION['id']);

if ($stmt->execute()) {
  session_destroy();

  // ✅ Mensagem estilizada igual ao cadastro com sucesso
  echo "<!DOCTYPE html>
  <html lang='pt-BR'>
  <head>
    <meta charset='UTF-8'>
    <title>Conta Excluída</title>
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
      .message {
        background: #141418;
        padding: 40px;
        border-radius: 12px;
        border: 1px solid #E4002B;
        text-align: center;
        max-width: 400px;
        box-shadow: 0 0 12px rgba(255, 255, 255, 0.05);
      }
      h1 {
        color: #E4002B;
        margin-bottom: 16px;
      }
      p { margin-bottom: 16px; }
      a {
        color: #56C2C2;
        text-decoration: none;
        font-weight: bold;
      }
    </style>
  </head>
  <body>
    <div class='message'>
      <h1>Conta excluída com sucesso!</h1>
      <p>Sua conta foi removida do sistema.</p>
      <p><a href='index.php'>← Voltar para a página inicial</a></p>
    </div>
  </body>
  </html>";
} else {
  echo "<p style='color:red;'>Erro: " . $conn->error . "</p>";
}
