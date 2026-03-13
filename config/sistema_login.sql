-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-03-2026 a las 19:11:38
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
-- Base de datos: `sistema_login`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `publicacion_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `padre_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comentarios`
--

INSERT INTO `comentarios` (`id`, `publicacion_id`, `usuario_id`, `comentario`, `fecha`, `padre_id`) VALUES
(1, 24, 2, 'holi', '2026-03-05 16:49:34', NULL),
(2, 25, 2, 'gola', '2026-03-05 18:12:53', NULL),
(3, 25, 2, 'aaaaaa', '2026-03-05 18:17:07', NULL),
(4, 25, 2, 'hola', '2026-03-05 18:24:30', NULL),
(5, 25, 2, 'ooooo', '2026-03-05 18:48:26', 3),
(6, 28, 2, 'holaaa', '2026-03-05 19:20:06', NULL),
(7, 28, 2, 'holaaa', '2026-03-05 19:20:12', 6),
(8, 30, 2, 'adsdssad', '2026-03-09 16:17:41', NULL),
(9, 30, 2, 'holiii', '2026-03-09 16:17:52', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `publicaciones`
--

CREATE TABLE `publicaciones` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `texto` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `publicaciones`
--

INSERT INTO `publicaciones` (`id`, `usuario_id`, `texto`, `imagen`, `fecha`) VALUES
(1, 2, '', '', '2026-02-20 05:01:32'),
(2, 2, '', '', '2026-02-20 05:01:46'),
(3, 2, '', '', '2026-02-20 05:05:23'),
(4, 2, '', '', '2026-02-20 05:05:32'),
(5, 2, '', '', '2026-02-20 05:07:43'),
(6, 2, '', '', '2026-02-20 05:08:39'),
(7, 2, '', '', '2026-02-20 05:14:33'),
(8, 2, 'hola', '', '2026-02-20 05:18:22'),
(9, 2, 'prueba', '1771564767_Nendoroid-Marvel-Comics-Gwenpool-Non-scale-ABS-PVC-Pre-painted-Fully-Movable-Figure_b7963dd2-b5ad-4779-bd4e-afdbbef20097.fa7c308cbe0587f9f41899d17f607ef2.avif', '2026-02-20 05:19:27'),
(10, 2, 'prueba', '', '2026-02-20 05:24:40'),
(11, 2, 'prueba', '', '2026-02-20 05:26:10'),
(12, 2, '', '', '2026-02-20 05:27:13'),
(13, 2, 'mmm', '', '2026-02-20 05:29:01'),
(14, 2, 'aa', '', '2026-02-20 15:30:34'),
(15, 2, 'aa', '', '2026-02-20 19:39:11'),
(16, 2, 'si', '1771616360_Nendoroid-Marvel-Comics-Gwenpool-Non-scale-ABS-PVC-Pre-painted-Fully-Movable-Figure_b7963dd2-b5ad-4779-bd4e-afdbbef20097.fa7c308cbe0587f9f41899d17f607ef2.avif', '2026-02-20 19:39:20'),
(17, 2, '', '', '2026-02-26 16:53:20'),
(18, 2, '', '1772126876_YTDown.com_YouTube_Laufey-From-The-Start-Official-Music-Vid_Media_lSD_L-xic9o_004_360p.mp4', '2026-02-26 17:27:56'),
(19, 2, '', '1772126894_YTDown.com_YouTube_Laufey-From-The-Start-Official-Music-Vid_Media_lSD_L-xic9o_004_360p.mp4', '2026-02-26 17:28:14'),
(20, 2, '', '1772127677_YTDown.com_YouTube_Laufey-From-The-Start-Official-Music-Vid_Media_lSD_L-xic9o_002_720p.mp4', '2026-02-26 17:41:17'),
(21, 2, '', '', '2026-02-26 17:41:39'),
(22, 2, 'https://github.com', '', '2026-02-26 17:42:06'),
(23, 2, 'https://github.com', '', '2026-02-26 17:46:46'),
(24, 2, 'https://github.com', '', '2026-02-26 17:46:52'),
(25, 2, 'ok', '', '2026-03-05 18:09:39'),
(26, 2, 'Vendo Robot chino para asistente en el hogar', '1772736798_Screenshot_2026-02-26-11-27-22-166_jp.co.celsys.clipstudiopaint.googleplay-edit.jpg', '2026-03-05 18:53:18'),
(27, 2, 'golagolagolaolaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa\r\n', '', '2026-03-05 19:01:53'),
(28, 2, 'hola hola hola holaaaaaaaaaaaa holaaaaa holaaaaaaa', '', '2026-03-05 19:02:21'),
(29, 2, 'hfhfghjgh', '', '2026-03-09 15:54:46'),
(30, 2, 'holaaa', '', '2026-03-09 16:01:32'),
(31, 2, '', '', '2026-03-09 16:18:07'),
(32, 2, '', '', '2026-03-09 16:18:07'),
(33, 2, '', '', '2026-03-09 16:18:08'),
(34, 2, '', '', '2026-03-09 16:18:13'),
(35, 2, '', '', '2026-03-09 16:18:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reacciones`
--

CREATE TABLE `reacciones` (
  `id` int(11) NOT NULL,
  `publicacion_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `tipo` enum('like','dislike') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reacciones`
--

INSERT INTO `reacciones` (`id`, `publicacion_id`, `usuario_id`, `tipo`) VALUES
(26, 24, 2, 'like'),
(38, 26, 2, 'like'),
(56, 28, 2, 'like'),
(60, 30, 2, 'dislike'),
(61, 34, 2, 'dislike'),
(62, 35, 2, 'like'),
(63, 35, 4, 'like'),
(64, 34, 4, 'like');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `num` varchar(50) NOT NULL,
  `cuenta` varchar(100) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `rol` enum('lector','creador','admin') DEFAULT 'lector'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `num`, `cuenta`, `PASSWORD`, `rol`) VALUES
(1, 'juan', '', '', '123', 'lector'),
(2, 'maria', '', '', '456', 'creador'),
(3, 'Marco', '', '', '0911', 'admin'),
(4, 'marco antonio', '20223252', 'myanez4@ucol.mx', 'KanonHisui', 'creador'),
(5, 'José Carlo Ramirez Bacelis', '20250689', 'jramirez195@ucol.mx', 'MakimaSimp69!', 'lector');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `reacciones`
--
ALTER TABLE `reacciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reaccion_unica` (`publicacion_id`,`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `reacciones`
--
ALTER TABLE `reacciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `publicaciones`
--
ALTER TABLE `publicaciones`
  ADD CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
