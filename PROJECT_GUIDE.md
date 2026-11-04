# Panduan Presentasi PMB CAMPUS

## 1. Cara kerja database

Database bernama **pmb_campus**, terdiri dari empat tabel:

| Tabel | Fungsi |
|---|---|
| users | Nama, email unik, hash password, role admin/user |
| applicants | Biodata, nomor test, nilai, status kelulusan, daftar ulang, NIM |
| test_questions | Bank soal, empat pilihan, dan kunci jawaban |
| test_answers | Jawaban peserta dan salinan pertanyaan/kunci pada saat test |

Satu akun user memiliki satu biodata. `applicants.user_id` mengarah ke `users.id`. Satu pendaftar memiliki banyak jawaban; `test_answers.applicant_id` mengarah ke `applicants.id`. Foreign key dengan ON DELETE CASCADE menghapus biodata dan jawaban ketika admin menghapus akun pendaftar. Akun admin tidak memiliki biodata pendaftar.

Jawaban menyimpan salinan teks pertanyaan/kunci, bukan foreign key ke bank soal. Ini penyesuaian sederhana agar riwayat test tidak rusak ketika soal diedit atau dihapus. Tidak dibutuhkan tabel tambahan.

## 2. Cara registrasi

Buka `auth/register.php`. Form meminta nama, email, telepon, asal sekolah, alamat, dan password. Validasi server memeriksa kolom kosong, panjang data, email valid, dan password 8–72 karakter. Email dinormalisasi menjadi huruf kecil dan memiliki UNIQUE di database.

Fungsi `create_applicant()` di `includes/init.php` memasukkan akun dan biodata dengan prepared statement. Password disimpan memakai `password_hash()`. Transaksi membuat kedua insert berhasil bersama atau dibatalkan bersama. Email duplikat diberi pesan yang jelas. Registrasi berhasil mengarahkan ke login.

## 3. Cara login

`auth/login.php` menentukan role user; `admin/login.php` menentukan role admin. Keduanya memakai `includes/login_form.php`. Query mencari email dan role, kemudian `password_verify()` mencocokkan password dengan hash database. Setelah cocok, ID session diperbarui dengan `session_regenerate_id(true)` dan pengguna diarahkan ke dashboard sesuai role.

## 4. Cara session bekerja

`session_start()` membuka session. Server menyimpan `user_id`, `role`, token CSRF, dan sementara salinan soal. Browser hanya menerima ID session. `require_role()` memeriksa login, role, dan keberadaan akun setiap membuka halaman privat. User tidak dapat masuk halaman admin hanya dengan mengetik URL.

Logout dikirim melalui POST dengan token CSRF; session dikosongkan dan dihancurkan. CSRF adalah token acak yang memastikan form berasal dari session yang sama. Output data memakai `e()`, pembungkus `htmlspecialchars()`, agar input dianggap teks biasa.

## 5. Cara nomor test dibuat

Setelah insert biodata, aplikasi mengambil ID AUTO_INCREMENT melalui `lastInsertId()`. Formatnya:

~~~php
$number = 'TEST-' . date('Y') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
~~~

Contoh ID 1 pada tahun 2026 menjadi TEST-2026-0001. ID database unik sehingga nomor tidak bentrok walaupun pendaftar mendaftar hampir bersamaan. Nomor yang terhapus tidak dipakai ulang, jadi urutan dapat memiliki celah. Tahun mengikuti server.

## 6. Cara soal diambil

`user/test.php` mengambil bank soal dengan `SELECT * FROM test_questions ORDER BY id`. Daftar soal disalin ke session saat test pertama kali dibuka. Jika bank soal belum tersedia, aplikasi memberi pesan dan tidak menghitung nilai.

Salinan session mempertahankan pertanyaan dan kunci untuk test yang sedang dikerjakan walaupun admin mengubah bank soal. Test yang belum dimulai memakai soal terbaru. Salinan berlaku selama session pengguna aktif; tidak ada fitur simpan jawaban sementara atau timer.

## 7. Cara user mengerjakan test

Pilihan A–D ditampilkan dengan input radio. Nama input berisi ID soal, misalnya `answers[1]`. User harus memilih satu jawaban untuk setiap soal. Kunci jawaban tidak dicetak dalam HTML. Test hanya dapat dikirim satu kali; pengguna yang sudah selesai diarahkan ke hasil.

## 8. Cara jawaban diproses

Server memeriksa token CSRF, keberadaan seluruh jawaban, dan pilihan A/B/C/D. Proses simpan dijalankan dalam transaksi. Baris pendaftar dikunci dengan `FOR UPDATE` untuk mencegah pengiriman ganda. Setiap jawaban dibandingkan dengan kunci dan disimpan ke `test_answers`; jawaban benar bernilai 1, salah bernilai 0.

## 9. Cara nilai dihitung

~~~php
$score = $correct / count($questions) * 100;
~~~

Contoh: 7 jawaban benar dari 10 soal menghasilkan 70. Nilai disimpan dengan dua angka desimal. Semua soal memiliki bobot sama. Penentuan kelulusan memakai nilai asli sebelum pembulatan tampilan.

