<?php

require_once __DIR__ . '/../../lib/Db.php';
require_once __DIR__ . '/../../lib/Auth.php';

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$result = Auth::register($email, $password);

if ($result === true) {
  header('Location: /');
  exit;
}

header('Location: /?auth_error=' . urlencode($result));
exit;
