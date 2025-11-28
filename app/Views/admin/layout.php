<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Admin Panel' ?> - Alpay Mart</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('/') ?>" target="_blank">
          <i class="fas fa-external-link-alt"></i> Lihat Toko
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('admin/logout') ?>">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?= base_url('admin') ?>" class="brand-link">
      <span class="brand-text font-weight-light"><b>Alpay</b>Mart</span>
    </a>

    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="<?= base_url('admin') ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('admin/products') ?>" class="nav-link">
              <i class="nav-icon fas fa-box"></i>
              <p>Produk</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?= base_url('admin/orders') ?>" class="nav-link">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>Pesanan</p>
            </a>
          </li>
          <?php if (session()->get('role') === 'admin'): ?>
          <li class="nav-item">
            <a href="<?= base_url('admin/catalogs') ?>" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>Katalog</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('admin/users') ?>" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Manajemen User</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('admin/divisions') ?>" class="nav-link">
              <i class="nav-icon fas fa-sitemap"></i>
              <p>Divisi</p>
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0"><?= $title ?? 'Dashboard' ?></h1>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?= session()->getFlashdata('success') ?>
          </div>
        <?php endif; ?>
        
        <?= $this->renderSection('content') ?>
      </div>
    </section>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>