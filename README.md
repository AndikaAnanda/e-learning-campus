E-Learning Kampus — Laravel API

Aplikasi backend **E-Learning Kampus** berbasis REST API yang dibangun menggunakan Laravel. Mendukung autentikasi berbasis token, manajemen mata kuliah, upload materi, sistem tugas & penilaian, forum diskusi real-time, serta laporan statistik.


Tech Stack

| Layer | Teknologi |
|---|---|
| Framework | Laravel 10+ |
| Auth | Laravel Sanctum |
| Database | MySQL / PostgreSQL |
| File Storage | Laravel Storage (local/public) |
| WebSocket | Laravel Reverb |
| API Testing | Postman |
| Versioning | Git |

---

## 🚀 Fitur Utama

### 1. 🔐 Autentikasi Pengguna
- Registrasi sebagai **Mahasiswa** atau **Dosen**
- Login & logout menggunakan token via **Laravel Sanctum**
- Role-based access control (middleware per role)

### 2. 📚 Manajemen Mata Kuliah
- Dosen dapat membuat, mengedit, dan menghapus mata kuliah
- Mahasiswa dapat mendaftar (enroll) ke mata kuliah
- Relasi: `Dosen hasMany Courses`, `Mahasiswa belongsToMany Courses`
- Soft delete pada data mata kuliah

### 3. 📂 Upload & Unduh Materi
- Dosen dapat mengupload file materi perkuliahan
- Mahasiswa dapat mengunduh materi
- File disimpan menggunakan **Laravel Storage**

### 4. 📝 Tugas & Penilaian
- Dosen dapat membuat tugas dengan deadline
- Mahasiswa dapat mengunggah jawaban/submission
- Dosen dapat memberikan nilai pada submission
- Notifikasi email otomatis saat tugas baru dibuat atau nilai diberikan

### 5. 💬 Forum Diskusi (Real-Time)
- Mahasiswa & Dosen dapat membuat diskusi per mata kuliah
- Mendukung balasan (replies) pada diskusi
- Live chat menggunakan **Laravel Reverb** (WebSocket)

### 6. 📊 Laporan & Statistik
- Statistik jumlah mahasiswa per mata kuliah
- Statistik tugas yang sudah/belum dinilai
- Statistik tugas dan nilai per mahasiswa
- Menggunakan Eloquent Aggregates (`count`, `avg`, `sum`, dll.)

---

## 📡 API Documentation

Dokumentasi lengkap endpoint tersedia di Postman:

👉 **[Lihat Dokumentasi API](https://documenter.getpostman.com/view/24755913/2sBXcDH2Zt)**

---

## 🔗 Daftar Endpoint

### Auth
| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/register` | Registrasi pengguna |
| POST | `/login` | Login & dapatkan token |
| POST | `/logout` | Logout & revoke token |

### Mata Kuliah
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/courses` | Tampilkan semua mata kuliah |
| POST | `/courses` | Buat mata kuliah baru (Dosen) |
| PUT | `/courses/{id}` | Edit mata kuliah (Dosen) |
| DELETE | `/courses/{id}` | Hapus mata kuliah (Dosen) |
| POST | `/courses/{id}/enroll` | Daftar ke mata kuliah (Mahasiswa) |

### Materi
| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/materials` | Upload materi (Dosen) |
| GET | `/materials/{id}/download` | Unduh materi (Mahasiswa) |

### Tugas & Penilaian
| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/assignments` | Buat tugas (Dosen) |
| POST | `/submissions` | Submit jawaban (Mahasiswa) |
| POST | `/submissions/{id}/grade` | Beri nilai (Dosen) |

### Forum Diskusi
| Method | Endpoint | Deskripsi |
|---|---|---|
| POST | `/api/discussions` | Buat diskusi |
| POST | `/api/discussions/{id}/replies` | Balas diskusi |

### Laporan
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/reports/courses` | Statistik mahasiswa per mata kuliah |
| GET | `/reports/assignments` | Statistik tugas dinilai/belum |
| GET | `/reports/students/{id}` | Statistik nilai mahasiswa |

---

## ⚙️ Instalasi & Konfigurasi

```bash
# Clone repository
git clone <https://github.com/AndikaAnanda/e-learning-campus.git>
cd e-learning-campus

# Install dependencies
composer install

# Salin file environment
cp .env.example .env

# Generate app key
php artisan key:generate

# Konfigurasi database di .env, lalu jalankan migrasi
php artisan migrate

# Jalankan server
php artisan serve

# Jalankan Laravel Reverb (WebSocket)
php artisan reverb:start
```

---

## 🗃️ Struktur Database

- `users` — data pengguna (mahasiswa & dosen)
- `courses` — mata kuliah
- `course_user` — pivot table enroll mahasiswa
- `materials` — materi perkuliahan
- `assignments` — tugas
- `submissions` — jawaban mahasiswa
- `discussions` — forum diskusi
- `replies` — balasan diskusi

> Semua tabel utama menggunakan **Soft Delete**.

---

## 📬 Notifikasi Email

Email otomatis dikirim ke mahasiswa yang terdaftar saat:
- Dosen membuat tugas baru
- Dosen memberikan nilai pada submission

---

## 📁 Catatan Tambahan

- Semua endpoint yang membutuhkan autentikasi menggunakan header `Authorization: Bearer {token}`
- File upload disimpan di direktori `storage/app/public`
- WebSocket channel menggunakan **Laravel Reverb** untuk forum diskusi real-time
