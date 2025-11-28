<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Alpay Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .checkout-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
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
        }
        .btn-back:hover {
            transform: scale(1.1);
        }
        .btn-submit {
            background: linear-gradient(45deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 1rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.5);
        }
        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .order-summary {
            background: rgba(102, 126, 234, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            border: 2px solid rgba(102, 126, 234, 0.2);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="checkout-container">
            <div class="d-flex align-items-center mb-4">
                <a href="<?= base_url('cart') ?>" class="btn btn-back me-3">
                    <i class="fas fa-arrow-left text-white"></i>
                </a>
                <h2 class="mb-0" style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">Checkout</h2>
            </div>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-3"><i class="fas fa-user me-2"></i>Data Pembeli</h5>
                    <form action="<?= base_url('checkout/process') ?>" method="post">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?= old('nama') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="whatsapp" class="form-label">Nomor WhatsApp *</label>
                            <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="<?= old('whatsapp') ?>" placeholder="08xxxxxxxxxx" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="division_id" class="form-label">Divisi *</label>
                            <select class="form-select" id="division_id" name="division_id" required>
                                <option value="">Pilih Divisi</option>
                                <?php foreach ($divisions as $division): ?>
                                    <option value="<?= $division['id'] ?>" <?= old('division_id') == $division['id'] ? 'selected' : '' ?>>
                                        <?= $division['nama_divisi'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Metode Pembayaran *</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check p-3" style="background: rgba(255,255,255,0.8); border-radius: 15px; border: 2px solid #e9ecef;">
                                        <input class="form-check-input" type="radio" name="payment_method" id="qris" value="QRIS" <?= old('payment_method') == 'QRIS' ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="qris">
                                            <i class="fas fa-qrcode me-2"></i>QRIS
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check p-3" style="background: rgba(255,255,255,0.8); border-radius: 15px; border: 2px solid #e9ecef;">
                                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" <?= old('payment_method') == 'COD' ? 'checked' : '' ?> required>
                                        <label class="form-check-label" for="cod">
                                            <i class="fas fa-money-bill me-2"></i>Cash on Delivery (COD)
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-submit text-white w-100">
                            <i class="fas fa-check me-2"></i>Konfirmasi Pesanan
                        </button>
                    </form>
                </div>
                
                <div class="col-md-4">
                    <div class="order-summary">
                        <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Ringkasan Pesanan</h5>
                        <?php foreach ($cart as $item): ?>
                            <div class="d-flex justify-content-between mb-2">
                                <span><?= $item['product_name'] ?? 'Produk' ?> - <?= $item['variant_name'] ?> (x<?= $item['quantity'] ?>)</span>
                                <span>Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong style="color: #28a745;">Rp <?= number_format($total, 0, ',', '.') ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>