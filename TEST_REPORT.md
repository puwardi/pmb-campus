# Laporan Akhir PMB CAMPUS

Tanggal pengujian: 20 September 2026. Project dibuat dan diuji hanya secara lokal.

## Lokasi dan ruang lingkup

Lokasi: `C:\Users\Lenovo\Documents\Project_BSDW\pmb-campus`.

PHP Native, HTML, satu CSS utama, MySQL/MariaDB XAMPP. Tidak ada framework atau JavaScript aplikasi. BUNKO SPACE tidak diubah, dipindahkan, atau dihapus. Tidak ada perintah Git, GitHub, upload, publish, atau deployment.

## Struktur final

```text
pmb-campus/
  admin/
    applicant_add.php
    applicant_delete.php
    applicant_edit.php
    applicants.php
    dashboard.php
    login.php
    logout.php
    question_add.php
    question_delete.php
    question_edit.php
    questions.php
    re_registration.php
    results.php
  assets/
    css/
      style.css
  auth/
    login.php
    logout.php
    register.php
  config/
    database.php
  database/
    pmb_campus.sql
  includes/
    admin_applicant_form.php
    admin_question_form.php
    applicant_fields.php
    footer.php
    header.php
    init.php
    login_form.php
  index.php
  PROJECT_GUIDE.md
  README.md
  user/
    dashboard.php
    profile.php
    re_registration.php
    test_result.php
    test.php
  TEST_REPORT.md
```

## Database

Database `pmb_campus` berhasil diimport menggunakan SQL yang tersedia di `database/pmb_campus.sql`.

- `users`: akun, email unik, password hash, role.
- `applicants`: biodata, nomor test, hasil, status daftar ulang, NIM.
- `test_questions`: soal pilihan ganda dan kunci.
- `test_answers`: jawaban serta salinan pertanyaan/kunci untuk menjaga riwayat.

Foreign key users → applicants → test_answers memakai ON DELETE CASCADE. Kondisi akhir database: 1 admin, 10 soal, 0 pendaftar, 0 jawaban. Akun pengujian sudah dihapus; ID berikutnya dapat melompat karena AUTO_INCREMENT tidak direset.

## Halaman dan alur

User: register, login, logout, dashboard, profil, test, hasil test, daftar ulang. Alur: register → login → nomor test → test → hasil → jika lulus, konfirmasi daftar ulang → NIM. Nilai di bawah 70 tidak mendapat akses daftar ulang atau NIM.

Admin: login/logout, dashboard ringkasan, daftar/tambah/edit/hapus calon mahasiswa, daftar/tambah/edit/hapus soal, hasil test dengan filter lulus/tidak lulus, daftar ulang beserta NIM. Alur: login → pantau ringkasan → kelola pendaftar/soal → pantau hasil dan daftar ulang.

## Cara menjalankan

1. Jalankan MySQL melalui XAMPP Control Panel. Database sudah diimport pada komputer ini.
2. Buka PowerShell dan jalankan:

```powershell
Set-Location 'C:\Users\Lenovo\Documents\Project_BSDW\pmb-campus'
& 'C:\xampp\php\php.exe' -S 127.0.0.1:8000
```

3. Buka http://127.0.0.1:8000. Server pengujian sementara dihentikan setelah pengujian; perintah tersebut menjalankannya kembali.
4. Admin: http://127.0.0.1:8000/admin/login.php — email **admin@pmbcampus.local**, password **Admin123!**.

README memuat cara import melalui phpMyAdmin, alternatif Apache, dan solusi izin folder session. Password admin dalam SQL sudah berupa hasil password_hash().

## Hasil pengujian

