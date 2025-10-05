<?php
require_once __DIR__ . '/../inc/db.php';
try {
  $pdo = db();
  echo "OK: conectado ao banco '".DB_NAME."'.";
} catch (Throwable $e) {
  http_response_code(500);
  echo "ERRO: ".$e->getMessage();
}
