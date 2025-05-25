<style>
  .sidebar {
    width: 280px;
    height: 100dvh;
    position: fixed;
    top: 0;
    left: 0;
  }
</style>

<div class="sidebar d-flex flex-column flex-shrink-0 p-3 text-white bg-dark">
  <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
    <i class="bi bi-list"></i>
    <span class="fs-4">Sidebar</span>
  </a>
  <hr>
  <nav class="nav nav-pills flex-column mb-auto">
    <li class="nav-item">
      <a href="#" class="nav-link active" aria-current="page">
        <i class="bi bi-speedometer"></i>
        Dashboard
      </a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        <i class="bi bi-box-seam-fill"></i>
        Proyek
      </a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link text-white">
        <i class="bi bi-person-fill"></i>
        Mandor
      </a>
    </li>
  </nav>
  <hr>
  <div class="dropdown">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
      <strong><?= $_SESSION['user']['username'] ?></strong>
    </a>
    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
      <li><a class="dropdown-item" href="#">New project...</a></li>
      <li><a class="dropdown-item" href="#">Settings</a></li>
      <li><a class="dropdown-item" href="#">Profile</a></li>
      <li>
        <hr class="dropdown-divider">
      </li>
      <li><a class="dropdown-item" href="#">Sign out</a></li>
    </ul>
  </div>
</div>