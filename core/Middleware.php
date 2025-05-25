<?php

class Middleware
{
  public static function auth()
  {

    if (!isset($_SESSION['user'])) {
      redirect('/login');
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $csrfToken = $_POST['csrf_token'] ?? '';
      verifyCsrfToken($csrfToken); // Verifikasi token CSRF
    }
  }

  public static function role($role)
  {
    self::auth();

    // Periksa apakah peran pengguna sesuai
    if ($_SESSION['user']['role'] !== $role) {
      http_response_code(403);
      echo "403 Forbidden - Access Denied";
      exit;
    }
  }

  public static function guest()
  {
    if (isset($_SESSION['user'])) {
      $role = $_SESSION['user']['role'];
      if ($role === 'admin') {
        redirect('/admin/dashboard');
      } elseif ($role === 'mandor') {
        redirect('/mandor/dashboard');
      }
      exit;
    }
  }
}
