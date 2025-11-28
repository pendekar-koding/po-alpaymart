<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3><?= $total_products ?></h3>
        <p>Total Produk</p>
      </div>
      <div class="icon">
        <i class="fas fa-box"></i>
      </div>
      <a href="<?= base_url('admin/products') ?>" class="small-box-footer">
        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3><?= $total_orders ?></h3>
        <p>Total Pesanan</p>
      </div>
      <div class="icon">
        <i class="fas fa-shopping-cart"></i>
      </div>
      <a href="<?= base_url('admin/orders') ?>" class="small-box-footer">
        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Pesanan Terbaru</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Pembayaran</th>
              <th>Status</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recent_orders as $order): ?>
            <tr>
              <td><?= $order['id'] ?></td>
              <td><?= $order['customer_name'] ?></td>
              <td>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></td>
              <td><span class="badge badge-<?= $order['payment_method'] == 'QRIS' ? 'primary' : 'success' ?>"><?= $order['payment_method'] ?></span></td>
              <td>
                <span class="badge badge-<?= $order['status'] == 'pending' ? 'warning' : ($order['status'] == 'delivered' ? 'success' : 'info') ?>">
                  <?= ucfirst($order['status']) ?>
                </span>
              </td>
              <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>