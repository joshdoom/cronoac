-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-07-2025 a las 04:04:32
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cronoac1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id_actividad` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_materia` int(11) NOT NULL,
  `objetivo` text DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `fecha_entrega` date NOT NULL,
  `valor` decimal(5,2) DEFAULT NULL,
  `estado` enum('Pendiente','En Progreso','Completada','No Entregada') NOT NULL DEFAULT 'Pendiente',
  `nota` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id_actividad`, `id_usuario`, `id_materia`, `objetivo`, `tipo`, `fecha_entrega`, `valor`, `estado`, `nota`) VALUES
(3, 4, 4, 'integrales', 'Examen', '2025-08-25', 20.00, 'Pendiente', NULL),
(4, 4, 6, 'Tabla periodica', 'Examen', '2025-07-25', 20.00, 'Completada', NULL),
(5, 4, 8, 'El auditor', 'Revista digital', '2025-07-24', 25.00, 'En Progreso', NULL),
(6, 4, 7, 'HTML y CSS', 'Examen', '2025-07-30', 20.00, 'Pendiente', NULL),
(7, 4, 9, 'Avance del sistema', 'Exposicion', '2025-07-23', 30.00, 'En Progreso', NULL),
(8, 4, 5, 'Ley de newton', 'Taller', '2025-07-20', 15.00, 'No Entregada', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_de_usuario`
--

CREATE TABLE `datos_de_usuario` (
  `id_datos_usuario` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `segundo_nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) NOT NULL,
  `segundo_apellido` varchar(50) DEFAULT NULL,
  `cedula_identidad` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `datos_de_usuario`
--

INSERT INTO `datos_de_usuario` (`id_datos_usuario`, `id_usuario`, `nombre`, `segundo_nombre`, `apellido`, `segundo_apellido`, `cedula_identidad`) VALUES
(3, 4, 'joshua', 'moises', 'vizcaino', 'canales', '28623901'),
(4, 5, 'marta', 'aleja', 'Dias', 'fernandez', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `id_materia` int(11) NOT NULL,
  `nombre_materia` varchar(150) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `materia`
--

INSERT INTO `materia` (`id_materia`, `nombre_materia`, `id_usuario`) VALUES
(4, 'Matematica', 4),
(5, 'fisica', 4),
(6, 'Quimica', 4),
(7, 'Programacion VI', 4),
(8, 'Auditoria informatica', 4),
(9, 'PST', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `Password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `correo`, `Password`) VALUES
(3, 'joshviz@gmail.com', '1234'),
(4, 'joshua.vizcaino30@gmail.com', '6b854b3909c76533779dee3b5fa39deab7da02d6868bc79cdc3598a0a3061895'),
(5, 'alejadias@gmail.com', '6f15118fec1647cc0b969e4bfd449de23b9662968fa6b3a927c24330195fb31f');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_materia` (`id_materia`);

--
-- Indices de la tabla `datos_de_usuario`
--
ALTER TABLE `datos_de_usuario`
  ADD PRIMARY KEY (`id_datos_usuario`),
  ADD UNIQUE KEY `cedula_identidad` (`cedula_identidad`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id_materia`),
  ADD UNIQUE KEY `nombre_materia` (`nombre_materia`),
  ADD KEY `materia_id_usuario_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id_actividad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `datos_de_usuario`
--
ALTER TABLE `datos_de_usuario`
  MODIFY `id_datos_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `id_materia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `actividades_ibfk_2` FOREIGN KEY (`id_materia`) REFERENCES `materia` (`id_materia`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `datos_de_usuario`
--
ALTER TABLE `datos_de_usuario`
  ADD CONSTRAINT `datos_de_usuario_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `materia`
--
ALTER TABLE `materia`
  ADD CONSTRAINT `materia_id_usuario_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
