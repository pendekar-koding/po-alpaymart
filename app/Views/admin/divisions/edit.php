<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Edit Divisi</h2>
    <a href="<?= base_url('admin/divisions') ?>" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="<?= base_url('admin/divisions/update/' . $division['id']) ?>" method="post">
            <div class="mb-3">
                <label for="nama_divisi" class="form-label">Nama Divisi</label>
                <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" 
                       value="<?= old('nama_divisi', $division['nama_divisi']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
            </button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>