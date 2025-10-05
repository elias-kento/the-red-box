<?php
require_once __DIR__ . '/../inc/render.php';
require_once __DIR__ . '/../inc/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }

// exige login
if (empty($_SESSION['user_id'])) { header('Location: login.php'); exit; }

// garante que é CLIENTE
$pdo = db();
$stmt = $pdo->prepare("SELECT id, tipo_usuario, nome_completo FROM users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) { header('Location: logout.php'); exit; }

$tipo = strtoupper(trim($user['tipo_usuario'] ?? ''));
if ($tipo !== 'CLIENTE') {
  http_response_code(403);
  function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
  die('Apenas usuários CLIENTE podem acessar esta página. Tipo atual: ' . e($tipo));
}

function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$errors = [];
$ok = false;

// carrega perfil existente (se houver)
$stmt = $pdo->prepare("SELECT * FROM client_profiles WHERE user_id = :id");
$stmt->execute([':id' => $user['id']]);
$profile = $stmt->fetch() ?: [
  'tipo_cliente' => 'PF',
  'razao_social' => '',
  'nome_fantasia' => '',
  'cnpj' => '',
  'site' => '',
  'contato_nome' => '',
  'contato_telefone' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $tipo_cliente = strtoupper(trim($_POST['tipo_cliente'] ?? 'PF'));
  $razao_social = trim($_POST['razao_social'] ?? '');
  $nome_fantasia = trim($_POST['nome_fantasia'] ?? '');
  $cnpj = trim($_POST['cnpj'] ?? '');
  $site = trim($_POST['site'] ?? '');
  $contato_nome = trim($_POST['contato_nome'] ?? '');
  $contato_telefone = trim($_POST['contato_telefone'] ?? '');

  // validações básicas
  if (!in_array($tipo_cliente, ['PF','PJ'], true)) $errors[] = 'Tipo de cliente inválido.';
  if ($tipo_cliente === 'PJ') {
    if ($razao_social === '' || mb_strlen($razao_social) > 120) $errors[] = 'Razão social é obrigatória (máx. 120).';
    // aceita CNPJ com máscara ou só dígitos; valida apenas tamanho mínimo
    $cnpj_digits = preg_replace('/\D+/', '', $cnpj);
    if (strlen($cnpj_digits) < 14) $errors[] = 'CNPJ deve ter 14 dígitos.';
  }
  if ($site !== '' && !filter_var($site, FILTER_VALIDATE_URL)) $errors[] = 'URL do site inválida.';
  if ($contato_nome !== '' && mb_strlen($contato_nome) > 80) $errors[] = 'Nome do contato muito longo (máx. 80).';
  if ($contato_telefone !== '' && mb_strlen($contato_telefone) > 20) $errors[] = 'Telefone muito longo (máx. 20).';

  if (!$errors) {
    try {
      // normaliza CNPJ armazenando como foi digitado (ou só dígitos, se preferir)
      $cnpj_to_save = $cnpj === '' ? null : $cnpj;

      $sql = "INSERT INTO client_profiles
                (user_id, tipo_cliente, razao_social, nome_fantasia, cnpj, site, contato_nome, contato_telefone)
              VALUES
                (:uid, :tipo, :razao, :fantasia, :cnpj, :site, :contato_nome, :contato_tel)
              ON DUPLICATE KEY UPDATE
                tipo_cliente = VALUES(tipo_cliente),
                razao_social = VALUES(razao_social),
                nome_fantasia = VALUES(nome_fantasia),
                cnpj = VALUES(cnpj),
                site = VALUES(site),
                contato_nome = VALUES(contato_nome),
                contato_telefone = VALUES(contato_telefone)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':uid' => $user['id'],
        ':tipo' => $tipo_cliente,
        ':razao' => ($tipo_cliente === 'PJ') ? $razao_social : null,
        ':fantasia' => ($tipo_cliente === 'PJ') ? ($nome_fantasia ?: null) : null,
        ':cnpj' => ($tipo_cliente === 'PJ') ? $cnpj_to_save : null,
        ':site' => $site ?: null,
        ':contato_nome' => $contato_nome ?: null,
        ':contato_tel' => $contato_telefone ?: null,
      ]);
      $ok = true;

      // recarrega perfil salvo
      $stmt = $pdo->prepare("SELECT * FROM client_profiles WHERE user_id = :id");
      $stmt->execute([':id' => $user['id']]);
      $profile = $stmt->fetch();
    } catch (PDOException $e) {
      $errors[] = 'Erro no banco: ' . $e->getMessage();
    }
  }
}

rb_header('Perfil do Cliente');
?>
<main class="container">
  <h1>Perfil do Cliente</h1>
  <p><strong>Usuário:</strong> <?= e($user['nome_completo']) ?></p>

  <?php if ($ok): ?>
    <div class="alert success">Perfil salvo com sucesso.</div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="alert error">
      <ul><?php foreach ($errors as $e) echo "<li>".e($e)."</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" class="form-card" id="formCliente">
    <div class="grid-2">
      <label>Tipo de cliente
        <select name="tipo_cliente" id="tipo_cliente">
          <?php
            $sel = $_POST['tipo_cliente'] ?? ($profile['tipo_cliente'] ?? 'PF');
            $opts = ['PF' => 'Pessoa Física', 'PJ' => 'Pessoa Jurídica (Empresa)'];
            foreach ($opts as $k => $v) {
              $s = ($sel === $k) ? 'selected' : '';
              echo "<option value='".e($k)."' $s>".e($v)."</option>";
            }
          ?>
        </select>
      </label>
      <label>Site
        <input type="url" name="site" value="<?= e($_POST['site'] ?? ($profile['site'] ?? '')) ?>">
      </label>
    </div>

    <fieldset class="pj-only">
      <legend>Dados da empresa (PJ)</legend>
      <div class="grid-2">
        <label>Razão social
          <input type="text" name="razao_social" maxlength="120" value="<?= e($_POST['razao_social'] ?? ($profile['razao_social'] ?? '')) ?>">
        </label>
        <label>Nome fantasia
          <input type="text" name="nome_fantasia" maxlength="120" value="<?= e($_POST['nome_fantasia'] ?? ($profile['nome_fantasia'] ?? '')) ?>">
        </label>
      </div>
      <label>CNPJ
        <input type="text" name="cnpj" maxlength="18" placeholder="00.000.000/0000-00"
               value="<?= e($_POST['cnpj'] ?? ($profile['cnpj'] ?? '')) ?>">
      </label>
    </fieldset>

    <fieldset>
      <legend>Contato</legend>
      <div class="grid-2">
        <label>Nome do contato
          <input type="text" name="contato_nome" maxlength="80" value="<?= e($_POST['contato_nome'] ?? ($profile['contato_nome'] ?? '')) ?>">
        </label>
        <label>Telefone
          <input type="text" name="contato_telefone" maxlength="20" value="<?= e($_POST['contato_telefone'] ?? ($profile['contato_telefone'] ?? '')) ?>">
        </label>
      </div>
    </fieldset>

    <button type="submit">Salvar perfil</button>
    <p><a href="usuario.php">Voltar para minha conta</a></p>
  </form>

  <script>
    // Mostra/esconde o bloco PJ conforme seleção
    const tipoSel = document.getElementById('tipo_cliente');
    const pjBlock = document.querySelector('.pj-only');
    function togglePJ(){
      pjBlock.style.display = (tipoSel.value === 'PJ') ? 'block' : 'none';
    }
    togglePJ();
    tipoSel.addEventListener('change', togglePJ);
  </script>
</main>
<?php rb_footer(); ?>
