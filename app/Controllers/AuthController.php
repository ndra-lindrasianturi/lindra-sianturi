<?php


require_once __DIR__ . '/../Models/User.php';

class AuthController
{
  public static function index()
  {
    view('pages/auth/login');
  }

  public static function login()
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $rules = [
        'username' => 'required|string',
        'phone_number' => 'required|numeric',
      ];

      validate($_POST, $rules);

      $username = $_POST['username'];
      $phone_number = $_POST['phone_number'];

      $userModel = new User();
      $user = $userModel->findByCredentials($username, $phone_number);

      if (!$user) {
        throw new Exception('Login gagal. Username atau nomor telepon tidak valid.');
      }

      login($user);

      if ($user['role'] === 'admin') {
        redirect('/admin/dashboard');
      } elseif ($user['role'] === 'mandor') {
        redirect('/mandor/dashboard');
      } else {
        throw new Exception('Role tidak valid.');
      }
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
      redirect('/login');
    }
  }

  public static function logout()
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);
      logout();
      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Logout berhasil.'];
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Terjadi kesalahan saat logout.'];
    }

    redirect('/login');
  }
}
