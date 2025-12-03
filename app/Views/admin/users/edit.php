<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit User</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="full_name" value="<?= $user['full_name'] ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Toko</label>
                                <input type="text" class="form-control" name="shop_name" value="<?= $user['shop_name'] ?? '' ?>" placeholder="Nama toko/bisnis">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password (kosongkan jika tidak diubah)</label>
                                <input type="password" class="form-control" name="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-control" name="role" required>
                                    <option value="seller" <?= $user['role'] == 'seller' ? 'selected' : '' ?>>Seller</option>
                                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status" required>
                                    <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="inactive" <?= $user['status'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Status Toko</label>
                                <select class="form-control" name="shop_status" required>
                                    <option value="open" <?= ($user['shop_status'] ?? 'open') == 'open' ? 'selected' : '' ?>>Buka</option>
                                    <option value="closed" <?= ($user['shop_status'] ?? 'open') == 'closed' ? 'selected' : '' ?>>Tutup</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>