## 10. Cara menentukan kelulusan

Di `config/database.php` terdapat:

~~~php
const PASSING_SCORE = 70;
~~~

Nilai ≥ 70 berstatus `passed` (Lulus), nilai < 70 berstatus `failed` (Tidak Lulus). Sebelum test statusnya `pending` (Belum Mengerjakan). Nilai dan status tersimpan di applicants dan ditampilkan di dashboard/hasil. Tetapkan batas sebelum periode test dimulai: perubahan konstanta tidak menghitung ulang hasil lama.

## 11. Cara daftar ulang

`user/re_registration.php` memeriksa status passed dari database. Status pending/failed ditolak oleh server, termasuk jika user langsung mengirim POST ke URL. User lulus membaca biodata ringkas dan mencentang konfirmasi. Tidak ada pembayaran maupun upload berkas.

## 12. Cara NIM dibuat

~~~php
$nim = date('Y') . str_pad($a['id'], 4, '0', STR_PAD_LEFT);
~~~

ID 1 pada tahun 2026 menjadi 20260001. NIM awalnya NULL, lalu disimpan bersama status daftar ulang = 1. Query hanya memperbarui pendaftar berstatus passed yang belum daftar ulang. Permintaan ulang tidak mengganti NIM yang sudah ada. ID memakai ID pendaftar, sehingga bukan nomor urut khusus peserta lulus; celah nomor adalah perilaku yang diharapkan.

## 13. Cara CRUD Admin bekerja

- **Create:** validasi form, prepared INSERT. Calon mahasiswa baru mendapat akun dan nomor test otomatis.
- **Read:** SELECT dan JOIN untuk menampilkan nama akun dengan biodata. Dashboard memakai COUNT/SUM, tanpa chart.
- **Update:** ID harus bilangan positif dan data harus ditemukan. Biodata diperbarui melalui prepared UPDATE. Password edit boleh kosong agar password lama dipertahankan. Admin tidak mengubah nilai atau kelulusan secara manual.
- **Delete:** GET hanya menampilkan konfirmasi. Penghapusan berjalan pada POST dengan CSRF. Akun calon mahasiswa dan data terkait dihapus bersama. Penghapusan bank soal tidak menghapus riwayat jawaban.

Halaman hasil menampilkan nilai dan filter lulus/tidak lulus. Halaman daftar ulang menampilkan peserta yang sudah konfirmasi beserta NIM. Semua pendaftar, termasuk yang belum test, ada di `admin/applicants.php`.

## 14. File penting dan fungsi masing-masing

| File | Bagian yang dijelaskan |
|---|---|
| database/pmb_campus.sql | Empat tabel, relasi, unique email/NIM, admin hash dan 10 soal |
| config/database.php | PDO, pengaturan MySQL, PASSING_SCORE |
| includes/init.php | Session, role, CSRF, escape output, registrasi, nomor test |
| includes/login_form.php | SELECT akun dan password_verify |
| auth/register.php | Validasi form dan simpan akun |
| user/dashboard.php | Status dan tombol sesuai kondisi |
| user/test.php | Ambil soal, validasi jawaban, nilai dan status |
| user/test_result.php | Hasil seleksi |
| user/re_registration.php | Pembatasan lulus, konfirmasi, generate NIM |
| includes/admin_applicant_form.php | Tambah/edit biodata |
| includes/admin_question_form.php | Tambah/edit soal |
| admin/applicant_delete.php | Hapus akun calon mahasiswa |
| admin/question_delete.php | Hapus bank soal |
| includes/header.php dan footer.php | Navbar/footer konsisten |
| assets/css/style.css | Tata letak desktop/ponsel dan identitas warna |

Folder includes menghindari pengulangan form dan navigasi; tetap menggunakan fungsi PHP sederhana, tanpa class/framework.

## 15. Flow User untuk demonstrasi

1. Register menggunakan email baru, lalu login.
2. Tunjukkan nomor test, status Belum Mengerjakan, dan NIM Belum tersedia.
3. Kerjakan 10 soal. Untuk demo lulus: kunci contoh adalah B, A, C, D, A, B, C, D, A, C.
4. Kirim jawaban, tunjukkan nilai dan status. Tujuh benar sudah lulus.
5. Daftar ulang dan tunjukkan NIM pada dashboard.
6. Buat akun lain dengan jawaban kurang dari tujuh benar untuk menunjukkan hasil Tidak Lulus dan tidak adanya hak daftar ulang.
7. Logout untuk menunjukkan proteksi session.

## 16. Flow Admin untuk demonstrasi

1. Masuk melalui admin/login.php dengan akun development di README.
2. Tunjukkan ringkasan jumlah pendaftar, soal, lulus, tidak lulus, daftar ulang.
3. Tambah calon mahasiswa, edit biodata, dan hapus data demo melalui halaman konfirmasi.
4. Tambah, edit, dan hapus satu soal demo.
5. Buka semua pendaftar, filter hasil lulus/tidak lulus, dan daftar ulang/NIM.
6. Logout. Semua proses dijalankan lokal; tidak ada Git, upload, atau deployment.
