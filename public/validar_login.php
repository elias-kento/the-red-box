<?php
session_start();

// CONEXÃO COM O BANCO
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "redbox";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
  die("Erro na conexão: " . $conn->connect_error);
}

// RECEBE DADOS DO FORM
$email = $_POST['email'] ?? '';
$senha_digitada = $_POST['senha'] ?? '';
$origem = $_POST['origem'] ?? 'index.php';

if ($email && $senha_digitada) {
  $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if (password_verify($senha_digitada, $usuario['senha'])) {
      // cria sessão
      $_SESSION['id']    = $usuario['id'];
      $_SESSION['nome']  = $usuario['nome'];
      $_SESSION['email'] = $email;
      $_SESSION['tipo']  = strtoupper($usuario['tipo']);

      // Verifica se perfil já foi preenchido
      $user_id = intval($usuario['id']);
      $tipo = $_SESSION['tipo'];

      $sql = "SELECT cidade, telefone, bio FROM perfil_usuarios WHERE user_id = ?";
      $check = $conn->prepare($sql);
      $check->bind_param("i", $user_id);
      $check->execute();
      $res = $check->get_result();
      $perfil = $res->fetch_assoc() ?: [];

      $check->close();
      $conn->close();

      // Se não houver perfil completo → redireciona para completar
      if (empty($perfil['cidade']) && empty($perfil['telefone']) && empty($perfil['bio'])) {
        if ($tipo === 'HACKER') {
          header("Location: perfil_hacker.php");
        } else {
          header("Location: perfil_cliente.php");
        }
      } else {
        // Senão, vai para a visualização
        header("Location: ver_perfil.php");
      }
      exit;
    } else {
      $_SESSION['erro_login'] = "Senha incorreta.";
    }
  } else {
    $_SESSION['erro_login'] = "Usuário não encontrado.";
  }
} else {
  $_SESSION['erro_login'] = "Preencha todos os campos.";
}

// Retorna para a página de origem (index, sobre, entrar, etc.)
header("Location: $origem");
exit;
