<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - Alpay Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #8b5cf6 100%);
            min-height: 100vh;
        }
        .cart-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .cart-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
        .btn-checkout {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 1rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.5);
        }
        .btn-shop {
            background: linear-gradient(45deg, #007bff, #6610f2);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-shop:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }
        .empty-cart {
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .cart-item-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .cart-item-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        @media (max-width: 768px) {
            .cart-table { display: none; }
            .cart-cards { display: block; }
        }
        @media (min-width: 769px) {
            .cart-table { display: block; }
            .cart-cards { display: none; }
        }
    </style>
</head>
<body>


    <div class="container py-4">
        <div class="cart-container">
            <div class="d-flex align-items-center mb-4">
                <a href="<?= base_url() ?>" class="btn btn-back me-3">
                    <i class="fas fa-arrow-left text-white"></i>
                </a>
                <h2 class="mb-0" style="background: linear-gradient(45deg, #1e3a8a, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">Keranjang Belanja</h2>
            </div>
        
        <?php if (!empty($cart)): ?>
            <!-- Desktop Table View -->
            <div class="table-responsive cart-table">
                <table class="table table-hover mb-0">
                    <thead style="background: linear-gradient(45deg, #1e3a8a, #8b5cf6); color: white;">
                        <tr>
                            <th style="border: none; padding: 1rem;"><i class="fas fa-box me-2"></i>Produk</th>
                            <th style="border: none; padding: 1rem;"><i class="fas fa-tags me-2"></i>Varian</th>
                            <th style="border: none; padding: 1rem;"><i class="fas fa-tag me-2"></i>Harga</th>
                            <th style="border: none; padding: 1rem;"><i class="fas fa-sort-numeric-up me-2"></i>Qty</th>
                            <th style="border: none; padding: 1rem;"><i class="fas fa-calculator me-2"></i>Subtotal</th>
                            <th style="border: none; padding: 1rem;"><i class="fas fa-cog me-2"></i>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($cart as $item): 
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td style="padding: 1rem; font-weight: 600; color: #495057;"><?= $item['product_name'] ?? 'Produk' ?></td>
                            <td style="padding: 1rem; color: #6c757d;"><?= $item['variant_name'] ?></td>
                            <td style="padding: 1rem; color: #28a745; font-weight: 500;">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                            <td style="padding: 1rem;">
                                <span class="badge" style="background: linear-gradient(45deg, #1e3a8a, #8b5cf6); padding: 0.5rem 1rem; border-radius: 15px;"><?= $item['quantity'] ?></span>
                            </td>
                            <td style="padding: 1rem; color: #28a745; font-weight: 600; font-size: 1.1em;">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            <td style="padding: 1rem;">
                                <button class="btn btn-sm btn-danger" onclick="removeFromCart(<?= $item['variant_id'] ?>)" title="Hapus dari keranjang">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot style="background: rgba(30, 58, 138, 0.1);">
                        <tr>
                            <th colspan="5" style="padding: 1.5rem; font-size: 1.2em; color: #495057;">Total Pembayaran</th>
                            <th style="padding: 1.5rem; font-size: 1.3em; color: #28a745; font-weight: bold;">Rp <?= number_format($total, 0, ',', '.') ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Mobile Card View -->
            <div class="cart-cards">
                <?php 
                $total = 0;
                foreach ($cart as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total += $subtotal;
                ?>
                <div class="cart-item-card">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-0" style="color: #495057; font-weight: 600;"><?= $item['product_name'] ?? 'Produk' ?></h6>
                            <small class="text-muted"><?= $item['variant_name'] ?></small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background: linear-gradient(45deg, #1e3a8a, #8b5cf6); padding: 0.4rem 0.8rem; border-radius: 12px;">x<?= $item['quantity'] ?></span>
                            <button class="btn btn-sm btn-danger" onclick="removeFromCart(<?= $item['variant_id'] ?>)" title="Hapus dari keranjang">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <small class="text-muted"><i class="fas fa-tag me-1"></i>Harga Satuan</small>
                            <div style="color: #28a745; font-weight: 500;">Rp <?= number_format($item['price'], 0, ',', '.') ?></div>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted"><i class="fas fa-calculator me-1"></i>Subtotal</small>
                            <div style="color: #28a745; font-weight: 600; font-size: 1.1em;">Rp <?= number_format($subtotal, 0, ',', '.') ?></div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <!-- Total Card -->
                <div class="cart-item-card" style="background: linear-gradient(45deg, rgba(30, 58, 138, 0.1), rgba(139, 92, 246, 0.1)); border: 2px solid rgba(30, 58, 138, 0.2);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" style="color: #495057; font-weight: bold;"><i class="fas fa-receipt me-2"></i>Total Pembayaran</h5>
                        <h4 class="mb-0" style="color: #28a745; font-weight: bold;">Rp <?= number_format($total, 0, ',', '.') ?></h4>
                    </div>
                </div>
            </div>
            
            <?php if ($donation_amount > 0): ?>
            <div class="alert alert-info mt-4">
                <i class="fas fa-heart me-2"></i>
                <strong>Info Donasi:</strong> Harga sudah termasuk donasi sebesar Rp <?= number_format($donation_amount, 0, ',', '.') ?> per produk untuk <?= $donation_description ?>
            </div>
            <?php endif; ?>
            
            <div class="text-end mt-4">
                <a href="<?= base_url('checkout') ?>" class="btn btn-checkout text-white" data-loading>
                    <i class="fas fa-credit-card me-2"></i>Checkout Sekarang
                </a>
            </div>
        <?php else: ?>
            <div class="empty-cart text-center">
                <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #6c757d; margin-bottom: 1rem;"></i>
                <h4 style="color: #495057; margin-bottom: 1rem;">Keranjang Belanja Kosong</h4>
                <p style="color: #6c757d; margin-bottom: 2rem;">Yuk mulai berbelanja dan temukan produk menarik!</p>
                <a href="<?= base_url() ?>" class="btn btn-shop text-white">
                    <i class="fas fa-shopping-bag me-2"></i>Mulai Belanja
                </a>
            </div>
        <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('public/js/popup-alerts.js') ?>"></script>
    <script src="<?= base_url('public/js/loading-overlay.js') ?>"></script>
    <script>
        async function removeFromCart(variantId) {
            // Wait for popup to be initialized
            if (typeof popup === 'undefined') {
                setTimeout(() => removeFromCart(variantId), 100);
                return;
            }
            const confirmed = await popup.confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?', 'Konfirmasi Hapus');
            if (confirmed) {
                showLoading();
                fetch('<?= base_url('cart/remove') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: 'variant_id=' + variantId
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        popup.success('Produk berhasil dihapus dari keranjang!');
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        popup.error(data.message);
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    popup.error('Terjadi kesalahan saat menghapus produk');
                });
            }
        }
    </script>
</body>
</html>