<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Daftar Produk</h3>
                    <?php if (session()->get('role') === 'seller'): ?>
                    <a href="<?= base_url('admin/products/create') ?>" class="btn btn-primary">Tambah Produk</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <?php if ($is_admin): ?>
                    <th>Toko</th>
                    <?php endif; ?>
                    <th>Jumlah Varian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= $product['id'] ?></td>
                    <td><?= $product['name'] ?></td>
                    <?php if ($is_admin): ?>
                    <td><?= $product['shop_name'] ?? 'Tidak ada' ?></td>
                    <?php endif; ?>
                    <td><?= $product['variant_count'] ?> varian</td>
                    <td>
                        <span class="badge bg-<?= $product['status'] == 'active' ? 'success' : 'danger' ?>">
                            <?= ucfirst($product['status']) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/products/view/' . $product['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                        <?php if (session()->get('role') === 'seller'): ?>
                        <a href="<?= base_url('admin/products/edit/' . $product['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= base_url('admin/products/delete/' . $product['id']) ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirmDelete(event, 'produk ini')">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
                
                <?php if (empty($products)): ?>
                    <div class="text-center mt-4">
                        <?php if (session()->get('role') === 'seller'): ?>
                        <p>Belum ada produk. <a href="<?= base_url('admin/products/create') ?>">Tambah produk pertama</a></p>
                        <?php else: ?>
                        <p>Belum ada produk dari seller manapun.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>