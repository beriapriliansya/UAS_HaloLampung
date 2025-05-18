-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table parawisata.bookings
DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `destination_id` bigint unsigned NOT NULL,
  `booking_date` date NOT NULL,
  `num_tickets` int NOT NULL,
  `base_ticket_price_at_booking` decimal(10,2) NOT NULL,
  `total_facility_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_id` bigint unsigned DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_payment',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_destination_id_foreign` (`destination_id`),
  KEY `bookings_payment_id_foreign` (`payment_id`),
  KEY `bookings_created_at_index` (`created_at`),
  KEY `bookings_payment_status_index` (`payment_status`),
  KEY `bookings_status_index` (`status`),
  CONSTRAINT `bookings_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.bookings: ~0 rows (approximately)

-- Dumping structure for table parawisata.booking_facility
DROP TABLE IF EXISTS `booking_facility`;
CREATE TABLE IF NOT EXISTS `booking_facility` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `facility_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_at_booking` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_facility_booking_id_facility_id_unique` (`booking_id`,`facility_id`),
  KEY `booking_facility_facility_id_foreign` (`facility_id`),
  CONSTRAINT `booking_facility_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_facility_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.booking_facility: ~0 rows (approximately)

-- Dumping structure for table parawisata.destinations
DROP TABLE IF EXISTS `destinations`;
CREATE TABLE IF NOT EXISTS `destinations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `operating_hours` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_ticket_price` decimal(10,2) NOT NULL,
  `visitor_quota` int DEFAULT NULL,
  `main_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `destinations_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.destinations: ~2 rows (approximately)
REPLACE INTO `destinations` (`id`, `name`, `slug`, `description`, `location`, `operating_hours`, `base_ticket_price`, `visitor_quota`, `main_photo`, `created_at`, `updated_at`) VALUES
	(1, 'Pantai Sari Ringgung', 'pantai-sari-ringgung', 'Surga Tersembunyi di Pesisir Lampung yang Siap Menyambutmu!\r\n\r\nBayangkan berjalan di atas pasir putih yang lembut, ditemani desiran ombak dan angin sepoi-sepoi. Di depanmu, laut biru membentang luas, dan Pulau Tegal Mas berdiri megah di kejauhan.\r\n\r\n🌊 Pantai Sari Ringgung bukan sekadar tempat berlibur, tapi tempat untuk melepas penat, menyatu dengan alam, dan menciptakan kenangan tak terlupakan.\r\n\r\n📸 Spot foto hits: Pasir Timbul, Masjid Apung, dan dermaga ikonik yang jadi incaran para content creator!\r\n🚤 Naik banana boat? Snorkeling? Jelajah pulau? Semua bisa kamu nikmati di sini!\r\n🍴 Kuliner seafood segar dan hidangan lokal juga siap memanjakan lidahmu!', 'Desa Sidodadi, Teluk Pandan, Kabupaten Pesawaran, Lampung', '08.00 – 18.00 WIB', 12000.00, NULL, 'destinations/jGnVFYmSXENeHA5hbHeitczVq1xvxhw9tudbqql8.jpg', '2025-04-25 13:51:07', '2025-05-17 08:55:46'),
	(3, 'Taman Nasional Way Kambas', 'taman-nasional-way-kambas', 'Dekat dengan Alam, Dekat dengan Sang Raksasa Lembut Nusantara\r\n\r\nRasakan pengalaman tak terlupakan bertemu langsung dengan gajah Sumatera di habitat aslinya!\r\nDi Taman Nasional Way Kambas, kamu bukan hanya melihat — tapi juga berinteraksi langsung dengan satwa langka yang dilindungi.\r\n\r\n🌿 Eksplorasi alam liar yang masih asri\r\n🐘 Menyaksikan pelatihan gajah di pusat konservasi\r\n🌅 Nikmati keindahan sunrise di tengah hutan tropis\r\n📚 Edukasi seru tentang pelestarian satwa dan ekosistem', 'Lampung Timur, ±2 jam dari Kota Bandar Lampung', '08.00 – 18.00 WIB', 20000.00, NULL, 'destinations/IrdLeLOJDBYz6oCoLR1sbY1xvcRHygoIaqW9BZmf.jpg', '2025-04-25 17:42:19', '2025-05-17 08:58:21'),
	(4, 'Pulau Pahawang', 'pulau-pahawang', 'Laut jernih kebiruan, terumbu karang yang memesona, dan ikan-ikan cantik yang berenang bebas — semua bisa kamu temukan di Pulau Pahawang, surga tersembunyi di ujung Selatan Sumatera.\r\n\r\n🌊 Aktivitas wajib:\r\n🔹 Snorkeling di Taman Nemo\r\n🔹 Jelajah Pulau Pahawang Kecil & Besar\r\n🔹 Berfoto di pasir timbul saat laut surut\r\n🔹 BBQ seru di pinggir pantai\r\n\r\n🏡 Tersedia penginapan homestay hingga resort dengan nuansa tropis yang nyaman.\r\n🚤 Paket wisata juga banyak tersedia, cocok untuk solo trip, pasangan, hingga rombongan keluarga.', 'Kecamatan Punduh Pidada, Kabupaten Pesawaran, Lampung', '08.00 – 18.00 WIB', 500000.00, 100, 'destinations/ilWD7Ftdfmczk1N6vHzIHJMRNZRtC7inTZEU9pla.jpg', '2025-04-26 03:07:57', '2025-05-17 08:59:43'),
	(5, 'Air Terjun Putri Malu', 'air-terjun-putri-malu', 'Keindahan Alam Tersembunyi di Pelukan Hutan Lampung Barat\r\n\r\nDibalik rindangnya pepohonan dan sejuknya udara pegunungan, mengalir deras sebuah air terjun setinggi ±80 meter yang anggun, memikat, dan misterius—itulah Air Terjun Putri Malu.\r\n\r\n🌲 Dikelilingi hutan tropis yang masih alami\r\n🥾 Cocok untuk pecinta trekking dan petualangan\r\n📸 Spot foto Instagramable dengan lanskap megah\r\n🌿 Lokasi sempurna untuk healing dan reconnect dengan alam\r\n\r\nDisebut “Putri Malu” karena aliran airnya membelok layaknya sosok putri yang malu-malu — membuatnya unik dan berbeda dari air terjun lainnya', 'Desa Pekon Campang, Kecamatan Bandar Negeri Suoh, Kabupaten Lampung Barat', '06.00 – 18.00 WIB', 10000.00, NULL, 'destinations/oCN3maGnBadJZ24ZTNplaw9H876wRKsjhEuxBLhb.jpg', '2025-05-17 09:00:56', '2025-05-17 09:00:56');

-- Dumping structure for table parawisata.destination_facility
DROP TABLE IF EXISTS `destination_facility`;
CREATE TABLE IF NOT EXISTS `destination_facility` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `destination_id` bigint unsigned NOT NULL,
  `facility_id` bigint unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quota` int DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `destination_facility_destination_id_facility_id_unique` (`destination_id`,`facility_id`),
  KEY `destination_facility_facility_id_foreign` (`facility_id`),
  CONSTRAINT `destination_facility_destination_id_foreign` FOREIGN KEY (`destination_id`) REFERENCES `destinations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `destination_facility_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.destination_facility: ~5 rows (approximately)
