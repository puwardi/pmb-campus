# PMB CAMPUS

Aplikasi Pendaftaran Mahasiswa Baru untuk Project/Soal 2 USK SMK PPLG. Dibuat dengan HTML, CSS, PHP Native, MySQL/MariaDB, XAMPP dan phpMyAdmin; tanpa framework dan tanpa JavaScript karena seluruh alur cukup memakai form HTML.

## Aktor dan fitur

- **Admin:** login/logout, ringkasan pendaftaran, CRUD calon mahasiswa, CRUD soal pilihan ganda, semua pendaftar, hasil test (filter lulus/tidak lulus), serta daftar ulang dan NIM.
- **Calon mahasiswa:** registrasi, login/logout, dashboard, nomor test otomatis, profil, satu kali test, hasil otomatis, daftar ulang jika lulus, dan NIM.

## Struktur folder

~~~text
pmb-campus/
├── admin/          # Login, dashboard, CRUD, monitoring, logout
├── auth/           # Login, register, logout calon mahasiswa
├── user/           # Dashboard, test, hasil, daftar ulang, profil
├── assets/css/     # style.css (satu CSS utama)
├── config/         # database.php dan PASSING_SCORE = 70
├── database/       # pmb_campus.sql
├── includes/       # Session, form bersama, navbar, footer
├── index.php
├── README.md
├── PROJECT_GUIDE.md
└── TEST_REPORT.md
~~~

## Import database

1. Jalankan **MySQL** dan **Apache** melalui XAMPP Control Panel.
2. Buka http://localhost/phpmyadmin, pilih **Import**, lalu pilih `database/pmb_campus.sql` dan klik **Go/Kirim**.
3. SQL membuat database `pmb_campus`, empat tabel, satu admin, dan 10 soal. Import hanya sekali pada database baru; SQL tidak menghapus tabel yang ada.
4. Periksa `config/database.php`: host `127.0.0.1`, user `root`, password kosong. Sesuaikan jika MySQL lokal memakai password.

Database pada komputer ini sudah diimport dan diuji; tidak perlu import ulang.

## Menjalankan project

Jalankan MySQL XAMPP. Buka PowerShell, lalu:

~~~powershell
Set-Location 'C:\Users\Lenovo\Documents\Project_BSDW\pmb-campus'
& 'C:\xampp\php\php.exe' -S 127.0.0.1:8000
~~~

Buka http://127.0.0.1:8000. Jika port 8000 sedang digunakan, pakai 8001 dan sesuaikan alamat. Hentikan server terminal dengan Ctrl+C. Apache tidak diperlukan untuk PHP built-in server; Apache diperlukan jika membuka phpMyAdmin.

Alternatif: salin folder project ke `C:\xampp\htdocs\pmb-campus`, jalankan Apache + MySQL, lalu buka http://localhost/pmb-campus/. Folder asli tetap dapat disimpan sebagai sumber utama.

Jika muncul error izin session di XAMPP, jalankan server dengan folder session lokal di luar folder web:

~~~powershell
$sessionDir = Join-Path $env:TEMP 'pmb-campus-sessions'
New-Item -ItemType Directory -Path $sessionDir -Force | Out-Null
& 'C:\xampp\php\php.exe' -d "session.save_path=$sessionDir" -S 127.0.0.1:8000
~~~

## Akun admin development

- Halaman: http://127.0.0.1:8000/admin/login.php
- Email: `admin@pmbcampus.local`
- Password: `Admin123!`
- Database menyimpan hash dari `password_hash()`, bukan plaintext.

## Alur aplikasi

**User:** register → login → nomor test → mengerjakan test → hasil → jika nilai ≥ 70, daftar ulang → NIM. Peserta tidak lulus tetap dapat melihat hasil; tidak boleh daftar ulang atau mendapat NIM.

**Admin:** login → dashboard → kelola pendaftar/soal → pantau hasil dan daftar ulang → logout.

Nilai = jawaban benar ÷ jumlah soal × 100. Konstanta `PASSING_SCORE` mudah ditemukan di `config/database.php`. Nomor mengikuti tahun server dan ID pendaftar; nomor dapat melompat setelah data dihapus. Baca `PROJECT_GUIDE.md` untuk presentasi dan `TEST_REPORT.md` untuk hasil pengujian lokal.
