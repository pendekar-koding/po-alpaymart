<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar User</h3>
                    <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary">Tambah User</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Nama Toko</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Status Toko</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= $user['username'] ?></td>
                                <td><?= $user['full_name'] ?></td>
                                <td><?= $user['shop_name'] ?? '-' ?></td>
                                <td>
                                    <span class="badge bg-<?= $user['role'] == 'admin' ? 'danger' : 'info' ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $user['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($user['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['role'] === 'seller'): ?>
                                    <button class="btn btn-sm <?= ($user['shop_status'] ?? 'open') == 'open' ? 'btn-success' : 'btn-danger' ?>" 
                                            onclick="toggleShopStatus(<?= $user['id'] ?>, '<?= ($user['shop_status'] ?? 'open') == 'open' ? 'closed' : 'open' ?>')">
                                        <?= ($user['shop_status'] ?? 'open') == 'open' ? 'Buka' : 'Tutup' ?>
                                    </button>
                                    <?php else: ?>
                                    <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <?php if ($user['id'] != session()->get('user_id')): ?>
                                    <a href="<?= base_url('admin/users/delete/' . $user['id']) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirmDelete(event, 'user ini')">Hapus</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (empty($users)): ?>
                    <div class="text-center mt-4">
                        <p>Belum ada user. <a href="<?= base_url('admin/users/create') ?>">Tambah user pertama</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script>
function toggleShopStatus(userId, newStatus) {
    showLoading();
    fetch('<?= base_url('admin/users/toggle-shop-status') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'user_id=' + userId + '&shop_status=' + newStatus
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            location.reload();
        } else {
            popup.error(data.message || 'Gagal mengubah status toko');
        }
    })
    .catch(error => {
        hideLoading();
        popup.error('Terjadi kesalahan');
    });
}
</script>
<?= $this->endSection() ?>