<!-- Modal Delete Konfirmasi -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="deleteForm" method="POST">
        <!-- Generate CSRF Token -->
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">

        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
          <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <p id="deleteModalMessage">Apakah Anda yakin ingin menghapus data ini?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Hapus</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', (event) => {
      const button = event.relatedTarget; // Tombol yang memicu modal
      const actionUrl = button.getAttribute('data-action-url');
      const customMessage = button.getAttribute('data-custom-message');

      const deleteForm = document.getElementById('deleteForm');
      const messageEl = document.getElementById('deleteModalMessage');

      deleteForm.action = actionUrl;
      messageEl.textContent = customMessage || 'Apakah Anda yakin ingin menghapus data ini?';
    });
  });
</script>