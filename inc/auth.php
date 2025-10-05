<?php
// inc/auth.php - sessão e utilidades
session_start();
function hash_pass($p){ return password_hash($p, PASSWORD_DEFAULT); }
function verify_pass($p,$h){ return password_verify($p,$h); }
function require_login($role=null){
  if(empty($_SESSION['uid'])){ header('Location: /login.php'); exit; }
  if($role && ($_SESSION['role']??'') !== $role){ http_response_code(403); exit('Acesso negado'); }
}