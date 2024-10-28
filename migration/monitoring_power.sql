-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Okt 2024 pada 18.11
-- Versi server: 10.4.11-MariaDB
-- Versi PHP: 7.4.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `monitoring_power`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `logaktivitas`
--

CREATE TABLE `logaktivitas` (
  `log_id` int(11) NOT NULL,
  `monitoring_id` int(11) DEFAULT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `waktu` datetime DEFAULT NULL,
  `aktivitas` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `sumberdaya` varchar(255) DEFAULT NULL,
  `tegangan_lokasi` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `logaktivitas`
--

INSERT INTO `logaktivitas` (`log_id`, `monitoring_id`, `lokasi_id`, `waktu`, `aktivitas`, `status`, `sumberdaya`, `tegangan_lokasi`) VALUES
(1, 1, 1, '2024-10-10 12:00:00', 'Perubahan Tegangan', 'Turun', 'ACCU', '10.00'),
(2, 2, 2, '2024-10-10 11:00:00', 'Tegangan Normal', 'Normal', 'Listrik', '220.00'),
(3, 3, 3, '2024-10-10 10:00:00', 'Perubahan Tegangan', 'Naik', 'Ups', '12.00'),
(4, 4, 2, '2024-10-10 09:00:00', 'Perubahan Tegangan', 'Turun', 'Accu', '0.00'),
(5, 5, 3, '2024-10-10 08:00:00', 'Tegangan Normal', 'Normal', 'Listrik', '12.50'),
(6, 6, 1, '2024-10-10 07:00:00', 'Perubahan Tegangan', 'Turun', 'Accu', '0.00'),
(7, 7, 3, '2024-10-10 06:00:00', 'Tegangan Normal', 'Normal', 'Ups', '12.00'),
(8, 8, 2, '2024-10-10 05:00:00', 'Perubahan Tegangan', 'Turun', 'Listrik', '180.00'),
(9, 9, 3, '2024-10-10 04:00:00', 'Perubahan Tegangan', 'Naik', 'Listrik', '210.00'),
(10, 10, 1, '2024-10-10 03:00:00', 'Tegangan Normal', 'Normal', 'Accu', '12.50'),
(11, 11, 3, '2024-10-10 02:00:00', 'Perubahan Tegangan', 'Turun', 'Accu', '0.00'),
(12, 12, 2, '2024-10-10 01:00:00', 'Perubahan Tegangan', 'Naik', 'Ups', '12.00'),
(13, 13, 3, '2024-10-10 00:00:00', 'Perubahan Tegangan', 'Turun', 'Listrik', '0.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi`
--

CREATE TABLE `lokasi` (
  `lokasi_id` int(11) NOT NULL,
  `nama_lokasi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `lokasi`
--

INSERT INTO `lokasi` (`lokasi_id`, `nama_lokasi`) VALUES
(1, 'Router_Utama'),
(2, 'BTS1'),
(3, 'BTS2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `monitoring`
--

CREATE TABLE `monitoring` (
  `monitoring_id` int(11) NOT NULL,
  `sumberdaya_id` int(11) DEFAULT NULL,
  `tegangan` decimal(10,2) DEFAULT NULL,
  `arus` decimal(10,2) DEFAULT NULL,
  `aktivitas` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `suhu` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `monitoring`
--

INSERT INTO `monitoring` (`monitoring_id`, `sumberdaya_id`, `tegangan`, `arus`, `aktivitas`, `status`, `suhu`) VALUES
(1, 22, '10.00', '0.00', 'Perubahan Tegangan', 'Turun', '36.00'),
(2, 1, '220.00', '4.00', 'Tegangan Normal', 'Normal', '36.00'),
(3, 333, '12.00', '5.00', 'Perubahan Tegangan', 'Naik', '38.00'),
(4, 2, '0.00', '0.00', 'Perubahan Tegangan', 'Turun', '37.00'),
(5, 11, '12.50', '4.00', 'Tegangan Normal', 'Normal', '35.00'),
(6, 222, '0.00', '0.00', 'Perubahan Tegangan', 'Turun', '38.00'),
(7, 33, '12.00', '4.00', 'Tegangan Normal', 'Normal', '37.00'),
(8, 1, '180.00', '2.00', 'Perubahan Tegangan', 'Turun', '36.00'),
(9, 11, '210.00', '4.00', 'Perubahan Tegangan', 'Naik', '37.00'),
(10, 22, '12.50', '4.00', 'Tegangan Normal', 'Normal', '38.00'),
(11, 22, '0.00', '0.00', 'Perubahan Tegangan', 'Turun', '37.00'),
(12, 333, '12.00', '5.00', 'Perubahan Tegangan', 'Naik', '35.00'),
(13, 1, '0.00', '0.00', 'Perubahan Tegangan', 'Turun', '35.00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifikasi`
--

CREATE TABLE `notifikasi` (
  `notifikasi_id` int(11) NOT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `realtimedata`
--

CREATE TABLE `realtimedata` (
  `data_id` int(11) NOT NULL,
  `sumberdaya_id` int(11) DEFAULT NULL,
  `waktu_pengukuran` datetime DEFAULT NULL,
  `tegangan` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sumberdaya`
--

CREATE TABLE `sumberdaya` (
  `sumberdaya_id` int(11) NOT NULL,
  `lokasi_id` int(11) DEFAULT NULL,
  `jenis_sumberdaya` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `sumberdaya`
--

INSERT INTO `sumberdaya` (`sumberdaya_id`, `lokasi_id`, `jenis_sumberdaya`) VALUES
(1, 1, 'Listrik_PLN'),
(2, 1, 'ACCU'),
(3, 1, 'UPS'),
(11, 2, 'Listrik_PLN'),
(22, 2, 'ACCU'),
(33, 2, 'UPS'),
(111, 3, 'Listrik_PLN'),
(222, 3, 'ACCU'),
(333, 3, 'UPS');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `logaktivitas`
--
ALTER TABLE `logaktivitas`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `monitoring_id` (`monitoring_id`);

--
-- Indeks untuk tabel `lokasi`
--
ALTER TABLE `lokasi`
  ADD PRIMARY KEY (`lokasi_id`);

--
-- Indeks untuk tabel `monitoring`
--
ALTER TABLE `monitoring`
  ADD PRIMARY KEY (`monitoring_id`),
  ADD KEY `sumberdaya_id` (`sumberdaya_id`);

--
-- Indeks untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`notifikasi_id`),
  ADD KEY `lokasi_id` (`lokasi_id`);

--
-- Indeks untuk tabel `realtimedata`
--
ALTER TABLE `realtimedata`
  ADD PRIMARY KEY (`data_id`),
  ADD KEY `sumberdaya_id` (`sumberdaya_id`);

--
-- Indeks untuk tabel `sumberdaya`
--
ALTER TABLE `sumberdaya`
  ADD PRIMARY KEY (`sumberdaya_id`),
  ADD KEY `lokasi_id` (`lokasi_id`);

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `logaktivitas`
--
ALTER TABLE `logaktivitas`
  ADD CONSTRAINT `logaktivitas_ibfk_1` FOREIGN KEY (`monitoring_id`) REFERENCES `monitoring` (`monitoring_id`);

--
-- Ketidakleluasaan untuk tabel `monitoring`
--
ALTER TABLE `monitoring`
  ADD CONSTRAINT `monitoring_ibfk_1` FOREIGN KEY (`sumberdaya_id`) REFERENCES `sumberdaya` (`sumberdaya_id`);

--
-- Ketidakleluasaan untuk tabel `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi` (`lokasi_id`);

--
-- Ketidakleluasaan untuk tabel `realtimedata`
--
ALTER TABLE `realtimedata`
  ADD CONSTRAINT `realtimedata_ibfk_1` FOREIGN KEY (`sumberdaya_id`) REFERENCES `sumberdaya` (`sumberdaya_id`);

--
-- Ketidakleluasaan untuk tabel `sumberdaya`
--
ALTER TABLE `sumberdaya`
  ADD CONSTRAINT `sumberdaya_ibfk_1` FOREIGN KEY (`lokasi_id`) REFERENCES `lokasi` (`lokasi_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