- **PHP syntax: 30/30 PASS**, seluruh file diperiksa dengan C:\xampp\php\php.exe -l.
- **100 pemeriksaan integrasi HTTP + database: PASS**. Setiap sesi memakai cookie sendiri dan token form asli. Status redirect, halaman hasil, serta data tersimpan diverifikasi.
- User: registrasi, duplicate email, login/logout, nomor test, dashboard/profil, 10 soal, jawaban wajib, simpan jawaban, nilai 100/70/60, hasil lulus/tidak lulus, test satu kali, daftar ulang, NIM, pengiriman ulang.
- Admin: login/logout, seluruh halaman, tambah/edit/hapus calon mahasiswa dan soal, filter hasil, daftar ulang, password tetap saat kolom edit kosong.
- Keamanan dasar: role, session tamu, CSRF kosong, ID invalid/tidak ditemukan, input kosong, output XSS di-escape, penghapusan melalui POST.
- Database: insert/update/delete, transaksi, duplicate email, foreign key/cascade, NIM belum tersedia sebelum daftar ulang.
- Browser: halaman depan desktop, registrasi pada viewport 390 × 844, login dan dashboard admin diperiksa secara visual. Tidak terlihat masalah layout pada halaman yang diperiksa.
- Log runtime setelah perbaikan: tidak ada Warning, Fatal error, atau Parse error selama pengujian terakhir.

## Masalah yang ditemukan dan diperbaiki

1. **Redirect Windows:** dirname() pada root menghasilkan backslash. BASE_URL sekarang menormalisasi backslash menjadi slash sebelum membentuk redirect.
2. **Izin session lingkungan pengujian:** proses sandbox tidak dapat menulis session di C:\xampp\tmp. Server pengujian memakai folder session yang dapat ditulis lewat opsi PHP -d; konfigurasi global XAMPP tidak diubah.
3. **Alat pengujian:** pemisah baris CRLF keluaran MySQL mengganggu pembacaan kunci jawaban; parser alat uji diperbaiki, lalu pengujian diulang sampai seluruhnya lulus.

Tidak ada error aplikasi yang diketahui masih tersisa dari cakupan pengujian ini. Pengujian dilakukan pada server PHP built-in dan database XAMPP lokal; mode Apache dijelaskan sebagai alternatif tetapi tidak menjadi lingkungan pengujian utama.

## File yang dipelajari untuk presentasi

- `PROJECT_GUIDE.md`: 16 bagian penjelasan dan urutan demonstrasi.
- `config/database.php`: koneksi PDO dan **PASSING_SCORE = 70**.
- `includes/init.php`: session, role, registrasi, prepared statements, nomor test.
- `includes/login_form.php`: password_verify dan login.
- `user/test.php`: snapshot soal, penyimpanan jawaban, rumus nilai dan status.
- `user/re_registration.php`: syarat lulus, konfirmasi, generate NIM.
- `includes/admin_applicant_form.php` dan `includes/admin_question_form.php`: CRUD sederhana.
- `database/pmb_campus.sql`: schema, relasi, sample soal, hash admin.

Nomor test memakai TEST-tahun-ID, NIM memakai tahun+ID pendaftar. Angka bisa melompat setelah penghapusan. Test hanya sekali; soal disalin ke session agar perubahan admin tidak mengubah test yang sedang dibuka. Tidak ada timer, pembayaran, atau upload dokumen.

```text
PROJECT PMB CAMPUS
Development: PASS
Database: PASS
User Features: PASS
Admin Features: PASS
PHP Syntax: PASS
Local Testing: PASS
Git Commit: NOT PERFORMED
GitHub Push: NOT PERFORMED
Deployment: NOT PERFORMED
```

## Bukti pemeriksaan integrasi