REPLACE INTO `destination_facility` (`id`, `destination_id`, `facility_id`, `price`, `quota`, `is_available`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 50000.00, NULL, 1, '2025-04-25 14:04:14', '2025-04-25 14:04:14'),
	(2, 3, 3, 20000.00, NULL, 1, '2025-04-25 17:42:48', '2025-04-25 17:42:48'),
	(3, 3, 1, 10000.00, NULL, 1, '2025-04-25 17:42:57', '2025-04-25 17:42:57'),
	(4, 4, 3, 0.00, NULL, 1, '2025-04-26 03:09:30', '2025-04-26 03:09:30'),
	(5, 4, 4, 0.00, NULL, 1, '2025-04-26 03:09:50', '2025-04-26 03:09:50'),
	(6, 5, 1, 10000.00, NULL, 1, '2025-05-17 09:01:10', '2025-05-17 09:01:10');

-- Dumping structure for table parawisata.facilities
DROP TABLE IF EXISTS `facilities`;
CREATE TABLE IF NOT EXISTS `facilities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `facilities_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.facilities: ~2 rows (approximately)
REPLACE INTO `facilities` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Tour Guide', 'test', '2025-04-25 14:03:54', '2025-04-25 14:03:54'),
	(3, 'taxi', NULL, '2025-04-25 17:42:33', '2025-04-25 17:42:33'),
	(4, 'banana boat', NULL, '2025-04-26 03:09:07', '2025-04-26 03:09:07');

