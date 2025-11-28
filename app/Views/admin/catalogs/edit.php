<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Katalog</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/catalogs/update/' . $catalog['id']) ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Judul Katalog</label>
                        <input type="text" class="form-control" name="title" value="<?= $catalog['title'] ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"><?= $catalog['description'] ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">File Katalog Saat Ini</label>
                        <div class="alert alert-info">
                            <?php if (strpos($catalog['file_type'], 'image') !== false): ?>
                                <i class="fas fa-image"></i> Gambar - <?= $catalog['file_name'] ?>
                            <?php else: ?>
                                <i class="fas fa-file-pdf"></i> PDF - <?= $catalog['file_name'] ?>
                            <?php endif; ?>
                            (<?= number_format($catalog['file_size'] / 1024, 1) ?> KB)
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ganti File (Opsional)</label>
                        <input type="file" class="form-control" name="catalog_file" accept=".jpg,.jpeg,.png,.pdf">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Format: JPG, PNG, PDF (Max 10MB)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status" required>
                            <option value="active" <?= $catalog['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= $catalog['status'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="<?= base_url('admin/catalogs') ?>" class="btn btn-secondary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>