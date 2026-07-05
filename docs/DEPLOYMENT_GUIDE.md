# Panduan Deploy Laravel ke Shared Hosting via Git

Gunakan panduan ini untuk meng-online-kan aplikasi ERP Pesantren ke Shared Hosting (cPanel) memanfaatkan Git Version Control agar proses *update* ke depannya mudah.

## 1. Persiapan di Lokal (Komputer Lo)
Pastikan kode lo udah beres dan *build* aset *frontend* (CSS/JS) udah dijalankan.

1. Buka terminal, jalankan perintah *build* aset:
   ```bash
   npm run build
   ```
2. Pastikan file `.gitignore` tidak memblokir folder `public/build/` (karena hasil *build* Vite wajib ikut ter-push ke hosting yang biasanya nggak punya Node.js).
3. Commit dan push semua perubahan ke Github/Gitlab lo:
   ```bash
   git add .
   git commit -m "Siap deploy ke production"
   git push origin main
   ```

## 2. Setup Database di cPanel
1. Login ke cPanel hosting lo.
2. Buka menu **MySQL® Databases**.
3. Bikin *Database* baru (contoh: `pesantre_erp`).
4. Bikin *User* baru beserta *Password*-nya.
5. Tambahkan *User* tersebut ke *Database* (Add User to Database) dan centang **ALL PRIVILEGES**.

## 3. Clone Repo via Git Version Control cPanel
1. Di cPanel, cari menu **Git™ Version Control**.
2. Klik tombol **Create**.
3. Matikan "Clone a Repository" (atau biarkan jika clone dari eksternal). Masukkan URL *Clone* Github lo (kalau repo *private*, lo harus *setup* SSH key cPanel ke Github lo dulu).
4. Di bagian **Repository Path**, isi dengan folder di luar `public_html` biar lebih aman (contoh: `/repositories/erp-pesantren`).
5. Klik **Create** dan tunggu proses *clone* selesai.

## 4. Setup Environment (.env)
1. Buka menu **File Manager** di cPanel.
2. Masuk ke folder `/repositories/erp-pesantren`.
3. Copy file `.env.example` menjadi `.env`.
4. Edit file `.env`:
   - Ubah `APP_ENV=production`
   - Ubah `APP_DEBUG=false`
   - Ubah `APP_URL=https://domain-lo.com`
   - Masukkan kredensial database (DB_DATABASE, DB_USERNAME, DB_PASSWORD) sesuai yang lo bikin di langkah 2.

## 5. Install Dependencies (Vendor)
1. Buka menu **Terminal** di cPanel.
2. Masuk ke folder repo:
   ```bash
   cd repositories/erp-pesantren
   ```
3. Install *package* PHP:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
4. Generate App Key dan Migrate Database:
   ```bash
   php artisan key:generate
   php artisan migrate --force
   ```
5. *(Opsional)* Lo bisa jalanin Seeder atau Script bikin *User Demo* langsung di terminal cPanel.

## 6. Menghubungkan Folder Public ke Domain Utama (Symlink)
Shared hosting biasanya membaca folder `public_html` sebagai web root, padahal Laravel membaca folder `public`.
Lo harus "mengaitkan" folder `public_html` ke folder `public` aplikasi lo.

1. Pastikan folder `public_html` lo **KOSONG** (hapus isinya, atau kalau gak mau dihapus, backup dulu). Jika `public_html` nggak kosong, hapus foldernya sekalian dari File Manager.
2. Di Terminal cPanel, jalankan perintah Symlink ini (sesuaikan path-nya):
   ```bash
   ln -s /home/username_cpanel_lo/repositories/erp-pesantren/public /home/username_cpanel_lo/public_html
   ```
3. **Catatan:** Ganti `username_cpanel_lo` dengan username asli cPanel lo (biasanya kelihatan di path sebelah kiri terminal).

## 7. Optimasi Terakhir
Di Terminal cPanel, jalankan perintah ini biar aplikasi jalan ngebut:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Cara Update kalau ada Perubahan Kode Baru
Kalau besok-besok lo benerin *bug* atau nambah fitur di lokal:
1. `npm run build` di komputer lokal, lalu `git push`.
2. Masuk ke cPanel -> **Git™ Version Control**.
3. Klik tombol **Manage** di repo lo, terus klik tab **Pull or Deploy**.
4. Klik **Update from Remote**. Selesai! (Hosting lo otomatis narik kode terbaru).
5. (Jangan lupa jalankan `php artisan migrate --force` via Terminal cPanel kalau ada migrasi database baru).
