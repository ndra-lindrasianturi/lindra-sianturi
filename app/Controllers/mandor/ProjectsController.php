<?php

require_once __DIR__ . '/../../Models/Project.php';
require_once __DIR__ . '/../../Models/ProjectDetail.php';
require_once __DIR__ . '/../../Models/ProjectNotification.php';

class ProjectsController
{
  public static function index()
  {
    try {
      $userId = $_SESSION['user']['id'];
      $limit = 5;
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      $offset = ($page - 1) * $limit;

      $filters = [
        'search' => !empty($_GET['search']) ? $_GET['search'] : null,
        'status' => !empty($_GET['status']) ? $_GET['status'] : null,
        'commented' => isset($_GET['commented']) && $_GET['commented'] !== '' ? $_GET['commented'] : null,
      ];

      $project = new Project();
      $projectDetail = new ProjectDetail();
      $projectNotif = new ProjectNotification();

      $totalProjects = $project->countByUserIdWithFilters($userId, $filters);
      $projectList = $project->allPaginatedUserId($userId, $limit, $offset, $filters);

      foreach ($projectList as &$projectItem) {
        $lastComment = $projectDetail->findByProject($projectItem['id']);
        $notif = $projectNotif->findByProjectAndUser($projectItem['id'], $userId);

        $projectItem['last_comment'] = $lastComment ? $lastComment[0]['comment'] : '';
        $projectItem['notif_unread'] = $notif && $notif['is_read'] == 0;
        $projectItem['notif_id'] = $notif ? $notif['id'] : null;
      }

      $totalPages = ceil($totalProjects / $limit);

      view('pages/mandor/projects/index', [
        'projectList' => $projectList,
        'currentPage' => $page,
        'totalPages' => $totalPages,
        'filters' => $filters,
        'offset' => $offset,
      ]);
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Terjadi kesalahan saat memuat data proyek.' . $e];
      redirect('/mandor/dashboard');
    }
  }

  public static function store()
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $rules = [
        'project_name' => 'required|string',
        'customer_name' => 'string',
        'status' => 'required|string',
        'start_date' => 'date',
        'end_date' => 'date',
        'description' => 'string',
      ];

      validate($_POST, $rules);

      $data = [
        'user_id' => $_SESSION['user']['id'],
        'project_name' => $_POST['project_name'],
        'customer_name' => !empty($_POST['customer_name']) ? $_POST['customer_name'] : NULL,
        'status' => $_POST['status'],
        'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : NULL,
        'end_date' => !empty($_POST['end_date']) ? $_POST['end_date'] : NULL,
        'description' => !empty($_POST['description']) ? $_POST['description'] : NULL,
      ];

      $project = new Project();
      $project->create($data);

      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Proyek ' . $data['project_name'] . ' berhasil ditambahkan.'];
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
    }

    redirect('/mandor/projects');
  }

  public static function update($id)
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $rules = [
        'project_name' => 'required|string',
        'customer_name' => 'string',
        'status' => 'required|string',
        'start_date' => 'date',
        'end_date' => 'date',
        'description' => 'string',
      ];

      validate($_POST, $rules);

      $project = new Project();
      $existingProject = $project->find($id);

      if (!$existingProject || $existingProject['user_id'] !== $_SESSION['user']['id']) {
        throw new Exception('Proyek tidak ditemukan atau Anda tidak memiliki akses.');
      }

      $data = [
        'project_name' => $_POST['project_name'],
        'customer_name' => !empty($_POST['customer_name']) ? $_POST['customer_name'] : NULL,
        'status' => $_POST['status'],
        'start_date' => !empty($_POST['start_date']) ? $_POST['start_date'] : NULL,
        'end_date' =>  !empty($_POST['end_date']) ? $_POST['end_date'] : NULL,
        'description' => !empty($_POST['description']) ? $_POST['description'] : NULL,
      ];

      $project->update($id, $data);

      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Proyek ' . $data['project_name'] . ' diperbarui.'];
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
    }

    redirect('/mandor/projects');
  }

  public static function destroy($id)
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $project = new Project();
      $existingProject = $project->find($id);

      if (!$existingProject || $existingProject['user_id'] !== $_SESSION['user']['id']) {
        throw new Exception('Proyek tidak ditemukan atau Anda tidak memiliki akses.');
      }

      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Proyek ' . $existingProject['project_name'] . ' dihapus.'];

      $project->delete($id);
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
    }

    redirect('/mandor/projects');
  }

  public static function markAsRead($project_id)
  {
    try {
      verifyCsrfToken($_POST['csrf_token']);

      $user_id = $_SESSION['user']['id']; // ID mandor login
      $notifModel = new ProjectNotification();

      $notif = $notifModel->findByProjectAndUser($project_id, $user_id);
      if (!$notif) {
        throw new Exception('Notifikasi tidak ditemukan.');
      }

      $notifModel->markAsRead($notif['id']);

      $_SESSION['alert'] = ['type' => 'success', 'message' => 'Komentar ditandai sudah dibaca.'];
    } catch (Exception $e) {
      $_SESSION['alert'] = ['type' => 'danger', 'message' => $e->getMessage()];
    }

    redirect('/mandor/projects');
  }
}
