<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Katalog</h3>
                    <a href="<?= base_url('admin/catalogs/create') ?>" class="btn btn-primary">Tambah Katalog</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>File</th>
                                <th>Ukuran</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($catalogs as $catalog): ?>
                            <tr>
                                <td><?= $catalog['id'] ?></td>
                                <td><?= $catalog['title'] ?></td>
                                <td>
                                    <?php if (strpos($catalog['file_type'], 'image') !== false): ?>
                                        <i class="fas fa-image text-success"></i> Gambar
                                    <?php else: ?>
                                        <i class="fas fa-file-pdf text-danger"></i> PDF
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($catalog['file_size'] / 1024, 1) ?> KB</td>
                                <td>
                                    <span class="badge bg-<?= $catalog['status'] == 'active' ? 'success' : 'secondary' ?>">
                                        <?= ucfirst($catalog['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($catalog['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/catalogs/download/' . $catalog['id']) ?>" class="btn btn-sm btn-info">Download</a>
                                    <a href="<?= base_url('admin/catalogs/edit/' . $catalog['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="<?= base_url('admin/catalogs/delete/' . $catalog['id']) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin ingin menghapus katalog ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if (empty($catalogs)): ?>
                    <div class="text-center mt-4">
                        <p>Belum ada katalog. <a href="<?= base_url('admin/catalogs/create') ?>">Tambah katalog pertama</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>