```text
PASS: Landing page HTTP 200 tanpa PHP warning
PASS: Tamu diarahkan ke login user
PASS: Tamu diarahkan ke login admin
PASS: Registrasi menolak CSRF kosong
PASS: Validasi email server
PASS: Validasi input kosong
PASS: Password salah ditolak
PASS: Admin login password_verify
PASS: Role admin tidak masuk halaman user
PASS: Lulus register
PASS: Lulus duplicate email ditangani
PASS: Lulus login
PASS: Lulus nomor test otomatis, NIM belum tersedia
PASS: Lulus user tidak dapat mengakses admin
PASS: Lulus profil tampil
PASS: Lulus belum test tidak boleh daftar ulang
PASS: Lulus 10 soal tampil
PASS: Lulus jawaban kosong ditolak
PASS: Lulus submit test
PASS: Lulus nilai/status benar, NIM tetap kosong
PASS: Lulus jawaban tersimpan
PASS: Lulus hasil test tampil
PASS: Lulus test tidak dapat diulang
PASS: Lulus wajib konfirmasi daftar ulang
PASS: Lulus daftar ulang berhasil
PASS: Lulus NIM dibuat
PASS: Lulus daftar ulang berulang NIM tetap
PASS: Lulus logout
PASS: Lulus session logout ditolak
PASS: Batas70 register
PASS: Batas70 duplicate email ditangani
PASS: Batas70 login
PASS: Batas70 nomor test otomatis, NIM belum tersedia
PASS: Batas70 user tidak dapat mengakses admin
PASS: Batas70 profil tampil
PASS: Batas70 belum test tidak boleh daftar ulang
PASS: Batas70 10 soal tampil
PASS: Batas70 jawaban kosong ditolak
PASS: Batas70 submit test
PASS: Batas70 nilai/status benar, NIM tetap kosong
PASS: Batas70 jawaban tersimpan
PASS: Batas70 hasil test tampil
PASS: Batas70 test tidak dapat diulang
PASS: Batas70 wajib konfirmasi daftar ulang
PASS: Batas70 daftar ulang berhasil
PASS: Batas70 NIM dibuat
PASS: Batas70 daftar ulang berulang NIM tetap
PASS: Batas70 logout
PASS: Batas70 session logout ditolak
PASS: Gagal60 register
PASS: Gagal60 duplicate email ditangani
PASS: Gagal60 login
PASS: Gagal60 nomor test otomatis, NIM belum tersedia
PASS: Gagal60 user tidak dapat mengakses admin
PASS: Gagal60 profil tampil
PASS: Gagal60 belum test tidak boleh daftar ulang
PASS: Gagal60 10 soal tampil
PASS: Gagal60 jawaban kosong ditolak
PASS: Gagal60 submit test
PASS: Gagal60 nilai/status benar, NIM tetap kosong
PASS: Gagal60 jawaban tersimpan
PASS: Gagal60 hasil test tampil
PASS: Gagal60 test tidak dapat diulang
PASS: Peserta tidak lulus ditolak daftar ulang dan tidak memperoleh NIM
PASS: Gagal60 logout
PASS: Gagal60 session logout ditolak
PASS: Halaman admin dashboard
PASS: Halaman admin applicants
PASS: Halaman admin results
PASS: Halaman admin results.php?status=passed
PASS: Halaman admin results.php?status=failed
PASS: Halaman admin re_registration
PASS: Halaman admin questions
PASS: Filter hasil lulus
PASS: Filter hasil tidak lulus
PASS: List daftar ulang hanya yang sudah konfirmasi
PASS: Admin tambah calon mahasiswa
PASS: Output nama di-escape untuk mencegah XSS
PASS: Admin update biodata, password kosong dipertahankan
PASS: Password lama masih berfungsi setelah edit
PASS: Edit duplicate email ditolak
PASS: ID nonnumerik ditolak
PASS: ID tidak ditemukan ditangani
PASS: Admin tambah soal
PASS: Admin update soal
PASS: Update kunci tersimpan
PASS: GET hapus soal hanya konfirmasi
PASS: Hapus soal tanpa CSRF ditolak
PASS: Admin hapus soal
PASS: Admin hapus akun uji lulus@example.test
PASS: Relasi jawaban cascade bersih 10
PASS: Admin hapus akun uji batas70@example.test
PASS: Relasi jawaban cascade bersih 11
PASS: Admin hapus akun uji gagal60@example.test
PASS: Relasi jawaban cascade bersih 12
PASS: Admin hapus akun uji crud@example.test
PASS: Relasi jawaban cascade bersih 13
PASS: Admin logout
PASS: Session admin berakhir
PASS: Data uji dibersihkan: 1 admin dan 10 soal tetap tersedia
TOTAL 100 PASS
```
