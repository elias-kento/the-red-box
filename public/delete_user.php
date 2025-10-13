<?php
// public/delete_user.php
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
if ($self_id === $user_id) { $_SESSION['error'] = 'Você não pode excluir sua própria conta enquanto logado.'; header('Location: admin.php'); exit; }

// tenta usar PDO se inc/db.php existir
if (file_exists(__DIR__ . '/../inc/db.php')) {
    require_once __DIR__ . '/../inc/db.php';
    $pdo = db();
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM perfil_usuarios WHERE user_id = :uid");
        $stmt->execute([':uid'=>$user_id]);
        $stmt2 = $pdo->prepare("DELETE FROM usuarios WHERE id = :uid");
        $stmt2->execute([':uid'=>$user_id]);
        $affected = $stmt2->rowCount();
        $pdo->commit();
        $_SESSION['success'] = $affected>0 ? 'Usuário excluído.' : 'Usuário não encontrado.';
    } catch (Throwable $e) {
        $pdo->rollBack();
        $_SESSION['error'] = 'Erro: '.$e->getMessage();
    }
    header('Location: admin.php'); exit;
}

// fallback mysqli
$host="localhost"; $user="root"; $pass=""; $db="redbox";
$conn = new mysqli($host,$user,$pass,$db);
if ($conn->connect_error) { die('Erro: '.$conn->connect_error); }
try {
    $conn->begin_transaction();
    $stmt = $conn->prepare("DELETE FROM perfil_usuarios WHERE user_id = ?");
    if ($stmt) { $stmt->bind_param("i",$user_id); $stmt->execute(); $stmt->close(); }
    $stmt2 = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
    if (!$stmt2) throw new Exception('Erro preparando delete');
    $stmt2->bind_param("i",$user_id); $stmt2->execute(); $affected = $stmt2->affected_rows; $stmt2->close();
    $conn->commit();
    $_SESSION['success'] = $affected>0 ? 'Usuário excluído.' : 'Usuário não encontrado.';
} catch (Throwable $e) {
    $conn->rollback();
    $_SESSION['error'] = 'Erro: '.$e->getMessage();
}
$conn->close();
header('Location: admin.php');
exit;
