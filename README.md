# CloudBox - Secure Self-Hosted Cloud Storage ☁️

CloudBox adalah platform penyimpanan awan (Cloud Storage) berbasis web yang modern, cepat, dan aman. Dibangun menggunakan **Laravel 12**, **Livewire 3**, dan **Tailwind CSS**, aplikasi ini menawarkan pengalaman manajemen file layaknya Google Drive atau Cloudreve.

## ✨ Fitur Utama

* **Penyimpanan**: Kelola file dan folder dengan struktur hirarki yang rapi.
* **Chunk Upload (500MB+)**: Sistem unggah file besar dengan memecah file menjadi bagian-bagian kecil untuk stabilitas upload.
* **Preview Multi-Format**: Preview langsung untuk Foto, Video (MP4/WebM), Musik (MP3/WAV), dan Dokumen PDF.
* **Breadcrumbs Dinamis**: Navigasi hirarki folder yang memudahkan pelacakan posisi file.
* **Recycle Bin**: Sistem penghapusan sementara (Soft Delete) selama 30 hari sebelum dihapus permanen.
* **Berbagi File & Folder**: Buat link publik untuk berbagi file atau seluruh isi folder secara instan.
* **Klasifikasi Otomatis**: Sidebar yang mengelompokkan file berdasarkan tipe (Foto, Video, Dokumen, Musik).
* **Sistem Kuota**: Batasan penyimpanan per user (Default 1GB).
* **Dashboard Admin**: Kelola pengguna dan pantau penggunaan storage secara global.
* **Pencarian Global**: Cari file di folder mana pun secara instan.

---

## 📸 Preview
<img width="1311" height="768" alt="Image" src="https://github.com/user-attachments/assets/2c7a2bc5-dccd-42e9-bfc5-bc2f9a0a5579" />

<img width="1311" height="768" alt="Image" src="https://github.com/user-attachments/assets/2dc02366-6f31-4403-b2be-5eee88864874" />

<img width="1311" height="768" alt="Image" src="https://github.com/user-attachments/assets/94c618dc-af4c-4836-adef-9438a171e0d9" />

<img width="1345" height="769" alt="Image" src="https://github.com/user-attachments/assets/b8b027d0-c04e-48ed-b6a7-ee794f8251b5" />
---

## 🛠️ Tech Stack

* **Framework**: [Laravel 12](https://laravel.com)
* **Frontend**: [Livewire 3](https://livewire.laravel.com) & [Tailwind CSS](https://tailwindcss.com)
* **Database**: MySQL / PostgreSQL
* **Authentication**: Laravel Breeze
* **Icons**: Heroicons & Lucide Icons

---

## 🚀 Panduan Instalasi (Setup Project)

Ikuti langkah-langkah di bawah ini untuk menjalankan CloudBox di mesin lokal.

### 1. Prasyarat

* PHP >= 8.2
* Composer
* Node.js & NPM
* MySQL atau database lainnya

### 2. Clone Repository

```bash
git clone https://github.com/filipusarif/CloudBox-Laravel.git
cd CloudBox-Laravel

```

### 3. Instalasi Dependensi

```bash
composer install
npm install

```

### 4. Konfigurasi Environment

Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database.

```bash
cp .env.example .env

```

Jangan lupa untuk mengatur folder penyimpanan:

```env
APP_NAME=CloudBox
DB_DATABASE=nama_database

# Konfigurasi Disk (Jika menggunakan disk khusus)
FILESYSTEM_DISK=private

```

### 5. Generate Application Key & Link Storage

```bash
php artisan key:generate
php artisan storage:link

```

### 6. Migrasi Database

```bash
php artisan migrate
php artisan db:seed

```

### 7. Jalankan Aplikasi

Buka dua terminal berbeda:
**Terminal 1 (PHP Server):**

```bash
php artisan serve

```

**Terminal 2 (Asset Compiler):**

```bash
npm run dev

```

---

## ⚙️ Konfigurasi Penting (Upload 500MB)

Agar fitur upload file besar berjalan lancar, pastikan menyesuaikan konfigurasi server:

**PHP.ini:**

```ini
upload_max_filesize = 500M
post_max_size = 510M
memory_limit = 512M
max_execution_time = 600

```

**Nginx (Jika menggunakan):**

```nginx
client_max_body_size 500M;

```

---


## 📄 Lisensi

Didistribusikan di bawah Lisensi MIT. Lihat `LICENSE` untuk informasi lebih lanjut.

---

**Made On Earth By Human**

---
