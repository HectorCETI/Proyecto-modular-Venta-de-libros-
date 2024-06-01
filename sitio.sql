-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2024 a las 05:25:04
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
-- Base de datos: `sitio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `autor` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `descuento` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `nombre`, `titulo`, `autor`, `descripcion`, `imagen`, `precio`, `descuento`) VALUES
(1, 'Python 2', 'Titulo 1', 'Autor 1', 'Descripción del Libro 1', '1717104683_Aprendizaje Python.jpg', 119.99, 24),
(3, 'Android Grow', '', '', 'Descripción del Libro 3', '1717104830_AndroidGrow.png', 55.99, 28),
(4, 'Prueba sin imagen', '', '', 'Prueba sin imagen', '1717106930_CPlusPlusGrow.png', 99999.00, 48),
(7, 'Prueba 2eq', '', '', 'qwdwqdqd', '1717174398_CPlusPlusGrow.png', 121.00, 56),
(8, 'Prueba 22w1w1', '', '', '12211d1wdsws', '1717174413_BashGrow.png', 123.00, 20),
(9, 'fweqedqd', '', '', 'wqdqdew', '1717174440_PHP & MySQL.jpg', 1323.00, 55),
(10, 'Python prueba', '', '', 'prueba de imagen', '1717175922_AngularJSGrow.png', 3232.00, 28),
(13, 'Prueba sin imagen 11', '', '', 'prueba 11', '1717185559_DotNETFrameworkGrow.png', 1121.00, 48),
(14, 'Prueba sin imagen 11', '', '', 'prueba 11', '1717185890_DotNETFrameworkGrow.png', 1121.00, 21),
(17, 'Algorithms Grow 321', '', '', 'safdasdada', '1717206795_AndroidGrow.png', 123.00, 33),
(18, 'Android Grow qweqwe', '', '', 'qweqweqweq', '1717206815_Novela JAVA.jpg', 121.00, 35),
(19, 'Algorithms Grow rerereere', '', '', 'rerererererer', '1717207203_BashGrow.png', 12321.00, 50),
(20, 'Algorithms Grow teeteteteet', '', '', 'tetetee', '1717207237_AndroidGrow.png', 1233123.00, 29),
(21, 'Algorithms Grow 0000', '', '', '00000000', '1717207509_AlgorithmsGrow.png', 1.00, 44),
(22, 'zzzzz', '', '', 'zzzzzz', '1717207529_Level 1 - PHP.png', 123.00, 42),
(23, 'Algorithms Grow 333', '', '', '3333', '1717209618_Aprendizaje Python.jpg', 333.00, 26);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
