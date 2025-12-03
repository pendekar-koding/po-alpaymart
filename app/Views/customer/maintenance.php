
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Sedang Tutup - Alpay Mart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #1e3a8a 0%, #8b5cf6 100%); min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 text-center">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <i class="fas fa-store-slash fa-5x text-warning mb-4"></i>
                        <h2 class="mb-3">Website Sedang Tutup</h2>
                        <p class="text-muted mb-4">
                            Maaf, toko online kami sedang tutup sementara. 
                            Silakan kembali lagi nanti.
                        </p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Untuk informasi lebih lanjut, hubungi admin Alpaymart.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/<?= $admin_whatsapp ?>?text=Halo%20Admin%20Alpaymart,%20saya%20ingin%20bertanya" 
       class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <style>
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 20px;
            right: 20px;
            background-color: #25d366;
            color: white;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        .whatsapp-float:hover {
            background-color: #128c7e;
            color: white;
            transform: scale(1.1);
        }
    </style>
</body>
</html>