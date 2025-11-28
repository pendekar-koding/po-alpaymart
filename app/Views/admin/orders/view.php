<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Detail Pesanan #<?= $order['order_number'] ?></h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <h5>Informasi Customer</h5>
            <table class="table table-borderless">
              <tr>
                <td><strong>Nama:</strong></td>
                <td><?= $order['customer_name'] ?></td>
              </tr>
              <tr>
                <td><strong>WhatsApp:</strong></td>
                <td><?= $order['customer_whatsapp'] ?></td>
              </tr>
              <tr>
                <td><strong>Divisi:</strong></td>
                <td><?= $order['nama_divisi'] ?? '-' ?></td>
              </tr>
              <tr>
                <td><strong>Metode Pembayaran:</strong></td>
                <td><?= $order['payment_method'] ?></td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <h5>Informasi Pesanan</h5>
            <table class="table table-borderless">
              <tr>
                <td><strong>Total:</strong></td>
                <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
              </tr>
              <tr>
                <td><strong>Status:</strong></td>
                <td>
                  <span class="badge badge-<?= $order['status'] == 'pending' ? 'warning' : ($order['status'] == 'delivered' ? 'success' : 'info') ?>">
                    <?= ucfirst($order['status']) ?>
                  </span>
                </td>
              </tr>
              <tr>
                <td><strong>Tanggal:</strong></td>
                <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
              </tr>
            </table>
          </div>
        </div>

        <h5>Item Pesanan</h5>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Produk</th>
              <th>Qty</th>
              <th>Harga</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($order_items as $item): ?>
            <tr>
              <td><?= $item->product_name ?> - <?= $item->variant_name ?></td>
              <td><?= $item->quantity ?></td>
              <td>Rp <?= number_format($item->price, 0, ',', '.') ?></td>
              <td>Rp <?= number_format($item->subtotal, 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div class="mt-3">
          <form action="<?= base_url('admin/orders/update-status/' . $order['id']) ?>" method="post" class="d-inline">
            <select name="status" class="form-control d-inline" style="width: auto;">
              <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
              <option value="confirmed" <?= $order['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
              <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
              <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-primary">Update Status</button>
          </form>
          <a href="<?= base_url('admin/orders') ?>" class="btn btn-secondary">Kembali</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>