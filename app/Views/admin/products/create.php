<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah Produk Baru</h3>
            </div>
            <div class="card-body">
    
    <form action="<?= base_url('index.php/admin/products/store') ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea class="form-control" name="description" rows="3"></textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-control" name="status">
                <option value="active">Aktif</option>
                <option value="inactive">Tidak Aktif</option>
            </select>
        </div>
        
        <h4>Varian Produk</h4>
        <div id="variants-container">
            <div class="variant-item border p-3 mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Nama Varian</label>
                        <input type="text" class="form-control" name="variants[0][variant_name]" placeholder="Rasa Original">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" name="variants[0][price]" step="0.01">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Stok</label>
                        <input type="number" class="form-control" name="variants[0][stock]" value="0">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">SKU</label>
                        <input type="text" class="form-control" name="variants[0][sku]" placeholder="PRD-001-ORI">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="button" class="btn btn-danger d-block remove-variant">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
        
        <button type="button" class="btn btn-secondary mb-3" id="add-variant">Tambah Varian</button>
        
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= base_url('index.php/admin/products') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<script>
let variantIndex = 1;

document.getElementById('add-variant').addEventListener('click', function() {
    const container = document.getElementById('variants-container');
    const newVariant = `
        <div class="variant-item border p-3 mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Nama Varian</label>
                    <input type="text" class="form-control" name="variants[${variantIndex}][variant_name]" placeholder="Rasa Coklat">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Harga</label>
                    <input type="number" class="form-control" name="variants[${variantIndex}][price]" step="0.01">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Stok</label>
                    <input type="number" class="form-control" name="variants[${variantIndex}][stock]" value="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">SKU</label>
                    <input type="text" class="form-control" name="variants[${variantIndex}][sku]" placeholder="PRD-001-CHO">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-danger d-block remove-variant">Hapus</button>
                </div>
            </div>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newVariant);
    variantIndex++;
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-variant')) {
        e.target.closest('.variant-item').remove();
    }
});
</script>
<?= $this->endSection() ?>