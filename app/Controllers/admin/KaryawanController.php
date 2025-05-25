<?php

require_once __DIR__ . '/../../Models/User.php';

class KaryawanController
{
  public static function index()
  {
    try {
      $limit = 5;
      $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
      $offset = ($page - 1) * $limit;

      // Ambil filter dari query string
      $filters = [
        'search' => $_GET['search'] ?? '',
        'role' => $_GET['role'] ?? ''
      ];

      $user = new User();

      // Kirim filters ke model
      $totalUser = $user->total($filters);
      $karyawanList = $user->allPaginated($limit, $offset, $filters);

      $totalPages = max(ceil($totalUser / $limit), 1); // supaya tidak 0 halaman

      view('pages/admin/karyawan/index', [
        'karyawanList' => $karyawanList,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'search' => $filters['search'],
        'role' => $filters['role'],
        'offset' => $offset,
      ]);
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Terjadi kesalahan saat memuat data karyawan.'];
      redirect('/admin/dashboard');
    }
  }

  public static function store()
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $rules = [
        'username' => 'required|string',
        'phone_number' => 'required|numeric',
      ];

      validate($_POST, $rules);

      $data = [
        'username' => $_POST['username'],
        'phone_number' => $_POST['phone_number'],
        'role' => $_POST['role'],
      ];

      $user = new User();
      $user->create($data);

      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Karyawan berhasil ditambahkan.'];
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
    }

    redirect('/admin/karyawan');
  }

  public static function update($id)
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $rules = [
        'username' => 'required|string',
        'phone_number' => 'required|numeric',
        'role' => 'required|string',
      ];

      validate($_POST, $rules);

      $user = new User();
      $authUser = $_SESSION['user']['id'];
      $karyawan = $user->find($id);

      if (!$karyawan) {
        throw new Exception('Karyawan tidak ditemukan.');
      }

      if ($id == $authUser && isset($_POST['role']) && $_POST['role'] !== $karyawan['role']) {
        throw new Exception('Anda tidak dapat mengubah role akun Anda sendiri.');
      }

      $data = [
        'username' => $_POST['username'],
        'phone_number' => $_POST['phone_number'],
        'role' => $_POST['role'],
      ];

      $user->update($id, $data);

      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Karyawan berhasil diperbarui.'];
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
    }

    redirect('/admin/karyawan');
  }

  public static function destroy($id)
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $user = new User();
      $authUser = $_SESSION['user']['id'];
      $karyawan = $user->find($id);

      if (!$karyawan) {
        throw new Exception('Karyawan tidak ditemukan.');
      }

      if ($id == $authUser) {
        throw new Exception('Anda tidak dapat menghapus akun Anda sendiri.');
      }

      $user->delete($id);

      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Karyawan berhasil dihapus.'];
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
    }

    redirect('/admin/karyawan');
  }
}