-- Dumping structure for table parawisata.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table parawisata.jobs
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.jobs: ~0 rows (approximately)
REPLACE INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
	(8, 'default', '{"uuid":"741d06ff-9150-4650-94e3-0fea7897f0ad","displayName":"App\\\\Mail\\\\SubscriptionConfirmationMail","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Mail\\\\SendQueuedMailable","command":"O:34:\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\":15:{s:8:\\"mailable\\";O:37:\\"App\\\\Mail\\\\SubscriptionConfirmationMail\\":3:{s:10:\\"subscriber\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:21:\\"App\\\\Models\\\\Subscriber\\";s:2:\\"id\\";i:1;s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:2:\\"to\\";a:1:{i:0;a:2:{s:4:\\"name\\";N;s:7:\\"address\\";s:26:\\"fredlifourqoni35@gmail.com\\";}}s:6:\\"mailer\\";s:4:\\"smtp\\";}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:13:\\"maxExceptions\\";N;s:17:\\"shouldBeEncrypted\\";b:0;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:3:\\"job\\";N;}"}}', 0, NULL, 1747496429, 1747496429),
	(9, 'default', '{"uuid":"3db0ccc8-e378-4396-b025-ae9f4df45807","displayName":"App\\\\Mail\\\\SubscriptionConfirmationMail","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Mail\\\\SendQueuedMailable","command":"O:34:\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\":15:{s:8:\\"mailable\\";O:37:\\"App\\\\Mail\\\\SubscriptionConfirmationMail\\":3:{s:10:\\"subscriber\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:21:\\"App\\\\Models\\\\Subscriber\\";s:2:\\"id\\";i:2;s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:2:\\"to\\";a:1:{i:0;a:2:{s:4:\\"name\\";N;s:7:\\"address\\";s:26:\\"fredlifourqoni35@gmail.com\\";}}s:6:\\"mailer\\";s:4:\\"smtp\\";}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:13:\\"maxExceptions\\";N;s:17:\\"shouldBeEncrypted\\";b:0;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:3:\\"job\\";N;}"}}', 0, NULL, 1747496581, 1747496581),
	(10, 'default', '{"uuid":"691dd02c-5a2d-492f-9bab-5c6b5e6065a7","displayName":"App\\\\Mail\\\\SubscriptionConfirmationMail","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Mail\\\\SendQueuedMailable","command":"O:34:\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\":15:{s:8:\\"mailable\\";O:37:\\"App\\\\Mail\\\\SubscriptionConfirmationMail\\":3:{s:10:\\"subscriber\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:21:\\"App\\\\Models\\\\Subscriber\\";s:2:\\"id\\";i:3;s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:2:\\"to\\";a:1:{i:0;a:2:{s:4:\\"name\\";N;s:7:\\"address\\";s:15:\\"berry@gmail.com\\";}}s:6:\\"mailer\\";s:4:\\"smtp\\";}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:13:\\"maxExceptions\\";N;s:17:\\"shouldBeEncrypted\\";b:0;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:3:\\"job\\";N;}"}}', 0, NULL, 1747496884, 1747496884),
	(11, 'default', '{"uuid":"0e099e14-223a-49a7-99d5-a6638c64b036","displayName":"App\\\\Mail\\\\SubscriptionConfirmationMail","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Mail\\\\SendQueuedMailable","command":"O:34:\\"Illuminate\\\\Mail\\\\SendQueuedMailable\\":15:{s:8:\\"mailable\\";O:37:\\"App\\\\Mail\\\\SubscriptionConfirmationMail\\":3:{s:10:\\"subscriber\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:21:\\"App\\\\Models\\\\Subscriber\\";s:2:\\"id\\";i:4;s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:2:\\"to\\";a:1:{i:0;a:2:{s:4:\\"name\\";N;s:7:\\"address\\";s:26:\\"fredlifourqoni35@gmail.com\\";}}s:6:\\"mailer\\";s:4:\\"smtp\\";}s:5:\\"tries\\";N;s:7:\\"timeout\\";N;s:13:\\"maxExceptions\\";N;s:17:\\"shouldBeEncrypted\\";b:0;s:10:\\"connection\\";N;s:5:\\"queue\\";N;s:15:\\"chainConnection\\";N;s:10:\\"chainQueue\\";N;s:19:\\"chainCatchCallbacks\\";N;s:5:\\"delay\\";N;s:11:\\"afterCommit\\";N;s:10:\\"middleware\\";a:0:{}s:7:\\"chained\\";a:0:{}s:3:\\"job\\";N;}"}}', 0, NULL, 1747496899, 1747496899),
	(12, 'default', '{"uuid":"9dbed444-a4b4-4ff4-bb8e-442cd02ba185","displayName":"App\\\\Notifications\\\\BookingConfirmedNotification","job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","maxTries":null,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":null,"retryUntil":null,"data":{"commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications","command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:3;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:46:\\"App\\\\Notifications\\\\BookingConfirmedNotification\\":2:{s:7:\\"booking\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:18:\\"App\\\\Models\\\\Booking\\";s:2:\\"id\\";i:47;s:9:\\"relations\\";a:3:{i:0;s:4:\\"user\\";i:1;s:6:\\"ticket\\";i:2;s:11:\\"destination\\";}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:2:\\"id\\";s:36:\\"a4741a75-91e2-4d3d-a0e6-d8cd3f8dca75\\";}s:8:\\"channels\\";a:1:{i:0;s:4:\\"mail\\";}}"}}', 0, NULL, 1747499138, 1747499138);

