<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Activity Logs</h3>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <form method="GET" action="<?= base_url('admin/activity-logs') ?>">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan username, activity, atau description..." value="<?= $search ?? '' ?>">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Cari
                  </button>
                  <?php if (!empty($search)): ?>
                  <a href="<?= base_url('admin/activity-logs') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            </form>
          </div>
        </div>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Waktu</th>
              <th>Username</th>
              <th>Role</th>
              <th>Activity</th>
              <th>Description</th>
              <th>IP Address</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($logs as $log): ?>
            <tr>
              <td><?= date('d/m/Y H:i:s', strtotime($log['created_at'])) ?></td>
              <td><?= $log['username'] ?></td>
              <td><span class="badge badge-<?= $log['role'] == 'admin' ? 'danger' : 'primary' ?>"><?= ucfirst($log['role']) ?></span></td>
              <td><?= $log['activity'] ?></td>
              <td><?= $log['description'] ?? '-' ?></td>
              <td><?= $log['ip_address'] ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?= $pager->links() ?>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>