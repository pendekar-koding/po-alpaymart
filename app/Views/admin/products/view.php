<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Detail Produk</h3>
                    <a href="<?= base_url('admin/products') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">ID Produk:</th>
                                <td><?= $product['id'] ?></td>
                            </tr>
                            <tr>
                                <th>Nama Produk:</th>
                                <td><?= $product['name'] ?></td>
                            </tr>
                            <tr>
                                <th>Deskripsi:</th>
                                <td><?= $product['description'] ?: '-' ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge bg-<?= $product['status'] == 'active' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($product['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Toko:</th>
                                <td><?= $product['shop_name'] ?? 'Tidak ada' ?></td>
                            </tr>
                            <tr>
                                <th>Dibuat:</th>
                                <td><?= date('d/m/Y H:i', strtotime($product['created_at'])) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <h5>Varian Produk</h5>
                <?php if (!empty($product['variants'])): ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama Varian</th>
                                <th>SKU</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($product['variants'] as $variant): ?>
                            <tr>
                                <td><?= $variant['variant_name'] ?></td>
                                <td><?= $variant['sku'] ?: '-' ?></td>
                                <td>Rp <?= number_format($variant['price'], 0, ',', '.') ?></td>
                                <td><?= $variant['stock'] ?></td>
                                <td>
                                    <span class="badge bg-<?= $variant['status'] == 'active' ? 'success' : 'danger' ?>">
                                        <?= ucfirst($variant['status']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted">Tidak ada varian produk.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>