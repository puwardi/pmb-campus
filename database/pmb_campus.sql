-- Import sekali melalui phpMyAdmin. Tidak menghapus database atau tabel yang sudah ada.
CREATE DATABASE IF NOT EXISTS pmb_campus CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pmb_campus;
CREATE TABLE users (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL,
 email VARCHAR(150) NOT NULL UNIQUE,
 password VARCHAR(255) NOT NULL,
 role ENUM('admin','user') NOT NULL DEFAULT 'user',
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE applicants (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id INT UNSIGNED NOT NULL UNIQUE,
 test_number VARCHAR(30) UNIQUE,
 phone VARCHAR(25) NOT NULL,
 address TEXT NOT NULL,
 school_origin VARCHAR(150) NOT NULL,
 test_score DECIMAL(5,2) DEFAULT NULL,
 test_status ENUM('pending','passed','failed') NOT NULL DEFAULT 'pending',
 re_registration_status TINYINT(1) NOT NULL DEFAULT 0,
 nim VARCHAR(30) UNIQUE,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;
CREATE TABLE test_questions (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 question TEXT NOT NULL,
 option_a TEXT NOT NULL,
 option_b TEXT NOT NULL,
 option_c TEXT NOT NULL,
 option_d TEXT NOT NULL,
 correct_answer ENUM('A','B','C','D') NOT NULL,
 created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
-- Salinan pertanyaan/kunci menjaga riwayat ketika admin mengedit/menghapus bank soal.
CREATE TABLE test_answers (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 applicant_id INT UNSIGNED NOT NULL,
 question_text TEXT NOT NULL,
 selected_answer ENUM('A','B','C','D') NOT NULL,
 correct_answer ENUM('A','B','C','D') NOT NULL,
 is_correct TINYINT(1) NOT NULL,
 FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE
) ENGINE=InnoDB;
INSERT INTO users (name,email,password,role) VALUES
('Administrator','admin@pmbcampus.local','$2y$10$g.r/hqVqEFj1I3OQTWU4g.UVUk4sDeUnR3EI206rbhDFyrSQfaMm2','admin');
INSERT INTO test_questions (question,option_a,option_b,option_c,option_d,correct_answer) VALUES
('Hasil dari 12 × 8 adalah ...','86','96','108','112','B'),
('Sinonim kata inovasi adalah ...','Pembaruan','Pengulangan','Penundaan','Pemisahan','A'),
('Perangkat yang digunakan untuk mengetik adalah ...','Monitor','Speaker','Keyboard','Printer','C'),
('Hasil dari 150 dibagi 5 adalah ...','15','20','25','30','D'),
('Bahasa untuk menyusun struktur halaman web adalah ...','HTML','SQL','Python','PHP','A'),
('Jika semua mahasiswa membaca buku dan Rina adalah mahasiswa, maka ...','Rina tidak membaca','Rina membaca buku','Rina menulis buku','Tidak dapat disimpulkan','B'),
('Bentuk desimal dari 1/4 adalah ...','0,2','0,4','0,25','0,5','C'),
('Kata baku yang tepat adalah ...','Aktifitas','Ijin','Resiko','Aktivitas','D'),
('Sila pertama Pancasila adalah ...','Ketuhanan Yang Maha Esa','Persatuan Indonesia','Keadilan sosial','Kemanusiaan yang adil dan beradab','A'),
('Lanjutan pola 2, 4, 8, 16 adalah ...','18','24','32','64','C');
