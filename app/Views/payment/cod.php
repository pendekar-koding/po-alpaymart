<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran COD - Alpay Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #1e3a8a 0%, #8b5cf6 100%);
            min-height: 100vh;
        }
        .payment-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .cod-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .btn-home {
            background: linear-gradient(45deg, #007bff, #6610f2);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }
        .order-summary {
            background: rgba(30, 58, 138, 0.1);
            border-radius: 15px;
            padding: 1.5rem;
            border: 2px solid rgba(30, 58, 138, 0.2);
        }
        .cod-icon {
            font-size: 4rem;
            color: #28a745;
            margin-bottom: 1rem;
        }
        .btn-screenshot {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-screenshot:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.4);
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="payment-container">
            <div class="text-center mb-4">
                <h2 style="background: linear-gradient(45deg, #1e3a8a, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">
                    <i class="fas fa-money-bill me-2"></i>Pembayaran COD
                </h2>
                <p class="text-muted">Pesanan #<?= $order['order_number'] ?></p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="cod-container mb-4">
                        <i class="fas fa-handshake cod-icon"></i>
                        <h5 class="mb-3">Cash on Delivery (COD)</h5>
                        <p class="text-muted mb-4">Pembayaran dilakukan saat barang diterima</p>
                        
                        <div class="alert alert-success">
                            <h6><i class="fas fa-check-circle me-2"></i>Pesanan Berhasil Dibuat!</h6>
                            <p class="mb-0">Tim kami akan segera menghubungi Anda untuk konfirmasi.</p>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Informasi COD:</h6>
                        <ul class="mb-0">
                            <li>Siapkan uang pas sejumlah <strong>Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong></li>
                            <li>Periksa kondisi barang sebelum melakukan pembayaran</li>
                            <li>Tim kami akan menghubungi Anda via WhatsApp untuk konfirmasi</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="order-summary">
                        <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Detail Pesanan</h5>
                        
                        <div class="mb-3">
                            <strong>Data Pembeli:</strong><br>
                            <i class="fas fa-user me-1"></i> <?= $order['customer_name'] ?><br>
                            <i class="fas fa-phone me-1"></i> <?= $order['customer_whatsapp'] ?><br>
                            <i class="fas fa-building me-1"></i> <?= $division['nama_divisi'] ?? 'N/A' ?>
                        </div>

                        <div class="mb-3">
                            <strong>Item Pesanan:</strong>
                            <?php foreach ($orderItems as $item): ?>
                                <div class="mb-2 p-2" style="background: rgba(255,255,255,0.7); border-radius: 8px;">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><strong><?= $item['product_name'] ?></strong> - <?= $item['variant_name'] ?> (x<?= $item['quantity'] ?>)</span>
                                        <span><strong>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></strong></span>
                                    </div>
                                    <?php if (!empty($item['note'])): ?>
                                        <small class="text-muted"><i class="fas fa-sticky-note me-1"></i>Catatan: <?= esc($item['note']) ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mb-3">
                            <strong>Metode Pembayaran:</strong><br>
                            <span class="badge bg-success">Cash on Delivery (COD)</span>
                        </div>

                        <hr>
                        <?php if ($total_donation > 0): ?>
                        <div class="d-flex justify-content-between text-muted small">
                            <span><i class="fas fa-heart me-1"></i>Total Donasi:</span>
                            <span>Rp <?= number_format($total_donation, 0, ',', '.') ?></span>
                        </div>
                        <small class="text-muted d-block mb-2"><?= $donation_description ?></small>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between">
                            <strong>Total yang Harus Dibayar:</strong>
                            <strong style="color: #28a745; font-size: 1.2em;">Rp <?= number_format($order['total_amount'], 0, ',', '.') ?></strong>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button onclick="takeScreenshot()" class="btn btn-screenshot text-white me-2">
                            <i class="fas fa-camera me-2"></i>Screenshot
                        </button>
                        <a href="<?= base_url() ?>" class="btn btn-home text-white">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="<?= base_url('public/js/popup-alerts.js') ?>"></script>
    <script src="<?= base_url('public/js/loading-overlay.js') ?>"></script>
    <script>
        function takeScreenshot() {
            // Wait for popup to be initialized
            if (typeof popup === 'undefined') {
                setTimeout(takeScreenshot, 100);
                return;
            }
            popup.info('Sedang memproses screenshot...', 'Mohon Tunggu');
            html2canvas(document.querySelector('.payment-container')).then(canvas => {
                const link = document.createElement('a');
                link.download = 'COD-Pesanan-<?= $order['order_number'] ?>.png';
                link.href = canvas.toDataURL();
                link.click();
                popup.success('Screenshot berhasil diunduh!', 'Berhasil');
            }).catch(error => {
                popup.error('Gagal membuat screenshot. Silakan coba lagi.', 'Error');
            });
        }
    </script>
</body>
</html>