<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manajemen Divisi</h2>
    <a href="<?= base_url('admin/divisions/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Divisi
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Divisi</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($divisions)): ?>
                        <?php foreach ($divisions as $division): ?>
                        <tr>
                            <td><?= $division['id'] ?></td>
                            <td><?= $division['nama_divisi'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($division['created_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('admin/divisions/edit/' . $division['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="<?= base_url('admin/divisions/delete/' . $division['id']) ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirmDelete(event, 'divisi ini')">
                                    <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data divisi</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>