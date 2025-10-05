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

$user_id = intval($_SESSION['id']);

// primeiro, excluir o perfil complementar, se existir
$stmt = $conn->prepare("DELETE FROM perfil_usuarios WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// depois, excluir o usuário principal
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->close();

// encerra a sessão
session_unset();
session_destroy();

// redireciona para página de confirmação
header("Location: perfil_excluido.php");
exit;
?>
