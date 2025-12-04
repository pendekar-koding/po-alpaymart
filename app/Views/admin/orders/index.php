<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="card-title">Daftar Pesanan</h3>
          <?php if (session()->get('role') === 'admin' && !empty($orders)): ?>
          <a href="<?= base_url('admin/orders/delete-all') ?>" 
             class="btn btn-danger" 
             onclick="return confirmDelete(event, 'SEMUA pesanan')">
            <i class="fas fa-trash-alt"></i> Hapus Semua
          </a>
          <?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <div class="row mb-3">
          <div class="col-md-6">
            <form method="GET" action="<?= base_url('admin/orders') ?>">
              <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan nomor order, nama customer, atau WhatsApp..." value="<?= $search ?? '' ?>">
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Cari
                  </button>
                  <?php if (!empty($search)): ?>
                  <a href="<?= base_url('admin/orders') ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Reset
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            </form>
          </div>
        </div>
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No. Order</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Pembayaran</th>
              <th>Status</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
              <td><?= $order['order_number'] ?></td>
              <td><?= $order['customer_name'] ?></td>
              <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
              <td><span class="badge badge-<?= $order['payment_method'] == 'QRIS' ? 'primary' : 'success' ?>"><?= $order['payment_method'] ?></span></td>
              <td>
                <span class="badge badge-<?= $order['status'] == 'pending' ? 'warning' : ($order['status'] == 'confirmed' ? 'success' : 'info') ?>">
                  <?= ucfirst($order['status']) ?>
                </span>
              </td>
              <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
              <td>
                <a href="<?= base_url('admin/orders/view/' . $order['id']) ?>" class="btn btn-info btn-sm">
                  <i class="fas fa-eye"></i> Detail
                </a>
                <?php if ($order['status'] !== 'confirmed'): ?>
                <button class="btn btn-success btn-sm" onclick="confirmStatusChange(<?= $order['id'] ?>, 'confirmed')">
                  <i class="fas fa-check"></i> Konfirmasi
                </button>
                <?php endif; ?>
                <?php if (session()->get('role') === 'admin'): ?>
                <a href="<?= base_url('admin/orders/delete/' . $order['id']) ?>" 
                   class="btn btn-danger btn-sm" 
                   onclick="return confirmDelete(event, 'pesanan ini')">
                  <i class="fas fa-trash"></i> Hapus
                </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
async function confirmStatusChange(orderId, status) {
    const confirmed = await popup.confirm('Yakin ingin mengkonfirmasi pesanan ini?', 'Konfirmasi Status');
    if (confirmed) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= base_url('admin/orders/update-status/') ?>' + orderId;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        form.appendChild(statusInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?= $this->endSection() ?>