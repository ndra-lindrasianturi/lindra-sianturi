<nav>
  <ul class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <?php
      // Salin $_GET dan timpa page
      $params = $_GET;
      $params['page'] = $i;
      ?>
      <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
        <a class="page-link" href="?<?= http_build_query($params) ?>">
          <?= $i ?>
        </a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>