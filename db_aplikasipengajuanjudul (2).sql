-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 04, 2025 at 01:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_aplikasipengajuanjudul`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_daftarpengajuanjudul`
--

CREATE TABLE `tbl_daftarpengajuanjudul` (
  `id` int(11) NOT NULL,
  `mahasiswa_id` int(11) NOT NULL,
  `topik_penelitian` varchar(100) NOT NULL,
  `judul_penelitian` varchar(255) NOT NULL,
  `ringkasan_file` varchar(255) DEFAULT NULL,
  `jurnal_file` varchar(255) DEFAULT NULL,
  `dosen_pembimbing_1` int(11) NOT NULL,
  `dosen_pembimbing_2` int(11) NOT NULL,
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_dospem1` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `komentar_dospem1` text DEFAULT NULL,
  `timestamp_dospem1` timestamp NULL DEFAULT NULL,
  `status_dospem2` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `komentar_dospem2` text DEFAULT NULL,
  `timestamp_dospem2` timestamp NULL DEFAULT NULL,
  `status_dbs` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `komentar_dbs` text DEFAULT NULL,
  `timestamp_dbs` timestamp NULL DEFAULT NULL,
  `status_kaprodi` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `komentar_kaprodi` text DEFAULT NULL,
  `timestamp_kaprodi` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_daftarpengajuanjudul`
--

INSERT INTO `tbl_daftarpengajuanjudul` (`id`, `mahasiswa_id`, `topik_penelitian`, `judul_penelitian`, `ringkasan_file`, `jurnal_file`, `dosen_pembimbing_1`, `dosen_pembimbing_2`, `tanggal_pengajuan`, `status_dospem1`, `komentar_dospem1`, `timestamp_dospem1`, `status_dospem2`, `komentar_dospem2`, `timestamp_dospem2`, `status_dbs`, `komentar_dbs`, `timestamp_dbs`, `status_kaprodi`, `komentar_kaprodi`, `timestamp_kaprodi`, `created_at`, `updated_at`) VALUES
(56, 6, 'ai', 'Implementasi Algoritma Support Vector Machine untuk Prediksi Produktivitas Padi di Kuningan', '1735988727_fac0d10291b019eeaab7.pdf', '1735988727_a288b974071c7a9e0c9d.pdf', 21, 23, '2025-01-04 11:05:27', 'approved', NULL, '2025-01-04 11:08:11', 'approved', NULL, '2025-01-04 11:08:14', 'approved', NULL, '2025-01-04 11:10:16', 'pending', NULL, NULL, '2025-01-04 11:05:27', '2025-01-04 11:10:16');

--
-- Triggers `tbl_daftarpengajuanjudul`
--
DELIMITER $$
CREATE TRIGGER `trg_log_status_change` AFTER UPDATE ON `tbl_daftarpengajuanjudul` FOR EACH ROW BEGIN
    -- Log perubahan status_dospem1
    IF OLD.status_dospem1 <> NEW.status_dospem1 THEN
        INSERT INTO `tbl_status_log` (`listpengajuan_id`, `status_field`, `old_status`, `new_status`)
        VALUES (NEW.id, 'status_dospem1', OLD.status_dospem1, NEW.status_dospem1);
    END IF;

    -- Log perubahan status_dospem2
    IF OLD.status_dospem2 <> NEW.status_dospem2 THEN
        INSERT INTO `tbl_status_log` (`listpengajuan_id`, `status_field`, `old_status`, `new_status`)
        VALUES (NEW.id, 'status_dospem2', OLD.status_dospem2, NEW.status_dospem2);
    END IF;

    -- Log perubahan status_dbs
    IF OLD.status_dbs <> NEW.status_dbs THEN
        INSERT INTO `tbl_status_log` (`listpengajuan_id`, `status_field`, `old_status`, `new_status`)
        VALUES (NEW.id, 'status_dbs', OLD.status_dbs, NEW.status_dbs);
    END IF;

    -- Log perubahan status_kaprodi
    IF OLD.status_kaprodi <> NEW.status_kaprodi THEN
        INSERT INTO `tbl_status_log` (`listpengajuan_id`, `status_field`, `old_status`, `new_status`)
        VALUES (NEW.id, 'status_kaprodi', OLD.status_kaprodi, NEW.status_kaprodi);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_update_timestamp_dospem1` BEFORE UPDATE ON `tbl_daftarpengajuanjudul` FOR EACH ROW BEGIN
    IF OLD.status_dospem1 <> NEW.status_dospem1 THEN
        SET NEW.timestamp_dospem1 = CURRENT_TIMESTAMP;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_update_timestamp_dospem2` BEFORE UPDATE ON `tbl_daftarpengajuanjudul` FOR EACH ROW BEGIN
    IF OLD.status_dospem2 <> NEW.status_dospem2 THEN
        SET NEW.timestamp_dospem2 = CURRENT_TIMESTAMP;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_validate_references` BEFORE INSERT ON `tbl_daftarpengajuanjudul` FOR EACH ROW BEGIN
    DECLARE mahasiswa_exists INT;
    DECLARE dosen1_exists INT;
    DECLARE dosen2_exists INT;

    -- Validasi mahasiswa
    SELECT COUNT(*) INTO mahasiswa_exists FROM `tbl_mahasiswa` WHERE `id` = NEW.mahasiswa_id;
    IF mahasiswa_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Mahasiswa ID tidak valid';
    END IF;

    -- Validasi dosen pembimbing 1
    SELECT COUNT(*) INTO dosen1_exists FROM `tbl_dosen` WHERE `id` = NEW.dosen_pembimbing_1;
    IF dosen1_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Dosen Pembimbing 1 ID tidak valid';
    END IF;

    -- Validasi dosen pembimbing 2
    SELECT COUNT(*) INTO dosen2_exists FROM `tbl_dosen` WHERE `id` = NEW.dosen_pembimbing_2;
    IF dosen2_exists = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Dosen Pembimbing 2 ID tidak valid';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dosen`
--

CREATE TABLE `tbl_dosen` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nomor_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_dosen`
--

INSERT INTO `tbl_dosen` (`id`, `user_id`, `nik`, `nomor_hp`, `alamat`, `foto`, `created_at`, `updated_at`) VALUES
(21, 18, '', '', '', '', '2025-01-03 13:23:39', '2025-01-03 13:23:39'),
(22, 19, '', '', '', '', '2025-01-03 13:24:38', '2025-01-03 13:24:38'),
(23, 20, '', '', '', '', '2025-01-03 13:26:19', '2025-01-03 13:26:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_mahasiswa`
--

CREATE TABLE `tbl_mahasiswa` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nim` varchar(20) NOT NULL,
  `program_studi` varchar(100) NOT NULL,
  `jenjang` varchar(50) NOT NULL,
  `nomor_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_mahasiswa`
--

INSERT INTO `tbl_mahasiswa` (`id`, `user_id`, `nim`, `program_studi`, `jenjang`, `nomor_hp`, `alamat`, `foto`, `created_at`, `updated_at`) VALUES
(6, 17, '2025', 'Teknik Informatika', 'S1', '085215115', 'Jl. ', '', '2025-01-03 13:20:22', '2025-01-04 10:50:37'),
(7, 21, '2222', 'Teknik Informatika', 'S1', '5325235', 'Jl', '', '2025-01-03 13:50:03', '2025-01-03 13:50:31'),
(8, 22, '2222222222', 'Teknik Informatika', 'S1', '0877777777', 'Jl. ', '', '2025-01-03 14:24:59', '2025-01-03 14:25:35');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_status_log`
--

CREATE TABLE `tbl_status_log` (
  `id` int(11) NOT NULL,
  `listpengajuan_id` int(11) NOT NULL,
  `status_field` varchar(50) NOT NULL,
  `old_status` varchar(20) NOT NULL,
  `new_status` varchar(20) NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_status_log`
--

INSERT INTO `tbl_status_log` (`id`, `listpengajuan_id`, `status_field`, `old_status`, `new_status`, `changed_at`) VALUES
(51, 15, 'status_dbs', 'approved', 'pending', '2025-01-02 15:41:36'),
(52, 15, 'status_dospem2', 'approved', 'pending', '2025-01-02 15:41:44'),
(53, 15, 'status_dospem2', 'pending', 'approved', '2025-01-02 15:41:56'),
(54, 15, 'status_dospem1', 'approved', 'pending', '2025-01-02 15:42:02'),
(55, 15, 'status_dospem1', 'pending', 'rejected', '2025-01-02 15:42:08'),
(56, 15, 'status_dospem1', 'rejected', 'pending', '2025-01-02 15:45:45'),
(57, 15, 'status_dospem1', 'pending', 'rejected', '2025-01-02 15:45:50'),
(58, 15, 'status_dospem1', 'rejected', 'approved', '2025-01-02 15:45:57'),
(59, 15, 'status_kaprodi', 'pending', 'approved', '2025-01-02 15:46:17'),
(60, 15, 'status_kaprodi', 'approved', 'pending', '2025-01-02 15:46:22'),
(61, 15, 'status_dbs', 'pending', 'approved', '2025-01-02 15:46:25'),
(62, 15, 'status_kaprodi', 'pending', 'rejected', '2025-01-02 15:46:33'),
(63, 15, 'status_dbs', 'approved', 'rejected', '2025-01-02 15:46:39'),
(64, 15, 'status_dbs', 'rejected', 'approved', '2025-01-02 15:47:48'),
(65, 15, 'status_kaprodi', 'rejected', 'pending', '2025-01-02 15:47:53'),
(66, 15, 'status_dbs', 'approved', 'rejected', '2025-01-02 15:47:55'),
(67, 15, 'status_dbs', 'rejected', 'pending', '2025-01-02 15:48:08'),
(68, 15, 'status_dbs', 'pending', 'rejected', '2025-01-02 15:50:35'),
(69, 15, 'status_dbs', 'rejected', 'approved', '2025-01-02 15:50:43'),
(70, 15, 'status_kaprodi', 'pending', 'approved', '2025-01-02 15:51:50'),
(71, 15, 'status_kaprodi', 'approved', 'pending', '2025-01-02 15:52:01'),
(72, 15, 'status_dbs', 'approved', 'pending', '2025-01-02 15:52:05'),
(73, 15, 'status_dbs', 'pending', 'rejected', '2025-01-02 15:52:10'),
(74, 15, 'status_dospem2', 'approved', 'rejected', '2025-01-02 16:05:47'),
(75, 15, 'status_dbs', 'rejected', 'approved', '2025-01-02 16:05:59'),
(76, 15, 'status_dbs', 'approved', 'rejected', '2025-01-02 16:06:04'),
(77, 15, 'status_dbs', 'rejected', 'pending', '2025-01-02 16:06:07'),
(78, 15, 'status_dospem2', 'rejected', 'pending', '2025-01-02 16:06:22'),
(79, 15, 'status_dospem2', 'pending', 'rejected', '2025-01-02 16:06:33'),
(80, 15, 'status_dospem2', 'rejected', 'pending', '2025-01-02 16:06:56'),
(81, 15, 'status_dospem1', 'approved', 'pending', '2025-01-02 16:07:07'),
(82, 15, 'status_dospem2', 'pending', 'rejected', '2025-01-02 16:07:14'),
(83, 15, 'status_dospem1', 'pending', 'approved', '2025-01-02 16:07:20'),
(84, 15, 'status_dospem1', 'approved', 'rejected', '2025-01-02 16:10:41'),
(85, 15, 'status_dospem1', 'rejected', 'pending', '2025-01-02 16:12:20'),
(86, 15, 'status_dospem1', 'pending', 'approved', '2025-01-02 16:12:25'),
(87, 15, 'status_dospem1', 'approved', 'rejected', '2025-01-02 16:12:29'),
(88, 15, 'status_dospem2', 'rejected', 'approved', '2025-01-02 16:12:36'),
(89, 15, 'status_dospem2', 'approved', 'rejected', '2025-01-02 16:12:42'),
(90, 15, 'status_dospem2', 'rejected', 'pending', '2025-01-02 16:12:46'),
(91, 15, 'status_dospem1', 'rejected', 'pending', '2025-01-02 16:12:50'),
(92, 15, 'status_dospem2', 'pending', 'approved', '2025-01-02 16:12:52'),
(93, 15, 'status_dospem2', 'approved', 'rejected', '2025-01-02 16:12:56'),
(94, 15, 'status_dospem2', 'rejected', 'pending', '2025-01-02 16:13:02'),
(95, 15, 'status_dospem1', 'pending', 'approved', '2025-01-02 16:13:03'),
(96, 15, 'status_dospem2', 'pending', 'approved', '2025-01-02 16:13:06'),
(97, 15, 'status_dospem2', 'approved', 'rejected', '2025-01-02 16:13:14'),
(98, 15, 'status_dospem1', 'approved', 'rejected', '2025-01-02 16:13:20'),
(99, 15, 'status_dospem2', 'rejected', 'approved', '2025-01-02 16:13:24'),
(100, 15, 'status_dospem2', 'approved', 'pending', '2025-01-02 16:13:29'),
(101, 15, 'status_dospem1', 'rejected', 'approved', '2025-01-02 16:13:34'),
(102, 15, 'status_dospem2', 'pending', 'approved', '2025-01-02 16:13:35'),
(103, 15, 'status_dbs', 'pending', 'rejected', '2025-01-02 16:13:44'),
(104, 15, 'status_dbs', 'rejected', 'approved', '2025-01-02 16:13:50'),
(105, 15, 'status_dbs', 'approved', 'pending', '2025-01-02 16:13:55'),
(106, 15, 'status_dbs', 'pending', 'approved', '2025-01-02 16:13:58'),
(107, 15, 'status_kaprodi', 'pending', 'rejected', '2025-01-02 16:14:03'),
(108, 15, 'status_kaprodi', 'rejected', 'approved', '2025-01-02 16:14:09'),
(109, 15, 'status_kaprodi', 'approved', 'pending', '2025-01-02 16:14:13'),
(110, 15, 'status_kaprodi', 'pending', 'approved', '2025-01-02 16:14:39'),
(111, 15, 'status_kaprodi', 'approved', 'rejected', '2025-01-02 16:18:01'),
(112, 15, 'status_kaprodi', 'rejected', 'approved', '2025-01-02 16:18:11'),
(113, 15, 'status_kaprodi', 'approved', 'pending', '2025-01-02 16:18:38'),
(114, 15, 'status_dbs', 'approved', 'pending', '2025-01-02 16:21:06'),
(115, 15, 'status_dospem1', 'approved', 'pending', '2025-01-02 16:21:12'),
(116, 15, 'status_dospem2', 'approved', 'pending', '2025-01-02 16:21:22'),
(117, 14, 'status_dospem2', 'pending', 'rejected', '2025-01-02 16:25:24'),
(118, 14, 'status_dospem2', 'rejected', 'pending', '2025-01-02 16:25:37'),
(119, 14, 'status_dospem2', 'pending', 'rejected', '2025-01-02 16:31:35'),
(120, 14, 'status_dospem1', 'pending', 'approved', '2025-01-02 16:32:13'),
(121, 14, 'status_dospem2', 'rejected', 'approved', '2025-01-02 16:32:19'),
(122, 14, 'status_dospem1', 'approved', 'pending', '2025-01-02 16:33:51'),
(123, 14, 'status_dospem2', 'approved', 'pending', '2025-01-02 16:33:56'),
(124, 15, 'status_dospem1', 'pending', 'approved', '2025-01-02 16:36:17'),
(125, 15, 'status_dospem2', 'pending', 'rejected', '2025-01-02 16:36:40'),
(126, 15, 'status_dospem2', 'rejected', 'approved', '2025-01-02 16:36:49'),
(127, 15, 'status_dospem2', 'approved', 'pending', '2025-01-02 16:36:57'),
(128, 15, 'status_dospem1', 'approved', 'pending', '2025-01-02 16:36:59'),
(140, 28, 'status_dospem1', 'pending', 'rejected', '2025-01-03 11:06:06'),
(141, 32, 'status_dospem1', 'pending', 'rejected', '2025-01-03 11:17:29'),
(153, 42, 'status_dospem1', 'pending', 'rejected', '2025-01-03 12:14:26'),
(154, 42, 'status_dospem1', 'rejected', 'pending', '2025-01-03 12:19:11'),
(155, 42, 'status_dospem1', 'pending', 'rejected', '2025-01-03 12:19:19'),
(156, 42, 'status_dospem1', 'rejected', 'approved', '2025-01-03 12:32:17'),
(157, 42, 'status_dospem1', 'approved', 'rejected', '2025-01-03 12:32:22'),
(158, 45, 'status_dospem1', 'pending', 'rejected', '2025-01-03 12:38:08'),
(173, 53, 'status_dospem1', 'pending', 'approved', '2025-01-03 14:18:21'),
(174, 53, 'status_dospem2', 'pending', 'approved', '2025-01-03 14:18:23'),
(179, 56, 'status_dospem1', 'pending', 'approved', '2025-01-04 11:08:11'),
(180, 56, 'status_dospem2', 'pending', 'approved', '2025-01-04 11:08:14'),
(181, 56, 'status_dbs', 'pending', 'approved', '2025-01-04 11:10:16');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('mahasiswa','dospem','dbs','kaprodi') NOT NULL,
  `status_aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `name`, `email`, `password`, `role`, `status_aktif`, `created_at`, `updated_at`) VALUES
(17, 'Mahasiswa', 'mahasiswa@gmail.com', '$2y$10$2sZdtPlA5Aw.S4oU3zC6K.h5wwPxdf/xmznheDA0O7Tb8C.AJ8V8.', 'mahasiswa', 1, '2025-01-03 13:20:22', '2025-01-04 10:50:37'),
(18, 'Tito Sugiharto S.Kom., M.Eng.', 'tito@gmail.com', '$2y$10$f1GQrfZldLqPXQBGqYgWlehDqQrw8SX2fq625Ye3NZCuOFJ0raWdO', 'dbs', 1, '2025-01-03 13:23:39', '2025-01-03 13:23:39'),
(19, 'Yati Nurhayati, M.Kom.', 'yati@gmail.com', '$2y$10$tZUNweBSk0nWlEiVA/FT4OM3U8NTZZtz9nI8YrytO4Pzwv.6y3dBu', 'kaprodi', 1, '2025-01-03 13:24:38', '2025-01-03 13:24:38'),
(20, 'Panji Novantara, S.Kom., M. T.', 'panji@gmail.com', '$2y$10$LXObqm6XEd5lLfK7i4w7.OcYFEPrNr6PUo7dNEV850xKyc5KPJAFi', 'dospem', 1, '2025-01-03 13:26:19', '2025-01-03 13:26:19'),
(21, 'Mahasiswa 2', 'mahasiswa2@gmail.com', '$2y$10$Rwad8zEk0RH8ahHzQPlHYOkTQmuel5ngZoGVFpvGMlC1NXb1c//LG', 'mahasiswa', 1, '2025-01-03 13:50:03', '2025-01-03 13:50:31'),
(22, 'Mahasiswa 3', 'mahasiswa3@gmail.com', '$2y$10$gFibB5YEvrtIYN5APOmRwugBOGT8pzUu1tR0clW.ghNfNdsUd8zwu', 'mahasiswa', 1, '2025-01-03 14:24:59', '2025-01-03 14:25:35');

--
-- Triggers `tbl_users`
--
DELIMITER $$
CREATE TRIGGER `after_user_insert` AFTER INSERT ON `tbl_users` FOR EACH ROW BEGIN
    -- Jika role adalah mahasiswa, masukkan data ke tbl_mahasiswa
    IF NEW.role = 'mahasiswa' THEN
        INSERT INTO `tbl_mahasiswa` (`user_id`, `nim`, `program_studi`, `jenjang`, `nomor_hp`, `alamat`, `foto`)
        VALUES (NEW.id, '', '', '', '', '', ''); -- Sesuaikan dengan data yang ingin dimasukkan

    -- Jika role adalah dospem, dbs, atau kaprodi, masukkan data ke tbl_dosen
    ELSEIF NEW.role IN ('dospem', 'dbs', 'kaprodi') THEN
        INSERT INTO `tbl_dosen` (`user_id`, `nik`, `nomor_hp`, `alamat`, `foto`)
        VALUES (NEW.id, '', '', '', ''); -- Sesuaikan dengan data yang ingin dimasukkan
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `after_user_update` AFTER UPDATE ON `tbl_users` FOR EACH ROW BEGIN
    -- Periksa jika role berubah
    IF OLD.role <> NEW.role THEN
        -- Hapus data terkait role lama
        DELETE FROM `tbl_mahasiswa` WHERE `user_id` = NEW.id;
        DELETE FROM `tbl_dosen` WHERE `user_id` = NEW.id;

        -- Masukkan data terkait role baru
        IF NEW.role = 'mahasiswa' THEN
            INSERT INTO `tbl_mahasiswa` (`user_id`, `nim`, `program_studi`, `jenjang`, `nomor_hp`, `alamat`, `foto`)
            VALUES (NEW.id, '', '', '', '', '', '');
        ELSEIF NEW.role IN ('dospem', 'dbs', 'kaprodi') THEN
            INSERT INTO `tbl_dosen` (`user_id`, `nik`, `nomor_hp`, `alamat`, `foto`)
            VALUES (NEW.id, '', '', '', '');
        END IF;
    END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_daftarpengajuanjudul`
--
ALTER TABLE `tbl_daftarpengajuanjudul`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mahasiswa_id` (`mahasiswa_id`),
  ADD KEY `dosen_pembimbing_1` (`dosen_pembimbing_1`),
  ADD KEY `dosen_pembimbing_2` (`dosen_pembimbing_2`);

--
-- Indexes for table `tbl_dosen`
--
ALTER TABLE `tbl_dosen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_dosen_ibfk_1` (`user_id`);

--
-- Indexes for table `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_status_log`
--
ALTER TABLE `tbl_status_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listpengajuan_id` (`listpengajuan_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_daftarpengajuanjudul`
--
ALTER TABLE `tbl_daftarpengajuanjudul`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tbl_dosen`
--
ALTER TABLE `tbl_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_status_log`
--
ALTER TABLE `tbl_status_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_daftarpengajuanjudul`
--
ALTER TABLE `tbl_daftarpengajuanjudul`
  ADD CONSTRAINT `tbl_daftarpengajuanjudul_ibfk_1` FOREIGN KEY (`mahasiswa_id`) REFERENCES `tbl_mahasiswa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_daftarpengajuanjudul_ibfk_2` FOREIGN KEY (`dosen_pembimbing_1`) REFERENCES `tbl_dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tbl_daftarpengajuanjudul_ibfk_3` FOREIGN KEY (`dosen_pembimbing_2`) REFERENCES `tbl_dosen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_dosen`
--
ALTER TABLE `tbl_dosen`
  ADD CONSTRAINT `tbl_dosen_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD CONSTRAINT `tbl_mahasiswa_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_status_log`
--
ALTER TABLE `tbl_status_log`
  ADD CONSTRAINT `tbl_status_log_ibfk_1` FOREIGN KEY (`listpengajuan_id`) REFERENCES `tbl_daftarpengajuanjudul` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
