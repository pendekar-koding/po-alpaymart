<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Daftar Pesanan</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Customer</th>
              <th>Total</th>
              <th>Status</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
              <td><?= $order->id ?></td>
              <td><?= $order->customer_name ?></td>
              <td>Rp <?= number_format($order->total_amount, 0, ',', '.') ?></td>
              <td>
                <span class="badge badge-<?= $order->status == 'pending' ? 'warning' : ($order->status == 'delivered' ? 'success' : 'info') ?>">
                  <?= ucfirst($order->status) ?>
                </span>
              </td>
              <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
              <td>
                <a href="<?= base_url('admin/orders/view/' . $order->id) ?>" class="btn btn-info btn-sm">
                  <i class="fas fa-eye"></i> Detail
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>