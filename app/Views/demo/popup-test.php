<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Popup Alerts - Alpay Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }
        .test-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .btn-test {
            margin: 0.5rem;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-test:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="test-container">
            <h2 class="text-center mb-4" style="background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: bold;">
                <i class="fas fa-flask me-2"></i>Test Popup Alerts
            </h2>
            
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-info-circle me-2"></i>Basic Alerts</h5>
                    <button class="btn btn-success btn-test" onclick="popup.success('Operasi berhasil dilakukan!', 'Berhasil!')">
                        <i class="fas fa-check me-2"></i>Success Alert
                    </button>
                    <button class="btn btn-danger btn-test" onclick="popup.error('Terjadi kesalahan pada sistem!', 'Error!')">
                        <i class="fas fa-times me-2"></i>Error Alert
                    </button>
                    <button class="btn btn-warning btn-test" onclick="popup.warning('Harap periksa data Anda kembali!', 'Peringatan!')">
                        <i class="fas fa-exclamation-triangle me-2"></i>Warning Alert
                    </button>
                    <button class="btn btn-info btn-test" onclick="popup.info('Ini adalah informasi penting untuk Anda.', 'Informasi')">
                        <i class="fas fa-info me-2"></i>Info Alert
                    </button>
                </div>
                
                <div class="col-md-6">
                    <h5><i class="fas fa-question-circle me-2"></i>Confirmation</h5>
                    <button class="btn btn-primary btn-test" onclick="testConfirm()">
                        <i class="fas fa-question me-2"></i>Confirm Dialog
                    </button>
                    <button class="btn btn-secondary btn-test" onclick="testNativeAlert()">
                        <i class="fas fa-bell me-2"></i>Native Alert (Override)
                    </button>
                    <button class="btn btn-dark btn-test" onclick="testNativeConfirm()">
                        <i class="fas fa-question-circle me-2"></i>Native Confirm (Override)
                    </button>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row">
                <div class="col-12">
                    <h5><i class="fas fa-mobile-alt me-2"></i>Mobile Test</h5>
                    <p class="text-muted">Buka halaman ini di mobile untuk melihat optimasi mobile popup</p>
                    <button class="btn btn-gradient btn-test" style="background: linear-gradient(45deg, #ff6b6b, #ee5a24); color: white;" onclick="testLongMessage()">
                        <i class="fas fa-align-left me-2"></i>Long Message Test
                    </button>
                    <button class="btn btn-gradient btn-test" style="background: linear-gradient(45deg, #6c5ce7, #a29bfe); color: white;" onclick="testMultiplePopups()">
                        <i class="fas fa-layer-group me-2"></i>Multiple Popups
                    </button>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?= base_url() ?>" class="btn btn-outline-primary">
                    <i class="fas fa-home me-2"></i>Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('js/popup-alerts.js') ?>"></script>
    <script>
        async function testConfirm() {
            const result = await popup.confirm('Apakah Anda yakin ingin melanjutkan?', 'Konfirmasi Aksi');
            if (result) {
                popup.success('Anda memilih Ya!');
            } else {
                popup.info('Anda memilih Tidak!');
            }
        }
        
        function testNativeAlert() {
            alert('Ini adalah native alert yang sudah di-override menjadi popup!');
        }
        
        async function testNativeConfirm() {
            const result = await confirm('Ini adalah native confirm yang sudah di-override!');
            popup.info(result ? 'Anda pilih OK' : 'Anda pilih Cancel');
        }
        
        function testLongMessage() {
            popup.warning('Ini adalah pesan yang sangat panjang untuk menguji bagaimana popup menangani teks yang panjang. Pesan ini dibuat khusus untuk melihat responsivitas popup di berbagai ukuran layar, terutama di mobile device. Apakah popup masih terlihat bagus dan mudah dibaca?', 'Pesan Panjang');
        }
        
        async function testMultiplePopups() {
            popup.info('Popup pertama');
            setTimeout(() => {
                popup.warning('Popup kedua setelah 2 detik');
            }, 2000);
            setTimeout(() => {
                popup.success('Popup ketiga setelah 4 detik');
            }, 4000);
        }
    </script>
</body>
</html>