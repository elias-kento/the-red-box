<?php
require_once __DIR__ . '/../inc/render.php';
require_once __DIR__ . '/../inc/db.php';
session_start();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login'] ?? '');
  $senha = $_POST['senha'] ?? '';

  if ($login === '' || $senha === '') {
    $errors[] = 'Informe login e senha.';
  } else {
    try {
      $pdo = db();
      $stmt = $pdo->prepare('SELECT id, nome_completo, tipo_usuario, login, senha_hash FROM users WHERE login = :login LIMIT 1');
      $stmt->execute([':login' => $login]);
      $user = $stmt->fetch();

      if ($user && password_verify($senha, $user['senha_hash'])) {
        $_SESSION['user_id']   = (int)$user['id'];
        $_SESSION['user_name'] = $user['nome_completo'];
        $_SESSION['user_tipo'] = $user['tipo_usuario']; // HACKER ou CLIENTE
        header('Location: usuario.php');
        exit;
      } else {
        $errors[] = 'Credenciais inválidas.';
      }
    } catch (Throwable $e) {
      $errors[] = 'Erro: ' . $e->getMessage();
    }
  }
}

rb_header('Login');
?>
<main class="container">
  <h1>Login</h1>

  <?php if ($errors): ?>
    <div class="alert error">
      <ul><?php foreach ($errors as $e) echo "<li>".htmlspecialchars($e)."</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" class="form-card">
    <label>Login
      <input type="text" name="login" required value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
    </label>
    <label>Senha
      <input type="password" name="senha" required>
    </label>
    <button type="submit">Entrar</button>
    <p>Não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
  </form>
</main>
<?php rb_footer(); ?>
