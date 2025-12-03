<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Toko</h3>
            </div>
            <form action="<?= base_url('admin/seller-settings/update') ?>" method="post">
                <div class="card-body">
                    <div class="form-group">
                        <label>Status Toko</label>
                        <select name="shop_status" class="form-control" required>
                            <option value="open" <?= ($user['shop_status'] ?? 'open') == 'open' ? 'selected' : '' ?>>Buka</option>
                            <option value="closed" <?= ($user['shop_status'] ?? 'open') == 'closed' ? 'selected' : '' ?>>Tutup</option>
                        </select>
                        <small class="text-muted">Jika tutup, produk Anda tidak akan tampil di halaman customer</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>