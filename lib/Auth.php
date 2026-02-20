<?php

require_once __DIR__ . "/Db.php";

session_start();
Db::connect(
  getenv('DB_HOST') ?: 'db',
  getenv('DB_NAME'),
  getenv('DB_USER'),
  getenv('DB_PASS')
);

class Auth
{
  private static function ensureSession(): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      if (headers_sent($file, $line))
        die("Output started at $file on line $line");
      session_start();
    }
  }

  public static function login(string $email, string $password): bool|string
  {
    self::ensureSession();

    $user = Db::queryOne(
      "SELECT id, email, password FROM users WHERE email = ?",
      $email
    );

    if (!$user)
      return "User with email '$email' not found.";

    if (!password_verify($password, $user['password']))
      return "Incorrect password.";

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email']   = $user['email'];

    return true;
  }

  public static function register(string $email, string $password): bool|string
  {
    self::ensureSession();

    if ($email === '' || $password === '')
      return "Required fields cannot be empty.";

    if (Db::queryOne("SELECT id FROM users WHERE email = ?", $email))
      return "Email is already in use.";

    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
      Db::insert('users', [
        'email'     => $email,
        'password'  => $hash,
      ]);

      $_SESSION['user_id'] = Db::getLastId();
      $_SESSION['email']   = $email;

      return true;
    } catch (PDOException $e) {
      return "Registration failed: " . $e->getMessage();
    }
  }

  public static function logout(): void
  {
    self::ensureSession();
    $_SESSION = [];
    session_destroy();
  }

  public static function isLoggedIn(): bool
  {
    self::ensureSession();
    return isset($_SESSION['user_id']);
  }

  public static function userId(): ?int
  {
    self::ensureSession();
    return self::isLoggedIn() ? (int) $_SESSION['user_id'] : null;
  }

  public static function userEmail(): ?string
  {
    self::ensureSession();
    return $_SESSION['email'] ?? null;
  }
}
