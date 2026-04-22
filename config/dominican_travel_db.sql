-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-04-2026 a las 15:08:54
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dominican_travel_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `booking_reference` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) NOT NULL,
  `travel_date` date NOT NULL,
  `adults` int(11) NOT NULL DEFAULT 1,
  `children` int(11) DEFAULT 0,
  `special_requests` text DEFAULT NULL,
  `item_type` enum('package','excursion','transfer') NOT NULL,
  `item_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `payment_method` varchar(100) DEFAULT NULL,
  `payment_status` enum('pending','paid','refunded') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bookings`
--

INSERT INTO `bookings` (`id`, `booking_reference`, `customer_name`, `customer_email`, `customer_phone`, `travel_date`, `adults`, `children`, `special_requests`, `item_type`, `item_id`, `total_price`, `status`, `payment_method`, `payment_status`, `created_at`, `updated_at`) VALUES
(21, 'BK-69C1C78B6F728', 'pedro starlin urena cruz', 'starlin056@gmail.com', '', '2026-03-27', 3, 1, 'cedfwefwe', 'excursion', 5, 350.00, 'completed', 'cash', 'paid', '2026-03-23 23:06:51', '2026-03-27 15:55:09'),
(22, 'BK-69C6A7180791C', 'pedro starlin urena cruz', 'starlin056@gmail.com', '', '2026-04-03', 9, 0, 'AGUA,SODA.', 'excursion', 10, 891.00, 'completed', 'cash', 'paid', '2026-03-27 15:49:44', '2026-03-27 15:59:40'),
(23, 'BK-69CD1189A2263', 'pedro starlin urena cruz', 'starlin056@gmail.com', '', '2026-04-17', 5, 3, 'sfesfwefwefwefwef', 'excursion', 10, 643.50, 'completed', 'cash', 'paid', '2026-04-01 12:37:29', '2026-04-01 13:18:10'),
(24, 'BK-69CD13C7E9320', 'Administrador Principal', 'admin@dominican-travel.com', '+18299437780', '2026-04-22', 1, 0, 'xxxxxxxxxxx', 'excursion', 8, 10.00, 'cancelled', 'cash', 'refunded', '2026-04-01 12:47:03', '2026-04-01 13:18:26'),
(25, 'BK-69CD1A709FECD', 'pedro starlin urena cruz', 'starlin056@gmail.com', '8299437780', '2026-04-30', 1, 1, 'fgergergregerg', 'package', 3, 1949.99, 'completed', 'cash', 'paid', '2026-04-01 13:15:28', '2026-04-01 13:18:34'),
(26, 'BK-69D5AA831C17F', 'edwin A', 'ADA@GMAIL.COM', '8299437780', '2026-04-17', 2, 0, 'CDACDS', 'package', 4, 2800.00, 'pending', 'cash', 'pending', '2026-04-08 01:08:19', '2026-04-08 01:08:19'),
(27, 'BK-69D5AABE82257', 'edwin A', 'ADA@GMAIL.COM', '8299437780', '2026-04-09', 1, 0, 'LLL', 'excursion', 7, 300.00, 'pending', 'cash', 'pending', '2026-04-08 01:09:18', '2026-04-08 01:09:18'),
(28, 'BK-69D5AACDB1660', 'edwin A', 'ADA@GMAIL.COM', '8299437780', '2026-04-29', 1, 0, '', 'transfer', 47, 45.00, 'confirmed', 'cash', 'paid', '2026-04-08 01:09:33', '2026-04-08 17:21:56'),
(29, 'FK-69E54B01BEC92', 'Administrador Principal', 'admin@dominican-travel.com', '8299437780', '2026-04-22', 5, 5, 'gtgyhujujijijiji', 'transfer', 6, 337.50, 'confirmed', 'cash', 'paid', '2026-04-19 21:37:05', '2026-04-19 21:41:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `custom_excursion_requests`
--

CREATE TABLE `custom_excursion_requests` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `destinations` text NOT NULL COMMENT 'Destinos deseados',
  `activities` text DEFAULT NULL COMMENT 'Actividades de interés',
  `travel_date` date DEFAULT NULL,
  `people_count` int(11) DEFAULT 1,
  `budget` varchar(100) DEFAULT NULL,
  `additional_notes` text DEFAULT NULL,
  `status` enum('pending','reviewing','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `requirements_checklist` text DEFAULT NULL COMMENT 'Checklist de requerimientos en JSON',
  `quoted_price` decimal(10,2) DEFAULT NULL COMMENT 'Precio cotizado para esta solicitud',
  `proposal_attachment` varchar(255) DEFAULT NULL COMMENT 'Ruta del archivo de propuesta adjunta',
  `last_contacted_at` timestamp NULL DEFAULT NULL COMMENT 'Última vez que se contactó al cliente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `custom_excursion_requests`
--

INSERT INTO `custom_excursion_requests` (`id`, `customer_name`, `customer_email`, `customer_phone`, `destinations`, `activities`, `travel_date`, `people_count`, `budget`, `additional_notes`, `status`, `admin_notes`, `requirements_checklist`, `quoted_price`, `proposal_attachment`, `last_contacted_at`, `created_at`, `updated_at`) VALUES
(12, 'pedro starlin ureña', 'starlin056@gmail.com', '+18299437780', 'samana', 'Senderismo', '2026-03-28', 2, 'Menos de $100', 'ffffff', 'approved', '[]', '[]', NULL, NULL, NULL, '2026-03-26 21:23:40', '2026-04-03 14:33:31'),
(13, 'pedro urena', 'starlin056@gmail.com', '8299437780', 'samana,el penon,salton el limon,otros', 'Senderismo, Surf, Avistamiento de aves, Playa, Cascadas', '2026-04-02', 17, '$500-$1000', 'hwhjvfiolwqefhgwereryghdfiulk.aSDVGMJCH,UKAWERGMYJFG,UKWQERHF,UKJHQWERIK,HBF,UKGWRR,KFGW,RJYHRF,JKYWRQWYIUK,FUILERQHFUI;OG', 'approved', '[]', '[]', 850.00, 'assets/uploads/proposals/propuesta_13_69cfc8d4308c3.pdf', NULL, '2026-03-27 15:20:43', '2026-04-03 14:33:31'),
(14, 'pedro starlin ureña', 'starlin056@gmail.com', '8299437780', 'Samaná', 'Acepto que mis datos sean usados para coordinar mi solicitud.\r\n                                Ver política', '2026-04-16', 2, '$100 - $250', 'ededededede', 'approved', '[]', '[]', NULL, NULL, NULL, '2026-04-01 14:02:55', '2026-04-03 14:33:31'),
(15, 'Administrador Principal', 'admin@dominican-travel.com', '', 'Samaná', 'Gastronomía, Avistamiento de aves, Acepto que mis datos sean usados para coordinar mi solicitud.\r\n                                Ver política', '2026-04-07', 2, '', 'trhth', 'approved', '[{\"id\":\"69cfc84471712\",\"content\":\"tener pendiente agregar agua\",\"created_by\":3,\"created_at\":\"2026-04-03 10:01:40\"}]', '[]', NULL, NULL, NULL, '2026-04-01 14:06:31', '2026-04-03 14:33:31'),
(16, 'pedro urena', 'starlin056@gmail.com', '8299437780', 'Samaná', '', '2026-04-16', 2, '$100 - $250', 'wrw44t4r4wr', 'approved', '[{\"id\":\"69cfd0afac7cb\",\"content\":\"dsdsdsdsd\",\"created_by\":3,\"created_at\":\"2026-04-03 10:37:35\"},{\"id\":\"69cfd0b92bdb3\",\"content\":\"wdwdwdwdwdwdwdwdwdwdwd\",\"created_by\":3,\"created_at\":\"2026-04-03 10:37:45\"}]', '[]', 500.00, 'assets/uploads/proposals/propuesta_16_69cfdcef27b58.pdf', NULL, '2026-04-01 14:26:56', '2026-04-03 15:29:51'),
(17, 'pedro urena', 'starlin056@gmail.com', '8299437780', 'Punta Cana', 'Senderismo, Snorkel, Avistamiento de aves', '2026-04-16', 4, 'Más de $1,000', 'swrfgewfwerffwefwefwfwefwe', 'approved', '[{\"id\":\"69cfd7c905f2c\",\"content\":\"tener pendiente la compra de boletas\",\"created_by\":3,\"created_at\":\"2026-04-03 11:07:53\"}]', '[\"transporte\",\"entradas\",\"seguro\"]', NULL, NULL, '2026-04-03 13:00:43', '2026-04-01 14:35:08', '2026-04-03 15:07:53'),
(18, 'pedro starlin ureña', 'starlin056@gmail.com', '+18299437780', 'Samaná', 'Senderismo, Rafting', '2026-04-16', 4, '$500 - $1,000', 'fwefewfwefwe', 'approved', '[{\"id\":\"69d581ec8c714\",\"content\":\"tener presente comprar agua ectr\",\"created_by\":3,\"created_at\":\"2026-04-07 18:15:08\"}]', '[\"transporte\",\"guia\",\"comida\",\"seguro\",\"equipo\"]', 850.00, 'assets/uploads/proposals/propuesta_18_69d58285eef06.pdf', '2026-04-07 22:16:35', '2026-04-07 22:11:58', '2026-04-07 22:17:41'),
(19, 'Administrador Principal', 'admin@dominican-travel.com', '8299437780', 'Samaná', 'Avistamiento de aves', '2026-04-22', 2, '$250 - $500', 'rffrfr', 'approved', NULL, NULL, NULL, NULL, NULL, '2026-04-19 14:55:00', '2026-04-19 15:13:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `excursions`
--

CREATE TABLE `excursions` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_type` enum('persona','paquete') NOT NULL DEFAULT 'persona',
  `category` varchar(100) DEFAULT NULL,
  `includes` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL COMMENT 'JSON array de nombres de imágenes adicionales',
  `rating` decimal(3,1) DEFAULT 5.0 COMMENT 'Calificación promedio 1-5',
  `reviews_count` int(11) DEFAULT 0 COMMENT 'Número de reseñas',
  `featured` tinyint(1) DEFAULT 0 COMMENT 'Excursión destacada en homepage',
  `max_people` int(11) DEFAULT 15 COMMENT 'Máximo de personas por grupo',
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `excursions`
--

INSERT INTO `excursions` (`id`, `name`, `location`, `description`, `duration`, `price`, `price_type`, `category`, `includes`, `requirements`, `image`, `gallery`, `rating`, `reviews_count`, `featured`, `max_people`, `active`, `created_at`) VALUES
(4, 'City Tour Santo Domingo', 'Santo Domingo', 'Recorrido por la primera ciudad de América', '5 horas', 50.00, 'paquete', 'Cultura', '[\"GUIA\",\"COMIDA\",\"SEGURIDAD\"]', '[\"EDAD MINIMA DE 12 A\\u00d1OS\",\"SEGURO MEDICO.\"]', '696d3d2fcef7b.jpeg', NULL, 4.8, 24, 1, 15, 1, '2026-01-18 20:06:07'),
(5, 'City Tour punta cana', 'Santo Domingo', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '6 horas', 100.00, 'paquete', 'Cultura', '[\"GUIA\",\"COMIDA\",\"SEGURIDAD\"]', '[\"EDAD MINIMA DE 12 A\\u00d1OS\",\"SEGURO MEDICO.\"]', '696d3efb83e43.jpeg', NULL, 4.6, 18, 1, 12, 1, '2026-01-18 20:13:47'),
(6, 'Transfer Aeropuerto a Hotel Guala', 'Santo Domingo', 'dgdfgdfg', '6 horas', 50.00, 'persona', 'Cultura', '[\"GUIA\",\"COMIDA\",\"SEGURIDAD\"]', '[\"EDAD MINIMA DE 12 A\\u00d1OS\",\"  SEGURO MEDICO.\"]', '696d42c2641d8.jpeg', NULL, 5.0, 0, 0, 15, 1, '2026-01-18 20:29:54'),
(7, 'NOMBRE DE EJEMPLO', 'MICHE', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '6 horas', 300.00, 'persona', 'AVENTURA', '[\"GUIA\",\"COMIDA\",\"SEGURIDAD\"]', '[\"EDAD MINIMA DE 12 A\\u00d1OS\",\"SEGURO MEDICO.\"]', '696edafeb10da.jpg', '[\"69c06bb2ac662.jpeg\",\"69c06bb2ac956.jpeg\"]', 4.9, 32, 1, 10, 1, '2026-01-20 01:31:42'),
(8, 'NOMBRE DE EJEMPLO12', 'MICHE', 'fbdfbdfbdfbdffbdfbfdbdfbdf', '6 horas', 10.00, 'persona', 'playa', NULL, NULL, '69c072f1d76be.jpeg', '[\"69c072f1d785a.jpeg\",\"69c072f1d796d.jpeg\",\"69c072f1d7a94.jpg\"]', 5.0, 0, 0, 0, 1, '2026-03-22 22:53:37'),
(9, 'pedro starlin urena cruz', 'sxascda', 'sdsddwdwdw', '1', 80.00, 'paquete', 'playa', NULL, NULL, '69c4925873331.jpeg', '[\"69c49258734d7.png\",\"69c49258736f0.jpeg\",\"69c4925876946.png\"]', 5.0, 0, 0, 15, 1, '2026-03-26 01:56:40'),
(10, 'COCO-BONGO1', 'PUNTA CANA', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '5 horas', 50.00, 'paquete', 'night club', '[\"Tranporte\"]', '[\"18+\"]', '69c6a5fb753a0.jpg', '[\"69c6a5fb7557f.png\",\"69c6a5fb75714.jpg\"]', 5.0, 0, 1, 15, 1, '2026-03-27 15:44:59'),
(13, 'ejemplo22', 'punta cana', 'sdsdsdsd', '', 80.00, 'paquete', 'playa', NULL, NULL, '69e4e08f48703.jpeg', '[\"69e4e08f488fa.jpg\",\"69e4e08f48a9e.jpeg\",\"69e4e08f48dba.jpeg\",\"69e4e08f48f11.jpeg\"]', 5.0, 0, 0, 0, 1, '2026-04-19 14:02:55'),
(14, 'paquete de ejemplo foto', 'punta cana', 'ddfdfdfdf', '', 80.00, 'paquete', 'Aventura', NULL, NULL, '69e54fcb70710.jpeg', '[\"69e54fcb70810.jpeg\",\"69e54fcb70937.jpeg\",\"69e54fcb70a29.jpeg\",\"69e54fcb70b39.jpeg\"]', 5.0, 0, 0, 0, 1, '2026-04-19 21:56:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_type` enum('persona','paquete') NOT NULL DEFAULT 'persona',
  `discount_price` decimal(10,2) DEFAULT NULL,
  `days` int(11) NOT NULL,
  `nights` int(11) NOT NULL,
  `category` enum('playa','aventura','romantico','familiar','luxury') NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hotel_category` varchar(50) DEFAULT NULL,
  `max_people` int(11) DEFAULT 2,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `includes` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `packages`
--

INSERT INTO `packages` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `price_type`, `discount_price`, `days`, `nights`, `category`, `location`, `hotel_category`, `max_people`, `image`, `gallery`, `includes`, `requirements`, `featured`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Paraíso en Punta Cana Todo Incluido', 'para-so-en-punta-cana-todo-incluido', 'Disfruta de 5 días en los mejores resorts todo incluido de Punta Cana', 'Disfruta de 5 días en los mejores resorts todo incluido de Punta Cana', 899.99, 'persona', NULL, 5, 4, 'playa', '', '', 2, '69cb1043792cb.jpeg', NULL, '[]', NULL, 1, 1, '2026-01-17 12:56:57', '2026-03-31 00:07:31'),
(2, 'Aventura en la Montaña', 'aventura-en-la-monta-a', 'Excursiones de aventura en Jarabacoa con canopy y rafting', 'Excursiones de aventura en Jarabacoa con canopy y rafting', 649.99, 'persona', NULL, 3, 2, 'aventura', '', '', 2, '69cb104b6ba73.jpg', NULL, '[]', NULL, 1, 1, '2026-01-17 12:56:57', '2026-03-31 00:07:39'),
(3, 'Luna de Miel Romántica', 'luna-de-miel-rom-ntica', 'Paquete especial para parejas en resorts exclusivos', 'Paquete especial para parejas en resorts exclusivos', 1299.99, 'persona', NULL, 7, 6, 'romantico', '', '', 2, '69cb0da3026e0.jpg', '[\"69e4c9f74604b.jpg\",\"69e4c9f74612a.jpg\"]', NULL, NULL, 1, 1, '2026-01-17 12:56:57', '2026-04-19 12:26:31'),
(4, 'punta cana', 'punta-cana', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 1500.00, 'persona', 1400.00, 6, 5, 'playa', '', '', 4, '696d4ecc89316.jpeg', '[\"69c490bc9bdab.jpeg\",\"69c490bc9bf3c.jpeg\",\"69c490bc9c051.jpg\",\"69c490bc9c55b.png\"]', '[]', NULL, 1, 1, '2026-01-18 21:21:16', '2026-03-26 01:49:48'),
(5, 'EJEMPLO DE PAQUETE', 'ejemplo-de-paquete', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 10500.00, 'persona', 10400.00, 10, 9, 'playa', NULL, '', 10, '696d56e3bbe3c.jpeg', NULL, '[\"TODO INCLUIDO\"]', NULL, 1, 1, '2026-01-18 21:55:47', '2026-01-18 21:55:47'),
(6, 'EDWIN', 'edwin', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'Lorem ipsum dolor sit amet, consectetur adipisci elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 800.00, 'persona', 700.00, 5, 4, 'familiar', '', '', 2, '696eda55a7d5c.jpeg', '[\"69c6a40a2ace2.jpeg\",\"69c6a40a2b209.jpeg\"]', '[]', NULL, 1, 1, '2026-01-20 01:28:53', '2026-03-27 15:36:42'),
(7, 'pedro starlin urena cruz', 'pedro-starlin-urena-cruz', 'xcdccdcdcdcd', 'xcdccdcdcdcd', 80.00, 'persona', 75.00, 5, 4, 'playa', '', '', 2, '69c488cb9c531.jpeg', '[\"69c488cb9c620.jpeg\",\"69c488cb9c69e.png\",\"69c488cb9c98d.png\",\"69c488cb9cdcd.png\"]', '[]', NULL, 0, 0, '2026-03-26 01:15:55', '2026-03-27 15:35:31'),
(8, 'pedro starlin urena cruz1', 'pedro-starlin-urena-cruz1', 'erggergegeg', 'erggergegeg', 80.00, 'persona', NULL, 5, 4, 'luxury', '', '', 2, '69cb020e9e172.png', NULL, '[]', NULL, 0, 0, '2026-03-30 23:06:54', '2026-03-30 23:07:50'),
(9, 'COCO-BONGOddd', 'coco-bongoddd', 'no elimina se desactivan los pack', 'no elimina se desactivan los pack', 80.00, 'persona', NULL, 5, 4, 'playa', '', '', 2, '69cb037595b9c.jpg', '[\"69cb037596106.jpg\",\"69cb0375967b4.jpg\"]', '[]', NULL, 0, 0, '2026-03-30 23:12:53', '2026-03-30 23:12:59'),
(11, 'pedro starlin urena cruzs', 'pedro-starlin-urena-cruzs', 'sffssfff', 'sffssfff', 2.00, 'paquete', NULL, 5, 4, 'familiar', '', '', 0, '69d026e0bcb0e.jpg', '[\"69e4e0e1abcf8.jpeg\",\"69e4e0e1abe1c.jpeg\",\"69e4e29cbdd1e.jpg\",\"69e4e29cbdeb7.jpeg\"]', NULL, NULL, 1, 1, '2026-04-03 20:45:20', '2026-04-19 14:13:03');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quotations`
--

CREATE TABLE `quotations` (
  `id` int(11) NOT NULL,
  `quote_number` varchar(50) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(50) DEFAULT NULL,
  `travel_date` date DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `status` enum('draft','sent','confirmed','expired') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `agent_name` varchar(255) DEFAULT NULL,
  `is_tax_enabled` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `quotations`
--

INSERT INTO `quotations` (`id`, `quote_number`, `customer_name`, `customer_email`, `customer_phone`, `travel_date`, `subtotal`, `tax_amount`, `total_price`, `notes`, `status`, `created_at`, `updated_at`, `agent_name`, `is_tax_enabled`) VALUES
(1, 'QT-2026-0001', 'pedro starlin urena cruz', 'starlin056@gmail.com', '8299437780', '2026-04-23', 794.99, 0.00, 794.99, 'TÉRMINOS Y CONDICIONES:\r\n- Esta cotización tiene una validez de 30 días a partir de la fecha de emisión.\r\n- Los precios están sujetos a cambios sin previo aviso según la disponibilidad de los prestadores de servicios.\r\n- Para confirmar cualquier reserva, se requiere un depósito del 50% del total.\r\n- El saldo restante debe ser liquidado al menos 15 días antes de la fecha de inicio del servicio.\r\n- Políticas de cancelación: Reembolso del 100% si se cancela con más de 30 días de anticipación.', 'draft', '2026-04-20 16:00:42', '2026-04-20 16:00:42', 'Administrador Principal', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` int(11) NOT NULL,
  `quotation_id` int(11) NOT NULL,
  `item_type` enum('package','excursion','transfer','custom') NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `quotation_items`
--

INSERT INTO `quotation_items` (`id`, `quotation_id`, `item_type`, `item_id`, `description`, `quantity`, `unit_price`, `total`) VALUES
(1, 1, 'excursion', 5, 'EXCURSION: City Tour punta cana', 1, 100.00, 100.00),
(2, 1, 'transfer', 26, 'TRANSFER: PUJ → Club Med Punta Cana', 1, 45.00, 45.00),
(3, 1, 'package', 2, 'PACKAGE: Aventura en la Montaña', 1, 649.99, 649.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `request_activity_log`
--

CREATE TABLE `request_activity_log` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL COMMENT 'ID del admin que realizó la acción',
  `action_type` enum('note_added','status_changed','contacted','quote_sent','file_attached') NOT NULL,
  `action_details` text DEFAULT NULL COMMENT 'Detalles de la acción en JSON',
  `visible_to_client` tinyint(1) DEFAULT 0 COMMENT 'Si esta nota es visible para el cliente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `request_activity_log`
--

INSERT INTO `request_activity_log` (`id`, `request_id`, `admin_id`, `action_type`, `action_details`, `visible_to_client`, `created_at`) VALUES
(1, 15, 3, 'status_changed', '{\"from\":\"approved\",\"to\":\"approved\",\"note\":null}', 0, '2026-04-01 16:43:58'),
(2, 17, 3, 'contacted', '{\"method\":\"admin_panel\",\"timestamp\":\"2026-04-03 09:00:43\"}', 0, '2026-04-03 13:00:43'),
(3, 15, 3, 'note_added', '{\"id\":\"69cfc84471712\",\"content\":\"tener pendiente agregar agua\",\"created_by\":3,\"created_at\":\"2026-04-03 10:01:40\"}', 0, '2026-04-03 14:01:40'),
(4, 13, 3, 'quote_sent', '{\"file\":\"propuesta_13_69cfc8d4308c3.pdf\",\"price\":850}', 1, '2026-04-03 14:04:04'),
(5, 16, 3, 'note_added', '{\"id\":\"69cfd0afac7cb\",\"content\":\"dsdsdsdsd\",\"created_by\":3,\"created_at\":\"2026-04-03 10:37:35\"}', 0, '2026-04-03 14:37:35'),
(6, 16, 3, 'note_added', '{\"id\":\"69cfd0b92bdb3\",\"content\":\"wdwdwdwdwdwdwdwdwdwdwd\",\"created_by\":3,\"created_at\":\"2026-04-03 10:37:45\"}', 0, '2026-04-03 14:37:45'),
(7, 17, 3, 'note_added', '{\"id\":\"69cfd7c905f2c\",\"content\":\"tener pendiente la compra de boletas\",\"created_by\":3,\"created_at\":\"2026-04-03 11:07:53\"}', 0, '2026-04-03 15:07:53'),
(8, 16, 3, 'quote_sent', '{\"file\":\"propuesta_16_69cfdcef27b58.pdf\",\"price\":500}', 1, '2026-04-03 15:29:51'),
(9, 18, 3, 'note_added', '{\"id\":\"69d581ec8c714\",\"content\":\"tener presente comprar agua ectr\",\"created_by\":3,\"created_at\":\"2026-04-07 18:15:08\"}', 0, '2026-04-07 22:15:08'),
(10, 18, 3, 'status_changed', '{\"from\":\"approved\",\"to\":\"approved\",\"note\":null}', 0, '2026-04-07 22:16:33'),
(11, 18, 3, 'contacted', '{\"method\":\"admin_panel\",\"timestamp\":\"2026-04-07 18:16:35\"}', 0, '2026-04-07 22:16:35'),
(12, 18, 3, 'quote_sent', '{\"file\":\"propuesta_18_69d58285eef06.pdf\",\"price\":850}', 1, '2026-04-07 22:17:41'),
(13, 19, 3, 'status_changed', '{\"from\":\"approved\",\"to\":\"approved\",\"note\":null}', 0, '2026-04-19 15:13:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` text DEFAULT NULL,
  `company_phone` varchar(100) DEFAULT NULL,
  `company_email` varchar(255) DEFAULT NULL,
  `company_logo` varchar(255) DEFAULT NULL,
  `default_tax_rate` decimal(5,2) DEFAULT 18.00,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `settings`
--

INSERT INTO `settings` (`id`, `company_name`, `company_address`, `company_phone`, `company_email`, `company_logo`, `default_tax_rate`, `updated_at`) VALUES
(1, 'FUNTREK RD', 'Calle Ejemplo #123,Punta Cana', '+1-809-000-0000', 'info@dominicantravel.com', '69e64272e1287.png', 18.00, '2026-04-20 15:32:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_location` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transfers`
--

CREATE TABLE `transfers` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `from_location` varchar(255) NOT NULL,
  `to_location` varchar(255) NOT NULL,
  `vehicle_type` varchar(100) DEFAULT NULL,
  `max_passengers` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `price_type` enum('persona','paquete') NOT NULL DEFAULT 'paquete',
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` longtext DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `transfers`
--

INSERT INTO `transfers` (`id`, `name`, `from_location`, `to_location`, `vehicle_type`, `max_passengers`, `price`, `price_type`, `description`, `image`, `gallery`, `active`, `created_at`) VALUES
(1, 'Transfer Aeropuerto a Hotel', 'PUJ Aeropuerto', 'Bávaro/Punta Cana', 'Van privada', 12, 95.00, 'paquete', '', 'transfer_696c1bc0d8b4c.jpeg', NULL, 1, '2026-01-17 12:56:58'),
(2, 'Transfer Santo Domingo', 'SDQ Aeropuerto', 'Zona Colonial', 'Sedan ejecutivo', 3, 55.00, 'paquete', '', 'transfer_696c1bc8abff5.jpeg', NULL, 1, '2026-01-17 12:56:58'),
(5, 'Transfer Aeropuerto a Hotel Guala', 'SDQ Aeropuerto', 'Bávaro/Punta Cana', 'Van privada', 4, 150.00, 'paquete', '0jbkh', 'transfer_1768691396.jpg', NULL, 1, '2026-01-17 23:09:56'),
(6, 'PUJ → Barceló Bávaro Palace', 'PUJ Aeropuerto Internacional Punta Cana', 'Barceló Bávaro Palace, Punta Cana', 'Van privada', 0, 45.00, 'persona', 'Traslado privado desde el aeropuerto de Punta Cana hasta el Barceló Bávaro Palace. A/C, WiFi, conductor certificado.', 'transfer_generic.jpg', '[\"69e113d6d4621.jpeg\",\"69e113d6d4822.jpeg\",\"69e113d6d4997.jpeg\"]', 1, '2026-03-22 17:04:35'),
(7, 'PUJ → Barceló Bávaro Grand Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Barceló Bávaro Grand Resort, Punta Cana', 'Van privada', 0, 45.00, 'persona', 'Traslado privado desde PUJ hasta Barceló Bávaro Grand Resort. Servicio 24/7.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(8, 'PUJ → Hard Rock Hotel & Casino Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Hard Rock Hotel & Casino Punta Cana', 'Van privada', 8, 50.00, 'persona', 'Transfer privado PUJ → Hard Rock Hotel Punta Cana. Seguimiento de vuelo incluido.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(9, 'PUJ → Iberostar Selection Bávaro', 'PUJ Aeropuerto Internacional Punta Cana', 'Iberostar Selection Bávaro, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado puerta a puerta desde PUJ al Iberostar Bávaro. Vehículo climatizado.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(10, 'PUJ → Iberostar Grand Bávaro', 'PUJ Aeropuerto Internacional Punta Cana', 'Iberostar Grand Bávaro, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado privado con conductor profesional desde el aeropuerto de Punta Cana.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(11, 'PUJ → Royalton CHIC Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Royalton CHIC Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Transfer exclusivo PUJ → Royalton CHIC. Conductor bilingüe disponible.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(12, 'PUJ → Royalton Punta Cana Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Royalton Punta Cana Resort & Spa', 'Van privada', 8, 50.00, 'paquete', 'Traslado privado desde PUJ al Royalton Punta Cana. A/C y WiFi a bordo.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(13, 'PUJ → Riu Palace Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Riu Palace Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado aeropuerto Punta Cana → Riu Palace. 24/7.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(14, 'PUJ → Riu Bambu', 'PUJ Aeropuerto Internacional Punta Cana', 'Riu Bambu, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado puerta a puerta PUJ → Riu Bambu. Vehículo privado climatizado.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(15, 'PUJ → Riu Naiboa', 'PUJ Aeropuerto Internacional Punta Cana', 'Riu Naiboa, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado desde el aeropuerto de Punta Cana al Riu Naiboa.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(16, 'PUJ → Dreams Royal Beach Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Dreams Royal Beach Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Traslado privado PUJ → Dreams Royal Beach. Seguimiento de vuelo incluido.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(17, 'PUJ → Dreams Onyx Resort & Spa', 'PUJ Aeropuerto Internacional Punta Cana', 'Dreams Onyx Resort & Spa, Punta Cana', 'Van privada', 8, 55.00, 'paquete', 'Transfer privado desde PUJ al Dreams Onyx Resort. Conductor certificado.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(18, 'PUJ → Paradisus Palma Real', 'PUJ Aeropuerto Internacional Punta Cana', 'Paradisus Palma Real, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado privado aeropuerto Punta Cana → Paradisus Palma Real.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(19, 'PUJ → Meliá Caribe Beach Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Meliá Caribe Beach Resort, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado PUJ → Meliá Caribe Beach. A/C y WiFi incluidos.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(20, 'PUJ → Excellence Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Excellence Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Traslado exclusivo PUJ → Excellence Punta Cana. Solo adultos, servicio premium.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(21, 'PUJ → Zoëtry Agua Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Zoëtry Agua Punta Cana', 'Sedan ejecutivo', 4, 55.00, 'paquete', 'Transfer privado en sedan ejecutivo PUJ → Zoëtry Agua Punta Cana.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(22, 'PUJ → Grand Bahía Príncipe Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Grand Bahía Príncipe Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Traslado privado desde PUJ hasta Grand Bahía Príncipe Punta Cana.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(23, 'PUJ → Grand Bahía Príncipe La Romana', 'PUJ Aeropuerto Internacional Punta Cana', 'Grand Bahía Príncipe La Romana', 'Van privada', 8, 90.00, 'paquete', 'Transfer privado aeropuerto Punta Cana → Grand Bahía Príncipe La Romana (~75 min).', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(24, 'PUJ → Catalonia Bávaro Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Catalonia Bávaro Resort, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado privado PUJ → Catalonia Bávaro. Conductor profesional certificado.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(25, 'PUJ → Punta Cana Princess All Suites', 'PUJ Aeropuerto Internacional Punta Cana', 'Punta Cana Princess All Suites Resort', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado desde el aeropuerto de Punta Cana al Punta Cana Princess.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(26, 'PUJ → Club Med Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Club Med Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado privado PUJ → Club Med Punta Cana. A/C y WiFi a bordo.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(27, 'PUJ → Viva Wyndham V Heavens', 'PUJ Aeropuerto Internacional Punta Cana', 'Viva Wyndham V Heavens, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado aeropuerto PUJ → Viva Wyndham V Heavens.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(28, 'PUJ → Sunscape Bávaro Beach Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Sunscape Bávaro Beach Resort, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado privado PUJ → Sunscape Bávaro Beach. Servicio 24/7.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(29, 'PUJ → Majestic Elegance Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Majestic Elegance Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Transfer privado PUJ → Majestic Elegance. Conductor bilingüe disponible.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(30, 'PUJ → Majestic Colonial Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Majestic Colonial Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Traslado privado desde PUJ al Majestic Colonial. Seguimiento de vuelo incluido.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(31, 'PUJ → Be Live Collection Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Be Live Collection Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado PUJ → Be Live Collection Punta Cana.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(32, 'PUJ → Hyatt Ziva Cap Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Hyatt Ziva Cap Cana', 'Van privada', 8, 55.00, 'paquete', 'Transfer privado PUJ → Hyatt Ziva Cap Cana. Zona exclusiva Cap Cana (~15 min).', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(33, 'PUJ → Alua Romana Beach Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Alua Romana Beach Resort, La Romana', 'Van privada', 8, 95.00, 'paquete', 'Traslado privado aeropuerto Punta Cana → La Romana (~70 min). Van climatizada.', 'transfer_generic.jpg', '[\"69e114a8b5f1e.jpeg\",\"69e114a8b6134.jpeg\"]', 1, '2026-03-22 17:04:35'),
(34, 'PUJ → Cap Cana Marina & Golf Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Cap Cana Marina & Golf Resort', 'Van privada', 8, 55.00, 'paquete', 'Transfer privado PUJ → Cap Cana. Vehículo de lujo, conductor certificado.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(35, 'SDQ → Bávaro / Punta Cana (zona hotelera)', 'SDQ Aeropuerto Internacional Las Américas', 'Bávaro / Punta Cana (zona hotelera)', 'Van privada', 8, 140.00, 'paquete', 'Transfer privado desde el aeropuerto de Santo Domingo hasta la zona hotelera de Bávaro/Punta Cana (~2.5 h). A/C y WiFi.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(36, 'SDQ → Hard Rock Hotel Punta Cana', 'SDQ Aeropuerto Internacional Las Américas', 'Hard Rock Hotel & Casino Punta Cana', 'Van privada', 8, 140.00, 'paquete', 'Traslado privado SDQ → Hard Rock Hotel Punta Cana (~2.5 h). Conductor certificado.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(37, 'SDQ → Barceló Bávaro Palace', 'SDQ Aeropuerto Internacional Las Américas', 'Barceló Bávaro Palace, Punta Cana', 'Van privada', 8, 140.00, 'paquete', 'Transfer privado Santo Domingo → Barceló Bávaro Palace. Van climatizada.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(38, 'SDQ → Hotel Embajador (Santo Domingo)', 'SDQ Aeropuerto Internacional Las Américas', 'Hotel Embajador, Santo Domingo', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Transfer privado en sedan ejecutivo desde SDQ hasta el Hotel Embajador (~25 min).', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(39, 'SDQ → Renaissance Jaragua Hotel', 'SDQ Aeropuerto Internacional Las Américas', 'Renaissance Jaragua Hotel & Casino, Santo Domingo', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Traslado privado SDQ → Renaissance Jaragua. Conductor bilingüe disponible.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(40, 'SDQ → NH Collection Santo Domingo', 'SDQ Aeropuerto Internacional Las Américas', 'NH Collection Santo Domingo Royal', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Transfer privado aeropuerto SDQ → NH Collection Santo Domingo.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(41, 'SDQ → Intercontinental Real Santo Domingo', 'SDQ Aeropuerto Internacional Las Américas', 'Intercontinental Real Santo Domingo', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Traslado privado SDQ → Intercontinental Real. Van o sedan disponible.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(42, 'SDQ → Hotel V Samana', 'SDQ Aeropuerto Internacional Las Américas', 'Hotel V Samana, Las Terrenas', 'Van privada', 8, 130.00, 'paquete', 'Transfer privado SDQ → Samaná / Las Terrenas (~2.5 h por vía rápida). Van climatizada.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(43, 'SDQ → Zona Colonial (hoteles boutique)', 'SDQ Aeropuerto Internacional Las Américas', 'Zona Colonial, Santo Domingo', 'Sedan ejecutivo', 4, 30.00, 'paquete', 'Transfer privado SDQ → Zona Colonial (~20 min). Hoteles boutique, museos, restaurantes.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(44, 'PUJ → La Romana (Casa de Campo)', 'PUJ Aeropuerto Internacional Punta Cana', 'Casa de Campo Resort, La Romana', 'Van privada', 8, 90.00, 'paquete', 'Transfer privado Punta Cana → Casa de Campo La Romana (~75 min). Conductor profesional.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(45, 'PUJ → Las Terrenas / Samaná', 'PUJ Aeropuerto Internacional Punta Cana', 'Las Terrenas / Samaná', 'Van privada', 8, 180.00, 'paquete', 'Traslado privado PUJ → Samaná (~3.5 h). Van climatizada, conductor certificado.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(46, 'Hotel a Hotel — Zona Bávaro / Punta Cana', 'Zona Hotelera Bávaro / Punta Cana', 'Zona Hotelera Bávaro / Punta Cana', 'Van privada', 8, 25.00, 'paquete', 'Traslado privado entre hoteles dentro de la zona hotelera de Bávaro/Punta Cana. Ideal para excursiones o cambios de alojamiento.', 'transfer_generic.jpg', NULL, 0, '2026-03-22 17:04:35'),
(47, 'Hotel → Aeropuerto PUJ (salida)', 'Zona Hotelera Bávaro / Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer de regreso desde tu hotel en Punta Cana hasta el aeropuerto PUJ. Puntualidad garantizada.', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(48, 'Hotel → Aeropuerto SDQ (salida)', 'Zona Hotelera Bávaro / Punta Cana', 'SDQ Aeropuerto Internacional Las Américas', 'Van privada', 8, 140.00, 'paquete', 'Transfer de regreso desde Punta Cana hasta el aeropuerto de Santo Domingo (~2.5 h).', 'transfer_generic.jpg', NULL, 1, '2026-03-22 17:04:35'),
(49, 'PUJ → Barceló Bávaro Palace', 'PUJ Aeropuerto Internacional Punta Cana', 'Barceló Bávaro Palace, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado privado desde PUJ al Barceló Bávaro Palace.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(50, 'PUJ → Hard Rock Hotel & Casino Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Hard Rock Hotel & Casino Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Transfer privado PUJ → Hard Rock Hotel Punta Cana.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(51, 'PUJ → Iberostar Selection Bávaro', 'PUJ Aeropuerto Internacional Punta Cana', 'Iberostar Selection Bávaro, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado puerta a puerta desde PUJ al Iberostar Bávaro.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(52, 'PUJ → Royalton Punta Cana Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Royalton Punta Cana Resort & Spa', 'Van privada', 8, 50.00, 'paquete', 'Traslado privado desde PUJ al Royalton Punta Cana.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(53, 'PUJ → Riu Palace Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Riu Palace Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado aeropuerto PUJ → Riu Palace.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(54, 'PUJ → Dreams Royal Beach Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Dreams Royal Beach Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Traslado privado PUJ → Dreams Royal Beach.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(55, 'PUJ → Meliá Caribe Beach Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Meliá Caribe Beach Resort, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado PUJ → Meliá Caribe Beach.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(56, 'PUJ → Majestic Elegance Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'Majestic Elegance Punta Cana', 'Van privada', 8, 50.00, 'paquete', 'Transfer privado PUJ → Majestic Elegance.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(57, 'PUJ → Lopesan Costa Bávaro Resort', 'PUJ Aeropuerto Internacional Punta Cana', 'Lopesan Costa Bávaro Resort Spa & Casino', 'Van privada', 8, 55.00, 'paquete', 'Traslado privado PUJ → Lopesan Costa Bávaro.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(58, 'PUJ → Bahia Principe Esmeralda', 'PUJ Aeropuerto Internacional Punta Cana', 'Bahia Principe Esmeralda, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Transfer privado PUJ → Bahia Principe Esmeralda.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(59, 'PUJ → Catalonia Royal Bávaro', 'PUJ Aeropuerto Internacional Punta Cana', 'Catalonia Royal Bávaro, Punta Cana', 'Van privada', 8, 45.00, 'paquete', 'Traslado privado PUJ → Catalonia Royal Bávaro.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(60, 'PUJ → AC Hotel Punta Cana', 'PUJ Aeropuerto Internacional Punta Cana', 'AC Hotel by Marriott Punta Cana', 'Sedan ejecutivo', 4, 40.00, 'paquete', 'Transfer privado PUJ → AC Hotel Punta Cana.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(61, 'SDQ → Hotel Embajador', 'SDQ Aeropuerto Internacional Las Américas', 'Hotel Embajador, Santo Domingo', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Transfer privado SDQ → Hotel Embajador.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(62, 'SDQ → Renaissance Jaragua Hotel', 'SDQ Aeropuerto Internacional Las Américas', 'Renaissance Jaragua Hotel & Casino, Santo Domingo', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Traslado privado SDQ → Renaissance Jaragua.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(63, 'SDQ → Intercontinental Real Santo Domingo', 'SDQ Aeropuerto Internacional Las Américas', 'Intercontinental Real Santo Domingo', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Traslado privado SDQ → Intercontinental Real.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(64, 'SDQ → NH Collection Santo Domingo', 'SDQ Aeropuerto Internacional Las Américas', 'NH Collection Santo Domingo Royal', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Transfer privado SDQ → NH Collection Santo Domingo.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(65, 'SDQ → Courtyard by Marriott Santo Domingo', 'SDQ Aeropuerto Internacional Las Américas', 'Courtyard by Marriott Santo Domingo', 'Sedan ejecutivo', 4, 30.00, 'paquete', 'Traslado privado SDQ → Courtyard Marriott.', NULL, NULL, 0, '2026-04-16 17:44:25'),
(66, 'SDQ → Billini Hotel', 'SDQ Aeropuerto Internacional Las Américas', 'Billini Hotel, Zona Colonial', 'Sedan ejecutivo', 4, 35.00, 'paquete', 'Transfer privado SDQ → Billini Hotel.', NULL, NULL, 0, '2026-04-16 17:44:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `role` enum('admin','agent','client') DEFAULT 'agent',
  `active` tinyint(1) DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `active`, `last_login`, `reset_token`, `reset_expires`, `created_at`) VALUES
(3, 'admin', 'admin@dominican-travel.com', '$2y$10$drFflx0dnVf5gnOXpZtNSuIvSTqy8xmY2uimPMU4QuOA5GGrGcEVq', 'Administrador Principal', 'admin', 1, '2026-04-20 16:18:45', NULL, NULL, '2026-01-17 16:25:21'),
(4, 'pedro', 'starlin056@gmail.com', '$2y$10$XmWwmgsbBXo..2BY/gQMieKVeEWniQytYQM09VXxyvy/gPEBcQgau', 'pedro starlin urena cruz', 'client', 1, '2026-04-20 14:38:15', NULL, NULL, '2026-01-17 17:33:12'),
(5, 'Edwin', 'ejemplo@gmail.com', '$2y$10$wnYdtqP53Rz6NM6tjItG/Op8CWchXZtggEfknnhHNxVnQ.eyeyhNG', 'edwin ariel', 'client', 1, NULL, NULL, NULL, '2026-01-18 22:25:11'),
(6, 'EDWIN056', 'aj.adams21hs@gmail.com', '$2y$10$UWFdFCWKrOpUVTO/KZx8ieYDtOgdk6kjoofSC9dIdeYsWDKmq71Se', 'EDWIN ARIEL H', 'client', 1, '2026-01-27 22:44:02', NULL, NULL, '2026-01-19 15:14:04'),
(7, 'herre', 'jh@gmail.com', '$2y$10$uF6YfvAgzRGO1bWHtdGC/O7mv7eONoko3kDliLQDxIs.q./cazwIO', 'edwin h', 'client', 1, NULL, NULL, NULL, '2026-04-08 01:00:47'),
(8, 'AAHERR', 'ADA@GMAIL.COM', '$2y$10$V7ldoKyZjVhIppebdZook.swKdF6a6p1s5C1i.EIYSKe5U1eVdWPu', 'edwin A', 'client', 1, '2026-04-08 18:24:48', NULL, NULL, '2026-04-08 01:02:08');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_reference` (`booking_reference`);

--
-- Indices de la tabla `custom_excursion_requests`
--
ALTER TABLE `custom_excursion_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `excursions`
--
ALTER TABLE `excursions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indices de la tabla `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quote_number` (`quote_number`);

--
-- Indices de la tabla `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_id` (`quotation_id`);

--
-- Indices de la tabla `request_activity_log`
--
ALTER TABLE `request_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_request_id` (`request_id`);

--
-- Indices de la tabla `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `transfers`
--
ALTER TABLE `transfers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `custom_excursion_requests`
--
ALTER TABLE `custom_excursion_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `excursions`
--
ALTER TABLE `excursions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `request_activity_log`
--
ALTER TABLE `request_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transfers`
--
ALTER TABLE `transfers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_ibfk_1` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `request_activity_log`
--
ALTER TABLE `request_activity_log`
  ADD CONSTRAINT `request_activity_log_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `custom_excursion_requests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
