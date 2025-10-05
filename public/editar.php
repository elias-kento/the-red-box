<?php
session_start();
if (!isset($_SESSION['id'])) { header("Location: index.php"); exit; }

$host = "localhost"; $usuario = "root"; $senha = ""; $banco = "redbox";
$conn = new mysqli($host, $usuario, $senha, $banco);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = $_POST['nome'];
  $tipo = $_POST['tipo'];

  $stmt = $conn->prepare("UPDATE usuarios SET nome=?, tipo=? WHERE id=?");
  $stmt->bind_param("ssi", $nome, $tipo, $_SESSION['id']);
  if ($stmt->execute()) {
    $_SESSION['nome'] = $nome;
    $_SESSION['tipo'] = $tipo;
    header("Location: perfil.php");
    exit;
  } else {
    echo "Erro: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="UTF-8"><title>Editar Perfil</title></head>
<body style="background:#000; color:#fff; font-family:sans-serif;">
  <h2>Editar Perfil</h2>
  <form method="post">
    <label>Nome:</label><br>
    <input type="text" name="nome" value="<?= $_SESSION['nome']; ?>" required><br><br>

    <label>Tipo:</label><br>
    <select name="tipo" required>
      <option value="cliente" <?= $_SESSION['tipo']=='cliente'?'selected':''; ?>>Cliente</option>
      <option value="hacker" <?= $_SESSION['tipo']=='hacker'?'selected':''; ?>>Hacker</option>
    </select><br><br>

    <button type="submit">Salvar Alterações</button>
  </form>
</body>
</html>
