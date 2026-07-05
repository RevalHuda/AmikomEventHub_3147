# Setup Midtrans - Panduan Lengkap

## Langkah 1: Dapatkan Kredensial Midtrans

1. Buka https://dashboard.midtrans.com
2. Jika belum punya akun, daftar terlebih dahulu
3. Login ke dashboard
4. Pilih Environment: **SANDBOX** (untuk testing)
5. Pergi ke **Settings → API Keys**
6. Copy:
   - **Server Key**: `SB-Mid-server-xxxx...`
   - **Client Key**: `SB-Mid-client-xxxx...`

## Langkah 2: Update .env

Edit file `.env` di root project:

```
MIDTRANS_SERVER_KEY="SB-Mid-server-xxxx_xxxxxxxxxxxx"
MIDTRANS_CLIENT_KEY="SB-Mid-client-xxxx_xxxxxxxxxxxx"
MIDTRANS_IS_PRODUCTION=false
```

Kemudian jalankan:
```
php artisan config:clear
```

## Langkah 3: Setup Webhook di Midtrans Dashboard

1. Login ke https://dashboard.midtrans.com
2. Pergi ke **Settings → Notification/Webhooks**
3. Pada kolom **HTTP POST URL**, masukkan:
   ```
   http://localhost:8000/notification
   ```
   (atau URL produksi jika sudah live: `https://yourdomain.com/notification`)
4. Pastikan checkbox **Active** sudah dicentang
5. Klik **Save**

## Langkah 4: Testing Pembayaran

1. Buka halaman checkout event
2. Isi data pelanggan, klik "Lanjut ke Pembayaran"
3. Klik tombol "Bayar Sekarang"
4. Snap Midtrans popup akan muncul
5. Pilih metode pembayaran (untuk testing, bisa gunakan test payment methods)
6. Selesaikan pembayaran
7. Otomatis redirect ke halaman success
8. Status transaksi akan berubah menjadi "success"

## Langkah 5: Testing Manual (Jika Webhook Belum Berfungsi)

Akses URL ini untuk manual update status (HANYA UNTUK TESTING):
```
http://localhost:8000/test/update-status/TRX-xxxx
```

Ganti `TRX-xxxx` dengan Order ID transaksi yang ingin di-update.

## Catatan Penting

- **Sandbox Mode**: Gunakan untuk testing (MIDTRANS_IS_PRODUCTION=false)
- **Production Mode**: Ketika siap live, ubah ke MIDTRANS_IS_PRODUCTION=true dan gunakan Production Keys
- **Security**: Jangan commit .env dengan keys ke repository public!
- **Webhook URL**: Harus accessible dari internet (tidak bisa localhost di production)

## Test Payment Methods (Sandbox)

Untuk testing pembayaran di sandbox, gunakan test cards:
- **Virtual Account Success**: Gunakan nomor VA yang diberikan Midtrans
- **Credit Card Success**: 4811111111111114 (exp: 12/25, CVV: 123)
- **Transfer Bank**: Sesuai instruksi di Midtrans Snap

---

Jika ada pertanyaan atau error, cek log di `storage/logs/`
