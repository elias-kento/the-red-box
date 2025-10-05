<?php
require_once __DIR__ . '/../inc/render.php';
require_once __DIR__ . '/../inc/db.php';
if (session_status() !== PHP_SESSION_ACTIVE) { @session_start(); }

// exige login
if (empty($_SESSION['user_id'])) {
  header('Location: login.php'); exit;
}

// carrega tipo para garantir que é HACKER
$pdo = db();
$stmt = $pdo->prepare("SELECT id, tipo_usuario, nome_completo FROM users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $_SESSION['user_id']]);
$user = $stmt->fetch();
if (!$user) { header('Location: logout.php'); exit; }
$tipo = strtoupper(trim($user['tipo_usuario'] ?? ''));
if ($tipo !== 'HACKER') {
  http_response_code(403);
  die('Apenas usuários HACKER podem acessar esta página. Tipo atual: ' . e($tipo));
}


function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

$errors = [];
$ok = false;

// carregar perfil existente (se houver)
$stmt = $pdo->prepare("SELECT * FROM hacker_profiles WHERE user_id = :id");
$stmt->execute([':id' => $user['id']]);
$profile = $stmt->fetch() ?: [
  'apelido' => '',
  'bio' => '',
  'especialidades' => '',
  'valor_hora' => '',
  'website' => '',
  'github' => '',
  'linkedin' => '',
  'disponibilidade' => 'FREELANCER',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $apelido = trim($_POST['apelido'] ?? '');
  $bio = trim($_POST['bio'] ?? '');
  $especialidades = trim($_POST['especialidades'] ?? '');
  $valor_hora = trim($_POST['valor_hora'] ?? '');
  $website = trim($_POST['website'] ?? '');
  $github = trim($_POST['github'] ?? '');
  $linkedin = trim($_POST['linkedin'] ?? '');
  $disponibilidade = $_POST['disponibilidade'] ?? 'FREELANCER';

  // Validações básicas
  if ($apelido === '' || mb_strlen($apelido) > 60) $errors[] = 'Apelido é obrigatório (máx. 60).';
  $opts = ['FULLTIME','PARTTIME','FREELANCER','A_COMBINAR'];
  if (!in_array($disponibilidade, $opts, true)) $errors[] = 'Disponibilidade inválida.';
  if ($valor_hora !== '' && !preg_match('/^\d+(\.\d{1,2})?$/', $valor_hora)) $errors[] = 'Valor/hora deve ser número (ex.: 150 ou 150.50).';
  foreach (['website'=>$website,'github'=>$github,'linkedin'=>$linkedin] as $lbl=>$url) {
    if ($url !== '' && !filter_var($url, FILTER_VALIDATE_URL)) $errors[] = "URL inválida em $lbl.";
  }

  if (!$errors) {
    // UPSERT (insere ou atualiza)
    $sql = "INSERT INTO hacker_profiles
              (user_id, apelido, bio, especialidades, valor_hora, website, github, linkedin, disponibilidade)
            VALUES
              (:uid, :apelido, :bio, :especialidades, :valor_hora, :website, :github, :linkedin, :disp)
            ON DUPLICATE KEY UPDATE
              apelido = VALUES(apelido),
              bio = VALUES(bio),
              especialidades = VALUES(especialidades),
              valor_hora = VALUES(valor_hora),
              website = VALUES(website),
              github = VALUES(github),
              linkedin = VALUES(linkedin),
              disponibilidade = VALUES(disponibilidade)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':uid' => $user['id'],
      ':apelido' => $apelido,
      ':bio' => $bio ?: null,
      ':especialidades' => $especialidades ?: null,
      ':valor_hora' => ($valor_hora === '' ? null : $valor_hora),
      ':website' => $website ?: null,
      ':github' => $github ?: null,
      ':linkedin' => $linkedin ?: null,
      ':disp' => $disponibilidade,
    ]);
    $ok = true;

    // recarrega para preencher o form atualizado
    $stmt = $pdo->prepare("SELECT * FROM hacker_profiles WHERE user_id = :id");
    $stmt->execute([':id' => $user['id']]);
    $profile = $stmt->fetch();
  }
}

rb_header('Perfil de Hacker');
?>
<main class="container">
  <h1>Perfil de Hacker</h1>
  <p><strong>Usuário:</strong> <?= e($user['nome_completo']) ?></p>

  <?php if ($ok): ?>
    <div class="alert success">Perfil salvo com sucesso.</div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="alert error">
      <ul><?php foreach ($errors as $e) echo "<li>".e($e)."</li>"; ?></ul>
    </div>
  <?php endif; ?>

  <form method="post" class="form-card">
    <div class="grid-2">
      <label>Apelido (público)
        <input type="text" name="apelido" maxlength="60" required value="<?= e($_POST['apelido'] ?? $profile['apelido']) ?>">
      </label>
      <label>Disponibilidade
        <select name="disponibilidade">
          <?php
          $opts = ['FULLTIME'=>'Tempo integral','PARTTIME'=>'Meio período','FREELANCER'=>'Freelancer','A_COMBINAR'=>'A combinar'];
          $sel = $_POST['disponibilidade'] ?? $profile['disponibilidade'];
          foreach ($opts as $k=>$label) {
            $s = ($sel===$k)?'selected':'';
            echo "<option value='".e($k)."' $s>".e($label)."</option>";
          }
          ?>
        </select>
      </label>
    </div>

    <label>Bio
      <textarea name="bio" rows="4"><?= e($_POST['bio'] ?? ($profile['bio'] ?? '')) ?></textarea>
    </label>

    <label>Especialidades (separe por vírgula)
      <textarea name="especialidades" rows="3" placeholder="pentest web, forense, IoT, redes"><?= e($_POST['especialidades'] ?? ($profile['especialidades'] ?? '')) ?></textarea>
    </label>

    <div class="grid-3">
      <label>Valor/hora (R$)
        <input type="text" name="valor_hora" placeholder="ex.: 150.00" value="<?= e($_POST['valor_hora'] ?? ($profile['valor_hora'] ?? '')) ?>">
      </label>
      <label>Website
        <input type="url" name="website" value="<?= e($_POST['website'] ?? ($profile['website'] ?? '')) ?>">
      </label>
      <label>GitHub
        <input type="url" name="github" value="<?= e($_POST['github'] ?? ($profile['github'] ?? '')) ?>">
      </label>
    </div>

    <label>LinkedIn
      <input type="url" name="linkedin" value="<?= e($_POST['linkedin'] ?? ($profile['linkedin'] ?? '')) ?>">
    </label>

    <button type="submit">Salvar perfil</button>
    <p><a href="usuario.php">Voltar para minha conta</a></p>
  </form>
</main>
<?php rb_footer(); ?>
