<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Produk</h3>
            </div>
            <form action="<?= base_url('admin/products/update/' . $product['id']) ?>" method="post">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama Produk</label>
                        <input type="text" class="form-control" name="name" value="<?= $product['name'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea class="form-control" name="description" rows="3"><?= $product['description'] ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status" required>
                            <option value="active" <?= $product['status'] == 'active' ? 'selected' : '' ?>>Aktif</option>
                            <option value="inactive" <?= $product['status'] == 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Varian Produk</label>
                        <div id="variants-container">
                            <?php if (!empty($product['variants'])): ?>
                                <?php foreach ($product['variants'] as $index => $variant): ?>
                                <div class="variant-row border p-3 mb-2">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Nama Varian</label>
                                            <input type="text" class="form-control" name="variants[<?= $index ?>][variant_name]" value="<?= $variant['variant_name'] ?>" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Harga</label>
                                            <input type="number" class="form-control" name="variants[<?= $index ?>][price]" value="<?= $variant['price'] ?>" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label>Stok</label>
                                            <input type="number" class="form-control" name="variants[<?= $index ?>][stock]" value="<?= $variant['stock'] ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <button type="button" class="btn btn-danger btn-block remove-variant">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn btn-success" id="add-variant">Tambah Varian</button>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('admin/products') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let variantIndex = <?= !empty($product['variants']) ? count($product['variants']) : 0 ?>;

document.getElementById('add-variant').addEventListener('click', function() {
    const container = document.getElementById('variants-container');
    const variantHtml = `
        <div class="variant-row border p-3 mb-2">
            <div class="row">
                <div class="col-md-3">
                    <label>Nama Varian</label>
                    <input type="text" class="form-control" name="variants[${variantIndex}][variant_name]" required>
                </div>
                <div class="col-md-2">
                    <label>Harga</label>
                    <input type="number" class="form-control" name="variants[${variantIndex}][price]" required>
                </div>
                <div class="col-md-2">
                    <label>Stok</label>
                    <input type="number" class="form-control" name="variants[${variantIndex}][stock]" value="0">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger btn-block remove-variant">Hapus</button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', variantHtml);
    variantIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-variant')) {
        e.target.closest('.variant-row').remove();
    }
});
</script>
<?= $this->endSection() ?>