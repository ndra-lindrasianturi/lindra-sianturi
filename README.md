<<<<<<< HEAD
# lindra-sianturi
lindra(hadir)
=======
# Final Project Pemrograman Web

## Deskripsi
Tetap Semangat, Bambang Supratman
1. Uray (Hadir)
2. Jauhari (Hadir)
3. Amelia (Hadir)
4. Lindra (Hadir)

## Prasyarat
Sebelum memulai, pastikan memiliki hal-hal berikut:
1. **Apache dan PHP**:
   - PHP >= 7.4 (PHP 8 disarankan)
   - Apache Web Server
2. **Database (Opsional)**:
   - Jika menggunakan database (misalnya MySQL atau SQLite), pastikan untuk mengonfigurasi koneksi database di file `core/Connection.php`.
3. **XAMPP**: Gunakan XAMPP sebagai lingkungan pengembangan.

## Instalasi

### 1. Clone Repository
Pertama, klon repositori ini ke direktori lokal:
```bash
git clone https://github.com/UrayMR/fppemweb.git
```

### 2. Konfigurasi Virtual Host (Jika menggunakan XAMPP)
1. Buka file `httpd-vhosts.conf` yang terletak di folder `C:/xampp/apache/conf/extra/httpd-vhosts.conf`.
2. Tambahkan konfigurasi berikut:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/FPPemweb/public"
       ServerName fppemweb.test
       <Directory "C:/xampp/htdocs/FPPemweb/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
3. Buka file `hosts` di `C:/Windows/System32/drivers/etc/hosts` dan tambahkan apabila belum ada:
   ```txt
   127.0.0.1   fppemweb.test
   ```

### 3. Restart Apache
Jika menggunakan XAMPP, restart Apache agar perubahan konfigurasi diterapkan.

## Menjalankan Aplikasi

1. Jalankan server Apache.
2. Akses aplikasi melalui:
   ```
   http://fppemweb.test/
   ```

## Struktur Folder
```
FPPemweb/
├── app/                     # Kontroler dan Model
│   ├── Controllers/         # Kontroler untuk Auth, Admin, dan Mandor
│   ├── Models/              # Representasi data dari database
├── core/                    # File inti aplikasi
│   ├── App.php
│   ├── Connection.php
│   └── Middleware.php
├── routes/                  # Routing aplikasi
│   └── web.php
├── views/                   # Folder tampilan aplikasi
│   ├── pages/               # Halaman utama aplikasi
│   ├── layouts/             # Layout dasar
│   └── components/          # Komponen UI reusable
├── public/                  # Folder publik
│   └── .htaccess            # Konfigurasi untuk Apache
│   └── index.php            # Entry point aplikasi
└── .gitignore               # Mengabaikan file yang tidak perlu di-commit
```

## Kontribusi
1. Clone repositori ini.
2. Buat branch baru:
   ```bash
   git checkout -b namaanda
   ```
3. Lakukan perubahan pada fitur yang ingin ditambahkan atau diperbaiki.
4. Simpan semua perubahan:
   ```bash
   git add .
   ```
5. Commit perubahan:
   ```bash
   git commit -m 'Menambahkan fitur XYZ'
   ```
6. Push ke branch kamu:
   ```bash
   git push origin namaanda
   ```
7. Minta pemilik repositori untuk menggabungkan perubahan kamu.

> **Note**: Selalu tarik perubahan terbaru dari branch utama sebelum memulai:
> ```bash
> git pull origin main
> ```

## Pertanyaan
Jika ada pertanyaan lebih lanjut, jangan ragu untuk membuka [issue](https://github.com/UrayMR/fppemweb/issues) atau bertanya di sini.

>>>>>>> 81ffccc (Initial commit)
