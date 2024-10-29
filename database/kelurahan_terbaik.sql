-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 29, 2024 at 06:05 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kelurahan_terbaik`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrasi`
--

CREATE TABLE `administrasi` (
  `id_administrasi` int NOT NULL,
  `id_kelurahan` varchar(50) NOT NULL,
  `id_periode` int NOT NULL,
  `id_nilai_sub_indikator` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `administrasi`
--

INSERT INTO `administrasi` (`id_administrasi`, `id_kelurahan`, `id_periode`, `id_nilai_sub_indikator`) VALUES
(1, 'a', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `alternatif`
--

CREATE TABLE `alternatif` (
  `id` int NOT NULL,
  `id_assesment` int NOT NULL,
  `id_kriteria` int NOT NULL,
  `id_sub_wawncara` int NOT NULL,
  `id_sub_presesntasi` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assesment`
--

CREATE TABLE `assesment` (
  `id` int NOT NULL,
  `id_user` int NOT NULL,
  `tahun` year NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bidang`
--

CREATE TABLE `bidang` (
  `id` int NOT NULL,
  `nama_bidang` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bidang`
--

INSERT INTO `bidang` (`id`, `nama_bidang`) VALUES
(1, 'Pemerintahan'),
(2, 'Kewilayahan'),
(3, 'Kemasyarakatan');

-- --------------------------------------------------------

--
-- Table structure for table `detail_assesment`
--

CREATE TABLE `detail_assesment` (
  `id` int NOT NULL,
  `id_assesment` int NOT NULL,
  `id_sub_indikator` int NOT NULL,
  `point` int NOT NULL,
  `verifikasi` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indikator`
--

CREATE TABLE `indikator` (
  `id` int NOT NULL,
  `id_bidang` int NOT NULL,
  `nama_indikator` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `indikator`
--

INSERT INTO `indikator` (`id`, `id_bidang`, `nama_indikator`) VALUES
(1, 1, 'Sarana Prasarana'),
(2, 1, 'Akuntabilitasi'),
(3, 1, 'Administrasi'),
(4, 1, 'Kinerja'),
(5, 1, 'Inisiatif dan kreativitas'),
(6, 1, 'Ketersediaan sistem teknologi\ninformasi berbasis internet'),
(7, 1, 'Perangkat komputer'),
(8, 1, 'Administrasi dengan teknologi informasi'),
(9, 1, 'Pelestarian Adat dan Budaya'),
(10, 2, 'Embrio Aktivitas Inovasi'),
(11, 2, 'Kelembagaan Inovasi'),
(12, 2, 'Jejaring Inovasi'),
(13, 2, 'Budaya Inovasi Masyarakat'),
(14, 2, 'Keterpaduan Perencanaan Inovasi'),
(15, 2, 'Kepekaan Masyarakat terhadap Dinamika Global/Ekonomi'),
(16, 2, 'Faktor-faktor Kunci dalam Pengelolaan Potensi secara Inovatif'),
(17, 2, 'Perencanaan Kontingensi'),
(18, 2, 'Peta Risiko bencana'),
(19, 2, 'Sistem Peringatan Dini Terpusat Pada Masyarakat'),
(20, 2, 'Infrastruktur Evakuasi'),
(21, 2, 'Investasi yang masuk ke Desa dan Kelurahan'),
(22, 3, 'Musyawarah Dusun'),
(23, 3, 'Swadaya Masyarakat Untuk Pembangunan Sarana Prasarana Desa 2 Tahun Terakhir'),
(24, 3, 'Swakelola Masyarakat Untuk Pembangunan Sarana Prasarana Desa 2 Tahun Terakhir'),
(25, 3, 'Gotong Royong Penduduk Desa 2 Tahun Terakhir'),
(26, 3, 'Organisasi Pemuda'),
(27, 3, 'Organisasi Profesi (petani, pedagang, nelayan, buruh, paguyuban, dll)'),
(28, 3, 'Organisasi Olah Raga '),
(29, 3, 'LPM atau Sebutan Lain'),
(30, 3, 'Kelompok Gotong Royong'),
(31, 3, 'Karang Taruna '),
(32, 3, 'Lembaga Adat, Budaya, Dan Kesenian'),
(33, 3, 'Kelompok Usaha'),
(34, 3, 'Koperasi'),
(35, 3, 'Organisasi Perempuan'),
(36, 3, 'Lembaga PKK'),
(37, 3, 'Program PKK'),
(38, 3, 'Organisasi PKK'),
(39, 3, 'Pengamanan Lingkungan Dan Manusia'),
(40, 3, 'Konflik SARA'),
(41, 3, 'Perkelahian'),
(42, 3, 'Pencurian dan perampokan '),
(43, 3, 'Perjudian'),
(44, 3, 'Narkoba'),
(45, 3, 'Prostitusi'),
(46, 3, 'Pembunuhan'),
(47, 3, 'Kekerasan Seksual '),
(48, 3, 'Kekerasan dalam Keluarga'),
(49, 3, 'Penculikan'),
(50, 3, 'HIV/AIDS'),
(51, 3, 'Buta Huruf'),
(52, 3, 'Putus Sekolah'),
(53, 3, 'Tamat Sekolah'),
(54, 3, 'Kematian Bayi'),
(55, 3, 'Gizi dan Kematian Balita'),
(56, 3, 'Posyandu'),
(57, 3, 'Kepemilikan Jamban Dalam Rumah Tangga (RT)'),
(58, 3, 'Fasilitas Kesehatan Lingkungan'),
(59, 3, 'Pengangguran'),
(60, 3, 'Mata Pencaharian/Sumber Pendapatan (Checklist mayoritas penduduk yang mana dan lingkari khusus yang Pendapatan Perkapita)'),
(61, 3, 'Kelembagaan Ekonomi'),
(62, 3, 'Data Masyarakat Miskin'),
(63, 3, 'Program Penanggulangan Kemiskinan'),
(64, 3, 'Analisis Kebutuhan'),
(65, 3, 'Pelaksanaan Program');

-- --------------------------------------------------------

--
-- Table structure for table `kelurahan`
--

CREATE TABLE `kelurahan` (
  `id` varchar(50) NOT NULL,
  `kelurahan` varchar(100) NOT NULL,
  `tipologi` enum('Pantai','Dataran Rendah','Pegunungan','Pertanian') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `orbitas` enum('Lebih dari 6 jam','5 sampai 6 jam','3 sampai 4 jam','1 sampai 2 jam','Kurang dari 1 jam') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kondisi_wilayah_ibukota` enum('Ada di ibukota','Di luar ibukota') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kondisi_wilayah_bencana` enum('Rawan','Tidak rawan') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `batas_desa` enum('Tidak ada','Ada') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `batas_orbitas` enum('Tidak ada','Ada') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelurahan`
--

INSERT INTO `kelurahan` (`id`, `kelurahan`, `tipologi`, `orbitas`, `kondisi_wilayah_ibukota`, `kondisi_wilayah_bencana`, `batas_desa`, `batas_orbitas`) VALUES
('a', 'Kelurahan A', 'Pantai', 'Lebih dari 6 jam', 'Ada di ibukota', 'Rawan', 'Tidak ada', 'Tidak ada'),
('b', 'Kelurahan B', 'Pantai', 'Lebih dari 6 jam', 'Ada di ibukota', 'Rawan', 'Ada', 'Ada');

-- --------------------------------------------------------

--
-- Table structure for table `kelurahan_periode`
--

CREATE TABLE `kelurahan_periode` (
  `id_kelurahan` varchar(50) NOT NULL,
  `id_periode` int NOT NULL,
  `usia_kurang_15` int NOT NULL,
  `usia_15_56` int NOT NULL,
  `usia_lebih_56` int NOT NULL,
  `penduduk_total` int NOT NULL,
  `penduduk_laki_laki` int NOT NULL,
  `penduduk_perempuan` int NOT NULL,
  `jumlah_kepala_keluarga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelurahan_periode`
--

INSERT INTO `kelurahan_periode` (`id_kelurahan`, `id_periode`, `usia_kurang_15`, `usia_15_56`, `usia_lebih_56`, `penduduk_total`, `penduduk_laki_laki`, `penduduk_perempuan`, `jumlah_kepala_keluarga`) VALUES
('a', 3, 1, 1, 1, 1, 1, 1, 1),
('a', 4, 2, 2, 2, 2, 2, 2, 2),
('b', 3, 700, 600, 500, 400, 300, 200, 100),
('b', 4, 10, 20, 30, 40, 50, 60, 70);

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int NOT NULL,
  `jenis` int NOT NULL,
  `bobot` int NOT NULL,
  `cost_benefit` int NOT NULL,
  `tahun` year NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nilai_sub_indikator`
--

CREATE TABLE `nilai_sub_indikator` (
  `id` int NOT NULL,
  `id_sub_indikator` int NOT NULL,
  `nama_nilai_sub_indikator` varchar(255) NOT NULL,
  `point` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilai_sub_indikator`
--

INSERT INTO `nilai_sub_indikator` (`id`, `id_sub_indikator`, `nama_nilai_sub_indikator`, `point`) VALUES
(1, 1, 'ADA, DI RUMAH PRIBADI', 1),
(2, 1, 'ADA, SEWA', 2),
(3, 1, 'ADA, MILIK PEMDA, SEMIPERMANEN', 3),
(4, 1, 'ADA, MILIK PEMDA, PERMANEN', 4),
(5, 2, 'TIDAK ADA', 1),
(6, 2, 'ADA, MILIK PEMDA, SEMIPERMANEN', 2),
(7, 2, 'ADA, MILIK PEMDA, PERMANEN', 3),
(8, 3, '50% BUKAN PNS', 1),
(9, 3, 'DI ATAS 50% PNS', 2),
(10, 4, 'TIDAK ADA', 1),
(11, 4, 'ADA', 2),
(12, 5, 'TIDAK ADA', 1),
(13, 5, 'ADA', 2),
(14, 6, 'TIDAK ADA', 1),
(15, 6, 'ADA', 2),
(16, 7, 'TIDAK ADA', 1),
(17, 7, 'ADA', 2),
(18, 8, 'TIDAK ADA', 1),
(19, 8, 'ADA', 2),
(20, 9, 'TIDAK ADA', 1),
(21, 9, 'ADA', 2),
(22, 10, 'TIDAK ADA', 1),
(23, 10, 'ADA', 2),
(24, 11, 'TIDAK ADA', 1),
(25, 11, 'ADA TAPI TIDAK DIMANFAATKAN', 2),
(26, 11, 'ADA DAN DIMANFAATKAN', 3),
(27, 12, 'TIDAK ADA', 1),
(28, 12, 'ADA', 2),
(29, 13, 'TIDAK ADA', 1),
(30, 13, 'ADA', 2),
(31, 14, 'TIDAK ADA', 1),
(32, 14, 'ADA', 2),
(33, 15, 'TIDAK ADA', 1),
(34, 15, 'ADA', 2),
(35, 16, 'TIDAK ADA', 1),
(36, 16, 'ADA', 2),
(37, 17, 'TIDAK ADA', 1),
(38, 17, 'ADA', 4),
(39, 18, 'TIDAK ADA', 1),
(40, 18, 'ADA', 2),
(41, 19, 'TIDAK ADA', 1),
(42, 19, 'ADA', 2),
(43, 20, 'TIDAK ADA', 1),
(44, 20, 'ADA', 2),
(45, 21, 'TIDAK ADA', 1),
(46, 21, 'ADA', 2),
(47, 22, 'TIDAK ADA', 1),
(48, 22, 'ADA', 2),
(49, 23, 'TIDAK ADA', 1),
(50, 23, 'ADA', 2),
(51, 24, 'TIDAK ADA', 1),
(52, 24, 'ADA', 4),
(53, 25, 'TIDAK ADA', 1),
(54, 25, 'ADA', 4),
(55, 26, 'TIDAK ADA', 1),
(56, 26, 'ADA', 2),
(57, 27, 'TIDAK ADA', 1),
(58, 27, 'ADA', 2),
(59, 28, 'TIDAK ADA', 1),
(60, 28, 'ADA', 2),
(61, 29, 'TIDAK ADA', 1),
(62, 29, 'ADA', 2),
(63, 30, 'TIDAK ADA', 1),
(64, 30, 'ADA', 2),
(65, 31, 'TIDAK ADA', 1),
(66, 31, 'ADA', 2),
(67, 32, 'TIDAK ADA', 1),
(68, 32, 'ADA', 2),
(69, 33, 'TIDAK ADA', 1),
(70, 33, 'ADA', 2),
(71, 34, 'BELUM ADA', 1),
(72, 34, 'ADA', 2),
(73, 35, 'TIDAK ADA', 1),
(74, 35, 'ADA', 2),
(75, 36, 'TIDAK ADA', 1),
(76, 36, 'ADA', 2),
(77, 37, 'TIDAK ADA', 1),
(78, 37, 'ADA', 2),
(79, 38, 'TIDAK ADA', 1),
(80, 38, 'ADA', 2),
(81, 39, 'TIDAK ADA', 1),
(82, 39, 'ADA', 2),
(83, 40, 'TIDAK ADA', 1),
(84, 40, 'ADA', 2),
(85, 41, 'TIDAK ADA', 1),
(86, 41, 'ADA', 2),
(87, 42, 'TIDAK ADA', 1),
(88, 42, 'ADA', 2),
(89, 43, 'TIDAK ADA', 1),
(90, 43, 'ADA', 2),
(91, 44, 'TIDAK ADA', 1),
(92, 44, 'ADA', 2),
(93, 45, 'TIDAK ADA', 1),
(94, 45, 'ADA', 2),
(95, 46, 'TIDAK ADA', 1),
(96, 46, 'ADA', 2),
(97, 47, 'TIDAK ADA', 1),
(98, 47, 'ADA', 2),
(99, 48, 'TIDAK ADA', 1),
(100, 48, 'ADA', 2),
(101, 49, 'TIDAK ADA', 1),
(102, 49, 'ADA', 2),
(103, 50, 'TIDAK ADA', 1),
(104, 50, 'ADA', 2),
(105, 51, 'TIDAK ADA', 1),
(106, 51, 'ADA', 2),
(107, 52, 'TIDAK ADA', 1),
(108, 52, 'ADA', 2),
(109, 53, 'TIDAK ADA', 1),
(110, 53, 'ADA', 2),
(111, 54, 'TIDAK ADA', 1),
(112, 54, 'ADA', 2),
(113, 55, 'TIDAK ADA', 1),
(114, 55, 'ADA', 2),
(115, 56, 'TIDAK ADA', 1),
(116, 56, 'ADA', 2),
(117, 57, 'TIDAK ADA', 1),
(118, 57, 'ADA', 2),
(119, 58, 'TIDAK ADA', 1),
(120, 58, 'ADA', 2),
(121, 59, 'TIDAK ADA', 1),
(122, 59, 'ADA', 2),
(123, 60, 'TIDAK ADA', 1),
(124, 60, 'ADA', 2),
(125, 61, 'TIDAK ADA', 1),
(126, 61, 'ADA', 2),
(127, 62, 'TIDAK ADA', 1),
(128, 62, 'ADA', 2),
(129, 63, 'TIDAK ADA', 1),
(130, 63, 'ADA', 2),
(131, 64, 'TIDAK ADA', 1),
(132, 64, 'ADA', 2),
(133, 65, 'TIDAK ADA', 1),
(134, 65, 'ADA', 2),
(135, 66, 'TIDAK ADA', 1),
(136, 66, 'ADA', 2),
(137, 67, 'TIDAK ADA', 1),
(138, 67, 'ADA', 2),
(139, 68, 'TIDAK ADA', 1),
(140, 68, 'ADA', 2),
(141, 69, 'TIDAK ADA', 1),
(142, 69, 'ADA', 2),
(143, 70, 'TIDAK ADA', 1),
(144, 70, 'ADA', 2),
(145, 71, 'TIDAK ADA', 1),
(146, 71, 'ADA', 2),
(147, 72, 'TIDAK ADA', 1),
(148, 72, 'ADA', 4),
(149, 73, 'TIDAK ADA', 1),
(150, 73, 'ADA', 2),
(151, 74, 'TIDAK ADA', 1),
(152, 74, 'ADA', 2),
(153, 75, 'TIDAK ADA', 1),
(154, 75, 'ADA', 2),
(155, 76, 'TIDAK ADA', 1),
(156, 76, 'ADA', 2),
(157, 77, 'TIDAK ADA', 1),
(158, 77, 'ADA', 2),
(159, 78, 'TIDAK ADA', 1),
(160, 78, 'ADA', 2),
(161, 79, 'TIDAK ADA', 1),
(162, 79, 'ADA', 2),
(163, 80, 'TIDAK ADA', 1),
(164, 80, 'ADA', 2),
(165, 81, 'TIDAK ADA', 1),
(166, 81, 'ADA', 2),
(167, 82, 'TIDAK ADA', 1),
(168, 82, 'ADA', 2),
(169, 83, 'TIDAK ADA', 1),
(170, 83, 'ADA', 2),
(171, 84, 'TIDAK ADA', 1),
(172, 84, 'ADA', 2),
(173, 85, 'TIDAK ADA', 1),
(174, 85, 'ADA', 2),
(175, 86, 'TIDAK ADA', 1),
(176, 86, 'ADA', 2),
(177, 87, 'TIDAK ADA', 1),
(178, 87, 'ADA', 2),
(179, 88, 'TIDAK ADA', 1),
(180, 88, 'ADA', 2),
(181, 89, 'TIDAK ADA', 1),
(182, 89, 'ADA', 2),
(183, 90, 'TIDAK ADA', 1),
(184, 90, 'ADA', 2),
(185, 91, 'TIDAK ADA', 1),
(186, 91, 'ADA', 2),
(187, 92, 'TIDAK ADA', 1),
(188, 92, 'ADA', 2),
(189, 93, 'TIDAK ADA', 1),
(190, 93, 'ADA', 2),
(191, 94, 'TIDAK ADA', 1),
(192, 94, 'ADA', 2),
(193, 95, 'TIDAK ADA', 1),
(194, 95, 'ADA', 2),
(195, 96, 'TIDAK ADA', 1),
(196, 96, 'ADA', 2),
(197, 97, 'TIDAK ADA', 1),
(198, 97, 'ADA', 2),
(199, 98, 'TIDAK ADA', 1),
(200, 98, 'ADA', 2),
(201, 99, 'TIDAK ADA', 1),
(202, 99, 'ADA', 2),
(203, 100, 'TIDAK ADA', 1),
(204, 100, 'ADA', 2),
(205, 101, 'TIDAK ADA', 1),
(206, 101, 'ADA', 2),
(207, 102, 'TIDAK ADA', 1),
(208, 102, 'ADA', 2),
(209, 103, 'TIDAK ADA', 4),
(210, 103, 'ADA', 1),
(211, 104, 'TIDAK ADA', 4),
(212, 104, 'ADA', 1),
(213, 105, 'TIDAK ADA', 4),
(214, 105, 'ADA', 1),
(219, 107, 'TIDAK ADA', 5),
(220, 107, 'ADA', 1),
(221, 108, 'TIDAK SEIMBANG', 1),
(222, 108, 'SEIMBANG', 3),
(223, 109, 'TIDAK ADA', 1),
(224, 109, 'ADA', 2),
(225, 110, 'TIDAK ADA TIM PENGELOLA KEGIATAN', 1),
(226, 110, 'ADA TIM PENGELOLA KEGIATAN', 2),
(227, 111, 'TIDAK ADA', 1),
(228, 111, 'ADA', 4),
(229, 112, 'TIDAK ADA', 1),
(230, 112, 'ADA', 2),
(231, 113, 'TIDAK ADA', 1),
(232, 113, 'ADA', 2),
(233, 114, 'TIDAK ADA', 1),
(234, 114, 'ADA', 2),
(235, 115, 'TIDAK ADA', 1),
(236, 115, 'ADA', 2),
(237, 116, 'TIDAK ADA', 1),
(238, 116, 'ADA', 2),
(239, 117, 'TIDAK ADA', 1),
(240, 117, 'ADA', 2),
(241, 118, 'TIDAK ADA', 1),
(242, 118, 'ADA', 2),
(243, 119, 'TIDAK ADA', 1),
(244, 119, 'ADA', 2),
(245, 120, 'TIDAK ADA', 1),
(246, 120, 'ADA', 4),
(247, 121, 'TIDAK ADA', 1),
(248, 121, 'ADA', 2),
(249, 122, 'TIDAK ADA', 1),
(250, 122, 'ADA', 4),
(251, 123, 'TEREALISASI 1 KEGIATAN', 1),
(252, 123, 'TEREALISASI di atas 1 KEGIATAN', 2),
(253, 124, 'TIDAK LENGKAP', 1),
(254, 124, 'LENGKAP', 2),
(255, 125, 'TIDAK LENGKAP', 1),
(256, 125, 'LENGKAP', 2),
(257, 126, 'TIDAK ADA', 1),
(258, 126, 'ADA', 2),
(259, 127, 'TIDAK ADA', 1),
(260, 127, 'ADA', 2),
(261, 128, 'TIDAK ADA', 1),
(262, 128, 'ADA', 2),
(263, 129, 'TIDAK ADA', 1),
(264, 129, 'ADA', 2),
(265, 130, 'TIDAK ADA', 1),
(266, 130, 'ADA', 2),
(267, 131, 'TIDAK ADA', 4),
(268, 131, 'ADA', 1),
(269, 132, 'TIDAK ADA', 4),
(270, 132, 'ADA', 1),
(271, 133, 'TIDAK ADA', 4),
(272, 133, 'ADA', 1),
(273, 134, 'TIDAK ADA', 4),
(274, 134, 'ADA', 1),
(275, 135, 'TIDAK ADA', 4),
(276, 135, 'ADA', 1),
(277, 136, 'TIDAK ADA', 2),
(278, 136, 'ADA', 1),
(279, 137, 'TIDAK ADA', 2),
(280, 137, 'ADA', 1),
(281, 138, 'TIDAK ADA', 2),
(282, 138, 'ADA', 1),
(283, 139, 'TIDAK ADA', 4),
(284, 139, 'ADA', 1),
(285, 140, 'TIDAK ADA', 4),
(286, 140, 'ADA', 1),
(287, 141, 'TIDAK ADA', 4),
(288, 141, 'ADA', 1),
(289, 142, 'TIDAK ADA', 4),
(290, 142, 'ADA', 1),
(291, 143, 'TIDAK ADA', 4),
(292, 143, 'ADA', 1),
(293, 144, 'TIDAK ADA', 4),
(294, 144, 'ADA', 1),
(295, 145, 'TIDAK ADA', 4),
(296, 145, 'ADA', 1),
(297, 146, 'TIDAK ADA', 4),
(298, 146, 'ADA', 1),
(299, 147, 'TIDAK ADA', 4),
(300, 147, 'ADA', 1),
(301, 148, 'TIDAK ADA', 4),
(302, 148, 'ADA', 1),
(303, 149, 'TIDAK ADA', 4),
(304, 149, 'ADA', 1),
(305, 150, 'TIDAK ADA', 4),
(306, 150, 'ADA', 1),
(307, 151, 'KURANG DARI 1%', 4),
(308, 151, 'LEBIH DARI 1%', 1),
(309, 152, 'KURANG DARI 1%', 4),
(310, 152, 'LEBIH DARI 1%', 1),
(311, 153, 'KURANG DARI 1%', 1),
(312, 153, 'LEBIH DARI 1%', 4),
(313, 154, 'KURANG DARI 1%', 1),
(314, 154, 'LEBIH DARI 1%', 4),
(315, 155, 'KURANG DARI 1%', 1),
(316, 155, 'LEBIH DARI 1%', 4),
(317, 156, 'KURANG DARI 1%', 1),
(318, 156, 'LEBIH DARI 1%', 4),
(319, 157, 'PENURUNAN KURANG DARI 10% DARI TAHUN SEBELUMNYA', 1),
(320, 157, 'PENURUNAN DI ATAS 10% DARI TAHUN SEBELUMNYA', 4),
(321, 158, 'PENURUNAN KURANG DARI 10% DARI TAHUN SEBELUMNYA', 1),
(322, 158, 'PENURUNAN DI ATAS 10% DARI TAHUN SEBELUMNYA', 2),
(323, 159, 'KURANG DARI 1%', 2),
(324, 159, 'DI ATAS 1%', 1),
(325, 160, 'TIDAK ADA', 1),
(326, 160, 'ADA', 4),
(327, 161, 'PRATAMA', 1),
(328, 161, 'DI ATAS PRATAMA', 4),
(329, 162, 'TIDAK ADA', 1),
(330, 162, 'ADA', 2),
(331, 163, 'MENURUN', 1),
(332, 163, 'TETAP', 2),
(333, 163, 'MENIGKAT', 3),
(334, 164, 'MENURUN', 3),
(335, 164, 'TETAP', 2),
(336, 164, 'MENIGKAT', 1),
(337, 165, 'MENURUN', 1),
(338, 165, 'TETAP', 2),
(339, 165, 'MENIGKAT', 3),
(340, 166, 'MENURUN', 3),
(341, 166, 'TETAP', 2),
(342, 166, 'MENIGKAT', 1),
(343, 167, 'MENURUN', 3),
(344, 167, 'TETAP', 2),
(345, 167, 'MENIGKAT', 1),
(346, 168, 'TIDAK ADA', 1),
(347, 168, 'ADA', 2),
(348, 169, 'TIDAK ADA', 1),
(349, 169, 'ADA', 2),
(350, 170, 'TIDAK ADA', 1),
(351, 170, 'ADA', 2),
(352, 171, 'KURANG DARI 10%', 4),
(353, 171, 'LEBIH DARI ATAU SAMA DENGAN 10%', 1),
(354, 175, 'TIDAK ADA', 1),
(355, 175, 'ADA', 2),
(356, 176, 'TIDAK ADA', 1),
(357, 176, 'ADA', 2),
(358, 177, 'TIDAK ADA', 1),
(359, 177, 'ADA', 2),
(360, 178, 'TIDAK ADA', 1),
(361, 178, 'ADA', 2),
(362, 179, 'TIDAK ADA', 1),
(363, 179, 'ADA', 3),
(364, 180, 'TIDAK ADA', 1),
(365, 180, 'ADA', 2),
(366, 181, '1-3', 1),
(367, 181, 'LEBIH DARI 3', 2),
(370, 184, '1-3', 1),
(371, 184, 'LEBIH DARI 3', 2);

-- --------------------------------------------------------

--
-- Table structure for table `periode`
--

CREATE TABLE `periode` (
  `id` int NOT NULL,
  `periode` varchar(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `periode`
--

INSERT INTO `periode` (`id`, `periode`) VALUES
(3, '2020'),
(4, '2021');

-- --------------------------------------------------------

--
-- Table structure for table `ranking`
--

CREATE TABLE `ranking` (
  `ranking` int NOT NULL,
  `tahun` year NOT NULL,
  `id_alternatif` int NOT NULL,
  `nilai_akhir` int NOT NULL,
  `total_wawancara` int NOT NULL,
  `total_sub_presentasi` int NOT NULL,
  `total_klarifikasi_lapangan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_indikator`
--

CREATE TABLE `sub_indikator` (
  `id` int NOT NULL,
  `id_indikator` int NOT NULL,
  `nama_sub_indikator` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sub_indikator`
--

INSERT INTO `sub_indikator` (`id`, `id_indikator`, `nama_sub_indikator`) VALUES
(1, 1, 'Gedung kantor'),
(2, 1, 'Gedung Pertemuan'),
(3, 1, 'Perangkat Kelurahan'),
(4, 1, 'Mesin tik/komputer'),
(5, 1, 'Kendaraan dinas lurah'),
(6, 1, 'Struktur Organisasi'),
(7, 1, 'Jaringan Listrik'),
(8, 1, 'Sumber Daya Listrik (PLN)'),
(9, 1, 'Sumber Daya Listrik (NON-PLN)'),
(10, 1, 'Perpustakaan'),
(11, 2, 'Kotak Pengaduan Masyarakat'),
(12, 3, 'Laporan Kinerja Tahunan'),
(13, 3, 'Laporan Tahunan Lurah'),
(14, 3, 'Papan Informasi Pelayanan'),
(15, 3, 'Loket Pelayanan'),
(16, 3, 'Buku Registrasi Pelayanan'),
(17, 3, 'Buku Profil Kelurahan'),
(18, 3, 'Buku Administrasi Umum'),
(19, 3, 'Buku Administrasi Kependudukan'),
(20, 3, 'Buku Administrasi Keuangan'),
(21, 3, 'Buku Administrasi Pembangunan'),
(22, 3, 'Buku Administrasi Lainnya'),
(23, 3, 'Kartu Uraian Tugas '),
(24, 3, 'Peta Wilayah Kelurahan'),
(25, 3, 'Peta Batas Kelurahan'),
(26, 4, 'Perencanaan Pembangunan Partisipatif Masyarakat (P3MD) (Khusus diisi oleh Desa)'),
(27, 4, 'Peningkatan kapasitas kelompok masyarakat 2 tahun terakhir'),
(28, 4, 'Fasilitasi dalam pemasaran produk unggulan dari masyarakat'),
(29, 4, 'Ada tidaknya regulasi dalam pemberdayaan masyarakat'),
(30, 4, 'melakukan forum-forum kebersamaan seperti gotong royong 2 tahun terakhir'),
(31, 4, 'Reward bagi perangkat dan kelompok masyarakat'),
(32, 4, 'apakah telah mendata kehadiran pegawai secara rutin'),
(33, 4, 'apakah telah memiliki standar jam pelayanan'),
(34, 4, 'apakah sudah memiliki Standar Operasional Prosedur (SOP) pelayanan masyarakat'),
(35, 5, 'Visi dan misi pemberdayaan masyarakat'),
(36, 5, 'Kebijakan dalam rangka pemberdayaan masyarakat'),
(37, 5, 'Eksistensi institusi pemberdayaan masyarakat dan aparatur'),
(38, 5, 'Alokasi anggaran untuk program pemberdayaan masyarakat dan aparatur desa'),
(39, 5, 'Kebijakan pemerintah desa dalam pengentasan kemiskinan di pedesaan'),
(40, 5, 'Alokasi anggaran untuk program pengentasan kemiskinan'),
(41, 6, 'Jaringan Internet'),
(42, 6, 'Website Desa'),
(43, 7, 'Software Dengan Spesifikasi Minimal Untuk Operasi Jaringan Internet'),
(44, 7, 'Hardware Dengan Spesifikasi Minimal Untuk Operasi Jaringan Internet'),
(45, 8, 'Administrasi umum'),
(46, 8, 'Administrasi kependudukan'),
(47, 8, 'Administrasi keuangan'),
(48, 8, 'Administrasi BPD'),
(49, 8, 'Administrasi pembangunan'),
(50, 8, 'Administrasi lainnya'),
(51, 8, 'Perangkat yang mengelola Teknologi Informasi'),
(52, 8, 'Tersedia tokoh pemuda teknopreneur di tingkat RT/RW'),
(53, 8, 'Perpustaan online'),
(54, 8, 'Internet gratis/HotSpot'),
(55, 9, 'Pembinaan Partisipasi Masyarakat dalam Pelestarian Adat dan Budaya'),
(56, 9, 'Keterlibatan Kelembagaan Adat dalam Pelestarian Adat dan Budaya'),
(57, 9, 'Pembinaan Seni Budaya Setempat'),
(58, 9, 'Kebijakan Menjaga kelestarian adat'),
(59, 9, 'Alokasi Anggaran Pelestarian Adat'),
(60, 10, 'Adanya produk unggulan'),
(61, 10, 'Adanya Peran pemerintah dalam mengelola produk unggulan.'),
(62, 10, 'Adanya keuntungan finansial untuk dari aktivitas ekonomi produktif.'),
(63, 10, 'Adanya keuntungan sosial dari aktivitas ekonomi produktif.'),
(64, 10, 'Adanya kegiatan kreatif yang membutuhkan teknologi.'),
(65, 11, 'Adanya pelembagaan aktivitas inovasi masyarakat (mis. UMKM, koperasi, cluster)'),
(66, 11, 'Adanya peta rencana (roadmap inovasi) secara berkelanjutan dalam mengembangkan produk unggulan desa'),
(67, 12, 'Interkoneksitas yang terbangun dalam pengelolaan produk inovasi masyarakat (mis. adanya divisi kerjasama dalam manajemen UMKM/BUMDes).'),
(68, 12, 'Kesepahaman dan kerjasama dengan pemerintahan sekitar dalam pengelolaan potensi khususnya produk unggulan.'),
(69, 12, 'Dukungan pemerintahan supra (mis. kecamatan, kabupaten, provinsi, atau pusat) bagi pengembangan produk unggulan.'),
(70, 12, 'Jaringan pengembangan (mis. dukungan dunia pendidikan dan keterampilan) untuk peningkatan kualitas produk unggulan. '),
(71, 12, 'Jejaring Kerjasama dengan pihak ketiga dalam pemasaran produk inovasi.'),
(72, 13, 'Teknologi tepat guna yang ditemukan masyarakat 2 tahun terakhir.'),
(73, 13, 'Pemanfaatan teknologi tepat guna.'),
(74, 13, 'Lembaga penyedia teknologi.'),
(75, 13, 'Aktivitas masyarakat dalam pengembangan produk'),
(76, 13, 'Upaya pelestarian pengembangan produk.'),
(77, 14, 'Adanya integrasi antara peta rencana (roadmap) inovasi dengan perencanaan pembangunan tahunan dan lima tahunan (Mis. Rencana Pembangunan Jangka Pendek dan Menengah).'),
(78, 14, 'Adanya sinergi pengembangan inovasi dengan kerangka Sistem Inovasi Daerah (SIDa).'),
(79, 15, 'Penemuan inovasi yang ramah lingkungan'),
(80, 15, 'Kemampuan penyesuaian produk inovasi terhadap dinamika tuntutan konsumen/pasar'),
(81, 15, 'Adanya rencana pengembangan produk inovasi di dalam maupun di luar desa dan kelurahan'),
(82, 15, 'Adanya sinergi berbagai lembaga dalam pembangunan inovatif (Heksagonal)'),
(83, 16, 'Spesialisasi Produk Unggulan'),
(84, 16, 'Dukungan Penelitian dan Pengembangan'),
(85, 16, 'Pengembangan Sumber Daya Manusia ataupun dari Supranya'),
(86, 16, 'Ketersediaan dan Akses Bahan Baku dari dalam desa dan kelurahan'),
(87, 16, 'Ketersediaan Sumberdaya Modal dari pemerintah Desa dan Kelurahan atau pihak ke tiga'),
(88, 16, 'Pelatihan Kewirausahaan'),
(89, 16, 'Adanya Kepemimpinan dan Visi Bersama dalam mengembangkan dan mengelola potensi Desa dan Kelurahan'),
(90, 17, 'Adanya musyawarah perencanaan identifikasi bencana'),
(91, 18, 'Ketersediaan peta bencana beserta rambu-rambunya'),
(92, 18, 'Sosialisasi mengenai peta bencana pada masyarakat dalam waktu 2 tahun terakhir ini'),
(93, 19, 'Pengetahuan dan simulasi dalam menghadapi Risiko 2 tahun terakhir'),
(94, 19, 'Sistem Pemantauan yang dikembangkan pemerintah Desa dan Kelurahan dalam menghadapi bencana'),
(95, 19, 'Layanan TIM penanganan bencana yang di bentuk Desa dan Kelurahan'),
(96, 19, 'Penyebarluasan dan Komunikasi tanggap bencana'),
(97, 19, 'Alat deteksi dini bencana'),
(98, 20, 'Tempat Evakuasi'),
(99, 20, 'Jalur Evakuasi'),
(100, 20, 'Sarana Evakuasi'),
(101, 21, 'Investasi yang masik ke Desa dan Kelurahan dalam 2 tahun terakhir'),
(102, 21, 'Apakah melibatkan BPD dan Pemerintah Desa dan Kelurahan'),
(103, 21, 'Menyebabkan terjadinya pembebanan pada Desa dan Kelurahan'),
(104, 21, 'Menyebabkan terjadinya alih fungsi lahan pertanian'),
(105, 21, 'Mengurangi jumlah kepemilikan Tanah Desa/Tanah Kas Desa (Khusus Diisi Oleh Desa)'),
(107, 22, 'Partisipasi Masyarakat'),
(108, 22, 'Rasio Laki-Laki Dan Perempuan'),
(109, 23, 'Partisipasi Pendanaan Masyarakat'),
(110, 24, 'Partisipasi Pengelolaan Pembangunan oleh Masyarakat'),
(111, 25, 'Aktifitas Gotong Royong Penduduk'),
(112, 26, 'Aktifitas Organisasi Pemuda'),
(113, 27, 'Aktifitas Organisasi Profesi'),
(114, 28, 'Aktifitas Organisasi Olah Raga'),
(115, 29, 'Aktifitas LPM'),
(116, 30, 'Aktifitas Kelompok Gotong Royong'),
(117, 31, 'Aktifitas Karang Taruna '),
(118, 32, 'Aktifitas Lembaga Adat, Budaya, Dan Kesenian'),
(119, 33, 'Aktifitas Kelompok Usaha'),
(120, 34, 'Memiliki Koperasi'),
(121, 35, 'Aktifitas Organisasi Perempuan'),
(122, 36, 'Keberadaan PKK'),
(123, 37, 'Realisasi 10 Program Pokok'),
(124, 38, 'Kelengkapan Kelompok Kerja'),
(125, 38, 'Kelengkapan Kelompok Dasawisma'),
(126, 39, 'Kerja sama pelestarian lingkungan'),
(127, 39, 'Kerja sama pemantauan limbah perusahaan yang ada di desa'),
(128, 39, 'Kerja sama pendaur ulangan limbah'),
(129, 39, 'Petugas keamanan lingkungan (Linmas)'),
(130, 39, 'Pos kamling (Keamanan Lingkungan)'),
(131, 40, 'Konflik antar kelompok'),
(132, 40, 'Konflik antar suku '),
(133, 40, 'Konflik berbau agama/kepercayaan'),
(134, 40, 'Konflik antar RAS '),
(135, 41, 'Kasus perkelahian yang menimbulkan korban dalam 2 tahun terakhir'),
(136, 42, 'Kasus pencurian/perampokan biasa'),
(137, 42, 'Kasus pencurian/perampokan dengan kekerasan'),
(138, 43, 'Jumlah kasus perjudian dengan berbagai modus '),
(139, 44, 'Jumlah kasus narkoba dengan pelaku pemerintah desa'),
(140, 44, 'Anggota Masyarakat yang Terkena Narkoba'),
(141, 45, 'Jumlah kasus prostitusi dengan berbagai modus'),
(142, 46, 'Jumlah kasus pembunuhan '),
(143, 46, 'Jumlah kasus pembunuhan yang korbannya penduduk desa setempat'),
(144, 46, 'Jumlah kasus pembunuhan yang pelakunya penduduk desa setempat '),
(145, 47, 'Jumlah kasus perkosaan '),
(146, 48, 'Kekerasan terhadap anak'),
(147, 48, 'Kekerasan terhadap anggota keluarga lainnya'),
(148, 49, 'Jumlah kasus penculikan '),
(149, 50, 'Kasus HIV/AIDS'),
(150, 51, 'Penduduk yang tidak bisa baca tulis'),
(151, 52, 'Jumlah Penduduk Tidak Tamat SD/sederajat'),
(152, 52, 'Jumlah Penduduk Tidak Tamat SLTP/sederajat'),
(153, 53, 'Jumlah penduduk tamat SLTA/sederajat'),
(154, 53, 'Jumlah penduduk tamat D3/Sarjana muda'),
(155, 53, 'Jumlah penduduk tamat Sarjana/S-1'),
(156, 53, 'Jumlah penduduk tamat Pasca Sarjana'),
(157, 54, 'Jumlah Kematian Bayi'),
(158, 55, 'Jumlah Balita Gizi Buruk'),
(159, 55, 'Jumlah Balita Meninggal'),
(160, 56, 'Keberadaan Posyandu'),
(161, 56, 'Kelembagaan'),
(162, 56, 'Jumlah RT Pengguna Sumber Air Lainnya'),
(163, 57, 'Total RT Mempunyai Jamban/WC sendiri'),
(164, 57, 'Total RT yang tidak memiliki jamban/WC sendiri'),
(165, 57, 'Total RT pengguna MCK umum'),
(166, 57, 'Total RT pengguna MCK di sungai/kali'),
(167, 57, 'Total RT yang tidak mendapat air bersih'),
(168, 58, 'Puskesmas/Balai Pengobatan'),
(169, 58, 'Bidan/Mantri/Dokter'),
(170, 58, 'Jamban Keluarga/MCK'),
(171, 59, 'Jumlah penduduk usia 15-65 tahun yang tidak\r\nbekerja'),
(172, 60, 'Pertanian'),
(173, 60, 'Industri'),
(174, 60, 'Jasa'),
(175, 61, 'Pasar Tradisional'),
(176, 61, 'Toko/Kios'),
(177, 61, 'Pangkalan Ojek, Becak, Delman, Dan Sejenisnya'),
(178, 62, 'Data Masyarakat Miskin'),
(179, 63, 'Program Penanggulangan Kemiskinan'),
(180, 64, 'Penyusunan Analisis Kebutuhan Peningkatan Kapasitas Masyarakat'),
(181, 64, 'Banyaknya Kegiatan Dalam Peningkatan Kapasitas Masyarakat'),
(184, 65, 'Jumlah Jenis program Peningkatan Kapasitas Masyarakat');

-- --------------------------------------------------------

--
-- Table structure for table `sub_presentasi`
--

CREATE TABLE `sub_presentasi` (
  `id` int NOT NULL,
  `id_alternatif` int NOT NULL,
  `isi_materi` int NOT NULL,
  `organisir_waktu` int NOT NULL,
  `tanya_jawab` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sub_wawancara`
--

CREATE TABLE `sub_wawancara` (
  `id` int NOT NULL,
  `id_alternatif` int NOT NULL,
  `kerjasama_tim` int NOT NULL,
  `kemampuan_lurah` int NOT NULL,
  `kemampuan_problem_solving` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `id_kelurahan` varchar(50) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` enum('kelurahan','admin','pimpinan','penilai') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `id_kelurahan`, `username`, `password`, `role`) VALUES
(6, NULL, 'admin', '$2y$10$TQzDUHzdQQdGJWJC.hs5puSi87AVVlDm.j5ni9oFLp3JQiq2epmqy', 'admin'),
(9, NULL, 'pimpinan', '$2y$10$TXu/uroNhiOBkuqYYdF4s.NswA0JcyJPTC41KZkbPTbJFM/9ISfUa', 'pimpinan'),
(10, NULL, 'penilai1', '$2y$10$EW5heQ1x3.neRuFxo05fUeGkOmh9ExoTGHC44TxVm5s/7ZM3d4AnG', 'penilai'),
(11, NULL, 'penilai2', '$2y$10$PGFwQ6OzGCRtdR6RSw4hc.HouLw1ZX/s.knRTEZO1nrk5g0.jFlkq', 'penilai'),
(15, 'a', 'kelurahana', '$2y$10$ejlKnyQPgj/f7ugbXk2EcOXUZIodhOu0dlLq59uR59/SnBuvIrhke', 'kelurahan'),
(16, 'b', 'kelurahanb', '$2y$10$BfTRX6D3xPdzAx5qMtO/8eQK3E/ilNYrVuLbWwFOZVRJYVTvprVDi', 'kelurahan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrasi`
--
ALTER TABLE `administrasi`
  ADD PRIMARY KEY (`id_administrasi`),
  ADD KEY `id_kelurahan` (`id_kelurahan`),
  ADD KEY `id_periode` (`id_periode`),
  ADD KEY `id_nilai_sub_indikator` (`id_nilai_sub_indikator`);

--
-- Indexes for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_alternatif` (`id_assesment`),
  ADD KEY `FK_alternatif_id_kriteria` (`id_kriteria`);

--
-- Indexes for table `assesment`
--
ALTER TABLE `assesment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_assestment_id_user` (`id_user`);

--
-- Indexes for table `bidang`
--
ALTER TABLE `bidang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `detail_assesment`
--
ALTER TABLE `detail_assesment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_detail_assesment_id_assesment` (`id_assesment`),
  ADD KEY `FK_detail_assesment_id_sub_indikator` (`id_sub_indikator`);

--
-- Indexes for table `indikator`
--
ALTER TABLE `indikator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_indikator_id_bidang` (`id_bidang`);

--
-- Indexes for table `kelurahan`
--
ALTER TABLE `kelurahan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelurahan_periode`
--
ALTER TABLE `kelurahan_periode`
  ADD PRIMARY KEY (`id_kelurahan`,`id_periode`),
  ADD KEY `id_periode` (`id_periode`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `jenis` (`jenis`,`tahun`);

--
-- Indexes for table `nilai_sub_indikator`
--
ALTER TABLE `nilai_sub_indikator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sub_indikator` (`id_sub_indikator`);

--
-- Indexes for table `periode`
--
ALTER TABLE `periode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ranking`
--
ALTER TABLE `ranking`
  ADD PRIMARY KEY (`tahun`,`ranking`) USING BTREE,
  ADD KEY `FK_ranking_id_alternatif` (`id_alternatif`);

--
-- Indexes for table `sub_indikator`
--
ALTER TABLE `sub_indikator`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_sub_indikator_id_indikator` (`id_indikator`);

--
-- Indexes for table `sub_presentasi`
--
ALTER TABLE `sub_presentasi`
  ADD KEY `FK_sub_presentasi_id_alternatif` (`id_alternatif`);

--
-- Indexes for table `sub_wawancara`
--
ALTER TABLE `sub_wawancara`
  ADD KEY `FK_sub_wawancara_id_alternatif` (`id_alternatif`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_users_id_kelurahan` (`id_kelurahan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administrasi`
--
ALTER TABLE `administrasi`
  MODIFY `id_administrasi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bidang`
--
ALTER TABLE `bidang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `indikator`
--
ALTER TABLE `indikator`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `nilai_sub_indikator`
--
ALTER TABLE `nilai_sub_indikator`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=372;

--
-- AUTO_INCREMENT for table `periode`
--
ALTER TABLE `periode`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sub_indikator`
--
ALTER TABLE `sub_indikator`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrasi`
--
ALTER TABLE `administrasi`
  ADD CONSTRAINT `administrasi_ibfk_1` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `administrasi_ibfk_2` FOREIGN KEY (`id_periode`) REFERENCES `periode` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `administrasi_ibfk_3` FOREIGN KEY (`id_nilai_sub_indikator`) REFERENCES `nilai_sub_indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `alternatif`
--
ALTER TABLE `alternatif`
  ADD CONSTRAINT `FK_alternatif` FOREIGN KEY (`id_assesment`) REFERENCES `assesment` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_alternatif_id_kriteria` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `assesment`
--
ALTER TABLE `assesment`
  ADD CONSTRAINT `FK_assestment_id_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `detail_assesment`
--
ALTER TABLE `detail_assesment`
  ADD CONSTRAINT `FK_detail_assesment_id_assesment` FOREIGN KEY (`id_assesment`) REFERENCES `assesment` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_detail_assesment_id_sub_indikator` FOREIGN KEY (`id_sub_indikator`) REFERENCES `sub_indikator` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `indikator`
--
ALTER TABLE `indikator`
  ADD CONSTRAINT `FK_indikator_id_bidang` FOREIGN KEY (`id_bidang`) REFERENCES `bidang` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `kelurahan_periode`
--
ALTER TABLE `kelurahan_periode`
  ADD CONSTRAINT `kelurahan_periode_ibfk_1` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kelurahan_periode_ibfk_2` FOREIGN KEY (`id_periode`) REFERENCES `periode` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `nilai_sub_indikator`
--
ALTER TABLE `nilai_sub_indikator`
  ADD CONSTRAINT `nilai_sub_indikator_ibfk_1` FOREIGN KEY (`id_sub_indikator`) REFERENCES `sub_indikator` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ranking`
--
ALTER TABLE `ranking`
  ADD CONSTRAINT `FK_ranking_id_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `sub_indikator`
--
ALTER TABLE `sub_indikator`
  ADD CONSTRAINT `FK_sub_indikator_id_indikator` FOREIGN KEY (`id_indikator`) REFERENCES `indikator` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sub_presentasi`
--
ALTER TABLE `sub_presentasi`
  ADD CONSTRAINT `FK_sub_presentasi_id_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `sub_wawancara`
--
ALTER TABLE `sub_wawancara`
  ADD CONSTRAINT `FK_sub_wawancara_id_alternatif` FOREIGN KEY (`id_alternatif`) REFERENCES `alternatif` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_id_kelurahan` FOREIGN KEY (`id_kelurahan`) REFERENCES `kelurahan` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
