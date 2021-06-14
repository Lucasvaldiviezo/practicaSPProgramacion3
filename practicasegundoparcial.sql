-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2021 a las 09:56:29
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `practicasegundoparcial`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(18) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `dni` int(8) NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `nombre`, `apellido`, `dni`, `fecha_de_baja`) VALUES
(1, 'Antonio', 'Barreda', 20020804, NULL),
(2, 'Mario', 'Bros', 49990003, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hortalizas`
--

CREATE TABLE `hortalizas` (
  `id` int(18) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `precio` decimal(50,0) NOT NULL,
  `stock` int(50) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `hortalizas`
--

INSERT INTO `hortalizas` (`id`, `nombre`, `precio`, `stock`, `tipo`, `foto`, `fecha_de_baja`) VALUES
(1, 'banana', '7', 46, 'fruta', './app/fotoHortaliza/(deleted)banana+fruta.jpg', '2021-06-14'),
(2, 'papa', '12', 46, 'verdura', './app/fotoHortaliza/papa+verdura.jpg', NULL),
(3, 'tomate', '15', 100, 'fruta', './app/fotoHortaliza/tomate+fruta.jpg', NULL),
(4, 'remolacha', '10', 80, 'verdura', './app/fotoHortaliza/remolacha+verdura.jpg', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(19) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `sexo` varchar(50) NOT NULL,
  `puesto` varchar(50) NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `mail`, `clave`, `sexo`, `puesto`, `fecha_de_baja`) VALUES
(1, 'Lucas', 'Valdiviezo', 'lucas@lucas.com', '1234', 'masculino', 'admin', NULL),
(2, 'Martin', 'Bottani', 'martin@martin.com', '51xasd', 'masculino', 'vendedor', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(118) NOT NULL,
  `id_hortaliza` int(18) NOT NULL,
  `id_empleado` int(18) NOT NULL,
  `id_cliente` int(18) NOT NULL,
  `cantidad` int(50) NOT NULL,
  `total` varchar(50) NOT NULL,
  `fecha_venta` date NOT NULL,
  `foto` varchar(100) NOT NULL,
  `fecha_de_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `id_hortaliza`, `id_empleado`, `id_cliente`, `cantidad`, `total`, `fecha_venta`, `foto`, `fecha_de_baja`) VALUES
(1, 2, 1, 1, 5, '$24', '2021-06-14', './app/fotoVentas/2+Antonio.jpg', NULL),
(2, 1, 2, 1, 2, '$14', '2021-06-14', './app/fotoVentas/1+Antonio.jpg', NULL),
(3, 1, 2, 1, 2, '$14', '2021-06-14', './app/fotoVentas/1+Antonio.jpg', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `hortalizas`
--
ALTER TABLE `hortalizas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `hortalizas`
--
ALTER TABLE `hortalizas`
  MODIFY `id` int(18) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(19) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(118) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
