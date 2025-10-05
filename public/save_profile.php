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

// Dados do usuário logado
$user_id = intval($_SESSION['id']);
$tipo = strtoupper(trim($_POST['tipo'] ?? 'CLIENTE'));

// Recebe os campos (tratamento básico)
$cpf = trim($_POST['cpf'] ?? '');
$cnpj = trim($_POST['cnpj'] ?? '');
$cidade = trim($_POST['cidade'] ?? '');
$telefone = trim($_POST['telefone'] ?? '');
$habilidades = trim($_POST['habilidades'] ?? '');
$site_portfolio = trim($_POST['site_portfolio'] ?? '');
$bio = trim($_POST['bio'] ?? '');
$empresa = trim($_POST['empresa'] ?? '');

// Verifica se já existe perfil
$stmt = $conn->prepare("SELECT id FROM perfil_usuarios WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$existe = $result->num_rows > 0;
$stmt->close();

// Se existir, atualiza; senão, cria
if ($existe) {
  $sql = "UPDATE perfil_usuarios 
          SET tipo=?, cpf=?, cnpj=?, cidade=?, telefone=?, habilidades=?, site_portfolio=?, bio=?, empresa=?, updated_at=NOW() 
          WHERE user_id=?";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssssssssi", $tipo, $cpf, $cnpj, $cidade, $telefone, $habilidades, $site_portfolio, $bio, $empresa, $user_id);
  $ok = $stmt->execute();
  $stmt->close();
} else {
  $sql = "INSERT INTO perfil_usuarios 
          (user_id, tipo, cpf, cnpj, cidade, telefone, habilidades, site_portfolio, bio, empresa, created_at) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("isssssssss", $user_id, $tipo, $cpf, $cnpj, $cidade, $telefone, $habilidades, $site_portfolio, $bio, $empresa);
  $ok = $stmt->execute();
  $stmt->close();
}

if ($ok) {
  $_SESSION['profile_msg'] = "✅ Perfil salvo com sucesso!";
} else {
  $_SESSION['profile_msg'] = "⚠️ Erro ao salvar perfil. Tente novamente.";
}

// Redireciona para a página de visualização de perfil
header("Location: ver_perfil.php");
exit;
