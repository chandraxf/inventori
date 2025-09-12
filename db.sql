-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.11.9-MariaDB-log - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table inventori.barang_keluar
CREATE TABLE IF NOT EXISTS `barang_keluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_transaksi` varchar(50) NOT NULL,
  `tanggal_keluar` date NOT NULL,
  `unit_id` int(11) DEFAULT NULL COMMENT 'Unit/bagian penerima',
  `jenis_keluar` enum('pemakaian','retur_ke_supplier','rusak','koreksi_minus','lainnya') DEFAULT 'pemakaian',
  `nomor_permintaan` varchar(50) DEFAULT NULL COMMENT 'Nomor surat permintaan barang',
  `nama_penerima` varchar(100) DEFAULT NULL,
  `total_nilai` decimal(15,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `status` enum('draft','posted','cancelled') DEFAULT 'draft',
  `user_input` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_nomor_transaksi` (`nomor_transaksi`),
  KEY `idx_tanggal` (`tanggal_keluar`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `barang_keluar_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `master_unit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.barang_keluar: ~0 rows (approximately)
REPLACE INTO `barang_keluar` (`id`, `nomor_transaksi`, `tanggal_keluar`, `unit_id`, `jenis_keluar`, `nomor_permintaan`, `nama_penerima`, `total_nilai`, `keterangan`, `status`, `user_input`, `created_at`, `updated_at`) VALUES
	(1, 'BK-202509-0001', '2025-09-06', 3, 'pemakaian', '011', 'Chandra Endira', 240000.00, 'testing', 'posted', 'admin', '2025-09-06 09:10:46', '2025-09-06 09:11:50');

-- Dumping structure for table inventori.barang_keluar_detail
CREATE TABLE IF NOT EXISTS `barang_keluar_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_keluar_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL COMMENT 'Menggunakan harga rata-rata saat keluar',
  `total` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_transaksi` (`barang_keluar_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `barang_keluar_detail_ibfk_1` FOREIGN KEY (`barang_keluar_id`) REFERENCES `barang_keluar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_keluar_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `master_barang` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.barang_keluar_detail: ~0 rows (approximately)
REPLACE INTO `barang_keluar_detail` (`id`, `barang_keluar_id`, `barang_id`, `jumlah`, `harga_satuan`, `total`, `keterangan`) VALUES
	(1, 1, 560, 2, 120000.00, 240000.00, NULL);

-- Dumping structure for table inventori.barang_masuk
CREATE TABLE IF NOT EXISTS `barang_masuk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_transaksi` varchar(50) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `jenis_masuk` enum('pembelian','retur_dari_unit','koreksi_plus','stok_awal','lainnya') DEFAULT 'pembelian',
  `nomor_faktur` varchar(50) DEFAULT NULL COMMENT 'Nomor faktur/nota pembelian',
  `nama_supplier` varchar(255) DEFAULT NULL COMMENT 'Nama supplier (opsional)',
  `total_nilai` decimal(15,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `status` enum('draft','posted','cancelled') DEFAULT 'draft',
  `user_input` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_nomor_transaksi` (`nomor_transaksi`),
  KEY `idx_tanggal` (`tanggal_masuk`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.barang_masuk: ~0 rows (approximately)
REPLACE INTO `barang_masuk` (`id`, `nomor_transaksi`, `tanggal_masuk`, `jenis_masuk`, `nomor_faktur`, `nama_supplier`, `total_nilai`, `keterangan`, `status`, `user_input`, `created_at`, `updated_at`) VALUES
	(5, 'BM-202509-0001', '2025-09-05', 'pembelian', '121212', 'Twincom', 600000.00, 'Testing', 'posted', 'admin', '2025-09-05 22:58:59', '2025-09-05 22:59:00');

-- Dumping structure for table inventori.barang_masuk_detail
CREATE TABLE IF NOT EXISTS `barang_masuk_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_masuk_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `expired_date` date DEFAULT NULL COMMENT 'Untuk barang yang ada kadaluarsa',
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_transaksi` (`barang_masuk_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `barang_masuk_detail_ibfk_1` FOREIGN KEY (`barang_masuk_id`) REFERENCES `barang_masuk` (`id`) ON DELETE CASCADE,
  CONSTRAINT `barang_masuk_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `master_barang` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.barang_masuk_detail: ~0 rows (approximately)
REPLACE INTO `barang_masuk_detail` (`id`, `barang_masuk_id`, `barang_id`, `jumlah`, `harga_satuan`, `total`, `expired_date`, `keterangan`) VALUES
	(1, 5, 560, 5, 120000.00, 600000.00, NULL, NULL);

-- Dumping structure for table inventori.detail_permintaan
CREATE TABLE IF NOT EXISTS `detail_permintaan` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_PERMINTAAN` int(11) NOT NULL,
  `ID_BARANG` int(11) NOT NULL DEFAULT 0,
  `JUMLAH` int(11) NOT NULL,
  `JUMLAH_DISETUJUI` int(11) DEFAULT NULL,
  `STATUS` int(11) NOT NULL COMMENT '0 terkirim, 1 disetujui, 2 ditolak',
  `CREATED_AT` datetime NOT NULL DEFAULT current_timestamp(),
  `UPDATED_AT` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table inventori.detail_permintaan: ~0 rows (approximately)

-- Dumping structure for table inventori.kartu_stok
CREATE TABLE IF NOT EXISTS `kartu_stok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `barang_id` int(11) NOT NULL,
  `jenis_transaksi` enum('masuk','keluar','opname_plus','opname_minus','saldo_awal') NOT NULL,
  `nomor_referensi` varchar(50) NOT NULL,
  `stok_awal` int(11) NOT NULL,
  `masuk` int(11) DEFAULT 0,
  `keluar` int(11) DEFAULT 0,
  `stok_akhir` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `nilai_masuk` decimal(15,2) DEFAULT 0.00,
  `nilai_keluar` decimal(15,2) DEFAULT 0.00,
  `nilai_saldo` decimal(15,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_tanggal` (`tanggal`),
  KEY `idx_barang` (`barang_id`),
  CONSTRAINT `kartu_stok_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `master_barang` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.kartu_stok: ~1 rows (approximately)
REPLACE INTO `kartu_stok` (`id`, `tanggal`, `barang_id`, `jenis_transaksi`, `nomor_referensi`, `stok_awal`, `masuk`, `keluar`, `stok_akhir`, `harga_satuan`, `nilai_masuk`, `nilai_keluar`, `nilai_saldo`, `keterangan`, `created_at`) VALUES
	(5, '2025-09-05', 560, 'masuk', 'BM-202509-0001', 0, 5, 0, 5, 120000.00, 600000.00, 0.00, 600000.00, 'Barang Masuk No: BM-202509-0001', '2025-09-05 22:59:00'),
	(6, '2025-09-06', 560, 'keluar', 'BK-202509-0001', 5, 0, 2, 3, 120000.00, 0.00, 240000.00, 360000.00, 'Barang Keluar No: BK-202509-0001', '2025-09-06 09:11:50');

-- Dumping structure for table inventori.master_barang
CREATE TABLE IF NOT EXISTS `master_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_rek_108` varchar(50) NOT NULL,
  `nama_barang_108` varchar(100) NOT NULL,
  `kode_nusp` varchar(50) NOT NULL,
  `nama_nusp` varchar(255) NOT NULL,
  `kode_gudang` varchar(255) NOT NULL,
  `nama_gudang` varchar(255) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `stok_minimum` int(11) DEFAULT 0 COMMENT 'Stok minimum untuk warning',
  `harga_terakhir` decimal(15,2) DEFAULT 0.00,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_kode_rek` (`kode_rek_108`)
) ENGINE=InnoDB AUTO_INCREMENT=562 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.master_barang: ~549 rows (approximately)
REPLACE INTO `master_barang` (`id`, `kode_rek_108`, `nama_barang_108`, `kode_nusp`, `nama_nusp`, `kode_gudang`, `nama_gudang`, `satuan`, `stok_minimum`, `harga_terakhir`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
	(11, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0034', 'Baterai CMOS', '1.1.7.01.03.08.010.0034.001', 'Batterai Cmos', 'Buah', 0, 7000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(12, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.306', 'Catridge 810 Black', '1.1.7.01.03.06.004.306.001', 'Cartridge Canon 810 Hitam', 'Buah', 0, 220500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(13, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.307', 'Catridge 811 Color', '1.1.7.01.03.06.004.307.001', 'Cartridge Canon 811 Warna', 'Buah', 0, 275000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(14, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.0230', 'Cartridge BH-7 DAN CH-7', '1.1.7.01.03.06.004.0230.001', 'Catridge Warna Canon G2000', 'Buah', 0, 419000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(15, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0154', 'Klem', '1.1.7.01.03.08.012.0154.001', 'Clamp Kabel Beton', 'Buah', 0, 12000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(16, '1.1.7.01.03.06.006', 'USB/Flash Disk', '1.1.7.01.03.06.006.0004', 'Flasdisk 32 Gb', '1.1.7.01.03.06.006.0004.001', 'FD Sandisk 32 GB', 'Buah', 0, 190000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(17, '1.1.7.01.03.06.006', 'USB/Flash Disk', '1.1.7.01.03.06.006.0005', 'Flasdisk 64 Gb', '1.1.7.01.03.06.006.0005.001', 'FD Sandisk 64 GB', 'Buah', 0, 80000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(18, '1.1.7.01.03.06.006', 'USB/Flash Disk', '1.1.7.01.03.06.006.0008', 'Flasdisk 128 Gb', '1.1.7.01.03.06.006.0008.001', 'FD Sandisk 128 GB', 'Buah', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(19, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0035', 'External/ Portable Hardisk', '1.1.7.01.03.06.014.0035.001', 'Hardisk External ADATA HD710 PRO 1TB RED', 'Buah', 0, 35000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(20, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.36', 'HDD Caddy', '1.1.7.01.03.06.014.36.001', 'HDD Caddy 9,5 MM', 'Buah', 0, 400000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(21, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.37', 'HTB', '1.1.7.01.03.06.014.37.001', 'HTB', 'Buah', 0, 977999.99, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(22, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0107', 'Konektor Telpon RJ45', '1.1.7.01.03.08.001.0107.001', 'Konektor RJ45 Cat 6', 'Buah', 0, 1500000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(23, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.08.001.0094', 'Kabel ties ', '1.1.7.01.03.08.001.0094.001', 'Kabel Ties', 'Pak', 0, 2500000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(24, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.38', 'Kabel USB Ext + Chipset NYK', '1.1.7.01.03.06.014.38.001', 'Kabel USB Ext 2.0 + Chipset NYK 10M CE20', 'Buah', 0, 3000000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(25, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.39', 'Kabel USB Ext + Chipset NYK', '1.1.7.01.03.06.014.39.001', 'Kabel USB Ext 3.0 + Chipset NYK 10M CE20', 'Buah', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(26, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.40', 'Kabel Vga', '1.1.7.01.03.06.014.40.001', 'Kabel VGA Vention Gold Plate 10M DADBL10', 'Buah', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(27, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0001', 'Keybord & mouse wireles', '1.1.7.01.03.06.014.0001.001', 'Keyboard + Mouse Logitech MK-120', 'Set', 0, 205000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(28, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0001', 'Keybord & mouse wireles', '1.1.7.01.03.06.014.0001.002', 'Keyboard + Mouse Logitech', 'Set', 0, 110000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(29, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0001', 'Keybord & mouse wireles', '1.1.7.01.03.06.014.0001.003', 'Keyboard + Mouse M-Tech STK-03', 'Set', 0, 235000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(30, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0118', 'Kabel Patch Core', '1.1.7.01.03.08.001.0118.001', 'Kabel Patch Core', 'Buah', 0, 150000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(31, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.126', 'Kabel LAN CAT 5', '1.1.7.01.03.08.001.126.001', 'Kabel LAN CAT 5', 'Rol', 0, 220000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(32, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.127', 'Kabel LAN CAT 5', '1.1.7.01.03.08.001.127.001', 'Kabel LAN CAT 6', 'Rol', 0, 175000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(33, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.08.001.128', 'Kabel Fiber Optic Core Global', '1.1.7.01.03.08.001.128.001', 'Kabel Fiber Optic Core Global', 'Rol', 0, 300000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(34, '1.1.7.01.03.06.010', 'Mouse', '1.1.7.01.03.02.002.0181', 'Label Tape Epson 9 mm', '1.1.7.01.03.02.002.0181.001', 'Label Tape Epson 9 mm', 'Buah', 0, 120000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(35, '1.1.7.01.03.06.010', 'Mouse', '1.1.7.01.03.06.010.0005', 'Mouse Wireless', '1.1.7.01.03.06.010.0005.001', 'Mouse Wireles REXUS Q-50 PURPLSILENT CLICK', 'Unit', 0, 75000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(36, '1.1.7.01.03.06.010', 'Mouse', '1.1.7.01.03.06.010.0005', 'Mouse Wireless', '1.1.7.01.03.06.010.0005.002', 'Mouse Logitech MK-170', 'Unit', 0, 164000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(37, '1.1.7.01.03.06.010', 'Mouse', '1.1.7.01.03.06.010.0004', 'Mousepad', '1.1.7.01.03.06.010.0004.001', 'Mousepad', 'Buah', 0, 164000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(38, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.010.0003', 'Mouse  Kabel', '1.1.7.01.03.06.010.0003.001', 'Mouse Kabel Logitech', 'Buah', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(39, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.42', 'Paket Bahan Komputer', '1.1.7.01.03.06.014.42.001', 'Pembelian Paket Bahan Komputer', 'Paket', 0, 1500000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(40, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.43', 'Panel LCD', '1.1.7.01.03.06.014.43.001', 'Panel LCD FHD 144Hz', 'Unit', 0, 377500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(41, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.014.44', 'Part Printer', '1.1.7.01.03.06.014.44.001', 'Part ASF Printer Canon G2000', 'Buah', 0, 287578.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(42, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.007.0019', 'RAM 4GB', '1.1.7.01.03.06.007.0019.001', 'RAM Venom RX 4GB 2666Mhz', 'Unit', 0, 272800.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(43, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.007.0019', 'RAM 4GB', '1.1.7.01.03.06.007.0019.002', 'RAM DDR4 Dahua 2666', 'Unit', 0, 400000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(44, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.007.0019', 'RAM 4GB', '1.1.7.01.03.06.007.0019.003', 'RAM Venom RX 4GB 3200 Mhz', 'Unit', 0, 215000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(45, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.007.21', 'RAM 16GB', '1.1.7.01.03.06.007.21.001', 'RAM Venom RX 16GB DDR4', 'Unit', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(46, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0003', 'Speaker', '1.1.7.01.03.06.014.0003.001', 'Speaker Gaming ROBOT RS200 LED', 'Buah', 0, 535000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(47, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.50', 'SSD 512 GB', '1.1.7.01.03.06.014.50.001', 'SSD ADATA SU650 SATA 2,5" 512 GB', 'Unit', 0, 330000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(48, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.49', 'SSD 256 GB', '1.1.7.01.03.06.014.49.001', 'SSD Venom RX 256GB', 'Unit', 0, 122000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(49, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.014.46', 'USB HUB', '1.1.7.01.03.06.014.46.001', 'USB HUB 4 Port 3.0 1,2 M', 'Buah', 0, 73000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(50, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.007.20', 'RAM 8GB', '1.1.7.01.03.06.007.20.001', 'Venom RX 8GB SODIMm DDR4 2666Mhz', 'Unit', 0, 375000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(51, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.47', 'Wireles Router', '1.1.7.01.03.06.014.47.001', 'Wireles Router Ruijie 1200 Pro v.3.20', 'Buah', 0, 539000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(52, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.288', 'Acrylic F4', '1.1.7.01.03.01.016.288.001', 'Acrylic F4', 'Buah', 0, 84100.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(53, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.289', 'Acrylic Tempat Leaflet Uk.12', '1.1.7.01.03.01.016.289.001', 'Acrylic Tempat Leaflet Uk.12,3 x 25,4 20,3 cm', 'Buah', 0, 126000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(54, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.290', 'Acrylic Tempat Leaflet Uk.24', '1.1.7.01.03.01.016.290.001', 'Acrylic Tempat Leaflet Uk.24,1 x 32,1 20,3 cm', 'Buah', 0, 161500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(55, '1.1.7.01.03.02.004', 'Amplop', '1.1.7.01.03.02.004.0021', 'Amplop Besar ', '1.1.7.01.03.02.004.0021.001', 'Amplop Besar Pakai Lem', 'Buah', 0, 26000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(56, '1.1.7.01.03.02.004', 'Amplop', '1.1.7.01.03.02.004.0023', 'Amplop Kecil ', '1.1.7.01.03.02.004.0023.001', 'Amplop Kecil Pakai Lem', 'Lembar', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(57, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0161', 'Stampat Pat (Bantalan) Ot/T', '1.1.7.01.03.01.016.0161.001', 'Bak Stamp', 'Buah', 0, 15000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(58, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0001', 'Batteray  A2', '1.1.7.01.03.08.010.0001.001', 'Batterai A2', 'Set', 0, 9500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(59, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0002', 'Batteray  A3', '1.1.7.01.03.08.010.0002.001', 'Batterai A3', 'Set', 0, 8500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(60, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0023', 'Baterai Besar D (Isi 2)', '1.1.7.01.03.08.010.0023.001', 'Batterai Besar', 'Buah', 0, 14833.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(61, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0049', 'Binder Clips No. 105', '1.1.7.01.03.01.003.0049.001', 'Binder Clip No. 105', 'Kotak', 0, 45000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(62, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0045', 'Binder Clips no. 107', '1.1.7.01.03.01.003.0045.001', 'Binder Clip No. 107', 'Kotak', 0, 9000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(63, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0008', 'binder clips no. 155', '1.1.7.01.03.01.003.0008.001', 'Binder Clip No. 155', 'Buah', 0, 12000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(64, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0047', 'Binder Clips no. 200', '1.1.7.01.03.01.003.0047.001', 'Binder Clip No. 200', 'Kotak', 0, 19000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(65, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0048', 'Binder Clips no. 260', '1.1.7.01.03.01.003.0048.001', 'Binder Clip No. 260', 'Kotak', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(66, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0040', 'BOX FILE 4011 A', '1.1.7.01.03.01.006.0040.001', 'Box File', 'Buah', 0, 53282.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(67, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0072', 'Buku Agenda Keluar/Masuk Isi/100 Lembar', '1.1.7.01.03.01.005.0072.001', 'Buku Agenda', 'Buah', 0, 35000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(68, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0020', 'Buku Double Folio  Isi 100 Lembar', '1.1.7.01.03.01.005.0020.001', 'Buku Besar Double Folio', 'Buah', 0, 36000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(69, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0001', 'Buku Folio - Isi 100 Lembar', '1.1.7.01.03.01.005.0001.001', 'Buku Besar Folio', 'Buah', 0, 24000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(70, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0002', 'Buku Kwarto - Isi 100 Lembar', '1.1.7.01.03.01.005.0002.001', 'Buku Kecil Quarto', 'Buah', 0, 14000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(71, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0073', 'Buku Tulis Polos', '1.1.7.01.03.01.005.0073.001', 'Buku Tulis', 'Buah', 0, 6900.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(72, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0026', 'Karbon Folio/100 Lbr', '1.1.7.01.03.01.016.0026.001', 'Carbon Paper', 'Dus', 0, 797.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(73, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.306', 'Catridge 810 Black', '1.1.7.01.03.06.004.306.002', 'Catridge 810 Hitam', 'Buah', 0, 234000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(74, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.307', 'Catridge 811 Color', '1.1.7.01.03.06.004.307.002', 'Catridge 811', 'Buah', 0, 287000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(75, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.308', 'Catridge Canon 47 Black', '1.1.7.01.03.06.004.308.001', 'Catridge Canon 47', 'Buah', 0, 145000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(76, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.309', 'Catridge Canon 57 Color', '1.1.7.01.03.06.004.309.001', 'Catridge Canon 57 Warna', 'Buah', 0, 235000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(77, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0004', 'Paper Clips - No 3', '1.1.7.01.03.01.003.0004.001', 'Clip Paper', 'Pak', 0, 6000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(78, '1.1.7.01.03.01.008', 'Cutter (Alat Tulis Kantor)', '1.1.7.01.03.01.008.0005', 'cutter kecil', '1.1.7.01.03.01.008.0005.001', 'Cutter', 'Buah', 0, 22200.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(79, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0206', 'gunting ', '1.1.7.01.03.01.016.0206.001', 'Gunting Kertas', 'Buah', 0, 12000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(80, '1.1.7.01.03.01.013', 'Isi Staples', '1.1.7.01.03.01.013.0018', 'Isi Stapples Atom 24/63', '1.1.7.01.03.01.013.0018.001', 'Isi Staples Besar', 'Dus', 0, 4850.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(81, '1.1.7.01.03.01.013', 'Isi Staples', '1.1.7.01.03.01.013.0020', 'Isi Staples - No 10', '1.1.7.01.03.01.013.0020.001', 'Isi Staples Kecil', 'Pak', 0, 4000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(82, '1.1.7.01.03.01.013', 'Isi Staples', '1.1.7.01.03.01.013.0020', 'Isi Staples - No 10', '1.1.7.01.03.01.013.0020.002', 'Isi Staples Kecil A', 'Pak', 0, 3300.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(83, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0024', 'ISOLASI NACHI', '1.1.7.01.03.01.010.0024.001', 'Isolasi Bening', 'Buah', 0, 3900.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(84, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0019', 'Doubletape-2"', '1.1.7.01.03.01.010.0019.001', 'Isolasi Double Tipe', 'Rol', 0, 17000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(85, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0207', 'kalkulator', '1.1.7.01.03.01.016.0207.001', 'Kalkulator', 'Buah', 0, 73000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(86, '1.1.7.01.03.06.001', 'Continuous Form', '1.1.7.01.03.06.001.10', 'Kertas Continous Form 2ply', '1.1.7.01.03.06.001.10.001', 'Kertas 2 Ply', 'Box', 0, 375000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(87, '1.1.7.01.03.06.001', 'Continuous Form', '1.1.7.01.03.06.001.0003', 'Kertas Continous Form 3ply', '1.1.7.01.03.06.001.0003.001', 'Kertas 3 Ply PRS', 'Box', 0, 530000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(88, '1.1.7.01.03.06.001', 'Continuous Form', '1.1.7.01.03.06.001.11', 'Kertas Continous Form 4ply', '1.1.7.01.03.06.001.11.001', 'Kertas 4 Ply PRS 500 Sheet', 'Box', 0, 410000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(89, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0009', 'Kertas Hvs 70 Gr A3/500 Lbr', '1.1.7.01.03.02.001.0009.001', 'Kertas A3', 'Rim', 0, 92400.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(90, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0014', 'Kertas Hvs 80 Gr A3/500 Lbr', '1.1.7.01.03.02.001.0014.001', 'Kertas A3', 'Rim', 0, 122490.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(91, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.182', 'Kertas BC Isi 100 Lembar', '1.1.7.01.03.02.002.182.001', 'Kertas BC Putih', 'Pak', 0, 40000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(92, '1.1.7.01.03.02.002', 'Berbagai Kertas', '', '', '.000', 'Kertas Buram', '', 0, 45000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(93, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0114', 'kertas foto A4 ', '1.1.7.01.03.02.002.0114.001', 'Kertas Foto', 'Pak', 0, 80000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(94, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0002', 'Kertas HVS 70 gr A4/500 lbr', '1.1.7.01.03.02.001.0002.001', 'Kertas HVS A4 70 GSM', 'Rim', 0, 65000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(95, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0001', 'Kertas HVS 70 gr F4/500 lbr', '1.1.7.01.03.02.001.0001.001', 'Kertas HVS F4 70 GSM', 'Rim', 0, 70000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(96, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.30', 'Kertas HVS 70 Gr F4 / 500 lbr Blue', '1.1.7.01.03.02.001.30.001', 'Kertas HVS F4 70 GSM BLUE', 'Rim', 0, 89900.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(97, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.31', 'Kertas HVS 70 Gr F4 / 500 lbr Green', '1.1.7.01.03.02.001.31.001', 'Kertas HVS F4 70 GSM GREEN', 'Rim', 0, 89900.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(98, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.32', 'Kertas HVS 70 Gr F4 / 500 lbr Red', '1.1.7.01.03.02.001.32.001', 'Kertas HVS F4 70 GSM RED', 'Rim', 0, 89900.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(99, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.33', 'Kertas HVS 70 Gr F4 / 500 lbr Yellow', '1.1.7.01.03.02.001.33.001', 'Kertas HVS F4 70 GSM YELLOW', 'Rim', 0, 89900.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(100, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.183', 'Kertas PVC ID CARD', '1.1.7.01.03.02.002.183.001', 'Kertas Khusus ID Card', 'Pak', 0, 285000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(101, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0157', 'Kertas Sertifikat', '1.1.7.01.03.02.002.0157.001', 'Kertas Piagam', 'Pak', 0, 65900.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(102, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0140', 'Kertas Sticky Notes', '1.1.7.01.03.02.002.0140.001', 'Kertas Sticker', 'Pak', 0, 36000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(103, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.184', 'Kertas Struk Kasir 75 x 60 MM', '1.1.7.01.03.02.002.184.001', 'Kertas Struk 3 Ply Kasir', 'Rol', 0, 14000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(104, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0167', 'Thermal Paper Roll Pendek', '1.1.7.01.03.02.002.0167.001', 'Kertas Thermal Uk. 57 x 50 mm', 'Rol', 0, 13875.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(105, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0168', 'Thermal Paper Roll Sedang', '1.1.7.01.03.02.002.0168.001', 'Kertas Thermal Uk. 80 x 80 mm', 'Rol', 0, 16200.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(106, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.186', 'Label 80 X 50 Putih', '1.1.7.01.03.02.002.186.001', 'Label 80 X 50 Putih', 'Rol', 0, 277500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(107, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.291', 'Lakban', '1.1.7.01.03.01.016.291.001', 'Lakban', 'Buah', 0, 17000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(108, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0219', 'Lakban Bening', '1.1.7.01.03.01.016.0219.001', 'Lakban Bening', 'Buah', 0, 15000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(109, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0022', 'Lakban Coklat', '1.1.7.01.03.01.010.0022.001', 'Lakban Coklat', 'Buah', 0, 15000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(110, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0022', 'Lakban Coklat', '1.1.7.01.03.01.010.0022.002', 'Lakban Coklat A', 'Buah', 0, 25530.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(111, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0043', 'Lakban Hitam Besar', '1.1.7.01.03.01.010.0043.001', 'Lakban Hitam', 'Buah', 0, 19000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(112, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.49', 'Lakban Kertas uk. 48 mm x 12 m', '1.1.7.01.03.01.010.49.001', 'Lakban Kertas', 'Buah', 0, 11700.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(113, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0020', 'Glue Stick', '1.1.7.01.03.01.010.0020.001', 'Lem Stick', 'Buah', 0, 8000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(114, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.50', 'Magnet Papan Tulis ', '1.1.7.01.03.01.010.50.001', 'Magnet Papan Tulis', 'Buah', 0, 1200.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(115, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.292', 'Map Album Plastik', '1.1.7.01.03.01.016.292.001', 'Map Album', 'Buah', 0, 30000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(116, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0075', 'Map Odner', '1.1.7.01.03.01.006.0075.001', 'Map File', 'Buah', 0, 44000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(117, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0072', 'Map Odner uk 50 mm', '1.1.7.01.03.01.006.0072.001', 'Map File Kecil 50 mm', 'Buah', 0, 39600.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(118, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0029', 'Map Kertas', '1.1.7.01.03.01.006.0029.001', 'Map Kertas', 'Pak', 0, 1300.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(119, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.016.0257', 'Map Kertas Biasa ', '1.1.7.01.03.01.016.0257.001', 'Map Kertas Biasa', 'Pak', 0, 30500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(120, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0044', 'Map Plastik Jepit Folder', '1.1.7.01.03.01.006.0044.001', 'Map Plastik Jepit', 'Lusin', 0, 13000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(121, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0073', 'Map Plastik Berlubang', '1.1.7.01.03.01.006.0073.001', 'Map Plastik Lobang', 'Buah', 0, 12000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(122, '1.1.7.01.03.01.008', 'Cutter (Alat Tulis Kantor)', '1.1.7.01.03.01.008.0006', 'isi cutter kecil', '1.1.7.01.03.01.008.0006.001', 'Mata Cutter', 'Pak', 0, 1400.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(123, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0074', 'Mika ID Card', '1.1.7.01.03.01.006.0074.001', 'Mika Id Card', 'Buah', 0, 1800.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(124, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.293', 'Papan Tulis Uk. 120 X 240', '1.1.7.01.03.01.016.293.001', 'Papan Tulis Uk. 120 X 240', 'Buah', 0, 525000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(125, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0040', 'Pelobang Kertas Besar', '1.1.7.01.03.01.003.0040.001', 'Pelobang Kertas Besar', 'Buah', 0, 53100.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(126, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0041', 'Pelobang Kertas Kecil', '1.1.7.01.03.01.003.0041.001', 'Pelobang Kertas Kecil', 'Buah', 0, 17000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(127, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.294', 'Pembelian Paket', '1.1.7.01.03.01.016.294.001', 'Pembelian ATK', 'Paket', 0, 13680000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(128, '1.1.7.01.03.01.007', 'Penggaris', '1.1.7.01.03.01.007.0009', 'Penggaris Plastik 30 cm', '1.1.7.01.03.01.007.0009.001', 'Penggaris 30 cm', 'Pcs', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(129, '1.1.7.01.03.01.007', 'Penggaris', '1.1.7.01.03.01.007.0012', 'Penggaris Plastik 60 cm', '1.1.7.01.03.01.007.0012.001', 'Penggaris 60 cm', 'Buah', 0, 20100.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(130, '1.1.7.01.03.01.004', 'Penghapus/Korektor', '1.1.7.01.03.01.004.0001', 'Penghapus Whiteboard - Kecil', '1.1.7.01.03.01.004.0001.001', 'Penghapus Papan Tulis', 'Buah', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(131, '1.1.7.01.03.01.004', 'Penghapus/Korektor', '1.1.7.01.03.01.004.0040', ' Penghapus Pensil ', '1.1.7.01.03.01.004.0040.001', 'Penghapus Pensil', 'Buah', 0, 2500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(132, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0012', 'Pensil B 2B', '1.1.7.01.03.01.001.0012.001', 'Pensil', 'Buah', 0, 2000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(133, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0082', 'Pensil Merah Biru', '1.1.7.01.03.01.001.0082.001', 'Pensil Merah Biru', 'Buah', 0, 3850.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(134, '1.1.7.01.03.06.003', 'Pita Printer', '1.1.7.01.03.06.003.0010', 'Pita Catridge Epson TM U220', '1.1.7.01.03.06.003.0010.001', 'Pita Catridge Epson TM U220', 'Buah', 0, 65000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(135, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.310', 'Pita Printer & Catridge Epson LX-300', '1.1.7.01.03.06.004.310.001', 'Pita Printer & Catridge Epson LX-300', 'Buah', 0, 64000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(136, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.311', 'Pita Printer & Catridge Epson LX-310', '1.1.7.01.03.06.004.311.001', 'Pita Printer & Catridge Epson LX-310', 'Buah', 0, 83000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(137, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.295', 'Sheet Protector F4 / Folio', '1.1.7.01.03.01.016.295.001', 'Plastik Bantex F4 A', 'Pak', 0, 35000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(138, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.296', 'Post It uk. 76x101 mm / 3x4"', '1.1.7.01.03.01.016.296.001', 'Post IT Besar', 'Buah', 0, 8500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(139, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.297', 'Post It uk. 76x101 mm / 3x4"', '1.1.7.01.03.01.016.297.001', 'Post IT Besar', 'Buah', 0, 25000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(140, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.298', 'Post It Panah', '1.1.7.01.03.01.016.298.001', 'Post IT Panah', 'Buah', 0, 15000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(141, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.299', 'Post It uk. 76x76 mm / 3x3"', '1.1.7.01.03.01.016.299.001', 'Post IT Rainbow', 'Buah', 0, 19800.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(142, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0062', 'Ballpoint Balliner Biru', '1.1.7.01.03.01.001.0062.001', 'Pulpen Tinta Biru (Balliner)', 'Buah', 0, 19700.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(143, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.111', 'Pulpen Tinta Biru Standart', '1.1.7.01.03.01.001.111.001', 'Pulpen Tinta Biru (Standart)', 'Buah', 0, 2800.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(144, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0044', 'Ballpoint Tizzo Biru', '1.1.7.01.03.01.001.0044.001', 'Pulpen Tinta Biru (TIZO) A', 'Buah', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(145, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.112', 'Pulpen Tinta Hitam', '1.1.7.01.03.01.001.112.001', 'Pulpen Tinta Hitam', 'Buah', 0, 3000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(146, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.113', 'Pulpen Tinta Merah', '1.1.7.01.03.01.001.113.001', 'Pulpen Tinta Merah', 'Buah', 0, 4662.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(147, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.300', 'Push Pins ', '1.1.7.01.03.01.016.300.001', 'Push Pins', 'Buah', 0, 2900.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(148, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0123', 'Rautan Pensil ', '1.1.7.01.03.01.016.0123.001', 'Rautan Pensil', 'Buah', 0, 3052.50, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(149, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0023', 'spidol boardmarker', '1.1.7.01.03.01.001.0023.001', 'Spidol Board Marker', 'Buah', 0, 12000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(150, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0142', 'Spidol Marker', '1.1.7.01.03.01.016.0142.001', 'Spidol Marker', 'Buah', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(151, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0149', 'Spidol White Board ', '1.1.7.01.03.01.016.0149.001', 'Spidol White', 'Buah', 0, 17000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(152, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0217', 'Stabilo', '1.1.7.01.03.01.016.0217.001', 'Stabillo', 'Pcs', 0, 7500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(153, '1.1.7.01.03.01.012', 'Staples', '1.1.7.01.03.01.012.0011', 'Staples Besar', '1.1.7.01.03.01.012.0011.001', 'Staples Besar', 'Buah', 0, 34410.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(154, '1.1.7.01.03.01.012', 'Staples', '1.1.7.01.03.01.012.0012', 'Staples Kecil', '1.1.7.01.03.01.012.0012.001', 'Staples Kecil', 'Buah', 0, 17000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(155, '1.1.7.01.03.01.015', 'Seminar Kit', '1.1.7.01.03.01.015.0014', 'Isi Stapples 1210', '1.1.7.01.03.01.015.0014.001', 'Tali Id Card', 'Dus', 0, 1100.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(156, '1.1.7.01.03.01.008', 'Cutter (Alat Tulis Kantor)', '1.1.7.01.03.01.008.0016', 'Tape Cutter', '1.1.7.01.03.01.008.0016.001', 'Tape Cuttter', 'Buah', 0, 11500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(157, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.312', 'Tinta Barcode', '1.1.7.01.03.06.004.312.001', 'Tinta Barcode', 'Buah', 0, 62150.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(158, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.313', 'Tinta Printer Brother Biru', '1.1.7.01.03.06.004.313.001', 'Tinta Brother Biru', 'Buah', 0, 52000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(159, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.314', 'Tinta Printer Brother Hitam', '1.1.7.01.03.06.004.314.001', 'Tinta Brother Hitam', 'Buah', 0, 46000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(160, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.315', 'Tinta Printer Brother Kuning', '1.1.7.01.03.06.004.315.001', 'Tinta Brother Kuning', 'Buah', 0, 52000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(161, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.316', 'Tinta Printer Brother Merah', '1.1.7.01.03.06.004.316.001', 'Tinta Brother Merah', 'Buah', 0, 52000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(162, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.317', 'Tinta Print Epson 057', '1.1.7.01.03.06.004.317.001', 'Tinta Print Epson 057', 'Botol', 0, 310000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(163, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.318', 'Tinta Printer Canon Biru C790', '1.1.7.01.03.06.004.318.001', 'Tinta Printer Canon Biru C790', 'Botol', 0, 142100.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(164, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.319', 'Tinta Printer Canon Hitam', '1.1.7.01.03.06.004.319.001', 'Tinta Printer Canon Hitam', 'Buah', 0, 35000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(165, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.320', 'Tinta Printer Canon Hitam BK790', '1.1.7.01.03.06.004.320.001', 'Tinta Printer Canon Hitam BK790', 'Botol', 0, 148000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(166, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.321', 'Tinta Printer Canon Kuning Y790', '1.1.7.01.03.06.004.321.001', 'Tinta Printer Canon Kuning Y790', 'Botol', 0, 142100.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(167, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.322', 'Tinta Printer Canon Merah M790', '1.1.7.01.03.06.004.322.001', 'Tinta Printer Canon Merah M790', 'Buah', 0, 142100.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(168, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.326', 'Tinta Printer Warna', '1.1.7.01.03.06.004.326.001', 'Tinta Printer Canon Warna', 'Botol', 0, 33000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(169, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0007', 'Tinta Epson 003 Biru', '1.1.7.01.03.03.002.0007.001', 'Tinta Printer Epson Biru 003', 'Botol', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(170, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0012', 'Tinta Epson 664 Biru', '1.1.7.01.03.03.002.0012.001', 'Tinta Printer Epson Biru 664', 'Buah', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(171, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0013', 'Tinta Epson Biru C 001', '1.1.7.01.03.03.002.0013.001', 'Tinta Printer Epson Biru C 001', 'Buah', 0, 125000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(172, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0001', 'Tinta Epson Hitam 003', '1.1.7.01.03.03.002.0001.001', 'Tinta Printer Epson Hitam 003', 'Botol', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(173, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0014', 'Tinta Epson Hitam 008', '1.1.7.01.03.03.002.0014.001', 'Tinta Printer Epson Hitam 008', 'Buah', 0, 152500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(174, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0002', 'Tinta Epson Hitam 664', '1.1.7.01.03.03.002.0002.001', 'Tinta Printer Epson Hitam 664', 'Botol', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(175, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0015', 'Tinta Epson Hitam BK 001', '1.1.7.01.03.03.002.0015.001', 'Tinta Printer Epson Hitam BK 001', 'Buah', 0, 152500.00, NULL, 'aktif', '2025-09-05 22:39:50', NULL),
	(176, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0006', 'Tinta Epson 003 Kuning', '1.1.7.01.03.03.002.0006.001', 'Tinta Printer Epson Kuning 003', 'Botol', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(177, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0016', 'Tinta Epson 664 Kuning', '1.1.7.01.03.03.002.0016.001', 'Tinta Printer Epson Kuning 664', 'Botol', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(178, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0017', 'Tinta Epson Kuning Y 001', '1.1.7.01.03.03.002.0017.001', 'Tinta Printer Epson Kuning Y 001', 'Botol', 0, 130000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(179, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0008', 'Tinta Epson 003 Magenta', '1.1.7.01.03.03.002.0008.001', 'Tinta Printer Epson Merah 003', 'Botol', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(180, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0018', 'Tinta Epson 664 Merah', '1.1.7.01.03.03.002.0018.001', 'Tinta Printer Epson Merah 664', 'Botol', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(181, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0019', 'Tinta Epson Merah M 001', '1.1.7.01.03.03.002.0019.001', 'Tinta Printer Epson Merah M 001', 'Botol', 0, 130000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(182, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0020', 'Tinta Printer Fargo C50 SN : C1410125', '1.1.7.01.03.03.002.0020.001', 'Tinta Printer Fargo C50 SN : C1410125', 'Botol', 0, 555000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(183, '1.1.7.01.03.01.004', 'Penghapus/Korektor', '1.1.7.01.03.01.004.0046', 'Tipe X', '1.1.7.01.03.01.004.0046.001', 'Tipe X', 'Buah', 0, 7500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(184, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.226', 'Aki GS Goldstar P.NS402L', '1.1.7.01.03.08.012.226.001', 'Aki GS Goldstar P.NS402L', 'Buah', 0, 785000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(185, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0130', 'Alat cukur Rambut', '1.1.7.01.03.07.018.0130.001', 'Alat Cukur Rambut', 'Buah', 0, 299000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(186, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0392', 'Alat Pembersih Kaca', '1.1.7.01.03.07.015.0392.001', 'Alat Pembersih Kaca', 'Buah', 0, 17500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(187, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0286', 'Sabit', '1.1.7.01.03.07.015.0286.001', 'Arit', 'Buah', 0, 85000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(188, '1.1.7.01.04.01.001', 'Obat Cair', '1.1.7.01.04.01.001.0066', 'Antiseptik Terralin 2 Liter -', '1.1.7.01.04.01.001.0066.001', 'Aseptan', 'Kemasan', 0, 10450.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(189, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.883', 'Baby Oil 200 ml', '1.1.7.01.01.12.999.883.001', 'Baby Oil 200 ml', 'Botol', 0, 47269.23, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(190, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0131', 'Bantal Dakron', '1.1.7.01.03.07.018.0131.001', 'Bantal Dakron', 'Buah', 0, 96400.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(191, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.227', 'Batere As_GD Part No. 5706511', '1.1.7.01.03.08.012.227.001', 'Batere As_GD Part No. 5706511', 'Buah', 0, 1645167.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(192, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0018', 'Baterai 9V', '1.1.7.01.03.08.010.0018.001', 'Batterai 9 Volt', 'Buah', 0, 20900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(193, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0393', 'Batu Asahan', '1.1.7.01.03.07.015.0393.001', 'Batu Asahan', 'Buah', 0, 30000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(194, '1.1.7.01.04.01.001', 'Obat Cair', '1.1.7.01.04.01.001.1688', 'Bedak Wajah', '1.1.7.01.04.01.001.1688.001', 'Bedak Wajah', 'Buah', 0, 16750.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(195, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0014', 'Bendera', '1.1.7.01.03.09.007.0014.001', 'Bendera Merah Putih', 'Buah', 0, 50000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(196, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.32', 'Bento Box Deposible', '1.1.7.01.03.07.009.32.001', 'Bento Box Deposible', 'Pak', 0, 2500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(197, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.0025', 'Bento Makan', '1.1.7.01.03.07.009.0025.001', 'Bento Makan', 'Buah', 0, 75000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(198, '1.1.7.01.03.09.006', 'Atribut', '1.1.7.01.03.09.006.0031', 'Borgol Kecil', '1.1.7.01.03.09.006.0031.001', 'Borgol', 'Buah', 0, 96570.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(199, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0394', 'Botol Spray Semprotan Air', '1.1.7.01.03.07.015.0394.001', 'Botol Spray Semprotan Air', 'Buah', 0, 22500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(200, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0132', 'Box Container SAFE CB 65', '1.1.7.01.03.07.018.0132.001', 'Box Container SAFE CB 65', 'Buah', 0, 254967.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(201, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0133', 'Box Gajah 100 liter', '1.1.7.01.03.07.018.0133.001', 'Box Gajah 100 liter', 'Buah', 0, 164000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(202, '1.1.7.01.02.01.001', 'Suku Cadang Alat Angkutan Darat Bermotor', '1.1.7.01.02.01.001.0565', 'Busi', '1.1.7.01.02.01.001.0565.001', 'Busi Mesin Rumput', 'Buah', 0, 30000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(203, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0395', 'Cangkul', '1.1.7.01.03.07.015.0395.001', 'Cangkul', 'Buah', 0, 65000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(204, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.0025', 'Celana Dalam Wanita', '1.1.7.01.05.01.999.0025.001', 'Celana Dalam Wanita (CD)', 'Buah', 0, 11800.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(205, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.1000', 'Celemek Anti Air Terpal', '1.1.7.01.05.01.999.1000.001', 'Celemek Anti Air Terpal', 'Lembar', 0, 73400.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(206, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.1001', 'Celemek Kain', '1.1.7.01.05.01.999.1001.001', 'Celemek Kain', 'Lembar', 0, 118000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(207, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0396', 'Cotton Bud', '1.1.7.01.03.07.015.0396.001', 'Cotton Bud', 'Buah', 0, 7450.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(208, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0397', 'Cup Seller', '1.1.7.01.03.07.015.0397.001', 'Cup Seller', 'Pak', 0, 15000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(209, '1.1.7.01.03.07.003', 'Ember, Slang, Dan Tempat Air Lainnya', '1.1.7.01.03.07.003.0050', 'Ember Isi 10 Ltr', '1.1.7.01.03.07.003.0050.001', 'Ember Isi 10 Ltr', 'Buah', 0, 38000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(210, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.05.01.999.0080', 'Filter Air / Saringan Air', '1.1.7.01.05.01.999.0080.001', 'Filter Saringan Air 10"', 'Buah', 0, 28000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(211, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.05.01.999.0080', 'Filter Air / Saringan Air', '1.1.7.01.05.01.999.0080.002', 'Filter Saringan Air 20"', 'Buah', 0, 60000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(212, '1.1.7.01.01.12.001', 'Alat Kesehatan', '1.1.7.01.01.12.001.0628', 'Foot Step', '1.1.7.01.01.12.001.0628.001', 'Foot Step', 'Buah', 0, 874500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(213, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.27', 'Galon', '1.1.7.01.03.07.009.27.001', 'Galon', 'Buah', 0, 72000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(214, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.28', 'Galon Air Berkeran', '1.1.7.01.03.07.009.28.001', 'Galon Air Berkeran', 'Buah', 0, 70000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(215, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0017', 'Gayung Mandi ', '1.1.7.01.03.07.015.0017.001', 'Gayung Mandi', 'Buah', 0, 17900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(216, '1.1.7.01.04.01.002', 'Obat Padat', '1.1.7.01.04.01.002.1067', 'Gelang Dewasa Biru', '1.1.7.01.04.01.002.1067.001', 'Gelang Thermal Dewasa Blue', 'Biji', 0, 550000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(217, '1.1.7.01.04.01.002', 'Obat Padat', '1.1.7.01.04.01.002.1068', 'Gelang Dewasa Pink', '1.1.7.01.04.01.002.1068.001', 'Gelang Thermal Dewasa Pink', 'Biji', 0, 550000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(218, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0134', 'Gembok Uk. 50', '1.1.7.01.03.07.018.0134.001', 'Gembok Uk. 50', 'Buah', 0, 54500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(219, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0315', 'Gunting Rumput', '1.1.7.01.03.07.015.0315.001', 'Gunting Rumput', 'Buah', 0, 85000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(220, '1.1.7.01.03.07.016', 'Hand Sanitizer', '1.1.7.01.03.07.016.0001', 'Hand Sanitizer 5 L', '1.1.7.01.03.07.016.0001.001', 'Handwash 5 liter', 'Buah', 0, 850000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(221, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0016', 'Handy Talky', '1.1.7.01.03.09.007.0016.001', 'Handy Talky', 'Buah', 0, 743700.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(222, '1.1.7.01.03.07.012', 'Pengharum Ruangan', '1.1.7.01.03.07.012.0027', 'Pengharum Ruangan Spray', '1.1.7.01.03.07.012.0027.001', 'Isi Pengharum Ruangan Ref. 225 ml', 'Buah', 0, 60000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(223, '1.1.7.01.03.13.999', 'Alat/Bahan Untuk Kegiatan Kantor Lainnya Lain - lain', '1.1.7.01.03.13.999.0001', 'Jam Dinding', '1.1.7.01.03.13.999.0001.001', 'Jam Dinding', 'Buah', 0, 120000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(224, '1.1.7.01.03.09.003', 'Penutup Badan', '1.1.7.01.03.09.003.0005', 'Jas Hujan ', '1.1.7.01.03.09.003.0005.001', 'Jas Hujan', 'Buah', 0, 241980.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(225, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.0866', 'Jepitan Baju', '1.1.7.01.01.12.999.0866.001', 'Jepitan Baju', 'Unit', 0, 8250.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(226, '1.1.7.01.03.08.999', 'Alat Listrik dan Elektronik Lain-lain', '1.1.7.01.03.08.999.0016', 'kabel magnet', '1.1.7.01.03.08.999.0016.001', 'Kabel Magnet', 'Buah', 0, 50000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(227, '1.1.7.01.07.01.003', 'Makan Minum Harian Pasien', '1.1.7.01.07.01.003.0197', 'Kantong Plastik Gula', '1.1.7.01.07.01.003.0197.001', 'Kantong Plastik Gula', 'Pak', 0, 9500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(228, '1.1.7.01.07.01.003', 'Makan Minum Harian Pasien', '1.1.7.01.07.01.003.0198', 'Kantong Plastik Putih', '1.1.7.01.07.01.003.0198.001', 'Kantong Plastik Putih', 'Unit', 0, 15000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(229, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.867', 'Kapi 2"', '1.1.7.01.01.12.999.867.001', 'Kapi 2"', 'Buah', 0, 13000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(230, '1.1.7.01.01.02.999', 'Bahan Kimia Lain-lain', '1.1.7.01.01.02.999.0002', 'Kaporit', '1.1.7.01.01.02.999.0002.001', 'Kaporit Tjiwi Kimia 60 %', 'Kilogram', 0, 899100.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(231, '1.1.7.01.03.07.012', 'Pengharum Ruangan', '1.1.7.01.03.07.012.0026', 'Kapur Barus', '1.1.7.01.03.07.012.0026.001', 'Kapur Barus Ngengat', 'Bag / Bungkus ', 0, 23500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(232, '1.1.7.01.03.07.004', 'Keset Dan Tempat Sampah', '1.1.7.01.03.07.004.0024', 'Karpet Mie', '1.1.7.01.03.07.004.0024.001', 'Karpet Mie Bihun Anti Slip', 'Meter', 0, 650000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(233, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.868', 'Kastok Pakaian', '1.1.7.01.01.12.999.868.001', 'Kastok Pakaian', 'Buah', 0, 1833.32, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(234, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.869', 'Kenop Gas', '1.1.7.01.01.12.999.869.001', 'Kenop Gas', 'Buah', 0, 30000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(235, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0068', 'Keranjang  Keranjang Pakaian', '1.1.7.01.03.07.015.0068.001', 'Keranjang Basket Besar Uk. 43 cm', 'Buah', 0, 68000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(236, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0068', 'Keranjang  Keranjang Pakaian', '1.1.7.01.03.07.015.0068.002', 'Keranjang Pakaian', 'Buah', 0, 73000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(237, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0017', 'Kerucut Lalu Lintas', '1.1.7.01.03.09.007.0017.001', 'Kerucut Jalan', 'Buah', 0, 166500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(238, '1.1.7.01.01.02.999', 'Bahan Kimia Lain-lain', '1.1.7.01.01.02.999.0043', 'Klorset Tablet Isi 25 Tablet', '1.1.7.01.01.02.999.0043.001', 'Klorset Tablet Isi 25 Tablet', 'Botol', 0, 200000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(239, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.870', 'Kompor Mata Seribu', '1.1.7.01.01.12.999.870.001', 'Kompor Mata Seribu', 'Buah', 0, 125000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(240, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.33', 'Kotak Makan Disposible', '1.1.7.01.03.07.009.33.001', 'Kotak Makan Disposible', 'Pak', 0, 90000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(241, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.36', 'Kotak Makan Persegi Empat', '1.1.7.01.03.07.009.36.001', 'Kotak Makan Persegi Empat', 'Set', 0, 50000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(242, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.29', 'Kotak Makan Transparan Plastik', '1.1.7.01.03.07.009.29.001', 'Kotak Makan Transparan Plastik', 'Buah', 0, 22000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(243, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.34', 'Kotak Snack R3', '1.1.7.01.03.07.009.34.001', 'Kotak Snack R3', 'Pak', 0, 70000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(244, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.871', 'Kran Galon Standart', '1.1.7.01.01.12.999.871.001', 'Kran Galon Standart', 'Buah', 0, 7000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(245, '1.1.7.01.03.07.013', 'Kuas', '1.1.7.01.03.07.013.0002', 'Kuas 2.5 Inch', '1.1.7.01.03.07.013.0002.001', 'Kuas Cat 2,5"', 'Buah', 0, 15500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(246, '1.1.7.01.01.01.022', 'Bahan Bangunan Dan Konstruksi Lainnya', '1.1.7.01.01.01.022.0439', 'Kunci Dekson', '1.1.7.01.01.01.022.0439.001', 'Kunci Dekson', 'Buah', 0, 25000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(247, '1.1.7.01.01.01.022', 'Bahan Bangunan Dan Konstruksi Lainnya', '', '', '.000', 'Kunci Estilo', '', 0, 30000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(248, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0018', 'Lampu Lalu Lintas', '1.1.7.01.03.09.007.0018.001', 'Lampu Lalu Lintas', 'Buah', 0, 180000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(249, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.0016', 'Kain Pel  ', '1.1.7.01.03.07.002.0016.001', 'Lap Lantai Putih (Kain Selabar)', 'Buah', 0, 17000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(250, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.0034', 'Lap Meja (Kanebo)', '1.1.7.01.03.07.002.0034.001', 'Lap Meja (Kanebo)', 'Buah', 0, 19500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(251, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.35', 'Lap Micro Fiber', '1.1.7.01.03.07.002.35.001', 'Lap Micro Fiber', 'Lembar', 0, 12833.33, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(252, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.36', 'Lap Tangan', '1.1.7.01.03.07.002.36.001', 'Lap Tangan', 'Lembar', 0, 7000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(253, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.54', 'Lem Fox 1 Kg', '1.1.7.01.03.01.010.54.001', 'Lem Fox 1 Kg', 'Kaleng', 0, 55000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(254, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.55', 'Lem Fox 600 Gr', '1.1.7.01.03.01.010.55.001', 'Lem Fox 600 Gr', 'Kaleng', 0, 35000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(255, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.51', 'Lem Tikus', '1.1.7.01.03.01.010.51.001', 'Lem Tikus', 'Buah', 0, 24071.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(256, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.872', 'Lipstik', '1.1.7.01.01.12.999.872.001', 'Lipstik', 'Buah', 0, 18700.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(257, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.35', 'Mika KT4', '1.1.7.01.03.07.009.35.001', 'Mika KT4', 'Pak', 0, 18000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(258, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.04.01.999.0003', 'Minyak Kayu Putih 15 ml Lang', '1.1.7.01.04.01.999.0003.001', 'Minyak Kayu Putih 150 ml', 'Botol', 0, 46500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(259, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.05.01.999.0215', 'Minyak rambut', '1.1.7.01.05.01.999.0215.001', 'Minyak Rambut Urang Aring 125 ml', 'Pcs', 0, 18800.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(260, '1.1.7.01.03.13.999', 'Alat/Bahan Untuk Kegiatan Kantor Lainnya Lain - lain', '1.1.7.01.03.13.999.23', 'Minyak telon bayi', '1.1.7.01.03.13.999.23.001', 'Minyak Telon 150 ml', 'Buah', 0, 46644.44, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(261, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.00104', 'Mukena', '1.1.7.01.05.01.999.00104.001', 'Mukena', 'Lembar', 0, 100000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(262, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0105', 'Obat Nyamuk Cair 600 Ml', '1.1.7.01.03.07.015.0105.001', 'Obat Nyamuk Spray 600 ml', 'Kaleng', 0, 58400.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(263, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0398', 'Obat Rumput', '1.1.7.01.03.07.015.0398.001', 'Obat Rumput', 'Liter', 0, 105000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(264, '1.1.7.01.01.04.002', 'Minyak Pelumas', '1.1.7.01.01.04.002.0079', 'Oli Samping', '1.1.7.01.01.04.002.0079.001', 'Oli Samping', 'Buah', 0, 50000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(265, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.0023', 'Pakaian Dalam Wanita (Bra)', '1.1.7.01.05.01.999.0023.001', 'Pakaian Dalam Wanita (BH)', 'Buah', 0, 22700.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(266, '1.1.7.01.07.02.002', 'Pakan Ikan', '1.1.7.01.07.02.002.0012', 'Pakan Ikan Active', '1.1.7.01.07.02.002.0012.001', 'Pakan Ikan Active', 'Karung', 0, 211000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(267, '1.1.7.01.03.12.002', 'Souvenir / Cenderamata Lainnya', '1.1.7.01.03.12.002.0023', 'Paket Sembako', '1.1.7.01.03.12.002.0023.001', 'Paket Sembako', 'Paket', 0, 150000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(268, '1.1.7.01.03.13.999', 'Alat/Bahan Untuk Kegiatan Kantor Lainnya Lain - lain', '1.1.7.01.03.13.999.0034', 'Papan Tulis Magnet 60x90 cm', '1.1.7.01.03.13.999.0034.001', 'Papan Tulis Magnet 60x90 cm', 'Buah', 0, 99000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(269, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0399', 'Parang', '1.1.7.01.03.07.015.0399.001', 'Parang', 'Buah', 0, 100000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(270, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0400', 'Paratop', '1.1.7.01.03.07.015.0400.001', 'Paratop', 'Botol', 0, 100000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(271, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.0002', 'Pewangi dan Pelembut Pakaian 5 L', '1.1.7.01.01.12.999.0002.001', 'Parfum Laundry 5 ltr', 'Buah', 0, 240000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(272, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0134', 'Pasta Gigi 120 Gram', '1.1.7.01.03.07.015.0134.001', 'Pasta Gigi 120 gr', 'Buah', 0, 14400.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(273, '1.1.7.01.03.12.002', 'Souvenir / Cenderamata Lainnya', '1.1.7.01.03.12.002.0010', 'Payung', '1.1.7.01.03.12.002.0010.001', 'Payung Jumbo', 'Buah', 0, 194250.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(274, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.873', 'Pembalut Wanita Bersayap', '1.1.7.01.01.12.999.873.001', 'Pembalut Wanita Bersayap', 'Buah', 0, 19900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(275, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.83', 'Pembersih Kaca Ref. 425 ml', '1.1.7.01.03.07.008.83.001', 'Pembersih Kaca Ref. 425 ml', 'Bag / Bungkus ', 0, 17500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(276, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.0082', 'Pembersih Kaca Ref. 500 ml', '1.1.7.01.03.07.008.0082.001', 'Pembersih Kaca Ref. 500 ml', 'Botol', 0, 12490.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(277, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.0068', 'Pembersih Lantai 770 ml', '1.1.7.01.03.07.008.0068.001', 'Pembersih Pengharum Lantai 770 ml', 'Buah', 0, 23500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(278, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.0064', 'Pembersih Porselen 750 ml', '1.1.7.01.03.07.008.0064.001', 'Pembersih Porselen 750 ml (Vixal)', 'Botol', 0, 33500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(279, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.874', 'Pemotong Kuku', '1.1.7.01.01.12.999.874.001', 'Pemotong Kuku', 'Buah', 0, 11500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(280, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.0048', 'Pemutih Pakaian Regular 4 Lt', '1.1.7.01.03.07.008.0048.001', 'Pemutih Pakaian Bayclin 4 ltr', 'Jerigen', 0, 74500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(281, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.0726', 'Pisau Cukur ', '1.1.7.01.05.01.999.0726.001', 'Pencukur Jenggot (Gillite Goal)', 'Buah', 0, 14450.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(282, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.84', 'Pengharum Toilet ', '1.1.7.01.03.07.008.84.001', 'Pengharum Toilet (Wipol)', 'Buah', 0, 26000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(283, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0401', 'Pengharum WC', '1.1.7.01.03.07.015.0401.001', 'Pengharum WC', 'Buah', 0, 24500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(284, '1.1.7.01.03.09.006', 'Atribut', '1.1.7.01.03.09.006.0034', 'Pentungan ', '1.1.7.01.03.09.006.0034.001', 'Pentungan', 'Buah', 0, 96570.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(285, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0402', 'Pisau Besar', '1.1.7.01.03.07.015.0402.001', 'Pisau Besar', 'Buah', 0, 85000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(286, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0320', 'Pisau Pemotong Rumput', '1.1.7.01.03.07.015.0320.001', 'Pisau Mesin Rumput', 'Buah', 0, 65000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(287, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0403', 'Pisau Sedang', '1.1.7.01.03.07.015.0403.001', 'Pisau Sedang', 'Buah', 0, 70000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(288, '1.1.7.01.01.12.001', 'Alat Kesehatan', '1.1.7.01.01.12.001.629', 'Pispot Corong', '1.1.7.01.01.12.001.629.001', 'Pispot Corong', 'Buah', 0, 13724.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(289, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0404', 'Plastik Besar Loundry', '1.1.7.01.03.07.015.0404.001', 'Plastik Besar Loundry', 'Pak', 0, 39500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(290, '1.1.7.01.07.01.003', 'Makan Minum Harian Pasien', '1.1.7.01.07.01.003.0199', 'Plastik Cup Sealer', '1.1.7.01.07.01.003.0199.001', 'Plastik Cup Sealer', 'Rol', 0, 65000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(291, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0405', 'Plastik Kemas Loundry', '1.1.7.01.03.07.015.0405.001', 'Plastik Kemas Loundry', 'Pak', 0, 69500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(292, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0406', 'Plastik Medis Loundry Uk. 40x60 Isi 10', '1.1.7.01.03.07.015.0406.001', 'Plastik Medis Loundry Uk. 40x60 Isi 10', 'Pak', 0, 16500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(293, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0407', 'Plastik Sampah Kuning Uk. 40 x 60 Isi : 10 lbr', '1.1.7.01.03.07.015.0407.001', 'Plastik Sampah Kuning Uk. 40 x 60 Isi : 10 lbr', 'Pak', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(294, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0408', 'Plastik Sampah Medis Uk. 40 x 40 cm', '1.1.7.01.03.07.015.0408.001', 'Plastik Sampah Medis Uk. 40 x 40 cm', 'Pak', 0, 18000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(295, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0409', 'Plastik Putih Uk. 80 x 100 Isi : 10 lbr', '1.1.7.01.03.07.015.0409.001', 'Plastik Sampah Putih Uk. 80 x 100 Isi : 10 lbr', 'Pak', 0, 38000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(296, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0410', 'Plastik Sampah Tipis Uk. 80 x 100 cm', '1.1.7.01.03.07.015.0410.001', 'Plastik Sampah Tipis Uk. 80 x 100 cm', 'Pak', 0, 120000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(297, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0411', 'Plastik Sampah Uk. 15 x 15 cm', '1.1.7.01.03.07.015.0411.001', 'Plastik Sampah Uk. 15 x 15 cm', 'Pak', 0, 5900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(298, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0412', 'Plastik Sampah Uk. 40 x 40 cm', '1.1.7.01.03.07.015.0412.001', 'Plastik Sampah Uk. 40 x 40 cm', 'Pak', 0, 58000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(299, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0413', 'Plastik Sampah Ukuran Kecil', '1.1.7.01.03.07.015.0413.001', 'Plastik Sampah Ukuran Kecil', 'Pak', 0, 42000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(300, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0414', 'Plastik Sampah Ukuran Sedang', '1.1.7.01.03.07.015.0414.001', 'Plastik Sampah Ukuran Sedang', 'Pak', 0, 48000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(301, '1.1.7.01.07.01.003', 'Makan Minum Harian Pasien', '1.1.7.01.07.01.003.0200', 'Plastik Wraping 35 cm', '1.1.7.01.07.01.003.0200.001', 'Plastik Wraping 35 cm', 'Rol', 0, 275000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(302, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.875', 'Pompa Asi Manual', '1.1.7.01.01.12.999.875.001', 'Pompa Asi Manual', 'Buah', 0, 31000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(303, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '', '', '.000', 'Procent 50 ml', '', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(304, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.318', 'Pupuk Kandang', '1.1.7.01.01.08.002.318.001', 'Pupuk Kandang', 'Karung', 0, 35000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(305, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.317', 'Pupuk NPK', '1.1.7.01.01.08.002.317.001', 'Pupuk NPK', 'Karung', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(306, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.876', 'Rantai', '1.1.7.01.01.12.999.876.001', 'Rantai', 'Buah', 0, 58500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(307, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0416', 'Refill Urinal Cleaner Anti Pesing', '1.1.7.01.03.07.015.0416.001', 'Refill Urinal Cleaner Anti Pesing', 'Botol', 0, 108000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(308, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0009', 'Rompi Safety', '1.1.7.01.03.09.007.0009.001', 'Rompi Security', 'Buah', 0, 294150.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(309, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.0201', 'Pupuk Sp36 ', '1.1.7.01.01.08.002.0201.001', 'SP', 'Kilogram', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(310, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0417', 'Sabun Bodywash Refill 410 ml', '1.1.7.01.03.07.015.0417.001', 'Sabun Bodywash Refill 410 ml', 'Bag / Bungkus ', 0, 39000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(311, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0169', 'Sabun Detergen 900 Gr/700 Gr', '1.1.7.01.03.07.015.0169.001', 'Sabun Bubuk 700gr A', 'Buah', 0, 37500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(312, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0418', 'Sabun Cuci Piring (Sunlight 650 ml)', '1.1.7.01.03.07.015.0418.001', 'Sabun Cuci Piring (Sunlight 650 ml)', 'Bag / Bungkus ', 0, 13000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(313, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0419', 'Sabun Cuci Piring 420 ml', '1.1.7.01.03.07.015.0419.001', 'Sabun Cuci Piring 420 ml', 'Bag / Bungkus ', 0, 11900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(314, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0185', 'Sabun Mandi 250 Ml', '1.1.7.01.03.07.015.0185.001', 'Sabun Mandi (Lifebuoy) 250 ml Refil', 'Pcs', 0, 29400.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(315, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0026', 'sabut cuci piring/sikat kawat', '1.1.7.01.03.07.001.0026.001', 'Sabut Kawat', 'Buah', 0, 7500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(316, '1.1.7.01.04.01.002', 'Obat Padat', '1.1.7.01.04.01.002.0490', 'Safety Box', '1.1.7.01.04.01.002.0490.001', 'Safety Box', 'Pcs', 0, 18500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(317, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.0037', 'Sandal Pria', '1.1.7.01.05.01.999.0037.001', 'Sandal Jepit', 'Pasang', 0, 15900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(318, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0025', 'Sapu Lantai', '1.1.7.01.03.07.001.0025.001', 'Sapu Lantai', 'Pcs', 0, 37500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(319, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0013', 'Sapu Lidi  Tongkat', '1.1.7.01.03.07.001.0013.001', 'Sapu Lidi Panjang', 'Buah', 0, 33000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(320, '1.1.7.01.03.09.004', 'Penutup Tangan', '1.1.7.01.03.09.004.0004', 'Sarung Tangan  Bahan Kain Rajut Standart ', '1.1.7.01.03.09.004.0004.001', 'Sarung Tangan Kain', 'Pasang', 0, 7500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(321, '1.1.7.01.03.09.004', 'Penutup Tangan', '1.1.7.01.03.09.004.0004', 'Sarung Tangan  Bahan Kain Rajut Standart ', '1.1.7.01.03.09.004.0004.002', 'Sarung Tangan Kain Bintik A', 'Pasang', 0, 7500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(322, '1.1.7.01.03.09.004', 'Penutup Tangan', '1.1.7.01.03.09.004.0005', 'Sarung Tangan Bahan Karet ', '1.1.7.01.03.09.004.0005.001', 'Sarung Tangan Karet', 'Pasang', 0, 90000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(323, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.00118', 'Sejadah', '1.1.7.01.05.01.999.00118.001', 'Sejadah', 'Lembar', 0, 75000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(324, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.0305', 'Sekam Padi', '1.1.7.01.01.08.002.0305.001', 'Sekam', 'Sak', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(325, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0137', 'Senter LED Recharge', '1.1.7.01.03.08.012.0137.001', 'Sentar Holagen', 'Buah', 0, 741480.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(326, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.634', 'Sepatu Boots Petrova', '1.1.7.01.01.12.999.634.001', 'Sepatu Boots', 'Pasang', 0, 147500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(327, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0231', 'Serokan Sampah  Plastik', '1.1.7.01.03.07.015.0231.001', 'Serok Sampah', 'Buah', 0, 39000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(328, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0420', 'Shampo Lifebuoy 170 ml', '1.1.7.01.03.07.015.0420.001', 'Shampo Lifebuoy 170 ml', 'Botol', 0, 32450.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(329, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0003', 'Sikat Gigi ', '1.1.7.01.03.07.001.0003.001', 'Sikat Gigi', 'Buah', 0, 4950.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(330, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0030', 'Sikat Kawat', '1.1.7.01.03.07.001.0030.001', 'Sikat Kawat', 'Buah', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(331, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0004', 'Sikat Kamar Mandi ', '1.1.7.01.03.07.001.0004.001', 'Sikat Lantai Biasa', 'Buah', 0, 5500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(332, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0031', 'Sikat Lantai Gagang Panjang', '1.1.7.01.03.07.001.0031.001', 'Sikat Lantai Gagang Panjang', 'Buah', 0, 33500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(333, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.0001', 'Alat Pel  Karet', '1.1.7.01.03.07.002.0001.001', 'Sikat Lantai Karet', 'Buah', 0, 28000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(334, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0032', 'Sikat Punggung', '1.1.7.01.03.07.001.0032.001', 'Sikat Punggung', 'Buah', 0, 38225.81, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(335, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0005', 'Sikat Wc ', '1.1.7.01.03.07.001.0005.001', 'Sikat WC', 'Buah', 0, 21000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(336, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.877', 'Sisir Biasa', '1.1.7.01.01.12.999.877.001', 'Sisir Biasa', 'Buah', 0, 8900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(337, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.878', 'Sisir Rapat', '1.1.7.01.01.12.999.878.001', 'Sisir Rapat', 'Buah', 0, 6500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(338, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.879', 'Slender K', '1.1.7.01.01.12.999.879.001', 'Slender K', 'Buah', 0, 40000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(339, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.880', 'Spon Bedak', '1.1.7.01.01.12.999.880.001', 'Spon Bedak', 'Buah', 0, 4900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(340, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.881', 'Spon Cuci Piring', '1.1.7.01.01.12.999.881.001', 'Spon Cuci Piring', 'Buah', 0, 4900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(341, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.882', 'Spon Mandi', '1.1.7.01.01.12.999.882.001', 'Spon Mandi', 'Buah', 0, 9900.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(342, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.446', 'Stiker Papan Nama Peserta Apel', '1.1.7.01.03.03.999.446.001', 'Stiker Papan Nama Peserta Apel', 'Lembar', 0, 100000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(343, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0312', 'Kemoceng', '1.1.7.01.03.07.015.0312.001', 'Sula Debu', 'Buah', 0, 28000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(344, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.30', 'Talenan', '1.1.7.01.03.07.009.30.001', 'Talenan', 'Buah', 0, 200000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(345, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0019', 'Tali Bendera', '1.1.7.01.03.09.007.0019.001', 'Tali Bendera', 'Meter', 0, 7000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(346, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.316', 'tanah hitam', '1.1.7.01.01.08.002.316.001', 'Tanah Hitam', 'RIT', 0, 500000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(347, '1.1.7.01.03.07.003', 'Ember, Slang, Dan Tempat Air Lainnya', '1.1.7.01.03.07.003.51', 'Tandon Air 1200 Liter', '1.1.7.01.03.07.003.51.001', 'Tandon Air 1200 Liter', 'Buah', 0, 2900000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(348, '1.1.7.01.03.12.002', 'Souvenir / Cenderamata Lainnya', '1.1.7.01.03.12.002.0022', 'Tas Anyaman Plastik', '1.1.7.01.03.12.002.0022.001', 'Tas Anyaman Plastik', 'Buah', 0, 35000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(349, '1.1.7.01.03.07.004', 'Keset Dan Tempat Sampah', '1.1.7.01.03.07.004.0018', 'Plastik Sampah Hitam Besar', '1.1.7.01.03.07.004.0018.001', 'Tempat Sampah Besar Uk. 120 Ltr', 'Pak', 0, 1200000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(350, '1.1.7.01.01.12.001', 'Alat Kesehatan', '1.1.7.01.01.12.001.630', 'Termometer Kulkas', '1.1.7.01.01.12.001.630.001', 'Termometer Kulkas', 'Buah', 0, 343500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(351, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.31', 'Termos Air Panas 50H 2 Ltr', '1.1.7.01.03.07.009.31.001', 'Termos Air Panas 50H 2 Ltr', 'Buah', 0, 140000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(352, '1.1.7.01.03.09.006', 'Atribut', '1.1.7.01.03.09.006.51', 'Tiang Apel ', '1.1.7.01.03.09.006.51.001', 'Tiang Apel Bahan Besi', 'Buah', 0, 250000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(353, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0246', 'Tissue Gulung ', '1.1.7.01.03.07.015.0246.001', 'Tissue Gulung', 'Pak', 0, 6250.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(354, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0257', 'Tissue Kotak Facial', '1.1.7.01.03.07.015.0257.001', 'Tissue Kotak', 'Pak', 0, 15500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(355, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0421', 'Tissue Pengesat Tangan', '1.1.7.01.03.07.015.0421.001', 'Tissue Pengesat Tangan', 'Buah', 0, 33400.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(356, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0273', 'Tongkat Pel ', '1.1.7.01.03.07.015.0273.001', 'Tongkat Selabar', 'Buah', 0, 106000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(357, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.884', 'Viva Alka Liquid Alkali @25 ltr', '1.1.7.01.01.12.999.884.001', 'Viva Alka Liquid Alkali @25 ltr', 'Galon', 0, 961537.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(358, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.885', 'Viva Blanko Oxygen Bleach @25 ltr', '1.1.7.01.01.12.999.885.001', 'Viva Blanko Oxygen Bleach @25 ltr', 'Galon', 0, 1083000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(359, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.886', 'Viva Main Loundry Detergent @20 ltr', '1.1.7.01.01.12.999.886.001', 'Viva Main Loundry Detergent @20 ltr', 'Galon', 0, 960000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(360, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.887', 'Viva Surf Softener @20 ltr', '1.1.7.01.01.12.999.887.001', 'Viva Surf Softener @20 ltr', 'Galon', 0, 870000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(361, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.888', 'Viva Tard Neutralizer @20 ltr', '1.1.7.01.01.12.999.888.001', 'Viva Tard Neutralizer @20 ltr', 'Galon', 0, 961537.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(362, '1.1.7.01.03.04.001', 'Materai', '1.1.7.01.03.04.001.0004', 'Materai 10000', '1.1.7.01.03.04.001.0004.001', 'Materai @10.000', 'Pcs', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(363, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '.000', 'Alat Toll Pembuka Panel Modul LED', '', 0, 82500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(364, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '.000', 'CUP Pasitu AC', '', 0, 85000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(365, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '1.1.7.01.03.08.012.0007.001', 'Capasitor 2', 'Unit', 0, 16000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(366, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '1.1.7.01.03.08.012.0007.002', 'Capasitor 50', 'Unit', 0, 55000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(367, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0247', 'Lampu Downlight', '1.1.7.01.03.08.002.0247.001', 'DL Hannochs 5 W ( 3 Color )', 'Buah', 0, 47000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(368, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0247', 'Lampu Downlight', '1.1.7.01.03.08.002.0247.002', 'DL Hannochs 9 W ( 3 Color )', 'Buah', 0, 57000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(369, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '.000', 'Dinamo NPG 18-16', '', 0, 350000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(370, '1.1.7.01.03.08.008', 'Vitting', '1.1.7.01.03.08.008.0012', 'Fitting Tempel', '1.1.7.01.03.08.008.0012.001', 'Fitting', 'Buah', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(371, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '.000', 'Freon R22 @5kg', '', 0, 165000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(372, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '.000', 'Freon R32 @5kg', '', 0, 180000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(373, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '.000', 'Freon R410 @5kg', '', 0, 180000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(374, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.119', 'Termis 2 PK', '1.1.7.01.03.08.001.119.001', 'Termis 2 pk', 'Buah', 0, 75000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(375, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0019', 'Isolasi ', '1.1.7.01.03.08.012.0019.001', 'Isolasi Listrik 3M 33', 'Buah', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(376, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0019', 'Isolasi ', '1.1.7.01.03.08.012.0019.002', 'Isolasi Scoot 3M 33', 'Buah', 0, 75000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(377, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '.000', 'Join Sleeve 50', '', 0, 18000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(378, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.48', 'Kabel Data 40Cm', '1.1.7.01.03.06.014.48.001', 'Kabel Data 40Cm', 'Buah', 0, 6000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(379, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.120', 'Kabel Duck Putih 20 x 15', '1.1.7.01.03.08.001.120.001', 'Kabel Duck Putih 20 x 15', 'Buah', 0, 45000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(380, '1.1.7.01.03.08.001', 'Kabel Listrik', '', 'Kabel Nyaf 4 Mm ', '.000', 'Kabel NYAF 35', 'Meter', 0, 92000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(381, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0026', 'Kabel Nym 2 X 1,5 Mm ', '1.1.7.01.03.08.001.0026.001', 'Kabel NYM 2 x 1,5 Eterna 50m', 'Meter', 0, 550000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(382, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0032', 'Kabel Nym 3 X 2,5 Mm ', '1.1.7.01.03.08.001.0032.001', 'Kabel NYM 3 x 2,5', 'Meter', 0, 24000.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(383, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0080', 'Kabel Twis Teed 2 X 10 Mm', '1.1.7.01.03.08.001.0080.001', 'Kabel SR / Twis Teed 2x10', 'Meter', 0, 7500.00, NULL, 'aktif', '2025-09-05 22:39:51', NULL),
	(384, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0094', 'Kabel ties ', '1.1.7.01.03.08.001.0094.002', 'Kabel Ties 25 cm', 'Pak', 0, 25000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(385, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0131', 'Kabel Ties 25 cm', '1.1.7.01.03.08.001.0131.001', 'Kabel Ties 30 cm', 'Pak', 0, 25000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(386, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.124', 'Kabel Twis Teed 2 X 20 Mm.', '1.1.7.01.03.08.001.124.001', 'Kabel Twis Teed 2x20 mm', 'Meter', 0, 12500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(387, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '1.1.7.01.03.08.012.0007.003', 'Kapasitor 1,5 uf', 'Unit', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(388, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '1.1.7.01.03.08.012.0007.004', 'Kapasitor 2 uf', 'Unit', 0, 6000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(389, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '1.1.7.01.03.08.012.0007.005', 'Kapasitor 3 mf', 'Unit', 0, 80000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(390, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Klem Kabel No. 17 "Imundex"', '.000', 'Klem Kabel No. 17 "Imundex"', 'Pak', 0, 47300.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(391, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Klem No. 10 mm', '.000', 'Klem No. 10 mm', 'Pak', 0, 6000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(392, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Konektor Kabel', '.000', 'Konektor Kabel', 'Buah', 0, 5900.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(393, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0286', 'Lampu LED 20 Watt', '1.1.7.01.03.08.002.0286.001', 'LED Pila 20 W', 'Buah', 0, 40000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(394, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0237', 'Lampu LED 7 Watt', '1.1.7.01.03.08.002.0237.001', 'LED Pila 7 W', 'Buah', 0, 55000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(395, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0287', 'Lampu LED 9 Watt', '1.1.7.01.03.08.002.0287.001', 'LED Pila 9 W', 'Kotak', 0, 60000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(396, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0288', 'Lampu LED 18 Watt', '1.1.7.01.03.08.002.0288.001', 'Lampu LED 18 Watt', 'Buah', 0, 30000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(397, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0237', 'Lampu LED 7 Watt', '1.1.7.01.03.08.002.0237.002', 'Lampu LED 7 Watt', 'Buah', 0, 16000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(398, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0287', 'Lampu LED 9 Watt', '1.1.7.01.03.08.002.0287.002', 'Lampu LED 9 Watt', 'Kotak', 0, 18000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(399, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0290', 'Lampu Sorot LED 30 watt', '1.1.7.01.03.08.002.0290.001', 'Lampu Sorot LED 30 watt', 'Buah', 0, 90000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(400, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0291', 'Lampu Sorot LED 50 Watt', '1.1.7.01.03.08.002.0291.001', 'Lampu Sorot LED 50 Watt', 'Buah', 0, 135000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(401, '1.1.7.01.03.08.999', 'Alat Listrik dan Elektronik Lain-lain', '1.1.7.01.03.08.999.17', 'MCB 2 Phase 25 Amper - 300MA', '1.1.7.01.03.08.999.17.001', 'MCB 2 Phase 25 Amper - 300MA', 'Buah', 0, 584000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(402, '1.1.7.01.03.08.999', 'Alat Listrik dan Elektronik Lain-lain', '1.1.7.01.03.08.999.18', 'MCB 3 Phase 10 Amper', '1.1.7.01.03.08.999.18.001', 'MCB 3 Phase 10 Amper', 'Buah', 0, 298133.33, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(403, '1.1.7.01.03.08.999', 'Alat Listrik dan Elektronik Lain-lain', '1.1.7.01.03.08.999.19', 'MCB 3 Phase 40 Amper', '1.1.7.01.03.08.999.19.001', 'MCB 3 Phase 40 Amper', 'Buah', 0, 388400.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(404, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0031', 'Stop Kontak Bright G ', '1.1.7.01.03.08.003.0031.001', 'Stop Kontak Bright G ', 'Buah', 0, 14000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(405, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Pembelian Alat Listrik', '.000', 'Pembelian Alat Listrik', 'paket', 0, 787210.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(406, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Pipa AC 3/4', '.000', 'Pipa AC 3/4', 'Meter', 0, 98000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(407, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0019', 'Power Supply ', '1.1.7.01.03.06.014.0019.001', 'Power Supply 12V/10A', 'Buah', 0, 80000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(408, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0237', 'Lampu LED 7 Watt', '1.1.7.01.03.08.002.0237.003', 'SMD 7 Watt', 'Buah', 0, 16000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(409, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0289', 'Lampu LED 9 Watt', '1.1.7.01.03.08.002.0289.001', 'SMD 9 Watt', 'Buah', 0, 18000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(410, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Sending Card Novastar MSD300 (GKGD)', '.000', 'Sending Card Novastar MSD300 (GKGD)', 'Buah', 0, 1973200.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(411, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0071', 'Skun 35 mm Ins', '1.1.7.01.03.08.001.0071.001', 'Skun 35 mm Ins', 'Buah', 0, 7900.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(412, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.122', 'Skun 70', '1.1.7.01.03.08.001.122.001', 'Skun 70', 'Buah', 0, 9900.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(413, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.122', 'Skun T Kuning 50', '1.1.7.01.03.08.001.122.002', 'Skun T Kuning 50', 'Buah', 0, 21000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(414, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0019', 'Power Supply ', '1.1.7.01.03.06.014.0019.002', 'Slim Switching Power Supply PSU Tipis 5V 60A', 'Buah', 0, 160000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(415, '1.1.7.01.03.08.005', 'Stacker', '1.1.7.01.03.08.005.0010', 'Steker Arde', '1.1.7.01.03.08.005.0010.001', 'Steker Arda', 'Buah', 0, 10000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(416, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0032', 'Stop Kontak  Outbow 3 Lubang', '1.1.7.01.03.08.003.0032.001', 'Stop Kontak  Outbow 3 Lubang', 'Buah', 0, 16000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(417, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0033', 'Stop Kontak  Tanam', '1.1.7.01.03.08.003.0033.001', 'Stop Kontak  Tanam', 'Buah', 0, 13000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(418, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0034', 'Stop Kontak 2 Lobang', '1.1.7.01.03.08.003.0034.001', 'Stop Kontak 2 Lobang', 'Buah', 0, 12000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(419, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0035', 'Stop Kontak 3 Lobang', '1.1.7.01.03.08.003.0035.001', 'Stop Kontak 3 Lobang', 'Buah', 0, 16000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(420, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0036', 'Stop Kontak Tempel 1 Lubang', '1.1.7.01.03.08.003.0036.001', 'Stop Kontak Tempel 1 Lubang', 'Buah', 0, 14800.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(421, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0037', 'Stop Kontak Timer', '1.1.7.01.03.08.003.0037.001', 'Stop Kontak Timer', 'Buah', 0, 125000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(422, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.460', 'Amplop Berkop Coklat Besar A', '1.1.7.01.03.03.999.460.001', 'Amplop Berkop Coklat Besar A', 'Pak', 0, 250000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(423, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.461', 'Amplop Berkop Coklat Tanggung', '1.1.7.01.03.03.999.461.001', 'Amplop Berkop Coklat Tanggung', 'Pak', 0, 150000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(424, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.459', 'Amplop KOP RSJSL utk Foto Thorax', '1.1.7.01.03.03.999.459.001', 'Amplop KOP RSJSL utk Foto Thorax', 'Pak', 0, 650000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(425, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.463', 'Assesmen Awal Keperawatan Psikogeriatri 1 Rkp BB', '1.1.7.01.03.03.999.463.001', 'Assesmen Awal Keperawatan Psikogeriatri 1 Rkp BB', 'Rim', 0, 240000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(426, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.464', 'Assesmen Awal Nyeri', '1.1.7.01.03.03.999.464.001', 'Assesmen Awal Nyeri', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(427, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.465', 'Assesmen Awal Rawat Gigi & Mulut', '1.1.7.01.03.03.999.465.001', 'Assesmen Awal Rawat Gigi & Mulut', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(428, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.0422', 'Assesmen Awal Rawat Inap Jiwa', '1.1.7.01.03.03.999.0422.001', 'Assesmen Awal Rawat Inap Jiwa', 'Buku', 0, 325000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(429, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.423', 'Assesmen Awal Rawat Inap Napza', '1.1.7.01.03.03.999.423.001', 'Assesmen Awal Rawat Inap Napza', 'Buku', 0, 325000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(430, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.466', 'Assesmen Awal Rawat Jalan Jiwa 4rkp BB', '1.1.7.01.03.03.999.466.001', 'Assesmen Awal Rawat Jalan Jiwa 4rkp BB', 'Rim', 0, 226550.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(431, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.467', 'Assesmen Awal Rawat Jalan Napza A', '1.1.7.01.03.03.999.467.001', 'Assesmen Awal Rawat Jalan Napza A', 'Rim', 0, 226550.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(432, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.468', 'Assesmen Awal Rawat Jalan Penyakit Dalam', '1.1.7.01.03.03.999.468.001', 'Assesmen Awal Rawat Jalan Penyakit Dalam', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(433, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.469', 'Assesmen Lanjutan Gizi', '1.1.7.01.03.03.999.469.001', 'Assesmen Lanjutan Gizi', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(434, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.470', 'Assesmen Lanjutan Nyeri', '1.1.7.01.03.03.999.470.001', 'Assesmen Lanjutan Nyeri', 'Rim', 0, 200000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(435, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.471', 'Assesmen Lanjutan Psikogeriatri', '1.1.7.01.03.03.999.471.001', 'Assesmen Lanjutan Psikogeriatri', 'Rim', 0, 226550.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(436, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.424', 'Assesmen Medis (IGD)', '1.1.7.01.03.03.999.424.001', 'Assesmen Medis (IGD)', 'Buku', 0, 245000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(437, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.472', 'Assesmen Medis Pediatri Anak & Remaja', '1.1.7.01.03.03.999.472.001', 'Assesmen Medis Pediatri Anak & Remaja', 'Rim', 0, 226550.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(438, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.473', 'Assesmen Medis Poliklinik Psikiatrik Anak & Remaja', '1.1.7.01.03.03.999.473.001', 'Assesmen Medis Poliklinik Psikiatrik Anak & Remaja', 'Rim', 0, 226550.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(439, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.474', 'Assesmen Medis Psikogeriatri', '1.1.7.01.03.03.999.474.001', 'Assesmen Medis Psikogeriatri', 'Rim', 0, 226550.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(440, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.475', 'Assesmen Medis Rawat Inap Jiwa', '1.1.7.01.03.03.999.475.001', 'Assesmen Medis Rawat Inap Jiwa', 'Rim', 0, 429000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(441, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.476', 'Assesmen Pasien Terminal', '1.1.7.01.03.03.999.476.001', 'Assesmen Pasien Terminal', 'Rim', 0, 228750.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(442, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.477', 'Assesmen Poliklinik 2 Rkp BB', '1.1.7.01.03.03.999.477.001', 'Assesmen Poliklinik 2 Rkp BB', 'Rim', 0, 226550.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(443, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.478', 'Asuhan Gizi Anak', '1.1.7.01.03.03.999.478.001', 'Asuhan Gizi Anak', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(444, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.479', 'Asuhan Gizi Dewasa', '1.1.7.01.03.03.999.479.001', 'Asuhan Gizi Dewasa', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(445, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.425', 'Blangko Rujukan / Formulir Konsultasi antar Unit', '1.1.7.01.03.03.999.425.001', 'Blangko Rujukan / Formulir Konsultasi antar Unit', 'Buku', 0, 59500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(446, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.426', 'Bon Permintaan Makanan', '1.1.7.01.03.03.999.426.001', 'Bon Permintaan Makanan', 'Buku', 0, 9000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(447, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.427', 'Buku CFIT', '1.1.7.01.03.03.999.427.001', 'Buku CFIT', 'Buku', 0, 16000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(448, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.428', 'Buku MSDT ( Kepemimpinan )', '1.1.7.01.03.03.999.428.001', 'Buku MSDT ( Kepemimpinan )', 'Buku', 0, 30000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(449, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.429', 'Buku PAPI', '1.1.7.01.03.03.999.429.001', 'Buku PAPI', 'Buku', 0, 18000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(450, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.480', 'Catatan Perkembangan', '1.1.7.01.03.03.999.480.001', 'Catatan Perkembangan', 'Rim', 0, 345000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(451, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.481', 'Cheklist Keselamatan Pasien Di Poli Gigi Dan Mulut', '1.1.7.01.03.03.999.481.001', 'Cheklist Keselamatan Pasien Di Poli Gigi Dan Mulut', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(452, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.482', 'Clinical Pathway Demensia', '1.1.7.01.03.03.999.482.001', 'Clinical Pathway Demensia', 'Rim', 0, 275800.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(453, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.483', 'Clinical Pathway Depresi Berat Dg Gejala Psikotik', '1.1.7.01.03.03.999.483.001', 'Clinical Pathway Depresi Berat Dg Gejala Psikotik', 'Rim', 0, 275800.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(454, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.484', 'Clinical Pathway Gangguan Bipolar 2 Rkp BB', '1.1.7.01.03.03.999.484.001', 'Clinical Pathway Gangguan Bipolar 2 Rkp BB', 'Rim', 0, 275800.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(455, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.485', 'Clinical Pathway Skizoafektif 2 Rkp BB', '1.1.7.01.03.03.999.485.001', 'Clinical Pathway Skizoafektif 2 Rkp BB', 'Rim', 0, 275800.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(456, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.486', 'Clinical Pathway Skizofrenia', '1.1.7.01.03.03.999.486.001', 'Clinical Pathway Skizofrenia', 'Rim', 0, 650000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(457, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.487', 'Evaluasi Keperawatan', '1.1.7.01.03.03.999.487.001', 'Evaluasi Keperawatan', 'Rim', 0, 325000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(458, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.488', 'Form Hasil Tindakan Prosedur KFR', '1.1.7.01.03.03.999.488.001', 'Form Hasil Tindakan Prosedur KFR', 'Rim', 0, 275000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(459, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.489', 'Form Layanan Kedokteran Fisik Dan Rehabilitasi RJ', '1.1.7.01.03.03.999.489.001', 'Form Layanan Kedokteran Fisik Dan Rehabilitasi RJ', 'Rim', 0, 275000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(460, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.490', 'Form Penerimaan Pasien Oleh Keluarga / Instansi', '1.1.7.01.03.03.999.490.001', 'Form Penerimaan Pasien Oleh Keluarga / Instansi', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(461, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.491', 'Form Penolakan Pasien Oleh Keluarga / Instansi', '1.1.7.01.03.03.999.491.001', 'Form Penolakan Pasien Oleh Keluarga / Instansi', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(462, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.492', 'Form Permintaan Terapi', '1.1.7.01.03.03.999.492.001', 'Form Permintaan Terapi', 'Rim', 0, 275000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(463, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.493', 'Form Rawat Inap Detoxifikasi', '1.1.7.01.03.03.999.493.001', 'Form Rawat Inap Detoxifikasi', 'Rim', 0, 226500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(464, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.494', 'Formulir DPJP & Case Manager 1 Rkp', '1.1.7.01.03.03.999.494.001', 'Formulir DPJP & Case Manager 1 Rkp', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(465, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.430', 'Formulir Food Recal 24 Jam', '1.1.7.01.03.03.999.430.001', 'Formulir Food Recal 24 Jam', 'Buku', 0, 50900.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(466, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.431', 'Formulir Isian Penghargaan/Saran/Pengaduan', '1.1.7.01.03.03.999.431.001', 'Formulir Isian Penghargaan/Saran/Pengaduan', 'Buku', 0, 34000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(467, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.495', 'Formulir Laporan Insiden Keselamatan Pasien', '1.1.7.01.03.03.999.495.001', 'Formulir Laporan Insiden Keselamatan Pasien', 'Rim', 0, 226550.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(468, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.496', 'Formulir Laporan KNC', '1.1.7.01.03.03.999.496.001', 'Formulir Laporan KNC', 'Rim', 0, 236000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(469, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.497', 'Formulir Laporan KPC', '1.1.7.01.03.03.999.497.001', 'Formulir Laporan KPC', 'Rim', 0, 236000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(470, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.498', 'Formulir Laporan Operasi', '1.1.7.01.03.03.999.498.001', 'Formulir Laporan Operasi', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(471, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.499', 'Formulir Laporan Penerimaan Rehabilitan Putusan', '1.1.7.01.03.03.999.499.001', 'Formulir Laporan Penerimaan Rehabilitan Putusan', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(472, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.432', 'Formulir Lembar Transfer Parsial 2 Ply', '1.1.7.01.03.03.999.432.001', 'Formulir Lembar Transfer Parsial 2 Ply', 'Buku', 0, 39000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(473, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.500', 'Formulir Pengkajian Fisik', '1.1.7.01.03.03.999.500.001', 'Formulir Pengkajian Fisik', 'Rim', 0, 217771.43, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(474, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.501', 'Formulir Penolakan Resusitasi', '1.1.7.01.03.03.999.501.001', 'Formulir Penolakan Resusitasi', 'Rim', 0, 214000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(475, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.502', 'Formulir Penolakan Tindakan Kedokteran', '1.1.7.01.03.03.999.502.001', 'Formulir Penolakan Tindakan Kedokteran', 'Rim', 0, 219900.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(476, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.503', 'Formulir Penolakan Tindakan Medis Umum', '1.1.7.01.03.03.999.503.001', 'Formulir Penolakan Tindakan Medis Umum', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(477, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.504', 'Formulir Penyerahan & Pengambilan Rehabilitan', '1.1.7.01.03.03.999.504.001', 'Formulir Penyerahan & Pengambilan Rehabilitan', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(478, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.505', 'Formulir Perjanjian Masuk Rehabilitasi', '1.1.7.01.03.03.999.505.001', 'Formulir Perjanjian Masuk Rehabilitasi', 'Rim', 0, 226500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(479, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.506', 'Formulir Rencana Rawatan Pribadi Rehabilitan', '1.1.7.01.03.03.999.506.001', 'Formulir Rencana Rawatan Pribadi Rehabilitan', 'Rim', 0, 226500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(480, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.507', 'Formulir Transfer Pasien Eksternal', '1.1.7.01.03.03.999.507.001', 'Formulir Transfer Pasien Eksternal', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(481, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.508', 'Formulir Transfer Pasien Intra RS', '1.1.7.01.03.03.999.508.001', 'Formulir Transfer Pasien Intra RS', 'Rim', 0, 325000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(482, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.433', 'Jawaban Rujukan', '1.1.7.01.03.03.999.433.001', 'Jawaban Rujukan', 'Buku', 0, 55000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(483, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.447', 'Lembar Kartu Kendali', '1.1.7.01.03.03.999.447.001', 'Kartu Kendali', 'Lembar', 0, 9800.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(484, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.434', 'Buku Kartu Kendali', '1.1.7.01.03.03.999.434.001', 'Kartu Kendali', 'Buku', 0, 17500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(485, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.509', 'Kebutuhan komunikasi & edukasi Rawat Jalan', '1.1.7.01.03.03.999.509.001', 'Kebutuhan komunikasi & edukasi Rawat Jalan', 'Rim', 0, 210000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(486, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.510', 'Kop Surat RSJSL', '1.1.7.01.03.03.999.510.001', 'Kop Surat RSJSL', 'Rim', 0, 132000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(487, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.511', 'Kwitansi Poliklinik', '1.1.7.01.03.03.999.511.001', 'Kwitansi Poliklinik', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(488, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.512', 'Lembar Bukti Pelayanan', '1.1.7.01.03.03.999.512.001', 'Lembar Bukti Pelayanan', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(489, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.513', 'Lembar CFIT', '1.1.7.01.03.03.999.513.001', 'Lembar CFIT', 'Rim', 0, 265000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(490, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.435', 'Lembar Disposisi', '1.1.7.01.03.03.999.435.001', 'Lembar Disposisi', 'Buku', 0, 35000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(491, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.514', 'Lembar Informasi Penundaan Pelayanan 1 Rkp', '1.1.7.01.03.03.999.514.001', 'Lembar Informasi Penundaan Pelayanan 1 Rkp', 'Rim', 0, 222733.33, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(492, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.515', 'Lembar Investigasi Sederhana', '1.1.7.01.03.03.999.515.001', 'Lembar Investigasi Sederhana', 'Rim', 0, 214000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(493, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.516', 'Lembar Kerja Investigasi Sederhana', '1.1.7.01.03.03.999.516.001', 'Lembar Kerja Investigasi Sederhana', 'Rim', 0, 297333.33, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(494, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.517', 'Lembar Konsultasi / Konseling / Psikoterapi', '1.1.7.01.03.03.999.517.001', 'Lembar Konsultasi / Konseling / Psikoterapi', 'Rim', 0, 226500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(495, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.518', 'Lembar MSDT', '1.1.7.01.03.03.999.518.001', 'Lembar MSDT', 'Rim', 0, 375000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(496, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.519', 'Lembar Observasi Fisik', '1.1.7.01.03.03.999.519.001', 'Lembar Observasi Fisik', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(497, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.520', 'Lembar Observasi IGD', '1.1.7.01.03.03.999.520.001', 'Lembar Observasi IGD', 'Rim', 0, 220000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(498, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.521', 'Lembar Observasi Visum', '1.1.7.01.03.03.999.521.001', 'Lembar Observasi Visum', 'Rim', 0, 216533.33, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(499, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.522', 'Lembar Papi Kosrik', '1.1.7.01.03.03.999.522.001', 'Lembar Papi Kosrik', 'Rim', 0, 265000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(500, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.523', 'Lembar Penggunaan Fiksasi Dan Isolasi', '1.1.7.01.03.03.999.523.001', 'Lembar Penggunaan Fiksasi Dan Isolasi', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(501, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.524', 'Lembar Persetujuan Orang Tua/Wali Dan Rehabilitan', '1.1.7.01.03.03.999.524.001', 'Lembar Persetujuan Orang Tua/Wali Dan Rehabilitan', 'Rim', 0, 325000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(502, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.525', 'Lembar Wartegg', '1.1.7.01.03.03.999.525.001', 'Lembar Wartegg', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(503, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.526', 'Lembar Wategg_Zeichentest (WZT)', '1.1.7.01.03.03.999.526.001', 'Lembar Wategg_Zeichentest (WZT)', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(504, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.448', 'Map Karton RSJSL', '1.1.7.01.03.03.999.448.001', 'Map Karton RSJSL', 'Lembar', 0, 3500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(505, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.449', 'Map Rekam Medik', '1.1.7.01.03.03.999.449.001', 'Map Rekam Medik', 'Lembar', 0, 30000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(506, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.527', 'Mini Mental State Examination (MMSE)', '1.1.7.01.03.03.999.527.001', 'Mini Mental State Examination (MMSE)', 'Rim', 0, 228000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(507, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.528', 'Observasi Kasus Resiko Bunuh Diri', '1.1.7.01.03.03.999.528.001', 'Observasi Kasus Resiko Bunuh Diri', 'Rim', 0, 220000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(508, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.529', 'Observasi Pasien', '1.1.7.01.03.03.999.529.001', 'Observasi Pasien', 'Rim', 0, 220000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(509, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.530', 'Observasi Pasien Fiksasi', '1.1.7.01.03.03.999.530.001', 'Observasi Pasien Fiksasi', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(510, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.531', 'Pemantauan Intake Pasien', '1.1.7.01.03.03.999.531.001', 'Pemantauan Intake Pasien', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(511, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.462', 'Pembelian Cetak', '1.1.7.01.03.03.999.462.001', 'Pembelian Cetak', 'Paket', 0, 490000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(512, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.532', 'Pemeriksaan Dokter Poliklinik Psikogeriatri', '1.1.7.01.03.03.999.532.001', 'Pemeriksaan Dokter Poliklinik Psikogeriatri', 'Rim', 0, 453100.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(513, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.533', 'Pengantar Biaya Akomodasi', '1.1.7.01.03.03.999.533.001', 'Pengantar Biaya Akomodasi', 'Rim', 0, 22160.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(514, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.534', 'Pengantar Biaya Assesmen', '1.1.7.01.03.03.999.534.001', 'Pengantar Biaya Assesmen', 'Rim', 0, 214000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(515, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.535', 'Pengantar Biaya Keperawatan', '1.1.7.01.03.03.999.535.001', 'Pengantar Biaya Keperawatan', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(516, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.438', 'Pengantar Biaya Rawat Jalan (NCR 3 Ply)', '1.1.7.01.03.03.999.438.001', 'Pengantar Biaya Rawat Jalan (NCR 3 Ply)', 'Buku', 0, 50000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(517, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.439', 'Pengantar Biaya Tindakan Ins. Rehabilitasi Medik', '1.1.7.01.03.03.999.439.001', 'Pengantar Biaya Tindakan Ins. Rehabilitasi Medik', 'Buku', 0, 36000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(518, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.536', 'Pengantar Biaya Visite Dokter', '1.1.7.01.03.03.999.536.001', 'Pengantar Biaya Visite Dokter', 'Rim', 0, 221600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(519, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.537', 'Pengkajian & Monitoring Perioprerati Lokal Anastesi', '1.1.7.01.03.03.999.537.001', 'Pengkajian & Monitoring Perioprerati Lokal Anastes', 'Rim', 0, 226500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(520, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.538', 'Persetujuan Umum (General Consent)', '1.1.7.01.03.03.999.538.001', 'Persetujuan Umum (General Consent)', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(521, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.539', 'Progres Report', '1.1.7.01.03.03.999.539.001', 'Progres Report', 'Rim', 0, 223333.33, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(522, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.540', 'Rencana Asuhan Keperawatan Axis', '1.1.7.01.03.03.999.540.001', 'Rencana Asuhan Keperawatan Axis', 'Rim', 0, 150000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(523, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.541', 'Rencana Asuhan Keperawatan Jiwa', '1.1.7.01.03.03.999.541.001', 'Rencana Asuhan Keperawatan Jiwa', 'Rim', 0, 325000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(524, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.440', 'Resep Pasien Rawat Inap 3 Ply', '1.1.7.01.03.03.999.440.001', 'Resep Pasien Rawat Inap 3 Ply', 'Buku', 0, 55000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(525, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.441', 'Resume Rawat Jalan', '1.1.7.01.03.03.999.441.001', 'Resume Rawat Jalan', 'Buku', 0, 50000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(526, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.442', 'Ringkasan Pulang Rawat Inap 4 Ply A', '1.1.7.01.03.03.999.442.001', 'Ringkasan Pulang Rawat Inap 4 Ply A', 'Buku', 0, 120000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(527, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.450', 'Stiker Rekam Medis Warna Abu-Abu', '1.1.7.01.03.03.999.450.001', 'Stiker Rekam Medis Warna Abu-Abu', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(528, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.451', 'Stiker Rekam Medis Warna Biru', '1.1.7.01.03.03.999.451.001', 'Stiker Rekam Medis Warna Biru', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(529, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.452', 'Stiker Rekam Medis Warna Coklat', '1.1.7.01.03.03.999.452.001', 'Stiker Rekam Medis Warna Coklat', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(530, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.453', 'Stiker Rekam Medis Warna Hijau', '1.1.7.01.03.03.999.453.001', 'Stiker Rekam Medis Warna Hijau', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(531, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.454', 'Stiker Rekam Medis Warna Krem', '1.1.7.01.03.03.999.454.001', 'Stiker Rekam Medis Warna Krem', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(532, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.455', 'Stiker Rekam Medis Warna Kuning', '1.1.7.01.03.03.999.455.001', 'Stiker Rekam Medis Warna Kuning', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(533, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.456', 'Stiker Rekam Medis Warna Merah', '1.1.7.01.03.03.999.456.001', 'Stiker Rekam Medis Warna Merah', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(534, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.457', 'Stiker Rekam Medis Warna Putih', '1.1.7.01.03.03.999.457.001', 'Stiker Rekam Medis Warna Putih', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(535, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.458', 'Stiker Rekam Medis Warna Ungu', '1.1.7.01.03.03.999.458.001', 'Stiker Rekam Medis Warna Ungu', 'Lembar', 0, 5000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(536, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.443', 'Surat Ijin Pulang', '1.1.7.01.03.03.999.443.001', 'Surat Ijin Pulang', 'Buku', 0, 39200.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(537, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.444', 'Surat Keterangan Kematian A', '1.1.7.01.03.03.999.444.001', 'Surat Keterangan Kematian A', 'Buku', 0, 90000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(538, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.445', 'Surat Kontrol', '1.1.7.01.03.03.999.445.001', 'Surat Kontrol', 'Buku', 0, 20000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(539, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.542', 'Surat Pernyt. Bersedia Menunggu Px Resiko Suicide', '1.1.7.01.03.03.999.542.001', 'Surat Pernyt. Bersedia Menunggu Px Resiko Suicide', 'Rim', 0, 210000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(540, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.543', 'Surat Pernyt. Menunggu (Situasional) 1 Rkp', '1.1.7.01.03.03.999.543.001', 'Surat Pernyt. Menunggu (Situasional) 1 Rkp', 'Rim', 0, 225000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(541, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0035', 'Baterai Tanggung R14', '1.1.7.01.03.08.010.0035.001', 'Batterai Tanggung', 'Buah', 0, 3500.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(542, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0024', 'Kertas HVS 75 gr A4/500 lbr', '1.1.7.01.03.02.001.0024.001', 'Kertas HVS A4 75 GSM', 'Rim', 0, 52800.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(543, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0025', 'Kertas HVS 75 gr F4/500 lbr', '1.1.7.01.03.02.001.0025.001', 'Kertas HVS F4 75 GSM', 'Rim', 0, 62000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(544, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.50', 'Magnet Papan Tulis ', '1.1.7.01.03.01.010.50.002', 'Magnet Papan Tulis', 'Buah', 0, 17600.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(545, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0002', 'Tinta Epson Hitam 664', '1.1.7.01.03.03.002.0002.002', 'Tinta Printer Epson Hitam 664', 'Botol', 0, 130000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(546, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.0070', 'Pakaian Olahraga', '1.1.7.01.03.09.001.0070.001', 'Baju Kaos Olahraga', 'Buah', 0, 149000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(547, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.0070', 'Pakaian Olahraga', '1.1.7.01.03.09.001.0070.002', 'Baju Kaos Olahraga Laki-laki', 'Buah', 0, 295000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(548, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.77', 'Baju Seragam Korpri', '1.1.7.01.03.09.001.77.001', 'Baju Seragam Korpri', 'Lembar', 0, 165000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(549, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.78', 'Celana Panjang Hitam Pria', '1.1.7.01.03.09.001.78.001', 'Celana Panjang Hitam Pria', 'Lembar', 0, 150000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(550, '1.1.7.01.03.09.006', 'Atribut', '1.1.7.01.03.09.006.0050', 'Emblem Bordir RSJ Sambang Lihum', '1.1.7.01.03.09.006.0050.001', 'Emblem Bordir RSJ Sambang Lihum', 'Lembar', 0, 3000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(551, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.79', 'Jilbab Segi Empat', '1.1.7.01.03.09.001.79.001', 'Jilbab Segi Empat Warna Biru', 'Lembar', 0, 24000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(552, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.80', 'Jilbab Voal Premium', '1.1.7.01.03.09.001.80.001', 'Jilbab Voal Premium', 'Lembar', 0, 50000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(553, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.81', 'Kain Sasirangan', '1.1.7.01.03.09.001.81.001', 'Kain Sasirangan', 'Lembar', 0, 375000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(554, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.82', 'Kain Sasirangan Khas Kalimantan Selatan', '1.1.7.01.03.09.001.82.001', 'Kain Sasirangan Khas Kalimantan Selatan', 'Lembar', 0, 135000.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(555, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.83', 'Pakaian Duta Excelent', '1.1.7.01.03.09.001.83.001', 'Pakaian Duta Excelent', 'Lembar', 0, 987900.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(556, '1.1.7.01.03.09.002', 'Penutup Kepala', '1.1.7.01.03.09.002.0002', 'Peci Nasional Bahan Bludru', '1.1.7.01.03.09.002.0002.001', 'Peci Nasional Pria', 'Buah', 0, 115100.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(557, '1.1.7.01.03.09.005', 'Penutup Kaki ', '1.1.7.01.03.09.005.0002', 'Sepatu Kerja ', '1.1.7.01.03.09.005.0002.001', 'Sepatu PDH Satpam', 'Pasang', 0, 119325.00, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(558, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0020', 'Seragam Dinas Khusus Driver', '1.1.7.01.03.09.007.0020.001', 'Seragam Dinas Khusus Driver', 'Stel', 0, 514762.50, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(559, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0006', 'Baju pengawasan', '1.1.7.01.03.09.007.0006.001', 'Seragam Satpam', 'Buah', 0, 168085.71, NULL, 'aktif', '2025-09-05 22:39:52', NULL),
	(560, '9.9.9.99.99.99.999', 'Testing', '9.9.9.99.99.99.999.99', 'Testing NUSP edit', '9.9.9.99.99.99.999.99.001', 'Testing Barang 1', 'Buah', 0, 120000.00, '1757084279_7183b615-4ffd-4d11-9c37-adcd2bb48f46.jpg', 'aktif', '2025-09-05 14:57:59', '2025-09-05 22:59:00');

-- Dumping structure for table inventori.master_old
CREATE TABLE IF NOT EXISTS `master_old` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `KODE_REK_108` varchar(50) NOT NULL,
  `NAMA_BARANG_108` varchar(100) NOT NULL,
  `KODE_NUSP` varchar(50) NOT NULL,
  `NAMA_NUSP` varchar(255) NOT NULL,
  `KODE_GUDANG` varchar(50) DEFAULT NULL,
  `NAMA_GUDANG` varchar(255) NOT NULL,
  `SATUAN` varchar(50) NOT NULL,
  `STOK_AKHIR` int(11) NOT NULL DEFAULT 0,
  `GAMBAR` varchar(100) DEFAULT NULL,
  `CREATED_AT` datetime NOT NULL DEFAULT current_timestamp(),
  `UPDATED_AT` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=550 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table inventori.master_old: ~549 rows (approximately)
REPLACE INTO `master_old` (`ID`, `KODE_REK_108`, `NAMA_BARANG_108`, `KODE_NUSP`, `NAMA_NUSP`, `KODE_GUDANG`, `NAMA_GUDANG`, `SATUAN`, `STOK_AKHIR`, `GAMBAR`, `CREATED_AT`, `UPDATED_AT`) VALUES
	(1, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0034', 'Baterai CMOS', '1.1.7.01.03.08.010.0034.001', 'Batterai Cmos', 'Buah', 0, NULL, '2025-08-29 11:11:29', NULL),
	(2, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.306', 'Catridge 810 Black', '', 'Cartridge Canon 810 Hitam', 'Buah', 0, NULL, '2025-08-29 11:11:29', NULL),
	(3, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.307', 'Catridge 811 Color', '', 'Cartridge Canon 811 Warna', 'Buah', 0, NULL, '2025-08-29 11:11:29', NULL),
	(4, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.0230', 'Cartridge BH-7 DAN CH-7', '', 'Catridge Warna Canon G2000', 'Buah', 0, NULL, '2025-08-29 11:11:29', NULL),
	(5, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0154', 'Klem', '', 'Clamp Kabel Beton', 'Buah', 0, NULL, '2025-08-29 11:11:29', NULL),
	(6, '1.1.7.01.03.06.006', 'USB/Flash Disk', '1.1.7.01.03.06.006.0004', 'Flasdisk 32 Gb', '', 'FD Sandisk 32 GB', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(7, '1.1.7.01.03.06.006', 'USB/Flash Disk', '1.1.7.01.03.06.006.0005', 'Flasdisk 64 Gb', '', 'FD Sandisk 64 GB', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(8, '1.1.7.01.03.06.006', 'USB/Flash Disk', '1.1.7.01.03.06.006.0008', 'Flasdisk 128 Gb', '', 'FD Sandisk 128 GB', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(9, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0035', 'External/ Portable Hardisk', '', 'Hardisk External ADATA HD710 PRO 1TB RED', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(10, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.36', 'HDD Caddy', '', 'HDD Caddy 9,5 MM', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(11, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.37', 'HTB', '', 'HTB', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(12, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0107', 'Konektor Telpon RJ45', '', 'Konektor RJ45 Cat 6', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(13, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.08.001.0094', 'Kabel ties ', '', 'Kabel Ties', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(14, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.38', 'Kabel USB Ext + Chipset NYK', '', 'Kabel USB Ext 2.0 + Chipset NYK 10M CE20', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(15, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.39', 'Kabel USB Ext + Chipset NYK', '', 'Kabel USB Ext 3.0 + Chipset NYK 10M CE20', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(16, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.40', 'Kabel Vga', '', 'Kabel VGA Vention Gold Plate 10M DADBL10', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(17, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0001', 'Keybord & mouse wireles', '', 'Keyboard + Mouse Logitech MK-120', 'Set', 0, NULL, '2025-08-29 11:11:30', NULL),
	(18, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0001', 'Keybord & mouse wireles', '', 'Keyboard + Mouse Logitech', 'Set', 0, NULL, '2025-08-29 11:11:30', NULL),
	(19, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0001', 'Keybord & mouse wireles', '', 'Keyboard + Mouse M-Tech STK-03', 'Set', 0, NULL, '2025-08-29 11:11:30', NULL),
	(20, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0118', 'Kabel Patch Core', '', 'Kabel Patch Core', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(21, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.126', 'Kabel LAN CAT 5', '', 'Kabel LAN CAT 5', 'Rol', 0, NULL, '2025-08-29 11:11:30', NULL),
	(22, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.127', 'Kabel LAN CAT 5', '', 'Kabel LAN CAT 6', 'Rol', 0, NULL, '2025-08-29 11:11:30', NULL),
	(23, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.08.001.128', 'Kabel Fiber Optic Core Global', '', 'Kabel Fiber Optic Core Global', 'Rol', 0, NULL, '2025-08-29 11:11:30', NULL),
	(24, '1.1.7.01.03.06.010', 'Mouse', '1.1.7.01.03.02.002.0181', 'Label Tape Epson 9 mm', '', 'Label Tape Epson 9 mm', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(25, '1.1.7.01.03.06.010', 'Mouse', '1.1.7.01.03.06.010.0005', 'Mouse Wireless', '', 'Mouse Wireles REXUS Q-50 PURPLSILENT CLICK', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(26, '1.1.7.01.03.06.010', 'Mouse', '1.1.7.01.03.06.010.0005', 'Mouse Wireless', '', 'Mouse Logitech MK-170', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(27, '1.1.7.01.03.06.010', 'Mouse', '1.1.7.01.03.06.010.0004', 'Mousepad', '', 'Mousepad', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(28, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.010.0003', 'Mouse  Kabel', '', 'Mouse Kabel Logitech', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(29, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.42', 'Paket Bahan Komputer', '', 'Pembelian Paket Bahan Komputer', 'Paket', 0, NULL, '2025-08-29 11:11:30', NULL),
	(30, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.43', 'Panel LCD', '', 'Panel LCD FHD 144Hz', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(31, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.014.44', 'Part Printer', '', 'Part ASF Printer Canon G2000', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(32, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.007.0019', 'RAM 4GB', '', 'RAM Venom RX 4GB 2666Mhz', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(33, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.007.0019', 'RAM 4GB', '', 'RAM DDR4 Dahua 2666', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(34, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.007.0019', 'RAM 4GB', '', 'RAM Venom RX 4GB 3200 Mhz', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(35, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.007.21', 'RAM 16GB', '', 'RAM Venom RX 16GB DDR4', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(36, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0003', 'Speaker', '', 'Speaker Gaming ROBOT RS200 LED', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(37, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.50', 'SSD 512 GB', '', 'SSD ADATA SU650 SATA 2,5" 512 GB', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(38, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.49', 'SSD 256 GB', '', 'SSD Venom RX 256GB', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(39, '1.1.7.01.03.06.007', 'Kartu Memori', '1.1.7.01.03.06.014.46', 'USB HUB', '', 'USB HUB 4 Port 3.0 1,2 M', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(40, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.007.20', 'RAM 8GB', '', 'Venom RX 8GB SODIMm DDR4 2666Mhz', 'unit', 0, NULL, '2025-08-29 11:11:30', NULL),
	(41, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.47', 'Wireles Router', '', 'Wireles Router Ruijie 1200 Pro v.3.20', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(42, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.288', 'Acrylic F4', '', 'Acrylic F4', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(43, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.289', 'Acrylic Tempat Leaflet Uk.12', '', 'Acrylic Tempat Leaflet Uk.12,3 x 25,4 20,3 cm', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(44, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.290', 'Acrylic Tempat Leaflet Uk.24', '', 'Acrylic Tempat Leaflet Uk.24,1 x 32,1 20,3 cm', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(45, '1.1.7.01.03.02.004', 'Amplop', '1.1.7.01.03.02.004.0021', 'Amplop Besar ', '', 'Amplop Besar Pakai Lem', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(46, '1.1.7.01.03.02.004', 'Amplop', '1.1.7.01.03.02.004.0023', 'Amplop Kecil ', '', 'Amplop Kecil Pakai Lem', 'Lembar', 0, NULL, '2025-08-29 11:11:30', NULL),
	(47, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0161', 'Stampat Pat (Bantalan) Ot/T', '', 'Bak Stamp', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(48, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0001', 'Batteray  A2', '', 'Batterai A2', 'Set', 0, NULL, '2025-08-29 11:11:30', NULL),
	(49, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0002', 'Batteray  A3', '', 'Batterai A3', 'Set', 0, NULL, '2025-08-29 11:11:30', NULL),
	(50, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0023', 'Baterai Besar D (Isi 2)', '', 'Batterai Besar', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(51, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0049', 'Binder Clips No. 105', '', 'Binder Clip No. 105', 'Kotak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(52, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0045', 'Binder Clips no. 107', '', 'Binder Clip No. 107', 'Kotak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(53, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0008', 'binder clips no. 155', '', 'Binder Clip No. 155', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(54, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0047', 'Binder Clips no. 200', '', 'Binder Clip No. 200', 'Kotak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(55, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0048', 'Binder Clips no. 260', '', 'Binder Clip No. 260', 'Kotak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(56, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0040', 'BOX FILE 4011 A', '', 'Box File', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(57, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0072', 'Buku Agenda Keluar/Masuk Isi/100 Lembar', '', 'Buku Agenda', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(58, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0020', 'Buku Double Folio  Isi 100 Lembar', '', 'Buku Besar Double Folio', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(59, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0001', 'Buku Folio - Isi 100 Lembar', '', 'Buku Besar Folio', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(60, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0002', 'Buku Kwarto - Isi 100 Lembar', '', 'Buku Kecil Quarto', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(61, '1.1.7.01.03.01.005', 'Buku Tulis', '1.1.7.01.03.01.005.0073', 'Buku Tulis Polos', '', 'Buku Tulis', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(62, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0026', 'Karbon Folio/100 Lbr', '', 'Carbon Paper', 'Dus', 0, NULL, '2025-08-29 11:11:30', NULL),
	(63, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.306', 'Catridge 810 Black', '', 'Catridge 810 Hitam', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(64, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.307', 'Catridge 811 Color', '', 'Catridge 811', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(65, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.308', 'Catridge Canon 47 Black', '', 'Catridge Canon 47', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(66, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.309', 'Catridge Canon 57 Color', '', 'Catridge Canon 57 Warna', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(67, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0004', 'Paper Clips - No 3', '', 'Clip Paper', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(68, '1.1.7.01.03.01.008', 'Cutter (Alat Tulis Kantor)', '1.1.7.01.03.01.008.0005', 'cutter kecil', '', 'Cutter', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(69, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0206', 'gunting ', '', 'Gunting Kertas', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(70, '1.1.7.01.03.01.013', 'Isi Staples', '1.1.7.01.03.01.013.0018', 'Isi Stapples Atom 24/63', '', 'Isi Staples Besar', 'Dus', 0, NULL, '2025-08-29 11:11:30', NULL),
	(71, '1.1.7.01.03.01.013', 'Isi Staples', '1.1.7.01.03.01.013.0020', 'Isi Staples - No 10', '', 'Isi Staples Kecil', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(72, '1.1.7.01.03.01.013', 'Isi Staples', '1.1.7.01.03.01.013.0020', 'Isi Staples - No 10', '', 'Isi Staples Kecil A', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(73, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0024', 'ISOLASI NACHI', '', 'Isolasi Bening', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(74, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0019', 'Doubletape-2"', '', 'Isolasi Double Tipe', 'Rol', 0, NULL, '2025-08-29 11:11:30', NULL),
	(75, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0207', 'kalkulator', '', 'Kalkulator', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(76, '1.1.7.01.03.06.001', 'Continuous Form', '1.1.7.01.03.06.001.10', 'Kertas Continous Form 2ply', '', 'Kertas 2 Ply', 'Box', 0, NULL, '2025-08-29 11:11:30', NULL),
	(77, '1.1.7.01.03.06.001', 'Continuous Form', '1.1.7.01.03.06.001.0003', 'Kertas Continous Form 3ply', '', 'Kertas 3 Ply PRS', 'Box', 0, NULL, '2025-08-29 11:11:30', NULL),
	(78, '1.1.7.01.03.06.001', 'Continuous Form', '1.1.7.01.03.06.001.11', 'Kertas Continous Form 4ply', '', 'Kertas 4 Ply PRS 500 Sheet', 'Box', 0, NULL, '2025-08-29 11:11:30', NULL),
	(79, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0009', 'Kertas Hvs 70 Gr A3/500 Lbr', '', 'Kertas A3', 'Rim', 0, NULL, '2025-08-29 11:11:30', NULL),
	(80, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0014', 'Kertas Hvs 80 Gr A3/500 Lbr', '', 'Kertas A3', 'Rim', 0, NULL, '2025-08-29 11:11:30', NULL),
	(81, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.182', 'Kertas BC Isi 100 Lembar', '', 'Kertas BC Putih', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(82, '1.1.7.01.03.02.002', 'Berbagai Kertas', '', '', '', 'Kertas Buram', '', 0, NULL, '2025-08-29 11:11:30', NULL),
	(83, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0114', 'kertas foto A4 ', '', 'Kertas Foto', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(84, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0002', 'Kertas HVS 70 gr A4/500 lbr', '', 'Kertas HVS A4 70 GSM', 'Rim', 0, NULL, '2025-08-29 11:11:30', NULL),
	(85, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0001', 'Kertas HVS 70 gr F4/500 lbr', '', 'Kertas HVS F4 70 GSM', 'Rim', 0, NULL, '2025-08-29 11:11:30', NULL),
	(86, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.30', 'Kertas HVS 70 Gr F4 / 500 lbr Blue', '', 'Kertas HVS F4 70 GSM BLUE', 'Rim', 0, NULL, '2025-08-29 11:11:30', NULL),
	(87, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.31', 'Kertas HVS 70 Gr F4 / 500 lbr Green', '', 'Kertas HVS F4 70 GSM GREEN', 'Rim', 0, NULL, '2025-08-29 11:11:30', NULL),
	(88, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.32', 'Kertas HVS 70 Gr F4 / 500 lbr Red', '', 'Kertas HVS F4 70 GSM RED', 'Rim', 0, NULL, '2025-08-29 11:11:30', NULL),
	(89, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.33', 'Kertas HVS 70 Gr F4 / 500 lbr Yellow', '', 'Kertas HVS F4 70 GSM YELLOW', 'Rim', 0, NULL, '2025-08-29 11:11:30', NULL),
	(90, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.183', 'Kertas PVC ID CARD', '', 'Kertas Khusus ID Card', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(91, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0157', 'Kertas Sertifikat', '', 'Kertas Piagam', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(92, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0140', 'Kertas Sticky Notes', '', 'Kertas Sticker', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(93, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.184', 'Kertas Struk Kasir 75 x 60 MM', '', 'Kertas Struk 3 Ply Kasir', 'Rol', 0, NULL, '2025-08-29 11:11:30', NULL),
	(94, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0167', 'Thermal Paper Roll Pendek', '', 'Kertas Thermal Uk. 57 x 50 mm', 'Rol', 0, NULL, '2025-08-29 11:11:30', NULL),
	(95, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.0168', 'Thermal Paper Roll Sedang', '', 'Kertas Thermal Uk. 80 x 80 mm', 'Rol', 0, NULL, '2025-08-29 11:11:30', NULL),
	(96, '1.1.7.01.03.02.002', 'Berbagai Kertas', '1.1.7.01.03.02.002.186', 'Label 80 X 50 Putih', '', 'Label 80 X 50 Putih', 'Rol', 0, NULL, '2025-08-29 11:11:30', NULL),
	(97, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.291', 'Lakban', '', 'Lakban', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(98, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0219', 'Lakban Bening', '', 'Lakban Bening', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(99, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0022', 'Lakban Coklat', '', 'Lakban Coklat', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(100, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0022', 'Lakban Coklat', '', 'Lakban Coklat A', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(101, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0043', 'Lakban Hitam Besar', '', 'Lakban Hitam', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(102, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.49', 'Lakban Kertas uk. 48 mm x 12 m', '', 'Lakban Kertas', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(103, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.0020', 'Glue Stick', '', 'Lem Stick', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(104, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.50', 'Magnet Papan Tulis ', '', 'Magnet Papan Tulis', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(105, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.292', 'Map Album Plastik', '', 'Map Album', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(106, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0075', 'Map Odner', '', 'Map File', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(107, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0072', 'Map Odner uk 50 mm', '', 'Map File Kecil 50 mm', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(108, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0029', 'Map Kertas', '', 'Map Kertas', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(109, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.016.0257', 'Map Kertas Biasa ', '', 'Map Kertas Biasa', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(110, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0044', 'Map Plastik Jepit Folder', '', 'Map Plastik Jepit', 'Lusin', 0, NULL, '2025-08-29 11:11:30', NULL),
	(111, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0073', 'Map Plastik Berlubang', '', 'Map Plastik Lobang', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(112, '1.1.7.01.03.01.008', 'Cutter (Alat Tulis Kantor)', '1.1.7.01.03.01.008.0006', 'isi cutter kecil', '', 'Mata Cutter', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(113, '1.1.7.01.03.01.006', 'Ordner Dan Map', '1.1.7.01.03.01.006.0074', 'Mika ID Card', '', 'Mika Id Card', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(114, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.293', 'Papan Tulis Uk. 120 X 240', '', 'Papan Tulis Uk. 120 X 240', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(115, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0040', 'Pelobang Kertas Besar', '', 'Pelobang Kertas Besar', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(116, '1.1.7.01.03.01.003', 'Penjepit Kertas', '1.1.7.01.03.01.003.0041', 'Pelobang Kertas Kecil', '', 'Pelobang Kertas Kecil', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(117, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.294', 'Pembelian Paket', '', 'Pembelian ATK', 'Paket', 0, NULL, '2025-08-29 11:11:30', NULL),
	(118, '1.1.7.01.03.01.007', 'Penggaris', '1.1.7.01.03.01.007.0009', 'Penggaris Plastik 30 cm', '', 'Penggaris 30 cm', 'Pcs', 0, NULL, '2025-08-29 11:11:30', NULL),
	(119, '1.1.7.01.03.01.007', 'Penggaris', '1.1.7.01.03.01.007.0012', 'Penggaris Plastik 60 cm', '', 'Penggaris 60 cm', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(120, '1.1.7.01.03.01.004', 'Penghapus/Korektor', '1.1.7.01.03.01.004.0001', 'Penghapus Whiteboard - Kecil', '', 'Penghapus Papan Tulis', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(121, '1.1.7.01.03.01.004', 'Penghapus/Korektor', '1.1.7.01.03.01.004.0040', ' Penghapus Pensil ', '', 'Penghapus Pensil', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(122, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0012', 'Pensil B 2B', '', 'Pensil', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(123, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0082', 'Pensil Merah Biru', '', 'Pensil Merah Biru', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(124, '1.1.7.01.03.06.003', 'Pita Printer', '1.1.7.01.03.06.003.0010', 'Pita Catridge Epson TM U220', '', 'Pita Catridge Epson TM U220', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(125, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.310', 'Pita Printer & Catridge Epson LX-300', '', 'Pita Printer & Catridge Epson LX-300', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(126, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.311', 'Pita Printer & Catridge Epson LX-310', '', 'Pita Printer & Catridge Epson LX-310', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(127, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.295', 'Sheet Protector F4 / Folio', '', 'Plastik Bantex F4 A', 'Pak', 0, NULL, '2025-08-29 11:11:30', NULL),
	(128, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.296', 'Post It uk. 76x101 mm / 3x4"', '', 'Post IT Besar', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(129, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.297', 'Post It uk. 76x101 mm / 3x4"', '', 'Post IT Besar', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(130, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.298', 'Post It Panah', '', 'Post IT Panah', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(131, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.299', 'Post It uk. 76x76 mm / 3x3"', '', 'Post IT Rainbow', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(132, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0062', 'Ballpoint Balliner Biru', '', 'Pulpen Tinta Biru (Balliner)', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(133, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.111', 'Pulpen Tinta Biru Standart', '', 'Pulpen Tinta Biru (Standart)', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(134, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0044', 'Ballpoint Tizzo Biru', '', 'Pulpen Tinta Biru (TIZO) A', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(135, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.112', 'Pulpen Tinta Hitam', '', 'Pulpen Tinta Hitam', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(136, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.113', 'Pulpen Tinta Merah', '', 'Pulpen Tinta Merah', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(137, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.300', 'Push Pins ', '', 'Push Pins', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(138, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0123', 'Rautan Pensil ', '', 'Rautan Pensil', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(139, '1.1.7.01.03.01.001', 'Alat Tulis', '1.1.7.01.03.01.001.0023', 'spidol boardmarker', '', 'Spidol Board Marker', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(140, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0142', 'Spidol Marker', '', 'Spidol Marker', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(141, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0149', 'Spidol White Board ', '', 'Spidol White', 'Buah', 0, NULL, '2025-08-29 11:11:30', NULL),
	(142, '1.1.7.01.03.01.016', 'Alat Tulis Kantor Lainnya', '1.1.7.01.03.01.016.0217', 'Stabilo', '', 'Stabillo', 'Pcs', 0, NULL, '2025-08-29 11:11:31', NULL),
	(143, '1.1.7.01.03.01.012', 'Staples', '1.1.7.01.03.01.012.0011', 'Staples Besar', '', 'Staples Besar', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(144, '1.1.7.01.03.01.012', 'Staples', '1.1.7.01.03.01.012.0012', 'Staples Kecil', '', 'Staples Kecil', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(145, '1.1.7.01.03.01.015', 'Seminar Kit', '1.1.7.01.03.01.015.0014', 'Isi Stapples 1210', '', 'Tali Id Card', 'Dus', 0, NULL, '2025-08-29 11:11:31', NULL),
	(146, '1.1.7.01.03.01.008', 'Cutter (Alat Tulis Kantor)', '1.1.7.01.03.01.008.0016', 'Tape Cutter', '', 'Tape Cuttter', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(147, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.312', 'Tinta Barcode', '', 'Tinta Barcode', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(148, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.313', 'Tinta Printer Brother Biru', '', 'Tinta Brother Biru', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(149, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.314', 'Tinta Printer Brother Hitam', '', 'Tinta Brother Hitam', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(150, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.315', 'Tinta Printer Brother Kuning', '', 'Tinta Brother Kuning', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(151, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.316', 'Tinta Printer Brother Merah', '', 'Tinta Brother Merah', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(152, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.317', 'Tinta Print Epson 057', '', 'Tinta Print Epson 057', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(153, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.318', 'Tinta Printer Canon Biru C790', '', 'Tinta Printer Canon Biru C790', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(154, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.319', 'Tinta Printer Canon Hitam', '', 'Tinta Printer Canon Hitam', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(155, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.320', 'Tinta Printer Canon Hitam BK790', '', 'Tinta Printer Canon Hitam BK790', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(156, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.321', 'Tinta Printer Canon Kuning Y790', '', 'Tinta Printer Canon Kuning Y790', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(157, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.322', 'Tinta Printer Canon Merah M790', '', 'Tinta Printer Canon Merah M790', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(158, '1.1.7.01.03.06.004', 'Tinta/Toner Printer', '1.1.7.01.03.06.004.326', 'Tinta Printer Warna', '', 'Tinta Printer Canon Warna', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(159, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0007', 'Tinta Epson 003 Biru', '', 'Tinta Printer Epson Biru 003', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(160, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0012', 'Tinta Epson 664 Biru', '', 'Tinta Printer Epson Biru 664', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(161, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0013', 'Tinta Epson Biru C 001', '', 'Tinta Printer Epson Biru C 001', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(162, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0001', 'Tinta Epson Hitam 003', '', 'Tinta Printer Epson Hitam 003', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(163, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0014', 'Tinta Epson Hitam 008', '', 'Tinta Printer Epson Hitam 008', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(164, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0002', 'Tinta Epson Hitam 664', '', 'Tinta Printer Epson Hitam 664', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(165, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0015', 'Tinta Epson Hitam BK 001', '', 'Tinta Printer Epson Hitam BK 001', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(166, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0006', 'Tinta Epson 003 Kuning', '', 'Tinta Printer Epson Kuning 003', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(167, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0016', 'Tinta Epson 664 Kuning', '', 'Tinta Printer Epson Kuning 664', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(168, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0017', 'Tinta Epson Kuning Y 001', '', 'Tinta Printer Epson Kuning Y 001', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(169, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0008', 'Tinta Epson 003 Magenta', '', 'Tinta Printer Epson Merah 003', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(170, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0018', 'Tinta Epson 664 Merah', '', 'Tinta Printer Epson Merah 664', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(171, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0019', 'Tinta Epson Merah M 001', '', 'Tinta Printer Epson Merah M 001', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(172, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0020', 'Tinta Printer Fargo C50 SN : C1410125', '', 'Tinta Printer Fargo C50 SN : C1410125', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(173, '1.1.7.01.03.01.004', 'Penghapus/Korektor', '1.1.7.01.03.01.004.0046', 'Tipe X', '', 'Tipe X', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(174, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.226', 'Aki GS Goldstar P.NS402L', '', 'Aki GS Goldstar P.NS402L', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(175, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0130', 'Alat cukur Rambut', '', 'Alat Cukur Rambut', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(176, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0392', 'Alat Pembersih Kaca', '', 'Alat Pembersih Kaca', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(177, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0286', 'Sabit', '', 'Arit', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(178, '1.1.7.01.04.01.001', 'Obat Cair', '1.1.7.01.04.01.001.0066', 'Antiseptik Terralin 2 Liter -', '', 'Aseptan', 'Kemasan', 0, NULL, '2025-08-29 11:11:31', NULL),
	(179, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.883', 'Baby Oil 200 ml', '', 'Baby Oil 200 ml', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(180, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0131', 'Bantal Dakron', '', 'Bantal Dakron', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(181, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.227', 'Batere As_GD Part No. 5706511', '', 'Batere As_GD Part No. 5706511', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(182, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0018', 'Baterai 9V', '', 'Batterai 9 Volt', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(183, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0393', 'Batu Asahan', '', 'Batu Asahan', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(184, '1.1.7.01.04.01.001', 'Obat Cair', '1.1.7.01.04.01.001.1688', 'Bedak Wajah', '', 'Bedak Wajah', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(185, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0014', 'Bendera', '', 'Bendera Merah Putih', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(186, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.32', 'Bento Box Deposible', '', 'Bento Box Deposible', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(187, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.0025', 'Bento Makan', '', 'Bento Makan', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(188, '1.1.7.01.03.09.006', 'Atribut', '1.1.7.01.03.09.006.0031', 'Borgol Kecil', '', 'Borgol', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(189, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0394', 'Botol Spray Semprotan Air', '', 'Botol Spray Semprotan Air', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(190, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0132', 'Box Container SAFE CB 65', '', 'Box Container SAFE CB 65', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(191, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0133', 'Box Gajah 100 liter', '', 'Box Gajah 100 liter', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(192, '1.1.7.01.02.01.001', 'Suku Cadang Alat Angkutan Darat Bermotor', '1.1.7.01.02.01.001.0565', 'Busi', '', 'Busi Mesin Rumput', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(193, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0395', 'Cangkul', '', 'Cangkul', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(194, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.0025', 'Celana Dalam Wanita', '', 'Celana Dalam Wanita (CD)', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(195, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.1000', 'Celemek Anti Air Terpal', '', 'Celemek Anti Air Terpal', 'Lembar', 0, NULL, '2025-08-29 11:11:31', NULL),
	(196, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.1001', 'Celemek Kain', '', 'Celemek Kain', 'Lembar', 0, NULL, '2025-08-29 11:11:31', NULL),
	(197, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0396', 'Cotton Bud', '', 'Cotton Bud', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(198, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0397', 'Cup Seller', '', 'Cup Seller', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(199, '1.1.7.01.03.07.003', 'Ember, Slang, Dan Tempat Air Lainnya', '1.1.7.01.03.07.003.0050', 'Ember Isi 10 Ltr', '', 'Ember Isi 10 Ltr', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(200, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.05.01.999.0080', 'Filter Air / Saringan Air', '', 'Filter Saringan Air 10"', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(201, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.05.01.999.0080', 'Filter Air / Saringan Air', '', 'Filter Saringan Air 20"', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(202, '1.1.7.01.01.12.001', 'Alat Kesehatan', '1.1.7.01.01.12.001.0628', 'Foot Step', '', 'Foot Step', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(203, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.27', 'Galon', '', 'Galon', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(204, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.28', 'Galon Air Berkeran', '', 'Galon Air Berkeran', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(205, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0017', 'Gayung Mandi ', '', 'Gayung Mandi', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(206, '1.1.7.01.04.01.002', 'Obat Padat', '1.1.7.01.04.01.002.1067', 'Gelang Dewasa Biru', '', 'Gelang Thermal Dewasa Blue', 'Biji', 0, NULL, '2025-08-29 11:11:31', NULL),
	(207, '1.1.7.01.04.01.002', 'Obat Padat', '1.1.7.01.04.01.002.1068', 'Gelang Dewasa Pink', '', 'Gelang Thermal Dewasa Pink', 'Biji', 0, NULL, '2025-08-29 11:11:31', NULL),
	(208, '1.1.7.01.03.07.018', 'Perabot Kantor Lainnya', '1.1.7.01.03.07.018.0134', 'Gembok Uk. 50', '', 'Gembok Uk. 50', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(209, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0315', 'Gunting Rumput', '', 'Gunting Rumput', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(210, '1.1.7.01.03.07.016', 'Hand Sanitizer', '1.1.7.01.03.07.016.0001', 'Hand Sanitizer 5 L', '', 'Handwash 5 liter', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(211, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0016', 'Handy Talky', '', 'Handy Talky', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(212, '1.1.7.01.03.07.012', 'Pengharum Ruangan', '1.1.7.01.03.07.012.0027', 'Pengharum Ruangan Spray', '', 'Isi Pengharum Ruangan Ref. 225 ml', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(213, '1.1.7.01.03.13.999', 'Alat/Bahan Untuk Kegiatan Kantor Lainnya Lain - lain', '1.1.7.01.03.13.999.0001', 'Jam Dinding', '', 'Jam Dinding', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(214, '1.1.7.01.03.09.003', 'Penutup Badan', '1.1.7.01.03.09.003.0005', 'Jas Hujan ', '', 'Jas Hujan', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(215, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.0866', 'Jepitan Baju', '', 'Jepitan Baju', 'unit', 0, NULL, '2025-08-29 11:11:31', NULL),
	(216, '1.1.7.01.03.08.999', 'Alat Listrik dan Elektronik Lain-lain', '1.1.7.01.03.08.999.0016', 'kabel magnet', '', 'Kabel Magnet', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(217, '1.1.7.01.07.01.003', 'Makan Minum Harian Pasien', '1.1.7.01.07.01.003.0197', 'Kantong Plastik Gula', '', 'Kantong Plastik Gula', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(218, '1.1.7.01.07.01.003', 'Makan Minum Harian Pasien', '1.1.7.01.07.01.003.0198', 'Kantong Plastik Putih', '', 'Kantong Plastik Putih', 'unit', 0, NULL, '2025-08-29 11:11:31', NULL),
	(219, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.867', 'Kapi 2"', '', 'Kapi 2"', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(220, '1.1.7.01.01.02.999', 'Bahan Kimia Lain-lain', '1.1.7.01.01.02.999.0002', 'Kaporit', '', 'Kaporit Tjiwi Kimia 60 %', 'Kilogram', 0, NULL, '2025-08-29 11:11:31', NULL),
	(221, '1.1.7.01.03.07.012', 'Pengharum Ruangan', '1.1.7.01.03.07.012.0026', 'Kapur Barus', '', 'Kapur Barus Ngengat', 'Bag / Bungkus ', 0, NULL, '2025-08-29 11:11:31', NULL),
	(222, '1.1.7.01.03.07.004', 'Keset Dan Tempat Sampah', '1.1.7.01.03.07.004.0024', 'Karpet Mie', '', 'Karpet Mie Bihun Anti Slip', 'Meter', 0, NULL, '2025-08-29 11:11:31', NULL),
	(223, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.868', 'Kastok Pakaian', '', 'Kastok Pakaian', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(224, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.869', 'Kenop Gas', '', 'Kenop Gas', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(225, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0068', 'Keranjang  Keranjang Pakaian', '', 'Keranjang Basket Besar Uk. 43 cm', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(226, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0068', 'Keranjang  Keranjang Pakaian', '', 'Keranjang Pakaian', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(227, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0017', 'Kerucut Lalu Lintas', '', 'Kerucut Jalan', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(228, '1.1.7.01.01.02.999', 'Bahan Kimia Lain-lain', '1.1.7.01.01.02.999.0043', 'Klorset Tablet Isi 25 Tablet', '', 'Klorset Tablet Isi 25 Tablet', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(229, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.870', 'Kompor Mata Seribu', '', 'Kompor Mata Seribu', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(230, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.33', 'Kotak Makan Disposible', '', 'Kotak Makan Disposible', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(231, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.36', 'Kotak Makan Persegi Empat', '', 'Kotak Makan Persegi Empat', 'Set', 0, NULL, '2025-08-29 11:11:31', NULL),
	(232, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.29', 'Kotak Makan Transparan Plastik', '', 'Kotak Makan Transparan Plastik', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(233, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.34', 'Kotak Snack R3', '', 'Kotak Snack R3', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(234, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.871', 'Kran Galon Standart', '', 'Kran Galon Standart', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(235, '1.1.7.01.03.07.013', 'Kuas', '1.1.7.01.03.07.013.0002', 'Kuas 2.5 Inch', '', 'Kuas Cat 2,5"', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(236, '1.1.7.01.01.01.022', 'Bahan Bangunan Dan Konstruksi Lainnya', '1.1.7.01.01.01.022.0439', 'Kunci Dekson', '', 'Kunci Dekson', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(237, '1.1.7.01.01.01.022', 'Bahan Bangunan Dan Konstruksi Lainnya', '', '', '', 'Kunci Estilo', '', 0, NULL, '2025-08-29 11:11:31', NULL),
	(238, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0018', 'Lampu Lalu Lintas', '', 'Lampu Lalu Lintas', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(239, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.0016', 'Kain Pel  ', '', 'Lap Lantai Putih (Kain Selabar)', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(240, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.0034', 'Lap Meja (Kanebo)', '', 'Lap Meja (Kanebo)', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(241, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.35', 'Lap Micro Fiber', '', 'Lap Micro Fiber', 'Lembar', 0, NULL, '2025-08-29 11:11:31', NULL),
	(242, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.36', 'Lap Tangan', '', 'Lap Tangan', 'Lembar', 0, NULL, '2025-08-29 11:11:31', NULL),
	(243, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.54', 'Lem Fox 1 Kg', '', 'Lem Fox 1 Kg', 'Kaleng', 0, NULL, '2025-08-29 11:11:31', NULL),
	(244, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.55', 'Lem Fox 600 Gr', '', 'Lem Fox 600 Gr', 'Kaleng', 0, NULL, '2025-08-29 11:11:31', NULL),
	(245, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.51', 'Lem Tikus', '', 'Lem Tikus', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(246, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.872', 'Lipstik', '', 'Lipstik', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(247, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.35', 'Mika KT4', '', 'Mika KT4', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(248, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.04.01.999.0003', 'Minyak Kayu Putih 15 ml Lang', '', 'Minyak Kayu Putih 150 ml', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(249, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.05.01.999.0215', 'Minyak rambut', '', 'Minyak Rambut Urang Aring 125 ml', 'Pcs', 0, NULL, '2025-08-29 11:11:31', NULL),
	(250, '1.1.7.01.03.13.999', 'Alat/Bahan Untuk Kegiatan Kantor Lainnya Lain - lain', '1.1.7.01.03.13.999.23', 'Minyak telon bayi', '', 'Minyak Telon 150 ml', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(251, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.00104', 'Mukena', '', 'Mukena', 'Lembar', 0, NULL, '2025-08-29 11:11:31', NULL),
	(252, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0105', 'Obat Nyamuk Cair 600 Ml', '', 'Obat Nyamuk Spray 600 ml', 'Kaleng', 0, NULL, '2025-08-29 11:11:31', NULL),
	(253, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0398', 'Obat Rumput', '', 'Obat Rumput', 'Liter', 0, NULL, '2025-08-29 11:11:31', NULL),
	(254, '1.1.7.01.01.04.002', 'Minyak Pelumas', '1.1.7.01.01.04.002.0079', 'Oli Samping', '', 'Oli Samping', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(255, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.0023', 'Pakaian Dalam Wanita (Bra)', '', 'Pakaian Dalam Wanita (BH)', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(256, '1.1.7.01.07.02.002', 'Pakan Ikan', '1.1.7.01.07.02.002.0012', 'Pakan Ikan Active', '', 'Pakan Ikan Active', 'Karung', 0, NULL, '2025-08-29 11:11:31', NULL),
	(257, '1.1.7.01.03.12.002', 'Souvenir / Cenderamata Lainnya', '1.1.7.01.03.12.002.0023', 'Paket Sembako', '', 'Paket Sembako', 'Paket', 0, NULL, '2025-08-29 11:11:31', NULL),
	(258, '1.1.7.01.03.13.999', 'Alat/Bahan Untuk Kegiatan Kantor Lainnya Lain - lain', '1.1.7.01.03.13.999.0034', 'Papan Tulis Magnet 60x90 cm', '', 'Papan Tulis Magnet 60x90 cm', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(259, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0399', 'Parang', '', 'Parang', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(260, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0400', 'Paratop', '', 'Paratop', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(261, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.0002', 'Pewangi dan Pelembut Pakaian 5 L', '', 'Parfum Laundry 5 ltr', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(262, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0134', 'Pasta Gigi 120 Gram', '', 'Pasta Gigi 120 gr', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(263, '1.1.7.01.03.12.002', 'Souvenir / Cenderamata Lainnya', '1.1.7.01.03.12.002.0010', 'Payung', '', 'Payung Jumbo', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(264, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.873', 'Pembalut Wanita Bersayap', '', 'Pembalut Wanita Bersayap', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(265, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.83', 'Pembersih Kaca Ref. 425 ml', '', 'Pembersih Kaca Ref. 425 ml', 'Bag / Bungkus ', 0, NULL, '2025-08-29 11:11:31', NULL),
	(266, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.0082', 'Pembersih Kaca Ref. 500 ml', '', 'Pembersih Kaca Ref. 500 ml', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(267, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.0068', 'Pembersih Lantai 770 ml', '', 'Pembersih Pengharum Lantai 770 ml', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(268, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.0064', 'Pembersih Porselen 750 ml', '', 'Pembersih Porselen 750 ml (Vixal)', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(269, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.874', 'Pemotong Kuku', '', 'Pemotong Kuku', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(270, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.0048', 'Pemutih Pakaian Regular 4 Lt', '', 'Pemutih Pakaian Bayclin 4 ltr', 'Jerigen', 0, NULL, '2025-08-29 11:11:31', NULL),
	(271, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.0726', 'Pisau Cukur ', '', 'Pencukur Jenggot (Gillite Goal)', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(272, '1.1.7.01.03.07.008', 'Bahan Kimia Untuk Pembersih', '1.1.7.01.03.07.008.84', 'Pengharum Toilet ', '', 'Pengharum Toilet (Wipol)', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(273, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0401', 'Pengharum WC', '', 'Pengharum WC', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(274, '1.1.7.01.03.09.006', 'Atribut', '1.1.7.01.03.09.006.0034', 'Pentungan ', '', 'Pentungan', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(275, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0402', 'Pisau Besar', '', 'Pisau Besar', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(276, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0320', 'Pisau Pemotong Rumput', '', 'Pisau Mesin Rumput', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(277, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0403', 'Pisau Sedang', '', 'Pisau Sedang', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(278, '1.1.7.01.01.12.001', 'Alat Kesehatan', '1.1.7.01.01.12.001.629', 'Pispot Corong', '', 'Pispot Corong', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(279, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0404', 'Plastik Besar Loundry', '', 'Plastik Besar Loundry', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(280, '1.1.7.01.07.01.003', 'Makan Minum Harian Pasien', '1.1.7.01.07.01.003.0199', 'Plastik Cup Sealer', '', 'Plastik Cup Sealer', 'Rol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(281, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0405', 'Plastik Kemas Loundry', '', 'Plastik Kemas Loundry', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(282, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0406', 'Plastik Medis Loundry Uk. 40x60 Isi 10', '', 'Plastik Medis Loundry Uk. 40x60 Isi 10', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(283, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0407', 'Plastik Sampah Kuning Uk. 40 x 60 Isi : 10 lbr', '', 'Plastik Sampah Kuning Uk. 40 x 60 Isi : 10 lbr', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(284, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0408', 'Plastik Sampah Medis Uk. 40 x 40 cm', '', 'Plastik Sampah Medis Uk. 40 x 40 cm', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(285, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0409', 'Plastik Putih Uk. 80 x 100 Isi : 10 lbr', '', 'Plastik Sampah Putih Uk. 80 x 100 Isi : 10 lbr', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(286, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0410', 'Plastik Sampah Tipis Uk. 80 x 100 cm', '', 'Plastik Sampah Tipis Uk. 80 x 100 cm', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(287, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0411', 'Plastik Sampah Uk. 15 x 15 cm', '', 'Plastik Sampah Uk. 15 x 15 cm', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(288, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0412', 'Plastik Sampah Uk. 40 x 40 cm', '', 'Plastik Sampah Uk. 40 x 40 cm', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(289, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0413', 'Plastik Sampah Ukuran Kecil', '', 'Plastik Sampah Ukuran Kecil', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(290, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0414', 'Plastik Sampah Ukuran Sedang', '', 'Plastik Sampah Ukuran Sedang', 'Pak', 0, NULL, '2025-08-29 11:11:31', NULL),
	(291, '1.1.7.01.07.01.003', 'Makan Minum Harian Pasien', '1.1.7.01.07.01.003.0200', 'Plastik Wraping 35 cm', '', 'Plastik Wraping 35 cm', 'Rol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(292, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.875', 'Pompa Asi Manual', '', 'Pompa Asi Manual', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(293, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '', '', '', 'Procent 50 ml', '', 0, NULL, '2025-08-29 11:11:31', NULL),
	(294, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.318', 'Pupuk Kandang', '', 'Pupuk Kandang', 'Karung', 0, NULL, '2025-08-29 11:11:31', NULL),
	(295, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.317', 'Pupuk NPK', '', 'Pupuk NPK', 'Karung', 0, NULL, '2025-08-29 11:11:31', NULL),
	(296, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.876', 'Rantai', '', 'Rantai', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(297, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0416', 'Refill Urinal Cleaner Anti Pesing', '', 'Refill Urinal Cleaner Anti Pesing', 'Botol', 0, NULL, '2025-08-29 11:11:31', NULL),
	(298, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0009', 'Rompi Safety', '', 'Rompi Security', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(299, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.0201', 'Pupuk Sp36 ', '', 'SP', 'Kilogram', 0, NULL, '2025-08-29 11:11:31', NULL),
	(300, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0417', 'Sabun Bodywash Refill 410 ml', '', 'Sabun Bodywash Refill 410 ml', 'Bag / Bungkus ', 0, NULL, '2025-08-29 11:11:31', NULL),
	(301, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0169', 'Sabun Detergen 900 Gr/700 Gr', '', 'Sabun Bubuk 700gr A', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(302, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0418', 'Sabun Cuci Piring (Sunlight 650 ml)', '', 'Sabun Cuci Piring (Sunlight 650 ml)', 'Bag / Bungkus ', 0, NULL, '2025-08-29 11:11:31', NULL),
	(303, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0419', 'Sabun Cuci Piring 420 ml', '', 'Sabun Cuci Piring 420 ml', 'Bag / Bungkus ', 0, NULL, '2025-08-29 11:11:31', NULL),
	(304, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0185', 'Sabun Mandi 250 Ml', '', 'Sabun Mandi (Lifebuoy) 250 ml Refil', 'Pcs', 0, NULL, '2025-08-29 11:11:31', NULL),
	(305, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0026', 'sabut cuci piring/sikat kawat', '', 'Sabut Kawat', 'Buah', 0, NULL, '2025-08-29 11:11:31', NULL),
	(306, '1.1.7.01.04.01.002', 'Obat Padat', '1.1.7.01.04.01.002.0490', 'Safety Box', '', 'Safety Box', 'Pcs', 0, NULL, '2025-08-29 11:11:31', NULL),
	(307, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.0037', 'Sandal Pria', '', 'Sandal Jepit', 'Pasang', 0, NULL, '2025-08-29 11:11:31', NULL),
	(308, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0025', 'Sapu Lantai', '', 'Sapu Lantai', 'Pcs', 0, NULL, '2025-08-29 11:11:32', NULL),
	(309, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0013', 'Sapu Lidi  Tongkat', '', 'Sapu Lidi Panjang', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(310, '1.1.7.01.03.09.004', 'Penutup Tangan', '1.1.7.01.03.09.004.0004', 'Sarung Tangan  Bahan Kain Rajut Standart ', '', 'Sarung Tangan Kain', 'Pasang', 0, NULL, '2025-08-29 11:11:32', NULL),
	(311, '1.1.7.01.03.09.004', 'Penutup Tangan', '1.1.7.01.03.09.004.0004', 'Sarung Tangan  Bahan Kain Rajut Standart ', '', 'Sarung Tangan Kain Bintik A', 'Pasang', 0, NULL, '2025-08-29 11:11:32', NULL),
	(312, '1.1.7.01.03.09.004', 'Penutup Tangan', '1.1.7.01.03.09.004.0005', 'Sarung Tangan Bahan Karet ', '', 'Sarung Tangan Karet', 'Pasang', 0, NULL, '2025-08-29 11:11:32', NULL),
	(313, '1.1.7.01.05.01.999', 'Persediaan Untuk Dijual/Diserahkan Kepada Masyarakat Lain - lain', '1.1.7.01.05.01.999.00118', 'Sejadah', '', 'Sejadah', 'Lembar', 0, NULL, '2025-08-29 11:11:32', NULL),
	(314, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.0305', 'Sekam Padi', '', 'Sekam', 'Sak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(315, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0137', 'Senter LED Recharge', '', 'Sentar Holagen', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(316, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.634', 'Sepatu Boots Petrova', '', 'Sepatu Boots', 'Pasang', 0, NULL, '2025-08-29 11:11:32', NULL),
	(317, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0231', 'Serokan Sampah  Plastik', '', 'Serok Sampah', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(318, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0420', 'Shampo Lifebuoy 170 ml', '', 'Shampo Lifebuoy 170 ml', 'Botol', 0, NULL, '2025-08-29 11:11:32', NULL),
	(319, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0003', 'Sikat Gigi ', '', 'Sikat Gigi', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(320, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0030', 'Sikat Kawat', '', 'Sikat Kawat', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(321, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0004', 'Sikat Kamar Mandi ', '', 'Sikat Lantai Biasa', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(322, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0031', 'Sikat Lantai Gagang Panjang', '', 'Sikat Lantai Gagang Panjang', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(323, '1.1.7.01.03.07.002', 'Alat-Alat Pel Dan Lap', '1.1.7.01.03.07.002.0001', 'Alat Pel  Karet', '', 'Sikat Lantai Karet', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(324, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0032', 'Sikat Punggung', '', 'Sikat Punggung', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(325, '1.1.7.01.03.07.001', 'Sapu Dan Sikat', '1.1.7.01.03.07.001.0005', 'Sikat Wc ', '', 'Sikat WC', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(326, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.877', 'Sisir Biasa', '', 'Sisir Biasa', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(327, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.878', 'Sisir Rapat', '', 'Sisir Rapat', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(328, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.879', 'Slender K', '', 'Slender K', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(329, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.880', 'Spon Bedak', '', 'Spon Bedak', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(330, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.881', 'Spon Cuci Piring', '', 'Spon Cuci Piring', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(331, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.882', 'Spon Mandi', '', 'Spon Mandi', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(332, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.446', 'Stiker Papan Nama Peserta Apel', '', 'Stiker Papan Nama Peserta Apel', 'Lembar', 0, NULL, '2025-08-29 11:11:32', NULL),
	(333, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0312', 'Kemoceng', '', 'Sula Debu', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(334, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.30', 'Talenan', '', 'Talenan', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(335, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0019', 'Tali Bendera', '', 'Tali Bendera', 'Meter', 0, NULL, '2025-08-29 11:11:32', NULL),
	(336, '1.1.7.01.01.08.002', 'Bahan/Bibit Tanaman Perkebunan', '1.1.7.01.01.08.002.316', 'tanah hitam', '', 'Tanah Hitam', 'RIT', 0, NULL, '2025-08-29 11:11:32', NULL),
	(337, '1.1.7.01.03.07.003', 'Ember, Slang, Dan Tempat Air Lainnya', '1.1.7.01.03.07.003.51', 'Tandon Air 1200 Liter', '', 'Tandon Air 1200 Liter', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(338, '1.1.7.01.03.12.002', 'Souvenir / Cenderamata Lainnya', '1.1.7.01.03.12.002.0022', 'Tas Anyaman Plastik', '', 'Tas Anyaman Plastik', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(339, '1.1.7.01.03.07.004', 'Keset Dan Tempat Sampah', '1.1.7.01.03.07.004.0018', 'Plastik Sampah Hitam Besar', '', 'Tempat Sampah Besar Uk. 120 Ltr', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(340, '1.1.7.01.01.12.001', 'Alat Kesehatan', '1.1.7.01.01.12.001.630', 'Termometer Kulkas', '', 'Termometer Kulkas', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(341, '1.1.7.01.03.07.009', 'Alat Untuk Makan Dan Minum', '1.1.7.01.03.07.009.31', 'Termos Air Panas 50H 2 Ltr', '', 'Termos Air Panas 50H 2 Ltr', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(342, '1.1.7.01.03.09.006', 'Atribut', '1.1.7.01.03.09.006.51', 'Tiang Apel ', '', 'Tiang Apel Bahan Besi', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(343, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0246', 'Tissue Gulung ', '', 'Tissue Gulung', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(344, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0257', 'Tissue Kotak Facial', '', 'Tissue Kotak', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(345, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0421', 'Tissue Pengesat Tangan', '', 'Tissue Pengesat Tangan', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(346, '1.1.7.01.03.07.015', 'Peralatan Kebersihan Dan Bahan Pembersih Lainnya', '1.1.7.01.03.07.015.0273', 'Tongkat Pel ', '', 'Tongkat Selabar', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(347, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.884', 'Viva Alka Liquid Alkali @25 ltr', '', 'Viva Alka Liquid Alkali @25 ltr', 'Galon', 0, NULL, '2025-08-29 11:11:32', NULL),
	(348, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.885', 'Viva Blanko Oxygen Bleach @25 ltr', '', 'Viva Blanko Oxygen Bleach @25 ltr', 'Galon', 0, NULL, '2025-08-29 11:11:32', NULL),
	(349, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.886', 'Viva Main Loundry Detergent @20 ltr', '', 'Viva Main Loundry Detergent @20 ltr', 'Galon', 0, NULL, '2025-08-29 11:11:32', NULL),
	(350, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.887', 'Viva Surf Softener @20 ltr', '', 'Viva Surf Softener @20 ltr', 'Galon', 0, NULL, '2025-08-29 11:11:32', NULL),
	(351, '1.1.7.01.01.12.999', 'Bahan Lainnya Lain-lain', '1.1.7.01.01.12.999.888', 'Viva Tard Neutralizer @20 ltr', '', 'Viva Tard Neutralizer @20 ltr', 'Galon', 0, NULL, '2025-08-29 11:11:32', NULL),
	(352, '1.1.7.01.03.04.001', 'Materai', '1.1.7.01.03.04.001.0004', 'Materai 10000', '', 'Materai @10.000', 'Pcs', 0, NULL, '2025-08-29 11:11:32', NULL),
	(353, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '', 'Alat Toll Pembuka Panel Modul LED', '', 0, NULL, '2025-08-29 11:11:32', NULL),
	(354, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '', 'CUP Pasitu AC', '', 0, NULL, '2025-08-29 11:11:32', NULL),
	(355, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '', 'Capasitor 2', 'unit', 0, NULL, '2025-08-29 11:11:32', NULL),
	(356, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '', 'Capasitor 50', 'unit', 0, NULL, '2025-08-29 11:11:32', NULL),
	(357, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0247', 'Lampu Downlight', '', 'DL Hannochs 5 W ( 3 Color )', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(358, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0247', 'Lampu Downlight', '', 'DL Hannochs 9 W ( 3 Color )', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(359, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '', 'Dinamo NPG 18-16', '', 0, NULL, '2025-08-29 11:11:32', NULL),
	(360, '1.1.7.01.03.08.008', 'Vitting', '1.1.7.01.03.08.008.0012', 'Fitting Tempel', '', 'Fitting', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(361, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '', 'Freon R22 @5kg', '', 0, NULL, '2025-08-29 11:11:32', NULL),
	(362, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '', 'Freon R32 @5kg', '', 0, NULL, '2025-08-29 11:11:32', NULL),
	(363, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '', 'Freon R410 @5kg', '', 0, NULL, '2025-08-29 11:11:32', NULL),
	(364, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.119', 'Termis 2 PK', '', 'Termis 2 pk', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(365, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0019', 'Isolasi ', '', 'Isolasi Listrik 3M 33', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(366, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0019', 'Isolasi ', '', 'Isolasi Scoot 3M 33', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(367, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', '', '', 'Join Sleeve 50', '', 0, NULL, '2025-08-29 11:11:32', NULL),
	(368, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.48', 'Kabel Data 40Cm', '', 'Kabel Data 40Cm', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(369, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.120', 'Kabel Duck Putih 20 x 15', '', 'Kabel Duck Putih 20 x 15', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(370, '1.1.7.01.03.08.001', 'Kabel Listrik', '', 'Kabel Nyaf 4 Mm ', '', 'Kabel NYAF 35', 'Meter', 0, NULL, '2025-08-29 11:11:32', NULL),
	(371, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0026', 'Kabel Nym 2 X 1,5 Mm ', '', 'Kabel NYM 2 x 1,5 Eterna 50m', 'Meter', 0, NULL, '2025-08-29 11:11:32', NULL),
	(372, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0032', 'Kabel Nym 3 X 2,5 Mm ', '', 'Kabel NYM 3 x 2,5', 'Meter', 0, NULL, '2025-08-29 11:11:32', NULL),
	(373, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0080', 'Kabel Twis Teed 2 X 10 Mm', '', 'Kabel SR / Twis Teed 2x10', 'Meter', 0, NULL, '2025-08-29 11:11:32', NULL),
	(374, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0094', 'Kabel ties ', '', 'Kabel Ties 25 cm', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(375, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0131', 'Kabel Ties 25 cm', '', 'Kabel Ties 30 cm', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(376, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.124', 'Kabel Twis Teed 2 X 20 Mm.', '', 'Kabel Twis Teed 2x20 mm', 'Meter', 0, NULL, '2025-08-29 11:11:32', NULL),
	(377, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '', 'Kapasitor 1,5 uf', 'unit', 0, NULL, '2025-08-29 11:11:32', NULL),
	(378, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '', 'Kapasitor 2 uf', 'unit', 0, NULL, '2025-08-29 11:11:32', NULL),
	(379, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '1.1.7.01.03.08.012.0007', 'Capasitor ', '', 'Kapasitor 3 mf', 'unit', 0, NULL, '2025-08-29 11:11:32', NULL),
	(380, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Klem Kabel No. 17 "Imundex"', '', 'Klem Kabel No. 17 "Imundex"', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(381, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Klem No. 10 mm', '', 'Klem No. 10 mm', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(382, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Konektor Kabel', '', 'Konektor Kabel', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(383, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0286', 'Lampu LED 20 Watt', '', 'LED Pila 20 W', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(384, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0237', 'Lampu LED 7 Watt', '', 'LED Pila 7 W', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(385, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0287', 'Lampu LED 9 Watt', '', 'LED Pila 9 W', 'Kotak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(386, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0288', 'Lampu LED 18 Watt', '', 'Lampu LED 18 Watt', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(387, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0237', 'Lampu LED 7 Watt', '', 'Lampu LED 7 Watt', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(388, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0287', 'Lampu LED 9 Watt', '', 'Lampu LED 9 Watt', 'Kotak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(389, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0290', 'Lampu Sorot LED 30 watt', '', 'Lampu Sorot LED 30 watt', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(390, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0291', 'Lampu Sorot LED 50 Watt', '', 'Lampu Sorot LED 50 Watt', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(391, '1.1.7.01.03.08.999', 'Alat Listrik dan Elektronik Lain-lain', '1.1.7.01.03.08.999.17', 'MCB 2 Phase 25 Amper - 300MA', '', 'MCB 2 Phase 25 Amper - 300MA', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(392, '1.1.7.01.03.08.999', 'Alat Listrik dan Elektronik Lain-lain', '1.1.7.01.03.08.999.18', 'MCB 3 Phase 10 Amper', '', 'MCB 3 Phase 10 Amper', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(393, '1.1.7.01.03.08.999', 'Alat Listrik dan Elektronik Lain-lain', '1.1.7.01.03.08.999.19', 'MCB 3 Phase 40 Amper', '', 'MCB 3 Phase 40 Amper', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(394, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0031', 'Stop Kontak Bright G ', '', 'Stop Kontak Bright G ', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(395, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Pembelian Alat Listrik', '', 'Pembelian Alat Listrik', 'paket', 0, NULL, '2025-08-29 11:11:32', NULL),
	(396, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Pipa AC 3/4', '', 'Pipa AC 3/4', 'Meter', 0, NULL, '2025-08-29 11:11:32', NULL),
	(397, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0019', 'Power Supply ', '', 'Power Supply 12V/10A', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(398, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0237', 'Lampu LED 7 Watt', '', 'SMD 7 Watt', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(399, '1.1.7.01.03.08.002', 'Lampu Listrik', '1.1.7.01.03.08.002.0289', 'Lampu LED 9 Watt', '', 'SMD 9 Watt', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(400, '1.1.7.01.03.08.012', 'Alat Listrik Dan Elektronik Lainnya', '', 'Sending Card Novastar MSD300 (GKGD)', '', 'Sending Card Novastar MSD300 (GKGD)', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(401, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.0071', 'Skun 35 mm Ins', '', 'Skun 35 mm Ins', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(402, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.122', 'Skun 70', '', 'Skun 70', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(403, '1.1.7.01.03.08.001', 'Kabel Listrik', '1.1.7.01.03.08.001.122', 'Skun T Kuning 50', '', 'Skun T Kuning 50', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(404, '1.1.7.01.03.06.014', 'Bahan Komputer Lainnya', '1.1.7.01.03.06.014.0019', 'Power Supply ', '', 'Slim Switching Power Supply PSU Tipis 5V 60A', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(405, '1.1.7.01.03.08.005', 'Stacker', '1.1.7.01.03.08.005.0010', 'Steker Arde', '', 'Steker Arda', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(406, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0032', 'Stop Kontak  Outbow 3 Lubang', '', 'Stop Kontak  Outbow 3 Lubang', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(407, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0033', 'Stop Kontak  Tanam', '', 'Stop Kontak  Tanam', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(408, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0034', 'Stop Kontak 2 Lobang', '', 'Stop Kontak 2 Lobang', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(409, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0035', 'Stop Kontak 3 Lobang', '', 'Stop Kontak 3 Lobang', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(410, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0036', 'Stop Kontak Tempel 1 Lubang', '', 'Stop Kontak Tempel 1 Lubang', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(411, '1.1.7.01.03.08.003', 'Stop Kontak', '1.1.7.01.03.08.003.0037', 'Stop Kontak Timer', '', 'Stop Kontak Timer', 'Buah', 0, NULL, '2025-08-29 11:11:32', NULL),
	(412, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.460', 'Amplop Berkop Coklat Besar A', '', 'Amplop Berkop Coklat Besar A', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(413, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.461', 'Amplop Berkop Coklat Tanggung', '', 'Amplop Berkop Coklat Tanggung', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(414, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.459', 'Amplop KOP RSJSL utk Foto Thorax', '', 'Amplop KOP RSJSL utk Foto Thorax', 'Pak', 0, NULL, '2025-08-29 11:11:32', NULL),
	(415, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.463', 'Assesmen Awal Keperawatan Psikogeriatri 1 Rkp BB', '', 'Assesmen Awal Keperawatan Psikogeriatri 1 Rkp BB', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(416, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.464', 'Assesmen Awal Nyeri', '', 'Assesmen Awal Nyeri', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(417, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.465', 'Assesmen Awal Rawat Gigi & Mulut', '', 'Assesmen Awal Rawat Gigi & Mulut', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(418, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.0422', 'Assesmen Awal Rawat Inap Jiwa', '', 'Assesmen Awal Rawat Inap Jiwa', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(419, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.423', 'Assesmen Awal Rawat Inap Napza', '', 'Assesmen Awal Rawat Inap Napza', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(420, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.466', 'Assesmen Awal Rawat Jalan Jiwa 4rkp BB', '', 'Assesmen Awal Rawat Jalan Jiwa 4rkp BB', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(421, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.467', 'Assesmen Awal Rawat Jalan Napza A', '', 'Assesmen Awal Rawat Jalan Napza A', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(422, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.468', 'Assesmen Awal Rawat Jalan Penyakit Dalam', '', 'Assesmen Awal Rawat Jalan Penyakit Dalam', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(423, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.469', 'Assesmen Lanjutan Gizi', '', 'Assesmen Lanjutan Gizi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(424, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.470', 'Assesmen Lanjutan Nyeri', '', 'Assesmen Lanjutan Nyeri', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(425, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.471', 'Assesmen Lanjutan Psikogeriatri', '', 'Assesmen Lanjutan Psikogeriatri', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(426, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.424', 'Assesmen Medis (IGD)', '', 'Assesmen Medis (IGD)', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(427, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.472', 'Assesmen Medis Pediatri Anak & Remaja', '', 'Assesmen Medis Pediatri Anak & Remaja', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(428, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.473', 'Assesmen Medis Poliklinik Psikiatrik Anak & Remaja', '', 'Assesmen Medis Poliklinik Psikiatrik Anak & Remaja', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(429, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.474', 'Assesmen Medis Psikogeriatri', '', 'Assesmen Medis Psikogeriatri', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(430, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.475', 'Assesmen Medis Rawat Inap Jiwa', '', 'Assesmen Medis Rawat Inap Jiwa', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(431, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.476', 'Assesmen Pasien Terminal', '', 'Assesmen Pasien Terminal', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(432, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.477', 'Assesmen Poliklinik 2 Rkp BB', '', 'Assesmen Poliklinik 2 Rkp BB', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(433, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.478', 'Asuhan Gizi Anak', '', 'Asuhan Gizi Anak', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(434, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.479', 'Asuhan Gizi Dewasa', '', 'Asuhan Gizi Dewasa', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(435, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.425', 'Blangko Rujukan / Formulir Konsultasi antar Unit', '', 'Blangko Rujukan / Formulir Konsultasi antar Unit', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(436, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.426', 'Bon Permintaan Makanan', '', 'Bon Permintaan Makanan', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(437, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.427', 'Buku CFIT', '', 'Buku CFIT', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(438, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.428', 'Buku MSDT ( Kepemimpinan )', '', 'Buku MSDT ( Kepemimpinan )', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(439, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.429', 'Buku PAPI', '', 'Buku PAPI', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(440, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.480', 'Catatan Perkembangan', '', 'Catatan Perkembangan', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(441, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.481', 'Cheklist Keselamatan Pasien Di Poli Gigi Dan Mulut', '', 'Cheklist Keselamatan Pasien Di Poli Gigi Dan Mulut', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(442, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.482', 'Clinical Pathway Demensia', '', 'Clinical Pathway Demensia', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(443, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.483', 'Clinical Pathway Depresi Berat Dg Gejala Psikotik', '', 'Clinical Pathway Depresi Berat Dg Gejala Psikotik', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(444, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.484', 'Clinical Pathway Gangguan Bipolar 2 Rkp BB', '', 'Clinical Pathway Gangguan Bipolar 2 Rkp BB', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(445, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.485', 'Clinical Pathway Skizoafektif 2 Rkp BB', '', 'Clinical Pathway Skizoafektif 2 Rkp BB', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(446, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.486', 'Clinical Pathway Skizofrenia', '', 'Clinical Pathway Skizofrenia', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(447, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.487', 'Evaluasi Keperawatan', '', 'Evaluasi Keperawatan', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(448, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.488', 'Form Hasil Tindakan Prosedur KFR', '', 'Form Hasil Tindakan Prosedur KFR', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(449, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.489', 'Form Layanan Kedokteran Fisik Dan Rehabilitasi RJ', '', 'Form Layanan Kedokteran Fisik Dan Rehabilitasi RJ', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(450, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.490', 'Form Penerimaan Pasien Oleh Keluarga / Instansi', '', 'Form Penerimaan Pasien Oleh Keluarga / Instansi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(451, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.491', 'Form Penolakan Pasien Oleh Keluarga / Instansi', '', 'Form Penolakan Pasien Oleh Keluarga / Instansi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(452, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.492', 'Form Permintaan Terapi', '', 'Form Permintaan Terapi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(453, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.493', 'Form Rawat Inap Detoxifikasi', '', 'Form Rawat Inap Detoxifikasi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(454, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.494', 'Formulir DPJP & Case Manager 1 Rkp', '', 'Formulir DPJP & Case Manager 1 Rkp', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(455, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.430', 'Formulir Food Recal 24 Jam', '', 'Formulir Food Recal 24 Jam', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(456, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.431', 'Formulir Isian Penghargaan/Saran/Pengaduan', '', 'Formulir Isian Penghargaan/Saran/Pengaduan', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(457, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.495', 'Formulir Laporan Insiden Keselamatan Pasien', '', 'Formulir Laporan Insiden Keselamatan Pasien', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(458, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.496', 'Formulir Laporan KNC', '', 'Formulir Laporan KNC', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(459, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.497', 'Formulir Laporan KPC', '', 'Formulir Laporan KPC', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(460, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.498', 'Formulir Laporan Operasi', '', 'Formulir Laporan Operasi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(461, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.499', 'Formulir Laporan Penerimaan Rehabilitan Putusan', '', 'Formulir Laporan Penerimaan Rehabilitan Putusan', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(462, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.432', 'Formulir Lembar Transfer Parsial 2 Ply', '', 'Formulir Lembar Transfer Parsial 2 Ply', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(463, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.500', 'Formulir Pengkajian Fisik', '', 'Formulir Pengkajian Fisik', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(464, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.501', 'Formulir Penolakan Resusitasi', '', 'Formulir Penolakan Resusitasi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(465, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.502', 'Formulir Penolakan Tindakan Kedokteran', '', 'Formulir Penolakan Tindakan Kedokteran', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(466, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.503', 'Formulir Penolakan Tindakan Medis Umum', '', 'Formulir Penolakan Tindakan Medis Umum', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(467, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.504', 'Formulir Penyerahan & Pengambilan Rehabilitan', '', 'Formulir Penyerahan & Pengambilan Rehabilitan', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(468, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.505', 'Formulir Perjanjian Masuk Rehabilitasi', '', 'Formulir Perjanjian Masuk Rehabilitasi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(469, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.506', 'Formulir Rencana Rawatan Pribadi Rehabilitan', '', 'Formulir Rencana Rawatan Pribadi Rehabilitan', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(470, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.507', 'Formulir Transfer Pasien Eksternal', '', 'Formulir Transfer Pasien Eksternal', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(471, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.508', 'Formulir Transfer Pasien Intra RS', '', 'Formulir Transfer Pasien Intra RS', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(472, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.433', 'Jawaban Rujukan', '', 'Jawaban Rujukan', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(473, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.447', 'Lembar Kartu Kendali', '', 'Kartu Kendali', 'Lembar', 0, NULL, '2025-08-29 11:11:32', NULL),
	(474, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.434', 'Buku Kartu Kendali', '', 'Kartu Kendali', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(475, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.509', 'Kebutuhan komunikasi & edukasi Rawat Jalan', '', 'Kebutuhan komunikasi & edukasi Rawat Jalan', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(476, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.510', 'Kop Surat RSJSL', '', 'Kop Surat RSJSL', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(477, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.511', 'Kwitansi Poliklinik', '', 'Kwitansi Poliklinik', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(478, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.512', 'Lembar Bukti Pelayanan', '', 'Lembar Bukti Pelayanan', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(479, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.513', 'Lembar CFIT', '', 'Lembar CFIT', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(480, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.435', 'Lembar Disposisi', '', 'Lembar Disposisi', 'Buku', 0, NULL, '2025-08-29 11:11:32', NULL),
	(481, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.514', 'Lembar Informasi Penundaan Pelayanan 1 Rkp', '', 'Lembar Informasi Penundaan Pelayanan 1 Rkp', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(482, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.515', 'Lembar Investigasi Sederhana', '', 'Lembar Investigasi Sederhana', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(483, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.516', 'Lembar Kerja Investigasi Sederhana', '', 'Lembar Kerja Investigasi Sederhana', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(484, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.517', 'Lembar Konsultasi / Konseling / Psikoterapi', '', 'Lembar Konsultasi / Konseling / Psikoterapi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(485, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.518', 'Lembar MSDT', '', 'Lembar MSDT', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(486, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.519', 'Lembar Observasi Fisik', '', 'Lembar Observasi Fisik', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(487, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.520', 'Lembar Observasi IGD', '', 'Lembar Observasi IGD', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(488, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.521', 'Lembar Observasi Visum', '', 'Lembar Observasi Visum', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(489, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.522', 'Lembar Papi Kosrik', '', 'Lembar Papi Kosrik', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(490, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.523', 'Lembar Penggunaan Fiksasi Dan Isolasi', '', 'Lembar Penggunaan Fiksasi Dan Isolasi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(491, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.524', 'Lembar Persetujuan Orang Tua/Wali Dan Rehabilitan', '', 'Lembar Persetujuan Orang Tua/Wali Dan Rehabilitan', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(492, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.525', 'Lembar Wartegg', '', 'Lembar Wartegg', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(493, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.526', 'Lembar Wategg_Zeichentest (WZT)', '', 'Lembar Wategg_Zeichentest (WZT)', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(494, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.448', 'Map Karton RSJSL', '', 'Map Karton RSJSL', 'Lembar', 0, NULL, '2025-08-29 11:11:32', NULL),
	(495, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.449', 'Map Rekam Medik', '', 'Map Rekam Medik', 'Lembar', 0, NULL, '2025-08-29 11:11:32', NULL),
	(496, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.527', 'Mini Mental State Examination (MMSE)', '', 'Mini Mental State Examination (MMSE)', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(497, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.528', 'Observasi Kasus Resiko Bunuh Diri', '', 'Observasi Kasus Resiko Bunuh Diri', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(498, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.529', 'Observasi Pasien', '', 'Observasi Pasien', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(499, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.530', 'Observasi Pasien Fiksasi', '', 'Observasi Pasien Fiksasi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(500, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.531', 'Pemantauan Intake Pasien', '', 'Pemantauan Intake Pasien', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(501, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.462', 'Pembelian Cetak', '', 'Pembelian Cetak', 'Paket', 0, NULL, '2025-08-29 11:11:32', NULL),
	(502, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.532', 'Pemeriksaan Dokter Poliklinik Psikogeriatri', '', 'Pemeriksaan Dokter Poliklinik Psikogeriatri', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(503, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.533', 'Pengantar Biaya Akomodasi', '', 'Pengantar Biaya Akomodasi', 'Rim', 0, NULL, '2025-08-29 11:11:32', NULL),
	(504, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.534', 'Pengantar Biaya Assesmen', '', 'Pengantar Biaya Assesmen', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(505, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.535', 'Pengantar Biaya Keperawatan', '', 'Pengantar Biaya Keperawatan', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(506, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.438', 'Pengantar Biaya Rawat Jalan (NCR 3 Ply)', '', 'Pengantar Biaya Rawat Jalan (NCR 3 Ply)', 'Buku', 0, NULL, '2025-08-29 11:11:33', NULL),
	(507, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.439', 'Pengantar Biaya Tindakan Ins. Rehabilitasi Medik', '', 'Pengantar Biaya Tindakan Ins. Rehabilitasi Medik', 'Buku', 0, NULL, '2025-08-29 11:11:33', NULL),
	(508, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.536', 'Pengantar Biaya Visite Dokter', '', 'Pengantar Biaya Visite Dokter', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(509, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.537', 'Pengkajian & Monitoring Perioprerati Lokal Anastesi', '', 'Pengkajian & Monitoring Perioprerati Lokal Anastes', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(510, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.538', 'Persetujuan Umum (General Consent)', '', 'Persetujuan Umum (General Consent)', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(511, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.539', 'Progres Report', '', 'Progres Report', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(512, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.540', 'Rencana Asuhan Keperawatan Axis', '', 'Rencana Asuhan Keperawatan Axis', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(513, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.541', 'Rencana Asuhan Keperawatan Jiwa', '', 'Rencana Asuhan Keperawatan Jiwa', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(514, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.440', 'Resep Pasien Rawat Inap 3 Ply', '', 'Resep Pasien Rawat Inap 3 Ply', 'Buku', 0, NULL, '2025-08-29 11:11:33', NULL),
	(515, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.441', 'Resume Rawat Jalan', '', 'Resume Rawat Jalan', 'Buku', 0, NULL, '2025-08-29 11:11:33', NULL),
	(516, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.442', 'Ringkasan Pulang Rawat Inap 4 Ply A', '', 'Ringkasan Pulang Rawat Inap 4 Ply A', 'Buku', 0, NULL, '2025-08-29 11:11:33', NULL),
	(517, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.450', 'Stiker Rekam Medis Warna Abu-Abu', '', 'Stiker Rekam Medis Warna Abu-Abu', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(518, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.451', 'Stiker Rekam Medis Warna Biru', '', 'Stiker Rekam Medis Warna Biru', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(519, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.452', 'Stiker Rekam Medis Warna Coklat', '', 'Stiker Rekam Medis Warna Coklat', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(520, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.453', 'Stiker Rekam Medis Warna Hijau', '', 'Stiker Rekam Medis Warna Hijau', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(521, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.454', 'Stiker Rekam Medis Warna Krem', '', 'Stiker Rekam Medis Warna Krem', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(522, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.455', 'Stiker Rekam Medis Warna Kuning', '', 'Stiker Rekam Medis Warna Kuning', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(523, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.456', 'Stiker Rekam Medis Warna Merah', '', 'Stiker Rekam Medis Warna Merah', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(524, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.457', 'Stiker Rekam Medis Warna Putih', '', 'Stiker Rekam Medis Warna Putih', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(525, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.458', 'Stiker Rekam Medis Warna Ungu', '', 'Stiker Rekam Medis Warna Ungu', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(526, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.443', 'Surat Ijin Pulang', '', 'Surat Ijin Pulang', 'Buku', 0, NULL, '2025-08-29 11:11:33', NULL),
	(527, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.444', 'Surat Keterangan Kematian A', '', 'Surat Keterangan Kematian A', 'Buku', 0, NULL, '2025-08-29 11:11:33', NULL),
	(528, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.445', 'Surat Kontrol', '', 'Surat Kontrol', 'Buku', 0, NULL, '2025-08-29 11:11:33', NULL),
	(529, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.542', 'Surat Pernyt. Bersedia Menunggu Px Resiko Suicide', '', 'Surat Pernyt. Bersedia Menunggu Px Resiko Suicide', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(530, '1.1.7.01.03.01.014', 'Barang Cetakan', '1.1.7.01.03.03.999.543', 'Surat Pernyt. Menunggu (Situasional) 1 Rkp', '', 'Surat Pernyt. Menunggu (Situasional) 1 Rkp', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(531, '1.1.7.01.03.08.010', 'Batu Baterai', '1.1.7.01.03.08.010.0035', 'Baterai Tanggung R14', '', 'Batterai Tanggung', 'Buah', 0, NULL, '2025-08-29 11:11:33', NULL),
	(532, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0024', 'Kertas HVS 75 gr A4/500 lbr', '', 'Kertas HVS A4 75 GSM', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(533, '1.1.7.01.03.02.001', 'Kertas HVS', '1.1.7.01.03.02.001.0025', 'Kertas HVS 75 gr F4/500 lbr', '', 'Kertas HVS F4 75 GSM', 'Rim', 0, NULL, '2025-08-29 11:11:33', NULL),
	(534, '1.1.7.01.03.01.010', 'Alat Perekat', '1.1.7.01.03.01.010.50', 'Magnet Papan Tulis ', '', 'Magnet Papan Tulis', 'Buah', 0, NULL, '2025-08-29 11:11:33', NULL),
	(535, '1.1.7.01.03.03.002', 'Tinta Cetak', '1.1.7.01.03.03.002.0002', 'Tinta Epson Hitam 664', '', 'Tinta Printer Epson Hitam 664', 'Botol', 0, NULL, '2025-08-29 11:11:33', NULL),
	(536, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.0070', 'Pakaian Olahraga', '', 'Baju Kaos Olahraga', 'Buah', 0, NULL, '2025-08-29 11:11:33', NULL),
	(537, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.0070', 'Pakaian Olahraga', '', 'Baju Kaos Olahraga Laki-laki', 'Buah', 0, NULL, '2025-08-29 11:11:33', NULL),
	(538, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.77', 'Baju Seragam Korpri', '', 'Baju Seragam Korpri', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(539, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.78', 'Celana Panjang Hitam Pria', '', 'Celana Panjang Hitam Pria', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(540, '1.1.7.01.03.09.006', 'Atribut', '1.1.7.01.03.09.006.0050', 'Emblem Bordir RSJ Sambang Lihum', '', 'Emblem Bordir RSJ Sambang Lihum', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(541, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.79', 'Jilbab Segi Empat', '', 'Jilbab Segi Empat Warna Biru', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(542, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.80', 'Jilbab Voal Premium', '', 'Jilbab Voal Premium', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(543, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.81', 'Kain Sasirangan', '', 'Kain Sasirangan', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(544, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.82', 'Kain Sasirangan Khas Kalimantan Selatan', '', 'Kain Sasirangan Khas Kalimantan Selatan', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(545, '1.1.7.01.03.09.001', 'Bahan Baku Pakaian', '1.1.7.01.03.09.001.83', 'Pakaian Duta Excelent', '', 'Pakaian Duta Excelent', 'Lembar', 0, NULL, '2025-08-29 11:11:33', NULL),
	(546, '1.1.7.01.03.09.002', 'Penutup Kepala', '1.1.7.01.03.09.002.0002', 'Peci Nasional Bahan Bludru', '', 'Peci Nasional Pria', 'Buah', 0, NULL, '2025-08-29 11:11:33', NULL),
	(547, '1.1.7.01.03.09.005', 'Penutup Kaki ', '1.1.7.01.03.09.005.0002', 'Sepatu Kerja ', '', 'Sepatu PDH Satpam', 'Pasang', 0, NULL, '2025-08-29 11:11:33', NULL),
	(548, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0020', 'Seragam Dinas Khusus Driver', '', 'Seragam Dinas Khusus Driver', 'Stel', 0, NULL, '2025-08-29 11:11:33', NULL),
	(549, '1.1.7.01.03.09.007', 'Perlengkapan Lapangan', '1.1.7.01.03.09.007.0006', 'Baju pengawasan', '', 'Seragam Satpam', 'Buah', 0, NULL, '2025-08-29 11:11:33', NULL);

-- Dumping structure for table inventori.master_unit
CREATE TABLE IF NOT EXISTS `master_unit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_unit` varchar(50) NOT NULL,
  `nama_unit` varchar(255) NOT NULL,
  `penanggung_jawab` varchar(100) DEFAULT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_kode_unit` (`kode_unit`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.master_unit: ~4 rows (approximately)
REPLACE INTO `master_unit` (`id`, `kode_unit`, `nama_unit`, `penanggung_jawab`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'U001', 'Bagian Umum', 'Budi Santoso', 'aktif', '2025-08-29 21:28:38', NULL),
	(2, 'U002', 'Bagian Keuangan', 'Ani Wijaya', 'aktif', '2025-08-29 21:28:38', NULL),
	(3, 'U003', 'Bagian IT', 'Dedi Kurniawan', 'aktif', '2025-08-29 21:28:38', NULL),
	(4, 'U004', 'Bagian Pelayanan', 'Siti Nurhaliza', 'aktif', '2025-08-29 21:28:38', NULL);

-- Dumping structure for table inventori.permintaan_barang
CREATE TABLE IF NOT EXISTS `permintaan_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_permintaan` varchar(50) NOT NULL,
  `tanggal_permintaan` date NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'Reference to user.ID',
  `unit_id` int(11) DEFAULT NULL,
  `keperluan` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('pending','approved','partial','rejected','completed') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_nomor` (`nomor_permintaan`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_permintaan_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.permintaan_barang: ~0 rows (approximately)
REPLACE INTO `permintaan_barang` (`id`, `nomor_permintaan`, `tanggal_permintaan`, `user_id`, `unit_id`, `keperluan`, `keterangan`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
	(1, 'PRM-202509-0001', '2025-09-07', 2, NULL, 'Untuk Testing', 'testing', 'pending', NULL, NULL, '2025-09-07 14:06:58', NULL);

-- Dumping structure for table inventori.permintaan_barang_detail
CREATE TABLE IF NOT EXISTS `permintaan_barang_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permintaan_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `jumlah_diminta` int(11) NOT NULL,
  `jumlah_disetujui` int(11) DEFAULT 0,
  `jumlah_diberikan` int(11) DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permintaan_id` (`permintaan_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `permintaan_barang_detail_ibfk_1` FOREIGN KEY (`permintaan_id`) REFERENCES `permintaan_barang` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permintaan_barang_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `master_barang` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.permintaan_barang_detail: ~0 rows (approximately)
REPLACE INTO `permintaan_barang_detail` (`id`, `permintaan_id`, `barang_id`, `jumlah_diminta`, `jumlah_disetujui`, `jumlah_diberikan`, `keterangan`) VALUES
	(1, 1, 560, 1, 0, 0, '');

-- Dumping structure for table inventori.referensi
CREATE TABLE IF NOT EXISTS `referensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` int(11) NOT NULL,
  `isi` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table inventori.referensi: ~14 rows (approximately)
REPLACE INTO `referensi` (`id`, `jenis`, `isi`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Buah', '2025-09-06 14:16:42', NULL),
	(2, 1, 'Unit', '2025-09-06 14:16:50', NULL),
	(3, 1, 'Pcs', '2025-09-06 14:17:00', NULL),
	(4, 1, 'Lembar', '2025-09-06 14:17:07', NULL),
	(5, 1, 'Rim', '2025-09-06 14:17:13', NULL),
	(6, 1, 'Pack', '2025-09-06 14:17:22', NULL),
	(7, 1, 'Box', '2025-09-06 14:17:30', NULL),
	(8, 1, 'Lusin', '2025-09-06 14:17:38', NULL),
	(9, 1, 'Kg', '2025-09-06 14:17:44', NULL),
	(10, 1, 'Gram', '2025-09-06 14:17:50', NULL),
	(11, 1, 'Liter', '2025-09-06 14:17:56', NULL),
	(12, 1, 'Meter', '2025-09-06 14:18:02', NULL),
	(13, 1, 'Roll', '2025-09-06 14:18:10', NULL),
	(14, 1, 'Set', '2025-09-06 14:18:15', NULL),
	(17, 1, 'Testing Satuan', '2025-09-08 10:00:19', NULL);

-- Dumping structure for table inventori.riwayat
CREATE TABLE IF NOT EXISTS `riwayat` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_DETAIL` int(11) NOT NULL,
  `ID_BARANG` int(11) NOT NULL,
  `STOK_AWAL` int(11) NOT NULL,
  `JUMLAH` int(11) NOT NULL,
  `STOK_AKHIR` int(11) NOT NULL,
  `STATUS` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 Barang Minta,1 Barang Masuk',
  `CREATED_AT` datetime NOT NULL DEFAULT current_timestamp(),
  `UPDATE_AT` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table inventori.riwayat: ~0 rows (approximately)

-- Dumping structure for table inventori.stok_barang
CREATE TABLE IF NOT EXISTS `stok_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `stok_awal` int(11) DEFAULT 0 COMMENT 'Stok awal periode',
  `total_masuk` int(11) DEFAULT 0 COMMENT 'Total barang masuk periode ini',
  `total_keluar` int(11) DEFAULT 0 COMMENT 'Total barang keluar periode ini',
  `stok_akhir` int(11) DEFAULT 0 COMMENT 'Stok akhir saat ini',
  `harga_rata_rata` decimal(15,2) DEFAULT 0.00,
  `nilai_persediaan` decimal(15,2) DEFAULT 0.00 COMMENT 'Nilai total persediaan',
  `periode` varchar(7) NOT NULL COMMENT 'Format: YYYY-MM',
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_barang_periode` (`barang_id`,`periode`),
  CONSTRAINT `stok_barang_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `master_barang` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.stok_barang: ~1 rows (approximately)
REPLACE INTO `stok_barang` (`id`, `barang_id`, `stok_awal`, `total_masuk`, `total_keluar`, `stok_akhir`, `harga_rata_rata`, `nilai_persediaan`, `periode`, `updated_at`) VALUES
	(5, 560, 0, 5, 2, 3, 120000.00, 120000.00, '2025-09', '2025-09-06 09:11:50');

-- Dumping structure for table inventori.stok_opname
CREATE TABLE IF NOT EXISTS `stok_opname` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nomor_opname` varchar(50) NOT NULL,
  `tanggal_opname` date NOT NULL,
  `periode` varchar(7) NOT NULL COMMENT 'Format: YYYY-MM',
  `jenis_opname` enum('bulanan','triwulan','semester','tahunan') DEFAULT 'bulanan',
  `petugas` varchar(100) DEFAULT NULL,
  `status` enum('draft','posted','cancelled') DEFAULT 'draft',
  `total_selisih_nilai` decimal(15,2) DEFAULT 0.00,
  `keterangan` text DEFAULT NULL,
  `user_input` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_nomor_opname` (`nomor_opname`),
  KEY `idx_periode` (`periode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.stok_opname: ~0 rows (approximately)

-- Dumping structure for table inventori.stok_opname_detail
CREATE TABLE IF NOT EXISTS `stok_opname_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stok_opname_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `stok_sistem` int(11) NOT NULL COMMENT 'Stok menurut sistem',
  `stok_fisik` int(11) NOT NULL COMMENT 'Stok hasil perhitungan fisik',
  `selisih` int(11) NOT NULL COMMENT 'Selisih = stok_fisik - stok_sistem',
  `harga_satuan` decimal(15,2) NOT NULL,
  `nilai_selisih` decimal(15,2) NOT NULL COMMENT 'selisih * harga_satuan',
  `kondisi` enum('baik','rusak_ringan','rusak_berat') DEFAULT 'baik',
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_opname` (`stok_opname_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `stok_opname_detail_ibfk_1` FOREIGN KEY (`stok_opname_id`) REFERENCES `stok_opname` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stok_opname_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `master_barang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventori.stok_opname_detail: ~0 rows (approximately)

-- Dumping structure for table inventori.user
CREATE TABLE IF NOT EXISTS `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USERNAME` char(20) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `NAMA` char(50) NOT NULL,
  `IMG` varchar(50) DEFAULT NULL,
  `ROLE` tinyint(4) NOT NULL COMMENT '1 admin, 2 user 3, pptk',
  `CREATED_AT` datetime NOT NULL DEFAULT current_timestamp(),
  `UPDATED_AT` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Dumping data for table inventori.user: ~1 rows (approximately)
REPLACE INTO `user` (`ID`, `USERNAME`, `PASSWORD`, `NAMA`, `IMG`, `ROLE`, `CREATED_AT`, `UPDATED_AT`) VALUES
	(1, 'admin', '$2y$10$0.b.4ALddLxvbpL24TSLf.3VcLzty84Oxy/.g/b/TnHsrM8Ff5LZK', 'Jumai', NULL, 1, '2025-07-21 22:28:19', '2025-07-21 22:29:00'),
	(2, 'chandraxf', '$2y$10$0.b.4ALddLxvbpL24TSLf.3VcLzty84Oxy/.g/b/TnHsrM8Ff5LZK', 'Chandra Endira', NULL, 2, '2025-07-21 22:28:19', '2025-07-21 22:52:14');

-- Dumping structure for view inventori.v_kartu_stok
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_kartu_stok` (
	`tanggal` DATE NOT NULL,
	`kode_nusp` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`nama_nusp` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`satuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`jenis_transaksi` ENUM('masuk','keluar','opname_plus','opname_minus','saldo_awal') NOT NULL COLLATE 'utf8mb4_general_ci',
	`nomor_referensi` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stok_awal` INT(11) NOT NULL,
	`masuk` INT(11) NULL,
	`keluar` INT(11) NULL,
	`stok_akhir` INT(11) NOT NULL,
	`harga_satuan` DECIMAL(15,2) NOT NULL,
	`nilai_masuk` DECIMAL(15,2) NULL,
	`nilai_keluar` DECIMAL(15,2) NULL,
	`nilai_saldo` DECIMAL(15,2) NOT NULL,
	`keterangan` TEXT NULL COLLATE 'utf8mb4_general_ci'
);

-- Dumping structure for view inventori.v_laporan_stok
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_laporan_stok` (
	`kode_rek_108` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`nama_barang_108` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`kode_nusp` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`nama_nusp` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`kode_gudang` CHAR(0) NOT NULL COLLATE 'utf8mb4_general_ci',
	`nama_gudang` CHAR(0) NOT NULL COLLATE 'utf8mb4_general_ci',
	`satuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stok_awal` INT(11) NULL,
	`harga_awal` DECIMAL(15,2) NULL,
	`total_awal` DECIMAL(25,2) NULL,
	`jumlah_masuk` INT(11) NULL,
	`harga_masuk` DECIMAL(15,2) NULL,
	`total_masuk` DECIMAL(25,2) NULL,
	`jumlah_keluar` INT(11) NULL,
	`harga_keluar` DECIMAL(15,2) NULL,
	`total_keluar` DECIMAL(25,2) NULL,
	`jumlah_akhir` INT(11) NULL,
	`harga_akhir` DECIMAL(15,2) NULL,
	`total_akhir` DECIMAL(15,2) NULL
);

-- Dumping structure for view inventori.v_monitoring_stok
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_monitoring_stok` (
	`kode_nusp` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`nama_nusp` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`satuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_general_ci',
	`stok_minimum` INT(11) NULL COMMENT 'Stok minimum untuk warning',
	`stok_saat_ini` INT(11) NULL,
	`status_stok` VARCHAR(1) NULL COLLATE 'utf8mb4_general_ci'
);

-- Dumping structure for procedure inventori.sp_init_stok_periode
DELIMITER //
CREATE PROCEDURE `sp_init_stok_periode`(
    IN p_periode VARCHAR(7)
)
BEGIN
    -- Procedure untuk initialize stok awal di awal periode
    -- Ambil stok akhir periode sebelumnya sebagai stok awal periode ini
    
    DECLARE v_periode_sebelum VARCHAR(7);
    
    -- Get periode sebelumnya
    SET v_periode_sebelum = DATE_FORMAT(
        DATE_SUB(STR_TO_DATE(CONCAT(p_periode, '-01'), '%Y-%m-%d'), INTERVAL 1 MONTH), 
        '%Y-%m'
    );
    
    -- Insert stok awal untuk periode baru dari stok akhir periode sebelumnya
    INSERT INTO stok_barang (
        barang_id, periode, stok_awal, total_masuk, total_keluar, 
        stok_akhir, harga_rata_rata, nilai_persediaan
    )
    SELECT 
        barang_id, 
        p_periode, 
        stok_akhir,  -- stok akhir periode sebelum jadi stok awal periode ini
        0,           -- total masuk = 0
        0,           -- total keluar = 0  
        stok_akhir,  -- stok akhir = stok awal di awal periode
        harga_rata_rata, 
        stok_akhir * harga_rata_rata
    FROM stok_barang 
    WHERE periode = v_periode_sebelum
    ON DUPLICATE KEY UPDATE
        stok_awal = VALUES(stok_awal);
        
END//
DELIMITER ;

-- Dumping structure for procedure inventori.sp_posting_barang_keluar
DELIMITER //
CREATE PROCEDURE `sp_posting_barang_keluar`(
	IN `p_transaksi_id` INT
)
BEGIN
    DECLARE v_finished INT DEFAULT 0;
    DECLARE v_barang_id INT;
    DECLARE v_jumlah INT;
    DECLARE v_nomor VARCHAR(50);
    DECLARE v_tanggal DATE;
    DECLARE v_periode VARCHAR(7);
    DECLARE v_stok_tersedia INT;
    DECLARE v_harga_rata DECIMAL(15,2);
    DECLARE v_exists INT;
    
    -- Cursor untuk detail barang keluar
    DECLARE cur_detail CURSOR FOR 
        SELECT d.barang_id, d.jumlah, h.nomor_transaksi, h.tanggal_keluar
        FROM barang_keluar_detail d
        INNER JOIN barang_keluar h ON d.barang_keluar_id = h.id
        WHERE d.barang_keluar_id = p_transaksi_id;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;
    
    START TRANSACTION;
    
    -- Get periode
    SELECT DATE_FORMAT(tanggal_keluar, '%Y-%m') INTO v_periode
    FROM barang_keluar WHERE id = p_transaksi_id;
    
    OPEN cur_detail;
    
    read_loop: LOOP
        FETCH cur_detail INTO v_barang_id, v_jumlah, v_nomor, v_tanggal;
        
        IF v_finished = 1 THEN 
            LEAVE read_loop;
        END IF;
        
        -- Check if stok exists
        SELECT COUNT(*) INTO v_exists
        FROM stok_barang 
        WHERE barang_id = v_barang_id AND periode = v_periode;
        
        IF v_exists = 0 THEN
            -- Jika belum ada record stok untuk periode ini, buat dulu
            INSERT INTO stok_barang (
                barang_id, periode, stok_awal, total_masuk, total_keluar, 
                stok_akhir, harga_rata_rata, nilai_persediaan
            )
            SELECT 
                v_barang_id, 
                v_periode, 
                0, 0, 0, 0, 
                COALESCE(harga_terakhir, 0), 
                0
            FROM master_barang WHERE id = v_barang_id;
        END IF;
        
        -- Get stok tersedia dan harga rata-rata
        SELECT stok_akhir, COALESCE(harga_rata_rata, 0)
        INTO v_stok_tersedia, v_harga_rata
        FROM stok_barang 
        WHERE barang_id = v_barang_id AND periode = v_periode;
        
        -- Validasi stok
        IF v_stok_tersedia < v_jumlah THEN
            SIGNAL SQLSTATE '45000' 
            SET MESSAGE_TEXT = 'Stok tidak mencukupi untuk barang keluar';
        END IF;
        
        -- Update harga di detail dengan harga rata-rata
        UPDATE barang_keluar_detail 
        SET harga_satuan = v_harga_rata, 
            total = v_jumlah * v_harga_rata
        WHERE barang_keluar_id = p_transaksi_id AND barang_id = v_barang_id;
        
        -- Update stok barang
        UPDATE stok_barang SET
            total_keluar = total_keluar + v_jumlah,
            stok_akhir = stok_akhir - v_jumlah,
            nilai_persediaan = (stok_akhir - v_jumlah) * harga_rata_rata,
            updated_at = NOW()
        WHERE barang_id = v_barang_id AND periode = v_periode;
        
        -- Insert ke kartu stok
        INSERT INTO kartu_stok (
            tanggal, 
            barang_id, 
            jenis_transaksi, 
            nomor_referensi,
            stok_awal, 
            masuk, 
            keluar, 
            stok_akhir, 
            harga_satuan, 
            nilai_masuk,
            nilai_keluar, 
            nilai_saldo,
            keterangan,
            created_at
        )
        VALUES (
            v_tanggal, 
            v_barang_id, 
            'keluar', 
            v_nomor,
            v_stok_tersedia, 
            0, 
            v_jumlah, 
            v_stok_tersedia - v_jumlah, 
            v_harga_rata, 
            0,
            v_jumlah * v_harga_rata, 
            (v_stok_tersedia - v_jumlah) * v_harga_rata,
            CONCAT('Barang Keluar No: ', v_nomor),
            NOW()
        );
        
    END LOOP;
    
    CLOSE cur_detail;
    
    -- Update total nilai transaksi
    UPDATE barang_keluar bk 
    SET total_nilai = (
            SELECT SUM(total) 
            FROM barang_keluar_detail 
            WHERE barang_keluar_id = p_transaksi_id
        ),
        status = 'posted',
        updated_at = NOW()
    WHERE id = p_transaksi_id;
    
    COMMIT;
END//
DELIMITER ;

-- Dumping structure for procedure inventori.sp_posting_barang_masuk
DELIMITER //
CREATE PROCEDURE `sp_posting_barang_masuk`(
	IN `p_transaksi_id` INT
)
BEGIN
    DECLARE v_finished INT DEFAULT 0;
    DECLARE v_barang_id INT;
    DECLARE v_jumlah INT;
    DECLARE v_harga DECIMAL(15,2);
    DECLARE v_nomor VARCHAR(50);
    DECLARE v_tanggal DATE;
    DECLARE v_periode VARCHAR(7);
    DECLARE v_stok_lama INT;
    DECLARE v_harga_lama DECIMAL(15,2);
    DECLARE v_exists INT;
    
    -- Cursor untuk detail barang masuk
    DECLARE cur_detail CURSOR FOR 
        SELECT d.barang_id, d.jumlah, d.harga_satuan, 
               h.nomor_transaksi, h.tanggal_masuk
        FROM barang_masuk_detail d
        INNER JOIN barang_masuk h ON d.barang_masuk_id = h.id
        WHERE d.barang_masuk_id = p_transaksi_id;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;
    
    START TRANSACTION;
    
    -- Get periode
    SELECT DATE_FORMAT(tanggal_masuk, '%Y-%m') INTO v_periode
    FROM barang_masuk WHERE id = p_transaksi_id;
    
    OPEN cur_detail;
    
    read_loop: LOOP
        FETCH cur_detail INTO v_barang_id, v_jumlah, v_harga, v_nomor, v_tanggal;
        
        IF v_finished = 1 THEN 
            LEAVE read_loop;
        END IF;
        
        -- Check if record exists in stok_barang
        SELECT COUNT(*) INTO v_exists
        FROM stok_barang 
        WHERE barang_id = v_barang_id AND periode = v_periode;
        
        IF v_exists > 0 THEN
            -- Get stok lama jika sudah ada record
            SELECT stok_akhir, COALESCE(harga_rata_rata, 0)
            INTO v_stok_lama, v_harga_lama
            FROM stok_barang 
            WHERE barang_id = v_barang_id AND periode = v_periode;
            
            -- Update existing record
            UPDATE stok_barang SET
                total_masuk = total_masuk + v_jumlah,
                stok_akhir = stok_akhir + v_jumlah,
                harga_rata_rata = CASE 
                    WHEN v_stok_lama <= 0 THEN v_harga
                    ELSE ((v_stok_lama * v_harga_lama) + (v_jumlah * v_harga)) / (v_stok_lama + v_jumlah)
                END,
                nilai_persediaan = (stok_akhir) * harga_rata_rata,
                updated_at = NOW()
            WHERE barang_id = v_barang_id AND periode = v_periode;
        ELSE
            -- Set stok lama = 0 untuk barang baru
            SET v_stok_lama = 0;
            SET v_harga_lama = 0;
            
            -- Insert new record dengan semua kolom yang diperlukan
            INSERT INTO stok_barang (
                barang_id, 
                periode, 
                stok_awal,      -- Tambahkan stok_awal
                total_masuk, 
                total_keluar,   -- Set 0 untuk awal
                stok_akhir, 
                harga_rata_rata, 
                nilai_persediaan,
                updated_at
            )
            VALUES (
                v_barang_id, 
                v_periode, 
                0,              -- stok_awal = 0 untuk record baru
                v_jumlah, 
                0,              -- total_keluar = 0
                v_jumlah, 
                v_harga, 
                v_jumlah * v_harga,
                NOW()
            );
        END IF;
        
        -- Update harga terakhir di master barang
        UPDATE master_barang 
        SET harga_terakhir = v_harga,
            updated_at = NOW()
        WHERE id = v_barang_id;
        
        -- Insert ke kartu stok
        INSERT INTO kartu_stok (
            tanggal, 
            barang_id, 
            jenis_transaksi, 
            nomor_referensi,
            stok_awal, 
            masuk, 
            keluar,
            stok_akhir, 
            harga_satuan, 
            nilai_masuk, 
            nilai_keluar,
            nilai_saldo,
            keterangan,
            created_at
        )
        VALUES (
            v_tanggal, 
            v_barang_id, 
            'masuk', 
            v_nomor,
            v_stok_lama, 
            v_jumlah, 
            0,
            v_stok_lama + v_jumlah, 
            v_harga, 
            v_jumlah * v_harga, 
            0,
            (v_stok_lama + v_jumlah) * v_harga,
            CONCAT('Barang Masuk No: ', v_nomor),
            NOW()
        );
        
    END LOOP;
    
    CLOSE cur_detail;
    
    -- Update status transaksi
    UPDATE barang_masuk 
    SET status = 'posted',
        updated_at = NOW()
    WHERE id = p_transaksi_id;
    
    COMMIT;
END//
DELIMITER ;

-- Dumping structure for procedure inventori.sp_posting_stok_opname
DELIMITER //
CREATE PROCEDURE `sp_posting_stok_opname`(
	IN `p_opname_id` INT
)
BEGIN
    DECLARE v_finished INT DEFAULT 0;
    DECLARE v_barang_id INT;
    DECLARE v_selisih INT;
    DECLARE v_harga DECIMAL(15,2);
    DECLARE v_nomor VARCHAR(50);
    DECLARE v_tanggal DATE;
    DECLARE v_periode VARCHAR(7);
    DECLARE v_stok_sistem INT;
    DECLARE v_exists INT;
    
    -- Cursor untuk detail stok opname dengan selisih
    DECLARE cur_detail CURSOR FOR 
        SELECT d.barang_id, d.selisih, d.harga_satuan, d.stok_sistem,
               h.nomor_opname, h.tanggal_opname, h.periode
        FROM stok_opname_detail d
        INNER JOIN stok_opname h ON d.stok_opname_id = h.id
        WHERE d.stok_opname_id = p_opname_id AND d.selisih != 0;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;
    
    START TRANSACTION;
    
    OPEN cur_detail;
    
    read_loop: LOOP
        FETCH cur_detail INTO v_barang_id, v_selisih, v_harga, v_stok_sistem, v_nomor, v_tanggal, v_periode;
        
        IF v_finished = 1 THEN 
            LEAVE read_loop;
        END IF;
        
        -- Check if stok exists
        SELECT COUNT(*) INTO v_exists
        FROM stok_barang 
        WHERE barang_id = v_barang_id AND periode = v_periode;
        
        IF v_exists = 0 THEN
            -- Create new stok record if not exists
            INSERT INTO stok_barang (
                barang_id, periode, stok_awal, total_masuk, total_keluar,
                stok_akhir, harga_rata_rata, nilai_persediaan
            )
            VALUES (
                v_barang_id, v_periode, 0, 0, 0,
                v_selisih, v_harga, v_selisih * v_harga
            );
        ELSE
            -- Update stok barang
            UPDATE stok_barang SET
                stok_akhir = stok_akhir + v_selisih,
                nilai_persediaan = (stok_akhir + v_selisih) * harga_rata_rata,
                updated_at = NOW()
            WHERE barang_id = v_barang_id AND periode = v_periode;
        END IF;
        
        -- Insert ke kartu stok
        IF v_selisih > 0 THEN
            -- Selisih plus (barang lebih)
            INSERT INTO kartu_stok (
                tanggal, barang_id, jenis_transaksi, nomor_referensi,
                stok_awal, masuk, keluar, stok_akhir, 
                harga_satuan, nilai_masuk, nilai_keluar, nilai_saldo,
                keterangan, created_at
            )
            VALUES (
                v_tanggal, v_barang_id, 'opname_plus', v_nomor,
                v_stok_sistem, v_selisih, 0, v_stok_sistem + v_selisih, 
                v_harga, v_selisih * v_harga, 0, (v_stok_sistem + v_selisih) * v_harga,
                CONCAT('Stok Opname - Koreksi Plus: ', v_selisih), NOW()
            );
        ELSE
            -- Selisih minus (barang kurang)
            INSERT INTO kartu_stok (
                tanggal, barang_id, jenis_transaksi, nomor_referensi,
                stok_awal, masuk, keluar, stok_akhir, 
                harga_satuan, nilai_masuk, nilai_keluar, nilai_saldo,
                keterangan, created_at
            )
            VALUES (
                v_tanggal, v_barang_id, 'opname_minus', v_nomor,
                v_stok_sistem, 0, ABS(v_selisih), v_stok_sistem + v_selisih, 
                v_harga, 0, ABS(v_selisih) * v_harga, (v_stok_sistem + v_selisih) * v_harga,
                CONCAT('Stok Opname - Koreksi Minus: ', ABS(v_selisih)), NOW()
            );
        END IF;
        
    END LOOP;
    
    CLOSE cur_detail;
    
    -- Update status opname
    UPDATE stok_opname 
    SET status = 'posted',
        updated_at = NOW()
    WHERE id = p_opname_id;
    
    COMMIT;
END//
DELIMITER ;

-- Dumping structure for trigger inventori.trg_generate_nomor_keluar
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_generate_nomor_keluar` 
BEFORE INSERT ON `barang_keluar`
FOR EACH ROW
BEGIN
    IF NEW.nomor_transaksi IS NULL OR NEW.nomor_transaksi = '' THEN
        SET NEW.nomor_transaksi = CONCAT('BK-', DATE_FORMAT(NOW(), '%Y%m'), '-', LPAD((SELECT COUNT(*) + 1 FROM barang_keluar WHERE YEAR(tanggal_keluar) = YEAR(NOW()) AND MONTH(tanggal_keluar) = MONTH(NOW())), 4, '0'));
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger inventori.trg_generate_nomor_masuk
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_generate_nomor_masuk` 
BEFORE INSERT ON `barang_masuk`
FOR EACH ROW
BEGIN
    IF NEW.nomor_transaksi IS NULL OR NEW.nomor_transaksi = '' THEN
        SET NEW.nomor_transaksi = CONCAT('BM-', DATE_FORMAT(NOW(), '%Y%m'), '-', LPAD((SELECT COUNT(*) + 1 FROM barang_masuk WHERE YEAR(tanggal_masuk) = YEAR(NOW()) AND MONTH(tanggal_masuk) = MONTH(NOW())), 4, '0'));
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_kartu_stok`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_kartu_stok` AS SELECT 
    ks.tanggal,
    mb.kode_nusp,
    mb.nama_nusp,
    mb.satuan,
    ks.jenis_transaksi,
    ks.nomor_referensi,
    ks.stok_awal,
    ks.masuk,
    ks.keluar,
    ks.stok_akhir,
    ks.harga_satuan,
    ks.nilai_masuk,
    ks.nilai_keluar,
    ks.nilai_saldo,
    ks.keterangan
FROM kartu_stok ks
INNER JOIN master_barang mb ON ks.barang_id = mb.id
ORDER BY ks.barang_id, ks.tanggal, ks.id 
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_laporan_stok`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_laporan_stok` AS SELECT 
    mb.kode_rek_108,
    mb.nama_barang_108,
    mb.kode_nusp,
    mb.nama_nusp,
    '' AS kode_gudang,  -- Kosong karena tidak ada gudang
    '' AS nama_gudang,  -- Kosong karena tidak ada gudang
    mb.satuan,
    COALESCE(sb.stok_awal, 0) AS stok_awal,
    COALESCE(sb.harga_rata_rata, 0) AS harga_awal,
    COALESCE(sb.stok_awal * sb.harga_rata_rata, 0) AS total_awal,
    COALESCE(sb.total_masuk, 0) AS jumlah_masuk,
    COALESCE(sb.harga_rata_rata, 0) AS harga_masuk,
    COALESCE(sb.total_masuk * sb.harga_rata_rata, 0) AS total_masuk,
    COALESCE(sb.total_keluar, 0) AS jumlah_keluar,
    COALESCE(sb.harga_rata_rata, 0) AS harga_keluar,
    COALESCE(sb.total_keluar * sb.harga_rata_rata, 0) AS total_keluar,
    COALESCE(sb.stok_akhir, 0) AS jumlah_akhir,
    COALESCE(sb.harga_rata_rata, 0) AS harga_akhir,
    COALESCE(sb.nilai_persediaan, 0) AS total_akhir
FROM master_barang mb
LEFT JOIN stok_barang sb ON mb.id = sb.barang_id 
    AND sb.periode = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
WHERE mb.status = 'aktif'
ORDER BY mb.kode_rek_108, mb.kode_nusp 
;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_monitoring_stok`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_monitoring_stok` AS SELECT 
    mb.kode_nusp,
    mb.nama_nusp,
    mb.satuan,
    mb.stok_minimum,
    COALESCE(sb.stok_akhir, 0) AS stok_saat_ini,
    CASE 
        WHEN COALESCE(sb.stok_akhir, 0) <= mb.stok_minimum THEN 'PERLU RESTOCK'
        WHEN COALESCE(sb.stok_akhir, 0) <= (mb.stok_minimum * 1.5) THEN 'MENDEKATI MINIMUM'
        ELSE 'AMAN'
    END AS status_stok
FROM master_barang mb
LEFT JOIN stok_barang sb ON mb.id = sb.barang_id 
    AND sb.periode = DATE_FORMAT(CURRENT_DATE, '%Y-%m')
WHERE mb.status = 'aktif'
    AND mb.stok_minimum > 0
    AND COALESCE(sb.stok_akhir, 0) <= (mb.stok_minimum * 2)
ORDER BY sb.stok_akhir ASC 
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
