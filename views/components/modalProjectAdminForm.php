<div class="modal fade" id="viewProjectModal<?= $project['id'] ?>" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <form method="POST" action="/admin/projects/comment/<?= $project['id'] ?>" class="comment-form">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Detail Proyek: <?= htmlspecialchars($project['project_name']) ?></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p><strong>Customer:</strong>
            <span class="<?= empty($project['customer_name']) ? 'text-muted' : '' ?>">
              <?= !empty($project['customer_name']) ? htmlspecialchars($project['customer_name']) : 'Belum Diisi' ?>
            </span>
          </p>
          <p><strong>Status:</strong> <?= ucfirst(htmlspecialchars($project['status'])) ?></p>
          <p><strong>Waktu:</strong>
            <span class="<?= empty($project['start_date']) ? 'text-muted' : '' ?>">
              <?= !empty($project['start_date']) ? htmlspecialchars($project['start_date']) : 'Belum Diisi' ?>
            </span>
            s/d
            <span class="<?= empty($project['end_date']) ? 'text-muted' : '' ?>">
              <?= !empty($project['end_date']) ? htmlspecialchars($project['end_date']) : 'Belum Diisi' ?>
            </span>
          </p>
          <p><strong>Deskripsi:</strong>
            <span class="<?= empty($project['description']) ? 'text-muted' : '' ?>">
              <?= !empty($project['description']) ? nl2br(htmlspecialchars($project['description'])) : 'Belum Diisi' ?>
            </span>
          </p>

          <hr>
          <label for="comment">Komentar dari Admin:</label>
          <textarea id="comment" name="comment" class="form-control comment-field" rows="3" <?= !empty($project['last_comment']) ? 'disabled' : '' ?> required><?= htmlspecialchars($project['last_comment']) ?></textarea>
          <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

          <?php if (!empty($project['last_comment']) && isset($project['notif_is_read']) && $project['notif_is_read']): ?>
            <div class="alert alert-success py-2 px-3 mt-3 mb-1 alert-isread">
              Komentar ini sudah dibaca oleh Mandor.
            </div>
          <?php endif; ?>

        </div>
        <div class="modal-footer">
          <?php if (!empty($project['last_comment'])): ?>
            <button type="button" class="btn btn-warning edit-btn">Edit</button>
          <?php endif; ?>
          <button type="submit" class="btn btn-primary save-btn" style="<?= empty($project['last_comment']) ? 'display: inline-block;' : 'display: none;' ?>">Konfirmasi</button>
          <button type="button" class="btn btn-secondary cancel-btn" style="display: none;">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for all edit buttons when the DOM is fully loaded
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(function(editBtn) {
      editBtn.addEventListener('click', function() {
        const modal = this.closest('.modal');
        const commentField = modal.querySelector('.comment-field');
        const saveButton = modal.querySelector('.save-btn');
        const cancelButton = modal.querySelector('.cancel-btn');
        const closeButton = modal.querySelector('.btn-close');
        const alertIsRead = modal.querySelector('.alert-isread');

        // Enable comment field and show appropriate buttons
        commentField.disabled = false;
        commentField.focus();

        // Hide edit button and close button
        this.style.display = 'none';
        if (closeButton) closeButton.style.display = 'none';

        // Hide alert if it exists
        if (alertIsRead) alertIsRead.style.display = 'none';

        // Show save and cancel buttons
        saveButton.style.display = 'inline-block';
        cancelButton.style.display = 'inline-block';
      });
    });

    // Add event listeners for all cancel buttons
    const cancelButtons = document.querySelectorAll('.cancel-btn');
    cancelButtons.forEach(function(cancelBtn) {
      cancelBtn.addEventListener('click', function() {
        const modal = this.closest('.modal');
        const commentField = modal.querySelector('.comment-field');
        const editButton = modal.querySelector('.edit-btn');
        const saveButton = modal.querySelector('.save-btn');
        const closeButton = modal.querySelector('.btn-close');
        const alertIsRead = modal.querySelector('.alert-isread');

        // Reset and disable comment field
        commentField.disabled = true;
        commentField.value = commentField.defaultValue;

        // Show edit button and close button
        editButton.style.display = 'inline-block';
        if (closeButton) closeButton.style.display = 'block';

        // Show alert if it exists
        if (alertIsRead) alertIsRead.style.display = 'block';

        // Hide save and cancel buttons
        this.style.display = 'none';
        saveButton.style.display = 'none';
      });
    });
  });
</script>