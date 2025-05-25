<style>
  .card {
    width: 30rem;
    margin: auto;
    ;
  }
</style>

<section>
  <div class="card">
    <div class="card-body">
      <h3 class="card-title text-center">Login Form</h3>
      <hr>
      <?php include __DIR__ . '/../../components/alert.php'; ?>

      <form action="/login" method="POST">
        <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Number Phone</label>
          <input type="text" name="phone_number" class="form-control" required pattern="\d+">
        </div>
        <div class="mt-4">
          <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
        </div>
      </form>
    </div>
  </div>
</section>