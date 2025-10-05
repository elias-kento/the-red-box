<?php
require_once __DIR__ . '/../inc/render.php';
require_once __DIR__ . '/../inc/db.php';
session_start();

// Se não estiver logado, manda para o login (caminho relativo à pasta public)
if (empty($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

// Função simples para escapar HTML
function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

try {
  $pdo = db();
  $stmt = $pdo->prepare("
    SELECT id, nome_completo, email, login, tipo_usuario, status,
           endereco, data_nascimento, profissao, rg, cpf,
           created_at, updated_at
    FROM users
    WHERE id = :id
    LIMIT 1
  ");
  $stmt->execute([':id' => $_SESSION['user_id']]);
  $user = $stmt->fetch();

  // Se o usuário não existir mais (apagado), finaliza sessão
  if (!$user) {
    header('Location: logout.php');
    exit;
  }
} catch (Throwable $e) {
  http_response_code(500);
  echo "Erro ao carregar usuário: " . e($e->getMessage());
  exit;
}

rb_header('Minha conta');
?>
<main class="container">
  <h1>Olá, <?= e($_SESSION['user_name'] ?? $user['nome_completo']) ?>!</h1>
  <p><strong>Tipo de usuário:</strong> <?= e($user['tipo_usuario']) ?> • <strong>Status:</strong> <?= e($user['status']) ?></p>

  <section class="card">
    <h2>Dados da conta</h2>
    <ul>
      <li><strong>Login:</strong> <?= e($user['login']) ?></li>
      <li><strong>E-mail:</strong> <?= e($user['email']) ?></li>
      <li><strong>Criado em:</strong> <?= e($user['created_at']) ?></li>
      <li><strong>Atualizado em:</strong> <?= e($user['updated_at']) ?></li>
    </ul>
  </section>

  <section class="card">
    <h2>Dados pessoais</h2>
    <ul>
      <li><strong>Nome completo:</strong> <?= e($user['nome_completo']) ?></li>
      <li><strong>Endereço:</strong> <?= e($user['endereco'] ?? '-') ?></li>
      <li><strong>Data de nascimento:</strong> <?= e($user['data_nascimento'] ?? '-') ?></li>
      <li><strong>Profissão:</strong> <?= e($user['profissao'] ?? '-') ?></li>
      <li><strong>RG:</strong> <?= e($user['rg'] ?? '-') ?></li>
      <li><strong>CPF:</strong> <?= e($user['cpf'] ?? '-') ?></li>
    </ul>
  </section>

<?php
  $isHacker = (strcasecmp(trim($user['tipo_usuario'] ?? ''), 'HACKER') === 0);
?>
<p>
  <?php if ($isHacker): ?>
    <a href="perfil_hacker.php">Completar/editar perfil de Hacker</a> •
  <?php else: ?>
    <a href="perfil_cliente.php">Completar/editar perfil de Cliente</a> •
  <?php endif; ?>
  <a href="logout.php">Sair</a>
</p>



</main>
<?php rb_footer(); ?>
