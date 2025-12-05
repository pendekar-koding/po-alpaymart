<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <?php if (session()->get('role') === 'admin'): ?>
        <h3><?= $total_products ?></h3>
        <p>Total Produk</p>
        <?php else: ?>
        <h3><?= $total_sold ?? 0 ?></h3>
        <p>Barang Terjual</p>
        <?php endif; ?>
      </div>
      <div class="icon">
        <?php if (session()->get('role') === 'admin'): ?>
        <i class="fas fa-box"></i>
        <?php else: ?>
        <i class="fas fa-chart-line"></i>
        <?php endif; ?>
      </div>
      <?php if (session()->get('role') === 'admin'): ?>
      <a href="<?= base_url('admin/products') ?>" class="small-box-footer">
        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
      </a>
      <?php else: ?>
      <div class="small-box-footer" style="height: 40px;"></div>
      <?php endif; ?>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <?php if (session()->get('role') === 'admin'): ?>
        <h3><?= $total_orders ?></h3>
        <p>Total Pesanan</p>
        <?php else: ?>
        <h3>Rp <?= number_format($total_sales ?? 0, 0, ',', '.') ?></h3>
        <p>Total Pendapatan</p>
        <?php endif; ?>
      </div>
      <div class="icon">
        <?php if (session()->get('role') === 'admin'): ?>
        <i class="fas fa-shopping-cart"></i>
        <?php else: ?>
        <i class="fas fa-money-bill-wave"></i>
        <?php endif; ?>
      </div>
      <?php if (session()->get('role') === 'admin'): ?>
      <a href="<?= base_url('admin/orders') ?>" class="small-box-footer">
        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
      </a>
      <?php else: ?>
      <div class="small-box-footer" style="height: 40px;"></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if (session()->get('role') === 'admin'): ?>
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
<?php else: ?>
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="card-title">Analisis Penjualan Produk</h3>
          <a href="<?= base_url('admin/orders/export-seller-excel') ?>" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Laporan Pesanan
          </a>
        </div>
      </div>
      <div class="card-body">
        <?php if (!empty($sales_data)): ?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Ranking</th>
                <th>Produk</th>
                <th>Varian</th>
                <th>Total Terjual</th>
                <th>Total Pendapatan</th>
                <th>Popularitas</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sales_data as $index => $item): ?>
              <tr>
                <td>
                  <?php if ($index == 0): ?>
                    <span class="badge badge-warning"><i class="fas fa-trophy"></i> #1</span>
                  <?php elseif ($index == 1): ?>
                    <span class="badge badge-secondary"><i class="fas fa-medal"></i> #2</span>
                  <?php elseif ($index == 2): ?>
                    <span class="badge badge-info"><i class="fas fa-award"></i> #3</span>
                  <?php else: ?>
                    <span class="badge badge-light">#<?= $index + 1 ?></span>
                  <?php endif; ?>
                </td>
                <td><strong><?= $item['product_name'] ?></strong></td>
                <td><?= $item['variant_name'] ?></td>
                <td>
                  <span class="badge badge-success"><?= $item['total_sold'] ?> unit</span>
                </td>
                <td>Rp <?= number_format($item['total_revenue'], 0, ',', '.') ?></td>
                <td>
                  <div class="progress" style="height: 20px;">
                    <div class="progress-bar bg-primary" style="width: <?= ($item['total_sold'] / $sales_data[0]['total_sold']) * 100 ?>%">
                      <?= round(($item['total_sold'] / $sales_data[0]['total_sold']) * 100, 1) ?>%
                    </div>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php else: ?>
        <div class="text-center">
          <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
          <p class="text-muted">Belum ada data penjualan</p>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>