-- Dumping structure for table parawisata.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.migrations: ~13 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(3, '2019_08_19_000000_create_failed_jobs_table', 1),
	(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(5, '2025_04_25_100145_add_is_admin_to_users_table', 2),
	(6, '2025_04_25_101203_create_destinations_table', 3),
	(7, '2025_04_25_110958_create_facilities_table', 4),
	(8, '2025_04_25_111450_create_destination_facility_pivot_table', 4),
	(9, '2025_04_25_192215_create_payments_table', 5),
	(10, '2025_04_25_192809_create_bookings_table', 6),
	(11, '2025_04_25_192939_create_booking_facility_pivot_table', 7),
	(12, '2025_04_25_194427_remove_payment_id_fk_from_bookings_table', 8),
	(13, '2025_04_25_194736_create_payments_table', 9),
	(14, '2025_04_25_195646_create_tickets_table', 10),
	(15, '2025_04_25_225430_create_jobs_table', 11),
	(16, '2025_05_17_071228_create_news_table', 12),
	(17, '2025_05_17_152841_create_subscribers_table', 13);

-- Dumping structure for table parawisata.news
DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `featured_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_slug_unique` (`slug`),
  KEY `news_user_id_foreign` (`user_id`),
  KEY `news_published_at_index` (`published_at`),
  KEY `news_is_published_index` (`is_published`),
  CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.news: ~4 rows (approximately)
REPLACE INTO `news` (`id`, `title`, `slug`, `excerpt`, `content`, `featured_image`, `user_id`, `published_at`, `is_published`, `created_at`, `updated_at`) VALUES
	(1, 'Pulau Pahawang Jadi Primadona Wisata Bahari Lampung, Kunjungan Wisatawan Meningkat Tajam', 'test', 'Pulau Pahawang kembali jadi sorotan setelah lonjakan jumlah kunjungan wisatawan domestik dan mancanegara pasca Lebaran. Keindahan bawah laut dan kemudahan akses jadi daya tarik utama.', 'Pesawaran, Lampung – Pulau Pahawang, yang terletak di Kecamatan Punduh Pidada, Kabupaten Pesawaran, terus menunjukkan eksistensinya sebagai salah satu destinasi wisata unggulan di Provinsi Lampung. Setelah masa libur Lebaran 2025, data dari Dinas Pariwisata mencatat peningkatan kunjungan wisatawan sebesar 30% dibandingkan periode sebelumnya.\r\n\r\nPulau ini dikenal dengan panorama lautnya yang jernih, pasir putih yang lembut, serta kekayaan biota laut seperti terumbu karang dan ikan hias. Tak heran jika Pulau Pahawang kerap dijuluki sebagai "Surga Snorkeling" di ujung Selatan Sumatera.\r\n\r\n“Antusiasme wisatawan sangat tinggi, terutama dari Jakarta dan Palembang. Banyak juga yang datang dalam bentuk rombongan dan ikut open trip,” ujar salah satu pemandu wisata lokal, Rendi (27).\r\n\r\nFasilitas di Pulau Pahawang kini juga semakin lengkap. Mulai dari homestay, spot foto ikonik, hingga wahana snorkeling dan diving. Para pelaku wisata lokal turut merasakan dampak positif dari meningkatnya kunjungan ini.\r\n\r\n“Alhamdulillah, homestay kami full booked tiap akhir pekan. Semoga ini terus berlanjut,” ujar Ibu Yani, pemilik salah satu penginapan di Pahawang Besar.\r\n\r\nPemerintah daerah pun berkomitmen terus meningkatkan infrastruktur penunjang dan pelatihan SDM di sektor pariwisata, guna menjaga kualitas layanan bagi wisatawan.\r\n\r\nPulau Pahawang kini tidak hanya jadi pilihan berlibur, tetapi juga representasi kemajuan sektor pariwisata Lampung yang berkelanjutan dan berbasis alam.', 'news_images/qHzKoje9IEMWWnRXNw5o4p4mklPLMg2dwFdigvO5.jpg', 1, '2025-05-17 00:22:00', 1, '2025-05-17 00:22:21', '2025-05-17 09:13:36'),
	(2, 'Taman Nasional Way Kambas Diminati Wisatawan Edukatif dan Pecinta Alam, Konservasi Gajah Jadi Daya Tarik Utama', 'taman-nasional-way-kambas-diminati-wisatawan-edukatif-dan-pecinta-alam-konservasi-gajah-jadi-daya-tarik-utama', 'Way Kambas Lampung Timur semakin ramai dikunjungi wisatawan edukatif dan pecinta satwa. Aktivitas konservasi gajah menjadi daya tarik unik sekaligus sarana pembelajaran bagi generasi muda.', 'Lampung Timur – Taman Nasional Way Kambas (TNWK), yang dikenal sebagai pusat pelestarian gajah Sumatera, mencatat peningkatan signifikan dalam jumlah pengunjung sepanjang kuartal pertama 2025. Destinasi ini menjadi pilihan favorit sekolah, komunitas pecinta alam, hingga wisatawan mancanegara yang ingin merasakan pengalaman edukatif langsung di alam terbuka.\r\n\r\nMenurut Kepala Balai TNWK, Dr. Yuniar Saragih, program edukasi konservasi dan pelatihan gajah menjadi magnet utama bagi para wisatawan. “Kami tidak hanya menawarkan wisata alam, tapi juga pembelajaran tentang pentingnya menjaga spesies langka dan ekosistem hutan tropis,” ujarnya.\r\n\r\nPengunjung dapat melihat langsung aktivitas perawatan gajah di pusat pelatihan, menyaksikan atraksi edukatif, hingga berinteraksi dengan pawang gajah (mahout). Selain itu, TNWK juga menjadi rumah bagi berbagai satwa langka lainnya, seperti harimau Sumatera, tapir, dan burung rangkong.\r\n\r\nAnak-anak sekolah terlihat antusias saat kunjungan edukasi. Salah satu guru pendamping, Ibu Ratna dari Bandar Lampung, menyebut Way Kambas sebagai “kelas alam terbaik” bagi siswa. “Mereka belajar langsung dari alam. Ini jauh lebih membekas daripada hanya belajar dari buku,” ungkapnya.\r\n\r\nDengan dukungan dari pemerintah dan berbagai pihak, TNWK terus berupaya meningkatkan fasilitas dan memperluas program edukasi guna mendukung pariwisata berbasis konservasi yang berkelanjutan.', 'news_images/HdCinO4hxz2oDMBUGtnW69dBQkyeNOqPINo2bxbV.jpg', 1, '2025-05-17 09:14:49', 1, '2025-05-17 09:14:49', '2025-05-17 09:14:49'),
	(3, 'enjelajahi Pesona Wisata Lampung: Destinasi Impian di Ujung Sumatera', 'enjelajahi-pesona-wisata-lampung-destinasi-impian-di-ujung-sumatera', 'Lampung, provinsi di ujung selatan Sumatera, menawarkan pesona wisata alam dan budaya yang memukau, mulai dari pantai indah hingga situs bersejarah yang kaya akan cerita.', 'Lampung, sebuah provinsi yang terletak di ujung selatan Pulau Sumatera, semakin menarik perhatian wisatawan domestik maupun mancanegara. Dikenal dengan julukan "Sai Bumi Ruwa Jurai," Lampung menghadirkan perpaduan harmonis antara keindahan alam, kekayaan budaya, dan keramahan penduduk lokal yang membuat setiap kunjungan tak terlupakan.\r\n\r\nSalah satu destinasi unggulan adalah Pantai Mutun, yang terletak di Pesawaran. Pantai ini menawarkan pasir putih yang lembut, air laut yang jernih, serta panorama matahari terbenam yang memukau. Wisatawan dapat menikmati aktivitas seperti snorkeling, banana boat, atau sekadar bersantai di tepi pantai. "Pantai Mutun adalah tempat yang sempurna untuk melepas penat. Pemandangannya luar biasa, dan fasilitasnya cukup lengkap," ujar Rina, seorang wisatawan dari Jakarta.\r\n\r\nTak jauh dari sana, Pulau Pahawang menjadi surga bagi pecinta wisata bahari. Pulau ini terkenal dengan terumbu karang yang masih alami dan keanekaragaman biota lautnya. Aktivitas menyelam dan snorkeling di pulau ini memberikan pengalaman menyaksikan keindahan bawah laut yang sulit dilupakan. Menurut data Dinas Pariwisata Lampung, jumlah pengunjung ke Pulau Pahawang meningkat sebesar 15% pada tahun 2024 dibandingkan tahun sebelumnya.\r\n\r\nBagi yang menyukai wisata sejarah dan budaya, Taman Purbakala Pugung Raharjo di Lampung Timur wajib dikunjungi. Situs ini menyimpan peninggalan megalitik berupa batu-batu besar yang diyakini berasal dari zaman prasejarah. Selain itu, Krakatau Monument di Kalianda menjadi destinasi lain untuk mempelajari sejarah letusan Gunung Krakatau yang mendunia pada tahun 1883.\r\n\r\nLampung juga menawarkan wisata alam yang menakjubkan seperti Air Terjun Curup Gangsa di Way Kanan. Air terjun ini dikelilingi hutan tropis yang asri, cocok untuk wisatawan yang mencari ketenangan dan keindahan alam. "Saya merasa seperti berada di dunia lain saat berada di sini. Suara air dan udara segar benar-benar menyegarkan," kata Budi, seorang pendaki lokal.\r\n\r\nPemerintah Provinsi Lampung terus berupaya mempromosikan pariwisata dengan meningkatkan infrastruktur dan fasilitas di berbagai destinasi. "Kami ingin menjadikan Lampung sebagai salah satu destinasi wisata utama di Indonesia. Kami juga mendorong pelestarian budaya lokal agar wisatawan mendapatkan pengalaman yang autentik," ujar Kepala Dinas Pariwisata Lampung, Edarwan, dalam wawancara baru-baru ini.\r\n\r\nDengan beragam destinasi yang ditawarkan, Lampung siap menyambut wisatawan yang ingin menjelajahi keindahan alam, sejarah, dan budaya. Jadi, kapan Anda akan mengunjungi Lampung?', 'news_images/NLNSdwC0DSwEEoBaox7hNuwdhP47a8itQoCfNAbl.jpg', 1, '2025-05-17 09:18:34', 1, '2025-05-17 09:18:34', '2025-05-17 09:18:34'),
	(4, 'Pesona Baru Wisata Lampung: Menyusuri Keindahan Alam dan Kuliner Lokal', 'pesona-baru-wisata-lampung-menyusuri-keindahan-alam-dan-kuliner-lokal', 'Lampung kembali memikat hati wisatawan dengan destinasi alam yang menawan dan cita rasa kuliner khas yang menggugah selera, menjadikannya tujuan liburan yang wajib dikunjungi.', 'Lampung, provinsi di ujung Sumatera, terus menunjukkan pesonanya sebagai destinasi wisata yang kaya akan keindahan alam dan budaya. Di tahun 2025, Lampung memperkenalkan beberapa destinasi baru dan pengalaman kuliner yang semakin memperkaya pengalaman wisatawan.\r\n\r\nSalah satu destinasi yang sedang naik daun adalah Bukit Pangonan di Kabupaten Pringsewu. Bukit ini menawarkan pemandangan perbukitan hijau yang luas, hamparan sawah, dan udara sejuk yang menyegarkan. Cocok untuk aktivitas trekking ringan atau sekadar menikmati sunrise, Bukit Pangonan menjadi favorit baru bagi wisatawan muda. "Pemandangan di sini seperti lukisan alam. Sangat Instagramable dan cocok untuk refreshing," ujar Sarah, seorang wisatawan dari Bandung.\r\n\r\nUntuk pecinta petualangan air, Danau Suoh di Lampung Barat wajib masuk daftar kunjungan. Danau ini dikenal dengan fenomena air panas alami yang berpadu dengan pemandangan danau yang tenang. Wisatawan dapat berkemah di tepi danau atau menjelajahi sumber air panas yang unik. Menurut pengelola wisata setempat, kunjungan ke Danau Suoh meningkat sebesar 20% sepanjang tahun 2024, terutama setelah promosi di media sosial.\r\n\r\nWisata budaya juga tak kalah menarik. Kampung Adat Marga Agung di Lampung Selatan menghadirkan pengalaman autentik budaya Lampung. Pengunjung dapat melihat rumah adat, belajar tarian tradisional, dan mencoba kerajinan tangan seperti kain tapis. "Saya sangat terkesan dengan keramahan warga dan keindahan kain tapis. Ini pengalaman yang sangat berharga," kata Dian, wisatawan asal Surabaya.\r\n\r\nTak lengkap rasanya berkunjung ke Lampung tanpa menikmati kulinernya. Seruit, ikan bakar khas Lampung yang disajikan dengan sambal terasi dan tempoyak, menjadi hidangan wajib coba. Selain itu, kopi robusta Lampung, yang terkenal di pasar internasional, dapat dinikmati langsung di kafe-kafe lokal seperti di kawasan Teluk Betung. Wisata kuliner di Lampung semakin berkembang dengan hadirnya festival makanan tahunan yang menarik ribuan pengunjung.\r\n\r\nPemerintah setempat terus mendukung pengembangan pariwisata melalui perbaikan akses jalan dan pelatihan bagi pelaku wisata. "Kami berkomitmen untuk menjadikan Lampung sebagai destinasi yang ramah wisatawan, dengan menjaga kelestarian alam dan budaya kami," ujar Budi Santoso, pejabat Dinas Pariwisata Lampung, dalam konferensi pers pada Mei 2025.\r\n\r\nDari keindahan alam hingga cita rasa kuliner yang khas, Lampung menawarkan pengalaman wisata yang lengkap. Rencanakan liburan Anda ke Lampung dan temukan pesona yang tak tertandingi!', 'news_images/cbARxEjvDPaLJYOhHUdnOnyjz591xUQKPPXq4FdM.jpg', 1, '2025-05-17 09:22:18', 1, '2025-05-17 09:22:18', '2025-05-17 09:22:18');

-- Dumping structure for table parawisata.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table parawisata.payments
DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_gateway` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `gateway_details` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expired_at` timestamp NULL DEFAULT NULL,
  `refund_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `refunded_amount` decimal(12,2) DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `refund_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_booking_id_unique` (`booking_id`),
  UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  KEY `payments_status_index` (`status`),
  KEY `payments_refund_status_index` (`refund_status`),
  CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.payments: ~0 rows (approximately)

-- Dumping structure for table parawisata.personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.personal_access_tokens: ~0 rows (approximately)

-- Dumping structure for table parawisata.subscribers
DROP TABLE IF EXISTS `subscribers`;
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `is_subscribed` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_unique` (`email`),
  UNIQUE KEY `subscribers_token_unique` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.subscribers: ~1 rows (approximately)

-- Dumping structure for table parawisata.tickets
DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_id` bigint unsigned NOT NULL,
  `ticket_code` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'valid',
  `checked_in_at` timestamp NULL DEFAULT NULL,
  `checked_in_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tickets_booking_id_unique` (`booking_id`),
  UNIQUE KEY `tickets_ticket_code_unique` (`ticket_code`),
  KEY `tickets_checked_in_by_foreign` (`checked_in_by`),
  KEY `tickets_status_index` (`status`),
  CONSTRAINT `tickets_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tickets_checked_in_by_foreign` FOREIGN KEY (`checked_in_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.tickets: ~0 rows (approximately)

-- Dumping structure for table parawisata.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table parawisata.users: ~0 rows (approximately)
REPLACE INTO `users` (`id`, `name`, `email`, `is_admin`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Admin User', 'adminparawisata@gmail.com', 1, '2025-05-17 15:46:49', '$2y$10$s7CfmGw7U6w78Hs/1syNVOI7bTYS96Z2M5QLKg9a5BKZ256qmkIbm', NULL, '2025-04-25 03:04:41', '2025-04-25 03:04:41'),
	(2, 'berry', 'berry@gmail.com', 0, '2025-05-17 15:46:39', '$2y$10$jU5TvIaZr4jV0v2ucJ8qdesJbDa1G0k4EkHlnVTu8.16ykmer1XT2', NULL, '2025-04-26 17:55:16', '2025-04-26 17:55:16'),
	(3, 'femas', 'femas@gmail.com', 0, '2025-05-17 15:46:43', '$2y$10$UdppV5xZkW5feNBae3cQ2ea4PiAjfA3cNOFOAIq1LKwuy/eFyQ8xi', NULL, '2025-05-16 23:56:18', '2025-05-16 23:56:18'),
	(4, 'fredli', 'fredlifourqoni35@gmail.com', 0, NULL, '$2y$10$YrC1FmFh348q.tWvbJVAU.osRaP4IacKFFXYOhukeFvWOIGughitm', NULL, '2025-05-17 09:50:36', '2025-05-17 09:50:36');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
