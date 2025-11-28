<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $product['name'] ?> - Alpay Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .product-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .variant-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }
        .variant-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .btn-back {
            background: linear-gradient(45deg, #6c757d, #495057);
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }
        .btn-back:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.5);
        }
        .btn-add-cart {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.5);
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
    <!-- Floating Cart Button -->
    <a href="<?= base_url('cart') ?>" class="btn btn-primary" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; border-radius: 50%; width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <?php if ($cart_count > 0): ?>
        <span class="badge bg-danger" style="position: absolute; top: -5px; right: -5px; border-radius: 50%; width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; font-size: 12px;"><?= $cart_count ?></span>
        <?php endif; ?>
    </a>

    <div class="container py-4">
        <div class="product-container">
            <div class="d-flex align-items-center mb-4">
                <a href="<?= base_url() ?>" class="btn btn-back me-3">
                    <i class="fas fa-arrow-left text-white"></i>
                </a>
                <h1 class="mb-0" style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;"><?= $product['name'] ?></h1>
            </div>
            
            <div class="row">
                <div class="col-12">
                <h1><?= $product['name'] ?></h1>
                <p class="text-muted mb-3">Toko: <?= $product['shop_name'] ?? 'Tidak diketahui' ?></p>
                
                <?php if ($product['description']): ?>
                <div class="mb-4">
                    <h5>Deskripsi</h5>
                    <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                </div>
                <?php endif; ?>

                <div class="mb-4">
                    <h5>Pilih Varian</h5>
                    <?php if (!empty($variants)): ?>
                        <?php foreach ($variants as $variant): ?>
                        <div class="card mb-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= $variant['variant_name'] ?></h6>
                                        <small class="text-muted">SKU: <?= $variant['sku'] ?? '-' ?></small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-primary">Rp <?= number_format($variant['price'], 0, ',', '.') ?></div>
                                        <small class="text-muted">Stok: <?= $variant['stock'] ?></small>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <?php if ($variant['stock'] > 0): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="number" class="form-control form-control-sm" style="width: 80px;" value="1" min="1" max="<?= $variant['stock'] ?>" id="qty-<?= $variant['id'] ?>">
                                        <button class="btn btn-primary btn-sm" onclick="addToCart(<?= $variant['id'] ?>)">
                                            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
                                        </button>
                                    </div>
                                    <?php else: ?>
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        Stok Habis
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> Produk ini belum memiliki varian yang tersedia.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mt-4">
                    <a href="<?= base_url() ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function addToCart(variantId) {
        const quantity = document.getElementById('qty-' + variantId).value;
        
        fetch('<?= base_url('cart/add') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'variant_id=' + variantId + '&quantity=' + quantity
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Produk berhasil ditambahkan ke keranjang!');
                location.reload(); // Refresh untuk update cart count
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan saat menambahkan ke keranjang');
        });
    }
    </script>
</body>
</html>