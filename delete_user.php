<?php
// delete_user.php
session_start();
if (empty($_SESSION['id']) || strtoupper(($_SESSION['tipo'] ?? '')) !== 'ADMIN') {
    header('Location: entrar.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin.php'); exit; }

$posted_csrf = $_POST['csrf'] ?? '';
if (empty($_SESSION['admin_csrf']) || !hash_equals($_SESSION['admin_csrf'], $posted_csrf)) {
    die('Token CSRF inválido.');
}

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
if ($user_id <= 0) { $_SESSION['error'] = 'ID inválido.'; header('Location: admin.php'); exit; }

$self_id = (int)($_SESSION['id'] ?? 0);
if ($self_id === $user_id) {
    $_SESSION['error'] = 'Você não pode excluir sua própria conta enquanto logado.'; header('Location: admin.php'); exit;
}

// conexão
$host = "localhost"; $usuario = "root"; $senha = ""; $banco = "redbox";
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) die("Erro na conexão: " . $conn->connect_error);

try {
    $conn->begin_transaction();

    // remove perfil complementar (se houver)
    $stmt = $conn->prepare("DELETE FROM perfil_usuarios WHERE user_id = ?");
    if ($stmt) { $stmt->bind_param("i", $user_id); $stmt->execute(); $stmt->close(); }

    // remove usuário
    $stmt2 = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    if (!$stmt2) throw new Exception('Erro preparando exclusão.');
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $affected = $stmt2->affected_rows;
    $stmt2->close();

    $conn->commit();

    if ($affected > 0) { $_SESSION['success'] = 'Usuário excluído.'; }
    else { $_SESSION['error'] = 'Usuário não encontrado.'; }
} catch (Throwable $e) {
    $conn->rollback();
    $_SESSION['error'] = 'Erro ao excluir usuário: ' . $e->getMessage();
}

header('Location: admin.php');
exit;
