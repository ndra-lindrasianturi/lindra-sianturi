<?php

require_once __DIR__ . '/../../Models/Project.php';
require_once __DIR__ . '/../../Models/ProjectDetail.php';
require_once __DIR__ . '/../../Models/ProjectNotification.php';


class AdminProjectsController
{
  public static function index()
  {
    try {
      $limit = 5;
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $offset = ($page - 1) * $limit;

      // Ambil filter dari query string
      $filters = [
        'search' => $_GET['search'] ?? '',
        'status' => $_GET['status'] ?? '',
      ];

      $project = new Project();
      $projectDetail = new ProjectDetail();
      $projectNotification = new ProjectNotification();

      // Kirim filters ke model untuk total proyek
      $totalProjects = $project->total($filters);

      // Kirim filters ke model untuk mengambil proyek terpaginasikan
      $projectList = $project->allPaginated($limit, $offset, $filters);

      foreach ($projectList as &$projectItem) {
        $lastComment = $projectDetail->findByProject($projectItem['id']);
        $projectItem['last_comment'] = $lastComment ? $lastComment[0]['comment'] : '';

        // Tambahkan status sudah dibaca
        $notif = $projectNotification->findByProjectAndUser($projectItem['id'], $projectItem['user_id']);
        $projectItem['notif_is_read'] = $notif ? (bool)$notif['is_read'] : false;
      }

      $totalPages = ceil($totalProjects / $limit);

      view('pages/admin/projects/index', [
        'projectList' => $projectList,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'search' => $filters['search'],
        'status' => $filters['status'],
        'offset' => $offset,
      ]);
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Terjadi kesalahan saat memuat data proyek.'];
      redirect('/admin/dashboard');
    }
  }
  public static function comment($project_id)
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $rules = [
        'comment' => 'required|string',
      ];

      validate($_POST, $rules);

      $comment = trim($_POST['comment']);
      $admin_id = $_SESSION['user']['id'];

      if ($comment === '') {
        throw new Exception('Komentar tidak boleh kosong.');
      }

      $projectDetail = new ProjectDetail();
      $projectNotification = new ProjectNotification();

      // Cek apakah komentar sebelumnya sudah ada
      if ($projectDetail->findByProject($project_id)) {
        $projectDetail->updateComment($project_id, $admin_id, $comment);
      } else {
        $projectDetail->create([
          'project_id' => $project_id,
          'user_id' => $admin_id,
          'comment' => $comment,
        ]);
      }

      // Cari user mandor pemilik proyek
      $projectModel = new Project();
      $project = $projectModel->find($project_id);

      if ($project) {
        $mandor_id = $project['user_id'];

        // Cek jika sudah ada notifikasi sebelumnya
        $existingNotif = $projectNotification->findByProjectAndUser($project_id, $mandor_id);
        if ($existingNotif) {
          // Update is_read = 0 supaya dianggap notif baru
          $projectNotification->updateUnread($existingNotif['id']);
        } else {
          // Buat notifikasi baru
          $projectNotification->create([
            'user_id' => $mandor_id,
            'project_id' => $project_id,
          ]);
        }
      }

      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Komentar ' . $project['project_name'] . ' berhasil diperbarui.'];
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
    }

    redirect('/admin/projects');
  }
}
