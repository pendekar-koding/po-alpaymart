<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpay Mart - Online Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-store"></i> Alpay Mart
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('shop') ?>">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('cart') ?>">
                            <i class="fas fa-shopping-cart"></i> Cart
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1 class="display-4">Selamat Datang di Alpay Mart</h1>
                    <p class="lead">Temukan produk terbaik dengan harga terjangkau</p>
                    <a href="<?= base_url('shop') ?>" class="btn btn-primary btn-lg">Mulai Belanja</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Produk Terbaru</h2>
        <div class="row">
            <?php foreach ($products as $product): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $product['name'] ?></h5>
                        <p class="card-text"><?= substr($product['description'] ?? '', 0, 100) ?>...</p>
                        <p class="card-text">
                            <small class="text-muted">Toko: <?= $product['shop_name'] ?? 'Tidak diketahui' ?></small>
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="<?= base_url('shop/product/' . $product['id']) ?>" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Alpay Mart</h5>
                    <p>Online shop terpercaya dengan produk berkualitas</p>
                </div>
                <div class="col-md-6">
                    <h5>Kontak</h5>
                    <p>Email: info@alpaymart.com<br>
                       Phone: +62 123 456 789</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2024 Alpay Mart. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>