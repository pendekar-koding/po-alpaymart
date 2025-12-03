<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pengaturan Website</h3>
            </div>
            <form action="<?= base_url('admin/settings/update') ?>" method="post">
                <div class="card-body">
                    <div class="form-group">
                        <label>Status Website</label>
                        <select name="website_status" class="form-control" required>
                            <option value="open" <?= ($settings['website_status'] ?? 'open') == 'open' ? 'selected' : '' ?>>Buka</option>
                            <option value="closed" <?= ($settings['website_status'] ?? 'open') == 'closed' ? 'selected' : '' ?>>Tutup</option>
                        </select>
                        <small class="text-muted">Jika tutup, customer akan melihat halaman maintenance</small>
                    </div>

                    <div class="form-group">
                        <label>Nominal Donasi (Rp)</label>
                        <input type="number" name="donation_amount" class="form-control" 
                               value="<?= $settings['donation_amount'] ?? '0' ?>" min="0" required>
                        <small class="text-muted">Donasi akan ditambahkan ke setiap produk</small>
                    </div>

                    <div class="form-group">
                        <label>Keterangan Donasi</label>
                        <textarea name="donation_description" class="form-control" rows="3" required><?= $settings['donation_description'] ?? 'Donasi untuk kegiatan sosial' ?></textarea>
                        <small class="text-muted">Keterangan ini akan ditampilkan di halaman customer</small>
                    </div>

                    <div class="form-group">
                        <label>WhatsApp Admin</label>
                        <input type="text" name="admin_whatsapp" class="form-control" 
                               value="<?= $settings['admin_whatsapp'] ?? '6281234567890' ?>" 
                               placeholder="6281234567890" required>
                        <small class="text-muted">Nomor WhatsApp admin untuk konfirmasi pembayaran (format: 6281234567890)</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>