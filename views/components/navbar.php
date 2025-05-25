<?php

require_once __DIR__ . '/../../app/Models/ProjectNotification.php';

// Ambil notifikasi jika user adalah mandor
$notifList = [];
if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'mandor') {
  $notifModel = new ProjectNotification();
  $notifList = $notifModel->getUnreadByUser($_SESSION['user']['id']);
}
?>

<style>
  .hover-bg {
    transition: background-color 0.3s ease, color 0.3s ease;
    border-radius: 5px;
  }

  .hover-bg:hover {
    background-color: #495057;
  }

  .hover-bg:hover .fw-semibold {
    color: #ffffff;
  }

  .hover-bg:hover small.text-muted {
    color: #e0e0e0;
  }

  .bi-bell {
    transition: color 0.3s ease;
    color: white;
  }

  .bi-bell:hover {
    color: #ffc107;
    /* kuning Bootstrap */
  }
</style>


<nav class="navbar navbar-expand-lg bg-body-tertiary sticky" data-bs-theme="dark" style="position: sticky; top: 0; z-index: 999;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">CV Mentari Pagi Engineering</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <?php
        $uri = $_SERVER['REQUEST_URI'];
        function isActive($path)
        {
          return strpos($_SERVER['REQUEST_URI'], $path) === 0 ? 'active' : '';
        }
        ?>

        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
          <li class="nav-item">
            <a class="nav-link <?= isActive('/admin/dashboard') ?>" href="/admin/dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= isActive('/admin/karyawan') ?>" href="/admin/karyawan">Karyawan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= isActive('/admin/projects') ?>" href="/admin/projects">Proyek</a>
          </li>

        <?php elseif ($_SESSION['user']['role'] === 'mandor'): ?>
          <li class="nav-item">
            <a class="nav-link <?= isActive('/mandor/dashboard') ?>" href="/mandor/dashboard">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= isActive('/mandor/projects') ?>" href="/mandor/projects">Proyek</a>
          </li>
        <?php endif; ?>
      </ul>

      <div class="d-flex gap-3 align-items-center">
        <?php if ($_SESSION['user']['role'] === 'mandor'): ?>
          <?php $unreadCount = count($notifList); ?>
          <div class="nav-item dropdown me-3">
            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="bi bi-bell fs-4"></i>
              <?php if ($unreadCount > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <?= $unreadCount ?>
                </span>
              <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end p-2" style="min-width: 500px;">
              <li class="fw-bold">Notifikasi Proyek</li>
              <hr class="dropdown-divider">
              <?php if ($unreadCount === 0): ?>
                <li><span class="dropdown-item text-muted">Tidak ada notifikasi baru</span></li>
              <?php else: ?>
                <?php foreach ($notifList as $notif): ?>
                  <li class="d-flex justify-content-between align-items-center mb-2 px-2 hover-bg">
                    <a href="/mandor/projects?search=<?= urlencode($notif['project_name']) ?>&commented=1" class="text-decoration-none flex-grow-1">
                      <div>
                        <div class="fw-semibold text-light"><?= ucfirst(htmlspecialchars($notif['project_name'])) ?></div>
                        <small class="text-muted">Komentar baru dari admin</small>
                      </div>
                    </a>
                    <form method="POST" action="/mandor/projects/read/<?= $notif['project_id'] ?>">
                      <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                      <button class="btn btn-sm btn-outline-success ms-2">Tandai telah dibaca</button>
                    </form>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="POST" action="/logout">
          <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
          <button class="btn btn-outline-danger" type="submit">Logout</button>
        </form>
      </div>
    </div>
  </div>
</nav>