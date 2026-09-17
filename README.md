# Website E-Commerce Coffe Ayzel

Projek e-commerce ini dibangun dengan arsitektur terpisah (decoupled), menggunakan **CodeIgniter 4 (CI4)** sebagai backend API dan **React + Vite + Tailwind CSS** sebagai frontend.

---

## 💻 Tech Stack

**Backend:**
* CodeIgniter 4 (PHP)

**Frontend:**
* React
* Vite
* Tailwind CSS
* JavaScript

---

## 📁 Struktur Repositori

Repositori ini terdiri dari dua bagian utama:
* `backend-api-coffe-ayzel/` : Berisi kode sumber untuk REST API backend menggunakan CodeIgniter 4.
* `frontend-coffee-ayzel/` : Berisi antarmuka pengguna (UI) yang dibangun dengan React dan Vite.

---

## 🚀 Cara Menjalankan Projek Secara Lokal

Pastikan Anda telah menginstal **PHP**, **Composer**, dan **Node.js** di sistem Anda sebelum memulai.

### 1. Clone Repositori
```bash
git clone [https://github.com/febrytriharkasa/Website-E-Commerce-Coffe-Ayzel.git](https://github.com/febrytriharkasa/Website-E-Commerce-Coffe-Ayzel.git)

cd Website-E-Commerce-Coffe-Ayzel
```

### 2. Setup Backend (CodeIgniter 4)
```bash
# Masuk ke direktori backend
cd backend-api-coffe-ayzel

# Instal dependensi PHP
composer install

# Salin file environment dan sesuaikan konfigurasi database (jika ada)
cp env .env

# Jalankan server lokal CI4
php spark serve
```

### 3. Setup Frontend (React + Vite)

Buka terminal baru untuk menjalankan frontend secara bersamaan.
```bash
# Masuk ke direktori frontend
cd frontend-coffee-ayzel

# Instal dependensi Node.js
npm install

# Jalankan server pengembangan Vite
npm run dev
```
## 👨‍💻 Kontributor

Febry Tri Harkasa
