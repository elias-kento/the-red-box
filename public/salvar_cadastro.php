<?php
// salvar_cadastro.php (versão sem login automático)
session_start(); // usamos sessão só para mensagens temporárias

// conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "redbox";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// recebe e sanitiza
$nome  = trim($_POST['nome']  ?? '');
$email = trim($_POST['email'] ?? '');
$senha_plain = $_POST['senha'] ?? '';
$tipo  = strtoupper(trim($_POST['tipo'] ?? 'CLIENTE')); // espera HACKER ou CLIENTE

// validações básicas
if (!$nome || !$email || !$senha_plain) {
    $_SESSION['cad_msg'] = "Preencha nome, e-mail e senha.";
    header("Location: cadastro.php");
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['cad_msg'] = "E-mail inválido.";
    header("Location: cadastro.php");
    exit;
}
if (!in_array($tipo, ['HACKER','CLIENTE'])) {
    $tipo = 'CLIENTE';
}

// checar se já existe usuário com esse e-mail
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $_SESSION['cad_msg'] = "Já existe uma conta com este e-mail.";
    $stmt->close();
    header("Location: cadastro.php");
    exit;
}
$stmt->close();

// hash da senha
$senha_hash = password_hash($senha_plain, PASSWORD_DEFAULT);

// inserir usuário
$ins = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
$ins->bind_param("ssss", $nome, $email, $senha_hash, $tipo);
$ok = $ins->execute();

if (!$ok) {
    $_SESSION['cad_msg'] = "Erro ao criar conta. Por favor tente novamente.";
    $ins->close();
    header("Location: cadastro.php");
    exit;
}

$user_id = $conn->insert_id;
$ins->close();

// opcional: criar registro vazio em perfil_usuarios para facilitar edição posterior
$pi = $conn->prepare("INSERT IGNORE INTO perfil_usuarios (user_id, tipo) VALUES (?, ?)");
$pi->bind_param("is", $user_id, $tipo);
$pi->execute();
$pi->close();

// IMPORTANTE: NÃO criar/alterar $_SESSION['id'] ou outros — usuário NÃO fica logado.
// Em vez disso, levar o usuário a uma página de confirmação
header("Location: cadastro_sucesso.php");
exit;
