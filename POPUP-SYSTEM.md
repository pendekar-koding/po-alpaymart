# Sistem Popup Alert - Alpay Mart

## Overview
Sistem popup alert yang menggantikan alert, confirm, dan prompt standar browser dengan popup yang lebih menarik dan mobile-friendly.

## Fitur
- ✅ Desain modern dengan gradient dan animasi
- ✅ Responsive dan mobile-optimized
- ✅ Auto-override native alert/confirm
- ✅ Support dark mode dan high contrast
- ✅ Touch-friendly untuk mobile
- ✅ Animasi smooth dengan backdrop blur

## File yang Diubah

### 1. JavaScript Core
- `public/js/popup-alerts.js` - Sistem popup utama
- `public/css/mobile-popup.css` - Optimasi mobile

### 2. View Files yang Diupdate
- `app/Views/shop/cart.php` - Konfirmasi hapus item
- `app/Views/shop/product.php` - Notifikasi tambah ke keranjang
- `app/Views/payment/qris.php` - Notifikasi screenshot
- `app/Views/payment/cod.php` - Notifikasi screenshot
- `app/Views/admin/layout.php` - Include popup system
- `app/Views/admin/catalogs/index.php` - Konfirmasi hapus
- `app/Views/admin/products/index.php` - Konfirmasi hapus
- `app/Views/admin/divisions/index.php` - Konfirmasi hapus
- `app/Views/admin/users/index.php` - Konfirmasi hapus

### 3. Demo Page
- `app/Views/demo/popup-test.php` - Halaman test semua popup

## Cara Penggunaan

### Include di HTML
```html
<script src="<?= base_url('js/popup-alerts.js') ?>"></script>
```

### Method yang Tersedia
```javascript
// Basic alerts
popup.success('Pesan sukses', 'Judul');
popup.error('Pesan error', 'Judul');
popup.warning('Pesan peringatan', 'Judul');
popup.info('Pesan info', 'Judul');

// Confirmation
const result = await popup.confirm('Yakin hapus?', 'Konfirmasi');
if (result) {
    // User clicked Ya
}

// Native override (otomatis)
alert('Pesan'); // Akan menjadi popup
const confirmed = await confirm('Yakin?'); // Akan menjadi popup
```

### Contoh Implementasi
```javascript
// Konfirmasi delete
async function confirmDelete(event, itemName) {
    event.preventDefault();
    const confirmed = await popup.confirm(`Yakin ingin menghapus ${itemName}?`, 'Konfirmasi Hapus');
    if (confirmed) {
        window.location.href = event.target.href;
    }
    return false;
}

// Notifikasi AJAX
fetch('/api/data')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            popup.success('Data berhasil disimpan!');
        } else {
            popup.error(data.message);
        }
    })
    .catch(error => {
        popup.error('Terjadi kesalahan sistem');
    });
```

## Mobile Optimizations
- Popup width 95% di mobile
- Touch-friendly button size (min 44px)
- Stacked buttons di layar kecil
- Reduced motion support
- High contrast mode support
- Dark mode support

## Browser Support
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+
- Mobile browsers (iOS Safari, Chrome Mobile)

## Customization
Edit `popup-alerts.js` untuk mengubah:
- Warna gradient
- Durasi animasi
- Ukuran popup
- Icon yang digunakan

Edit `mobile-popup.css` untuk mengubah:
- Responsive breakpoints
- Mobile-specific styling
- Dark mode colors