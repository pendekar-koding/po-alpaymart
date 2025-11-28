<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alpay Mart - Online Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .welcome-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .catalog-card, .product-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
        }
        .catalog-card:hover, .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .section-title {
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            font-weight: 600;
        }
        .btn-download {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        .btn-download:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        .btn-detail {
            background: linear-gradient(45deg, #007bff, #6610f2);
            border: none;
            border-radius: 25px;
            transition: all 0.3s ease;
        }
        .btn-detail:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 123, 255, 0.4);
        }
        .floating-cart {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
            transition: all 0.3s ease;
        }
        .floating-cart:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(255, 107, 107, 0.6);
        }
    </style>
</head>
<body>
    <!-- Floating Download Button -->
    <?php if (!empty($catalogs)): ?>
    <a href="<?= base_url('catalog/download/' . $catalogs[0]['id']) ?>" class="btn btn-download" style="position: fixed; bottom: 150px; right: 20px; z-index: 1000; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);">
        <i class="fas fa-download fa-lg text-white"></i>
    </a>
    <?php endif; ?>
    
    <!-- Floating Cart Button -->
    <a href="<?= base_url('cart') ?>" class="btn floating-cart" style="position: fixed; bottom: 80px; right: 20px; z-index: 1000; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
        <i class="fas fa-shopping-cart fa-lg text-white"></i>
        <?php if ($cart_count > 0): ?>
        <span class="badge" style="position: absolute; top: -5px; right: -5px; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 12px; background: #ffd700; color: #333; font-weight: bold;"><?= $cart_count ?></span>
        <?php endif; ?>
    </a>

    <div class="container py-4" style="padding-bottom: 80px;">
        <!-- Welcome Section -->
        <div class="text-center mb-5 welcome-section">
            <h1 class="display-4 mb-3" style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">Selamat Datang di ALPAYMART</h1>
            <p class="lead" style="color: #6c757d; font-weight: 500;">Berbelanja sekaligus berdonasi</p>
            
            <!-- Search Form -->
            <form method="GET" action="<?= base_url() ?>" class="mt-4">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Cari produk atau nama toko..." value="<?= $search ?? '' ?>" style="border-radius: 25px 0 0 25px; border: 2px solid #667eea;">
                            <button class="btn" type="submit" style="background: linear-gradient(45deg, #667eea, #764ba2); border: 2px solid #667eea; border-radius: 0 25px 25px 0; color: white;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
            <?php if (!empty($search)): ?>
            <div class="mt-3">
                <span class="badge" style="background: linear-gradient(45deg, #667eea, #764ba2); color: white; padding: 0.5rem 1rem; border-radius: 15px;">
                    Hasil pencarian: "<?= htmlspecialchars($search) ?>"
                </span>
                <a href="<?= base_url() ?>" class="btn btn-sm btn-outline-secondary ms-2" style="border-radius: 15px;">Reset</a>
            </div>
            <?php endif; ?>
        </div>
        


        <!-- Products Section -->
        <h2 class="mb-4 section-title"><i class="fas fa-shopping-bag me-2"></i>Produk Tersedia</h2>
        <div class="row">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 product-card">
                        <div class="card-body">
                            <h5 class="card-title" style="color: #495057; font-weight: 600;"><?= $product['name'] ?></h5>
                            <p class="card-text" style="color: #6c757d;"><?= $product['description'] ?? '' ?></p>
                            <?php if ($product['variant_count'] > 0): ?>
                                <?php if ($product['min_price'] == $product['max_price']): ?>
                                    <p class="card-text"><strong style="color: #28a745; font-size: 1.1em;">Rp <?= number_format($product['min_price'], 0, ',', '.') ?></strong></p>
                                <?php else: ?>
                                    <p class="card-text"><strong style="color: #28a745; font-size: 1.1em;">Rp <?= number_format($product['min_price'], 0, ',', '.') ?> - Rp <?= number_format($product['max_price'], 0, ',', '.') ?></strong></p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <p class="card-text">
                                <small style="color: #6c757d;"><i class="fas fa-store me-1"></i>Toko: <?= $product['shop_name'] ?? 'Tidak diketahui' ?></small>
                            </p>
                        </div>
                        <div class="card-footer" style="background: transparent; border: none;">
                            <a href="<?= base_url('product/' . $product['id']) ?>" class="btn btn-detail text-white btn-sm w-100">
                                <i class="fas fa-eye me-2"></i>Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <?php if (!empty($search)): ?>
                            <i class="fas fa-search"></i> Tidak ada produk yang ditemukan untuk "<?= htmlspecialchars($search) ?>".
                            <br><a href="<?= base_url() ?>" class="btn btn-primary mt-2">Lihat Semua Produk</a>
                        <?php else: ?>
                            <i class="fas fa-info-circle"></i> Belum ada produk yang tersedia.
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white py-3" style="position: fixed; bottom: 0; width: 100%; z-index: 999; background: linear-gradient(45deg, #667eea, #764ba2);">
        <div class="container">
            <div class="text-center">
                <p class="mb-0" style="font-weight: 500;">&copy; 2025 Alpaymart. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>