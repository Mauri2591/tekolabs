-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-06-2025 a las 04:11:12
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
-- Base de datos: `ekolabs`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audios_paginas`
--

CREATE TABLE `audios_paginas` (
  `id` int(11) NOT NULL,
  `audio` varchar(255) DEFAULT NULL,
  `id_pagina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desafios`
--

CREATE TABLE `desafios` (
  `id` int(11) NOT NULL,
  `id_pagina` int(11) DEFAULT NULL,
  `id_nivel` int(11) DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `leyenda` varchar(2500) DEFAULT NULL,
  `id_vulnerabilidad_o_tematica` int(11) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_eliminacion` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `desafios`
--

INSERT INTO `desafios` (`id`, `id_pagina`, `id_nivel`, `id_estado`, `imagen`, `leyenda`, `id_vulnerabilidad_o_tematica`, `fecha_creacion`, `fecha_eliminacion`) VALUES
(283, 118, 1, 1, 'fde2d7b1d1f4200ce4cb8e8012c5c8bd3431d894.jpg', 'En medio de su escape por los oscuros pasillos del castillo, Lyon encuentra un terminal abandonado con acceso restringido. Ashley, desesperada por contactar con el exterior, le pide que reactive el sistema. Entre los archivos del sistema, Lyon nota que solo uno parece ejecutable por defecto, con una extensión peculiar que le da acceso a una antigua rutina de emergencia. Solo ese tipo de archivo puede iniciar la secuencia de rescate.', 37, '2025-06-09 23:47:39', NULL),
(284, 118, 2, 1, '1354b5dfc5515a87a92c5d9cd99e064215081b7c.jpg', 'Lyon y Ashley logran acceder a una vieja computadora militar conectada a una red interna abandonada. Los servidores locales contienen planos y archivos vitales, pero el tiempo apremia y deben replicar todo el contenido antes de que el sistema colapse. Solo una herramienta en consola les permite extraer rápidamente toda la estructura de la web remota para continuar su misión sin dejar rastro.', 39, '2025-06-09 23:47:39', NULL),
(285, 118, 3, 1, '5bd57cfbe56b33e2af69fcc87634c179b71b0912.jpg', 'Mientras investigaban un laboratorio oculto bajo el castillo, Lyon descubre una consola conectada a una base de datos protegida. Ashley advierte que las rutas están cifradas, pero un fallo en el sistema provoca mensajes de error detallados. Analizando cuidadosamente la salida del servidor, Lyon encuentra pistas ocultas entre las respuestas mal gestionadas del sistema. Cada fragmento revelado lo acerca a descubrir el acceso al núcleo del archivo de investigación de Umbrella.\n\n', 41, '2025-06-09 23:47:39', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id` int(11) NOT NULL,
  `estado` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `estado`) VALUES
(0, 'INACTIVO'),
(1, 'ACTIVO'),
(2, 'JUGANDO'),
(3, 'BLOQUEADO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `id` int(11) NOT NULL,
  `evento` varchar(255) DEFAULT NULL,
  `carpeta_imagen` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_evento` varchar(25) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`id`, `evento`, `carpeta_imagen`, `imagen`, `fecha_evento`, `id_estado`) VALUES
(13, 'EKOPARTY', '023392f367227089797b585a158e2d494652d92f', '023392f367227089797b585a158e2d494652d92f.webp', '2025-07-23', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `generos`
--

CREATE TABLE `generos` (
  `id` int(11) NOT NULL,
  `genero` varchar(255) DEFAULT NULL,
  `id_estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `generos`
--

INSERT INTO `generos` (`id`, `genero`, `id_estado`) VALUES
(1, 'SURVIVAL', 1),
(2, 'ACCION', 1),
(3, 'AVENTURA', 1),
(4, 'GUERRA', 1),
(5, 'COMEDIA', 1),
(6, 'ANIME', 1),
(7, 'FIGHT', 1),
(8, 'TERROR', 1),
(9, 'DEPORTE', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalidades`
--

CREATE TABLE `modalidades` (
  `id` int(11) NOT NULL,
  `modalidad` varchar(100) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modalidades`
--

INSERT INTO `modalidades` (`id`, `modalidad`, `id_estado`) VALUES
(1, 'CTF', 1),
(2, 'TRIVIA', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `niveles`
--

CREATE TABLE `niveles` (
  `id` int(11) NOT NULL,
  `nivel` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `niveles`
--

INSERT INTO `niveles` (`id`, `nivel`) VALUES
(1, 'FACIL'),
(2, 'MEDIO'),
(3, 'DIFICIL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paginas`
--

CREATE TABLE `paginas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `personaje_principal` varchar(100) DEFAULT NULL,
  `personaje_secundario` varchar(100) DEFAULT NULL,
  `id_evento` int(11) DEFAULT NULL,
  `id_genero` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `soundtrack` varchar(255) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_finalizacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paginas`
--

INSERT INTO `paginas` (`id`, `nombre`, `personaje_principal`, `personaje_secundario`, `id_evento`, `id_genero`, `id_estado`, `soundtrack`, `fecha_creacion`, `fecha_finalizacion`) VALUES
(118, 'RESIDENT EVIL 4', 'LYON', 'ASHLEY', 13, 1, 1, '8faa5796b5b1052ac8e06d717d7c3cb8aa08b68a.mp3', '2025-06-09 23:47:39', '2025-06-09 23:47:39');

--
-- Disparadores `paginas`
--
DELIMITER $$
CREATE TRIGGER `trg_deshab_pagina_desactiva_desafios` AFTER UPDATE ON `paginas` FOR EACH ROW BEGIN
    -- Solo ejecutar si el estado cambia a 0
    IF NEW.id_estado = 0 AND OLD.id_estado <> 0 THEN
        UPDATE desafios
        SET id_estado = 0
        WHERE id_pagina = NEW.id;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_insert_desafios_after_pagina` AFTER INSERT ON `paginas` FOR EACH ROW BEGIN
    INSERT INTO desafios (id_pagina, id_nivel, id_estado)
    VALUES 
        (NEW.id, 1, 1),  -- Fácil
        (NEW.id, 2, 1),  -- Medio
        (NEW.id, 3, 1);  -- Difícil
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_usuarios_ctf_vulnerable`
--

CREATE TABLE `roles_usuarios_ctf_vulnerable` (
  `id` int(11) NOT NULL,
  `rol` varchar(25) DEFAULT NULL,
  `estado` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_usuarios_ctf_vulnerable`
--

INSERT INTO `roles_usuarios_ctf_vulnerable` (`id`, `rol`, `estado`) VALUES
(1, 'ADMIN', 1),
(2, 'USER', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rutas_paginas`
--

CREATE TABLE `rutas_paginas` (
  `id` int(11) NOT NULL,
  `id_pagina` int(11) DEFAULT NULL,
  `nombre_carpeta` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_ctf_vulnerable`
--

CREATE TABLE `users_ctf_vulnerable` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` int(11) DEFAULT 1,
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users_ctf_vulnerable`
--

INSERT INTO `users_ctf_vulnerable` (`id`, `correo`, `password`, `rol`, `estado`) VALUES
(1, 'control', 'e10adc3949ba59abbe56e057f20f883e', 2, 1),
(2, 'logistica', 'c33367701511b4f6020ec61ded352059', 2, 1),
(3, 'provision', '401cec94d3ed586d8cb895c10c0f7db6', 2, 1),
(4, 'experimental', 'caff2749f2833128ee2d3fe5a61f8109', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users_ctf_vulnerable_2`
--

CREATE TABLE `users_ctf_vulnerable_2` (
  `id` int(11) NOT NULL,
  `correo` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` varchar(25) DEFAULT NULL,
  `sector` varchar(25) DEFAULT NULL,
  `estado` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users_ctf_vulnerable_2`
--

INSERT INTO `users_ctf_vulnerable_2` (`id`, `correo`, `password`, `rol`, `sector`, `estado`) VALUES
(1, 'ADMINISTRADOR', 'e10adc3949ba59abbe56e057f20f883e', 'ADMINISTRADOR', 'ADMINISTRACION', 'ACTIVO'),
(2, 'LOGISTICA', '2a9e6749f68d0a04f26a4210b0b4fd4f', 'USUARIO', 'LOGISTICA', 'ACTIVO'),
(3, 'CONTADURIA', 'f6585387e8d086a6cba5f6941a7301fa', 'USUARIO', 'CONTADURIA', 'ACTIVO'),
(4, 'DESPACHO', 'c719cfb45cd55ec9b080b40205d96b6b\n', 'USUARIO', 'DESPACHO', 'ACTIVO'),
(5, 'INVITADO', '\nNO POSEE', 'INVITADO', 'INVITADOS', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_desafios`
--

CREATE TABLE `usuarios_desafios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(255) DEFAULT NULL,
  `id_desafio` int(11) DEFAULT NULL,
  `id_version_paginas_eventos` int(11) DEFAULT NULL,
  `resolvio` varchar(2) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_desafios`
--

INSERT INTO `usuarios_desafios` (`id`, `usuario`, `id_desafio`, `id_version_paginas_eventos`, `resolvio`, `fecha_creacion`) VALUES
(237, 'mauriciorgonzalez25@gmail.com', 283, NULL, 'si', '2025-06-09 23:50:27'),
(238, 'prueba@gmail.com', 284, NULL, 'si', '2025-06-09 23:51:26'),
(239, 'mauriciorgonzalez25@gmail.com', 284, NULL, 'si', '2025-06-09 23:51:41'),
(240, 'ANONIMUS', 285, NULL, NULL, '2025-06-09 23:52:35'),
(241, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 11:14:30'),
(242, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 11:24:41'),
(243, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 11:25:24'),
(244, 'mrgonzalez@teco.com.ar', 285, NULL, NULL, '2025-06-10 11:47:36'),
(245, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 11:58:04'),
(246, 'gonzi@gmail.com', 285, NULL, NULL, '2025-06-10 11:58:38'),
(247, 'gonzi@gmail.com', 285, NULL, 'si', '2025-06-10 12:00:06'),
(248, 'prueba@gmial.com', 285, NULL, 'si', '2025-06-10 12:00:47'),
(249, 'ANONIMUS', 285, NULL, 'si', '2025-06-10 13:15:39'),
(250, 'ANONIMUS', 283, NULL, 'si', '2025-06-10 13:17:29'),
(251, 'mrgonzalez22222222@teco.com.ar', 284, NULL, NULL, '2025-06-10 13:26:20'),
(252, 'aaaaaaaaa@gmail.com', 284, NULL, NULL, '2025-06-10 13:32:56'),
(253, 'tiaMarta@gmail.com', 284, NULL, 'si', '2025-06-10 13:44:14'),
(254, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 13:44:48'),
(255, 'ANONIMUS', 285, NULL, 'si', '2025-06-10 13:53:31'),
(256, 'ANONIMUS', 285, NULL, 'si', '2025-06-10 13:55:47'),
(257, 'gonzi@gmail.com', 284, NULL, 'si', '2025-06-10 13:57:57'),
(258, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 14:11:12'),
(259, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 14:19:16'),
(260, 'carlos@gmail.com', 284, NULL, 'si', '2025-06-10 14:24:14'),
(261, 'ANONIMUS', 283, NULL, 'si', '2025-06-10 14:26:28'),
(262, 'ANONIMUS', 284, NULL, 'si', '2025-06-10 14:26:44'),
(263, 'mauriciorgonzalez25@gmail.com', 284, NULL, 'si', '2025-06-10 14:27:44'),
(264, 'ANONIMUS', 283, NULL, 'si', '2025-06-10 14:28:10'),
(265, 'mauriciorgonzalez25@gmail.com', 283, NULL, 'si', '2025-06-10 14:28:21'),
(266, 'BOCAJUNIORS@GMIAL.COM', 283, NULL, 'si', '2025-06-10 14:29:22'),
(267, 'carlacarla@gmial.com', 283, NULL, '', '2025-06-10 14:30:24'),
(268, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 15:30:30'),
(269, 'mauriciorgonzalez25@gmail.com', 285, NULL, NULL, '2025-06-10 15:31:08'),
(270, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 15:31:19'),
(271, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 15:31:52'),
(272, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 15:32:32'),
(273, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 15:33:19'),
(274, 'mauriciorgonzalez25@gmail.com', 285, NULL, NULL, '2025-06-10 15:39:16'),
(275, 'ANONIMUS', 284, NULL, NULL, '2025-06-10 15:39:57'),
(276, 'mauriciorgonzalez25@gmail.com', 284, NULL, 'si', '2025-06-10 19:47:35'),
(277, 'BOCAJUNIORS12@GMIAL.COM', 284, NULL, 'si', '2025-06-10 20:17:36'),
(278, 'ANONIMUS', 284, NULL, 'si', '2025-06-10 21:06:04'),
(279, 'ANONIMUS', 284, NULL, 'si', '2025-06-10 21:06:27'),
(280, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 21:27:49'),
(281, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 21:38:24'),
(282, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 21:39:11'),
(283, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 21:39:21'),
(284, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 21:41:48'),
(285, 'mauriciorgonzalez25@gmail.com', 285, NULL, NULL, '2025-06-10 21:45:46'),
(286, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 21:54:06'),
(287, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 21:55:26'),
(288, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 21:58:26'),
(289, 'mauriciorgonzalez25@gmail.com', 285, NULL, NULL, '2025-06-10 22:01:44'),
(290, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:02:00'),
(291, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:02:30'),
(292, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:11:17'),
(293, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:11:39'),
(294, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:11:59'),
(295, 'mauriciorgonzalez25@gmail.com', 285, NULL, NULL, '2025-06-10 22:13:29'),
(296, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:19:00'),
(297, 'mauriciorgonzalez25@gmail.com', 285, NULL, NULL, '2025-06-10 22:21:30'),
(298, 'mauriciorgonzalez25@gmail.com', 285, NULL, NULL, '2025-06-10 22:21:42'),
(299, 'mrgonzalez@teco.com.ar', 284, NULL, 'si', '2025-06-10 22:22:36'),
(300, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:26:05'),
(301, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:27:35'),
(302, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:28:33'),
(303, 'ANONIMUS', 285, NULL, NULL, '2025-06-10 22:30:26'),
(304, 'ANONIMUS', 284, NULL, NULL, '2025-06-10 22:30:51'),
(305, 'ANONIMUS', 284, NULL, NULL, '2025-06-10 22:31:15'),
(306, 'ANONIMUS', 283, NULL, 'si', '2025-06-10 22:31:50'),
(307, 'macri@gmail.com', 283, NULL, 'si', '2025-06-10 22:44:07'),
(308, 'ANONIMUS', 283, NULL, 'si', '2025-06-10 22:46:50'),
(309, 'ANONIMUS', 283, NULL, 'si', '2025-06-10 22:46:59'),
(310, 'ANONIMUS', 283, NULL, 'si', '2025-06-10 22:47:16'),
(311, 'ANONIMUS', 283, NULL, '', '2025-06-10 22:47:26'),
(312, 'ANONIMUS', 283, NULL, '', '2025-06-10 22:47:57'),
(313, 'ariana@teco.com.ar', 283, NULL, 'si', '2025-06-10 22:53:07'),
(314, 'ANONIMUS', 284, NULL, 'si', '2025-06-10 22:55:18'),
(315, 'ANONIMUS', 284, NULL, '', '2025-06-10 22:57:21'),
(316, 'ANONIMUS', 285, NULL, 'si', '2025-06-10 22:59:25'),
(317, 'ANONIMUS', 285, NULL, '', '2025-06-10 23:02:30'),
(318, 'jajaja@gmail.com', 285, NULL, 'si', '2025-06-10 23:02:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_gestion`
--

CREATE TABLE `usuarios_gestion` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `usuario` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_estados` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_gestion`
--

INSERT INTO `usuarios_gestion` (`id`, `nombre`, `usuario`, `password`, `id_estados`) VALUES
(1, 'Mauricio Raul', 'mrgonzalez@teco.com.ar', '$2y$10$mZ48zGP9Dklck1Jjdd0gxuZDHKNkQdGDRjJZPLhod2hBiFb/6SqYK', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `version_paginas_eventos`
--

CREATE TABLE `version_paginas_eventos` (
  `id` int(11) NOT NULL,
  `fk_pagina` int(11) DEFAULT NULL,
  `fk_evento` int(11) DEFAULT NULL,
  `fecha_version_evento` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vulnerabilidades_o_tematicas`
--

CREATE TABLE `vulnerabilidades_o_tematicas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` longtext DEFAULT NULL,
  `solucion` varchar(1000) DEFAULT NULL,
  `ayuda` varchar(255) DEFAULT NULL,
  `cve` varchar(50) DEFAULT NULL,
  `id_modalidad` int(11) DEFAULT NULL,
  `archivo_php` varchar(50) DEFAULT NULL,
  `id_nivel` int(11) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `vulnerabilidades_o_tematicas`
--

INSERT INTO `vulnerabilidades_o_tematicas` (`id`, `nombre`, `descripcion`, `solucion`, `ayuda`, `cve`, `id_modalidad`, `archivo_php`, `id_nivel`, `id_estado`) VALUES
(1, 'Herramienta útil para escanear redes en busca de puertos y servicios', '¿Que herramienta es útil para escanear redes en busca de puertos y servicios activos?<br>\n<select id=\"inputSolucion\">\n    <option value=\"0\">SELECCIONE</option>\n    <option value=\"1\">NMAP</option>\n    <option value=\"2\">BURPSUITE</option>\n    <option value=\"3\">KALI</option>\n</select>\n<br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>\n', '1', NULL, NULL, 2, NULL, 1, 1),
(2, '¿Nombre del exploit de SMBv1?', 'Nombre del exploit de SMBv1 filtrado por Shadow Brokers, usado en WannaCry y EternalRocks:<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"********\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>\n', 'eternalblue', NULL, 'CVE-2017–0144', 2, NULL, 1, 1),
(3, 'Xss-alert', 'Este campo es vulnerable solo a ciertos comandos. ¿Sabés usar JavaScript?<br><br>\n<input type=\"text\" name=\"xssSimple\" id=\"xssSimple\">\n<br><br>\n<button id=\"btn_enviar_vuln_3\">Enviar</button>\n\n<script>\nlet i = 0;\n\ndocument.getElementById(\"btn_enviar_vuln_3\").addEventListener(\"click\", function() {\n    const respuesta = document.getElementById(\"xssSimple\").value.trim().toLowerCase();\n\n    const regex = /^alert\\s*\\(\\s*.*\\s*\\)$/;\n\n    if (i === 0 && regex.test(respuesta)) {\n        i++;\n\n        alert();\n\n        $.ajax({\n            type: \"POST\",\n            url: \"../controllers/ctrDesafios.php?case=update_estado_esafio\",\n            data: {\n                id: ID_DESAFIO,\n                id_estado: 0\n            },\n            dataType: \"json\",\n            success: function(response) {\n                Swal.fire({\n                    title: \'Felicitaciones!\',\n                    text: \'Si lo deseas, ingresá tu correo electrónico para rankearte\',\n                    input: \'email\',\n                    inputPlaceholder: \'ejemplo@correo.com\',\n                    confirmButtonText: \'Enviar\',\n                    showCancelButton: true,\n                    cancelButtonText: \'Cancelar\',\n                    inputValidator: (value) => {\n                        if (!value) {\n                            return \'¡Debés ingresar un correo!\';\n                        }\n                    }\n                }).then((result) => {\n                    if (result.isConfirmed) {\n                        const email = result.value;\n                        Swal.fire({\n                            icon: \'success\',\n                            title: \'Correo recibido\',\n                            text: `Gracias por participar`,\n                            timer: 2500,\n                            showConfirmButton: false\n                        });\n\n                        $.post(\"../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos\",\n                            function(data, textStatus, jqXHR) {\n                                let ID_VERSION_PAGINAS_EVENTOS = data.id\n                                $.post(\"../controllers/ctrDesafios.php?case=inserar_usuario_desafio\", {\n                                    usuario: email,\n                                    id_desafio: ID_DESAFIO,\n                                    id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS\n                                }, function(data) {\n                                    setTimeout(() => {\n                                        window.location.replace(\n                                            \'../sessionDestroy.php\');\n                                    }, 2500);\n                                }, \"json\");\n                            },\n                            \"json\"\n                        );\n                    } else {\n                        window.location.replace(\'../sessionDestroy.php\');\n                    }\n                });\n            },\n            error: function(err) {\n                console.log(err);\n            }\n        });\n\n        $.post(\"../controllers/ctrDesafios.php?case=update_estados_desafios\", {\n                id: ID_DESAFIO,\n                id_estado: 1\n            },\n            function(data, textStatus, jqXHR) {\n\n            },\n            \"json\"\n        );\n\n    } else {\n        $.ajax({\n            type: \"POST\",\n            url: \"../controllers/ctrDesafios.php?case=update_estado_esafio\",\n            data: {\n                id: ID_DESAFIO,\n                id_estado: 1\n            },\n            dataType: \"json\",\n            success: function(response) {\n                Swal.fire({\n                    icon: \"error\",\n                    title: \"Perdiste\",\n                    text: \"Respuesta incorrecta o ya jugaste.\",\n                    timer: 2000,\n                    showConfirmButton: false\n                }).then(() => {\n                    localStorage.removeItem(\"expira_desafio\");\n                    window.location.replace(\"../cerrar_sesion.php\");\n                });\n            },\n            error: function(err) {\n                console.log(err);\n            }\n        });\n        $.post(\"../controllers/ctrDesafios.php?case=update_estados_desafios\", {\n                id: ID_DESAFIO,\n                id_estado: 1\n            },\n            function(data, textStatus, jqXHR) {\n\n            },\n            \"json\"\n        );\n    }\n});\n</script>', 'alert()', NULL, NULL, 1, NULL, 2, 1),
(4, 'Xss-con-etiquetas', 'Este campo es vulnerable a cierto comando que se ejecuta dentro de sus etiquetas.<br><br>\n<input type=\"text\" name=\"xssConEtiquetas\" id=\"xssConEtiquetas\" placeholder=\"<******>*****()</******>\">\n\n<br><br>\n<button id=\"btn_enviar_vuln_4\">Enviar</button>\n\n<script>\nlet i = 0;\n\ndocument.getElementById(\"btn_enviar_vuln_4\").addEventListener(\"click\", function() {\n    const respuesta = document.getElementById(\"xssConEtiquetas\").value.trim().toLowerCase();\n\nconst regex = /^<script>\\s*alert\\s*\\(\\s*\\)\\s*<\\/script>$/i;\n\n    if (i === 0 && regex.test(respuesta)) {\n        i++;\n\n        alert();\n\n        $.ajax({\n            type: \"POST\",\n            url: \"../controllers/ctrDesafios.php?case=update_estado_esafio\",\n            data: {\n                id: ID_DESAFIO,\n                id_estado: 0\n            },\n            dataType: \"json\",\n            success: function(response) {\n                Swal.fire({\n                    title: \'Felicitaciones!\',\n                    text: \'Si lo deseas, ingresá tu correo electrónico para rankearte\',\n                    input: \'email\',\n                    inputPlaceholder: \'ejemplo@correo.com\',\n                    confirmButtonText: \'Enviar\',\n                    showCancelButton: true,\n                    cancelButtonText: \'Cancelar\',\n                    inputValidator: (value) => {\n                        if (!value) {\n                            return \'¡Debés ingresar un correo!\';\n                        }\n                    }\n                }).then((result) => {\n                    if (result.isConfirmed) {\n                        const email = result.value;\n                        Swal.fire({\n                            icon: \'success\',\n                            title: \'Correo recibido\',\n                            text: `Gracias por participar`,\n                            timer: 2500,\n                            showConfirmButton: false\n                        });\n\n                        $.post(\"../controllers/ctrDesafios.php?case=get_ultimo_valor_version_paginas_eventos\",\n                            function(data, textStatus, jqXHR) {\n                                let ID_VERSION_PAGINAS_EVENTOS = data.id\n                                $.post(\"../controllers/ctrDesafios.php?case=inserar_usuario_desafio\", {\n                                    usuario: email,\n                                    id_desafio: ID_DESAFIO,\n                                    id_version_paginas_eventos: ID_VERSION_PAGINAS_EVENTOS\n                                }, function(data) {\n                                    setTimeout(() => {\n                                        window.location.replace(\n                                            \'../sessionDestroy.php\');\n                                    }, 2500);\n                                }, \"json\");\n                            },\n                            \"json\"\n                        );\n                    } else {\n                        window.location.replace(\'../sessionDestroy.php\');\n                    }\n                });\n            },\n            error: function(err) {\n                console.log(err);\n            }\n        });\n\n        $.post(\"../controllers/ctrDesafios.php?case=update_estados_desafios\", {\n                id: ID_DESAFIO,\n                id_estado: 1\n            },\n            function(data, textStatus, jqXHR) {\n\n            },\n            \"json\"\n        );\n\n    } else {\n        $.ajax({\n            type: \"POST\",\n            url: \"../controllers/ctrDesafios.php?case=update_estado_esafio\",\n            data: {\n                id: ID_DESAFIO,\n                id_estado: 1\n            },\n            dataType: \"json\",\n            success: function(response) {\n                Swal.fire({\n                    icon: \"error\",\n                    title: \"Perdiste\",\n                    text: \"Respuesta incorrecta o ya jugaste.\",\n                    timer: 2000,\n                    showConfirmButton: false\n                }).then(() => {\n                    localStorage.removeItem(\"expira_desafio\");\n                    window.location.replace(\"../cerrar_sesion.php\");\n                });\n            },\n            error: function(err) {\n                console.log(err);\n            }\n        });\n        $.post(\"../controllers/ctrDesafios.php?case=update_estados_desafios\", {\n                id: ID_DESAFIO,\n                id_estado: 1\n            },\n            function(data, textStatus, jqXHR) {\n\n            },\n            \"json\"\n        );\n    }\n});\n</script>', '<script>alert()</script>', NULL, NULL, 1, NULL, 3, 0),
(5, 'Xss-reflejado', 'El uso de etiquetas HTML puede revelar el uso de otras Etiquetas', NULL, NULL, NULL, 1, 'XssReflejado.php', 3, 1),
(6, '¿Nombre de proxy para analisis de vulnerabilidades en Sitios Web?', 'Nombre de proxy para analisis de vulnerabilidades en Sitios Web?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"*********\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'BurpSuite', NULL, NULL, 2, NULL, 1, 1),
(7, '¿Comando de Linux para ver interfaces de red activas?', '¿Comando de Linux para ver interfaces de red activas?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"********\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'ifconfig', NULL, NULL, 2, NULL, 1, 1),
(8, '¿Nombre del protocolo inseguro para transferencia de archivos?', '¿Nombre del protocolo inseguro para transferencia de archivos?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"***\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'ftp', NULL, NULL, 2, NULL, 1, 1),
(9, '¿Herramienta para capturar paquetes y analizar tráfico de red?', '¿Herramienta para capturar paquetes y analizar tráfico de red?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"*********\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'Wireshark', NULL, NULL, 2, NULL, 1, 1),
(10, '¿Cuál es el nombre del archivo de configuración principal del servidor Apache en sistemas Debian/Ubuntu?', '    ¿Cuál es el nombre del archivo de configuración principal del servidor Apache en sistemas Debian/Ubuntu?\n    <br>\n    <input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"******.****\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'apache2.conf', NULL, NULL, 2, NULL, 1, 1),
(11, '¿Puerto por defecto del servicio SSH?', '¿Puerto por defecto del servicio SSH?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"**\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', '22', NULL, NULL, 2, NULL, 1, 1),
(12, '¿Qué herramienta de Kali permite romper hashes con GPU?', '¿Qué herramienta de Kali permite romper hashes con GPU?<br> <input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"h*****t\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'hashcat', NULL, NULL, 2, NULL, 2, 1),
(13, '¿Nombre del archivo donde se definen los usuarios en Linux?', '¿Nombre del archivo donde se definen los usuarios en Linux?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"/***/*****\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', '/etc/passwd', NULL, NULL, 2, NULL, 2, 1),
(14, '¿Nombre de la vulnerabilidad que permite ejecución remota vía Log4j?', '¿Nombre de la vulnerabilidad que permite ejecución remota vía Log4j?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"*********\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'Log4Shell', NULL, NULL, 2, NULL, 2, 1),
(15, '¿Nombre de herramienta que permite enviar peticiones HTTP desde consola?', '¿Nombre de herramienta que permite enviar peticiones HTTP desde consola?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"****\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'curl', NULL, NULL, 2, NULL, 1, 1),
(16, '¿Nombre del servicio que corre en el puerto 80?', '¿Nombre del servicio que corre en el puerto 80?<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"****\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'http', NULL, NULL, 2, NULL, 1, 1),
(17, '¿Que extensión tienen los archivos de scripts en Bash?', 'Que extensión tienen los archivos de scripts en Bash?\n<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\".**\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', '.sh', NULL, NULL, 2, NULL, 1, 1),
(18, '¿Que comando se usa para buscar texto dentro de archivos?', '¿Que comando se usa para buscar texto dentro de archivos?<br>\n<input id=\"inputSolucion\" style=\"text-align:center\" type=\"text\" placeholder=\"g***\"><br><button onclick=\"funcionEnviarRespuesta()\" id=\"id=\"btnSolucion\"\">Enviar</button>', 'grep', NULL, NULL, 2, NULL, 2, 1),
(19, '¿Que hace un ataque de tipo “command injection”?', '¿Que hace un ataque de tipo “command injection”? Seleccione la opcion correcta:<br><br><br>\n\n  <div>\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n    <label for=\"opt1\">Inyecta comandos para ejecución arbitraria en el sistema.</label>\n  </div>\n\n  <div>\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n    <label for=\"opt2\">Instala un ransomware en la víctima.</label>\n  </div>\n\n  <div>\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\">Interrumpe el tráfico de red por flooding.</label>\n  </div>\n\n  <div>\n    <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"d\" />\n    <label for=\"opt4\">Borra el disco duro remotamente.</label>\n  </div>\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>\n\n\n', 'a', NULL, NULL, 2, NULL, 3, 1),
(20, 'Sqli-error', 'Una dosis equivocada puede revelar el dbms...', 'MariaDB ', '( \' ) este caracter puede ser de gran ayuda...', NULL, 1, 'sqliBoolObtenerDBMS.php', 2, 1),
(21, 'Information-Disclousure - MAIL_PASSWORD-publica', 'Information Disclosure se caracteriza por exponer archivos mal configurados que contienen datos sensibles. Tu objetivo es simple: encontrá el MAIL_PASSWORD. Solo eso. Nada más...', 'ZDgyNmRjYmQ3MTc4', NULL, NULL, 1, 'InformationDisclousureEnv.php', 3, 1),
(22, 'Curl-envio-peticion_header-y-body', 'Debes enviar una solicitud al endpoint usando únicamente curl, utilizando las opciones abreviadas -H para el encabezado y -d para los datos en formato JSON. Para obtener todos los usuarios, el valor del parámetro user es \"all\". Una vez recibida la respuesta, identificá el username con id=2.', 'shellshock', NULL, NULL, 1, 'CurlEnvioPeticionHeaderYBody.php', 3, 1),
(23, 'Curl-obtener-pass-admin', '\"Debes enviar una consulta al endpoint de la URL utilizando únicamente curl, incluyendo correctamente el Header y los datos. Esta aplicación acepta contenido en formato JSON, y en el body se deben enviar parámetros como user: \"all\" y rol: \"admin\" para acceder a los usuarios administradores.', 'ee11cbb19052e40b07aac0ca060c23ee', NULL, NULL, 1, 'CurlObtenerPassAdmin.php', 3, 1),
(24, 'Path-traversal', 'Este sitio es vulnerable a Path Traversal. Sabiendo ello, cuantas shell interactivas posee el archivo /etc/passwd?', '3', '../../../../../../etc/passwd', NULL, 1, 'PathTraversal.php', 2, 1),
(25, '¿Que tipo de codigo http corresponde a un redireccion?', '¿Que tipo de codigo http corresponde a un \"redireccion\"? Seleccione la opcion correcta:<br><br><br>\n\n  <div>\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n    <label for=\"opt1\">http code 100</label>\n  </div>\n\n  <div>\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n    <label for=\"opt2\">http code 200</label>\n  </div>\n\n  <div>\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\">http code 300</label>\n  </div>\n\n  <div>\n    <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"d\" />\n    <label for=\"opt4\">http code 400</label>\n  </div>\n\n  <div>\n    <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"d\" />\n    <label for=\"opt4\">http code 500</label>\n  </div>\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>\n\n\n', 'c', NULL, NULL, 2, NULL, 1, 1),
(26, '¿Que opcion es la correcta para el crackeo de hashes MD5 con john?', '¿Que opcion es la correcta para el crackeo de hashes MD5 con john?<br><br><br>\n\n  <div>\n  <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n  <label for=\"opt1\">john --wordlist=/usr/share/wordlists/rockyou.txt --format=raw-md4 hashes.txt</label>\n</div>\n\n<div>\n  <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n  <label for=\"opt2\">format= --wordlist=/usr/share/wordlists/rockyou.txt --john=raw-md5 hashes.txt</label>\n</div>\n\n<div>\n  <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n  <label for=\"opt3\">john --wordlist=/usr/share/wordlists/rockyou.txt --format=raw-md5 hashes.zip</label>\n</div>\n\n<div>\n  <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"d\" />\n  <label for=\"opt4\">john --wordlist=/usr/share/wordlists/rockyou.txt --format=raw-md5 hashes.txt</label>\n</div>\n\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>\n\n\n', 'd', NULL, NULL, 2, NULL, 3, 1),
(27, '¿Seleccione el tipo de hash que se considera insegura para datos sensibles?', '¿Seleccione el tipo de hash que se considera insegura para datos sensibles?<br><br><br>\n\n<div>\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n    <label for=\"opt1\">SHA-256</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\" style=\"padding:0 .2rem\">BCRYPT</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n    <label for=\"opt2\">SHA-512</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\" style=\"padding:0 1.1rem\">MD5</label>\n</div>\n\n\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'c', NULL, NULL, 2, NULL, 2, 1),
(28, 'Wget-descargar-sitio', 'Utilizá la herramienta wget para descargar el contenido del sitio http://anonymous.com e ingresá en la respuesta la respuesta codificada brindada por el equipo de Ethical Hacking', 'R0FOQVNURQ', 'wget URL', NULL, 1, 'WgetSitio.php', 2, 1),
(29, '¿El servicio FTP cuenta con la opción usuario anonimo?', '¿El servicio FTP cuenta con la opción usuario anonimo?\n\n<fieldset>\n  <div>\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n    <label for=\"opt1\">SI</label>\n  </div>\n  <div>\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n    <label for=\"opt2\">NO</label>\n  </div>\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'a', NULL, NULL, 2, NULL, 1, 1),
(30, '¿Qué es Meterpreter en el contexto de pruebas de penetración?', '¿Qué es Meterpreter en el contexto de pruebas de penetración?\n<br><br><br>\n\n<div>\n  <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n  <label for=\"opt2\">Un protocolo de transferencia de archivos para sistemas Linux</label>\n</div>\n<div>\n  <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n  <label for=\"opt1\">Un shell avanzado que se ejecuta en memoria utilizado por Metasploit</label>\n</div>\n<div>\n  <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n  <label for=\"opt3\">Un escáner de puertos usado para identificar servicios expuestos</label>\n</div>\n\n<div>\n  <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"d\" />\n  <label for=\"opt4\">Un firewall de red que bloquea conexiones maliciosas</label>\n</div>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>\n\n', 'a', NULL, NULL, 2, NULL, 2, 1),
(31, 'Sqli-Basado-En-Error-Con-Recuperacion-Datos', 'El sistema está devolviendo únicamente la cuenta de invitado. Aplicá una operacion booleana verdadera utilizando por ejemplo el operador OR para que te permita revelar todas las cuentas del sistema. Tu objetivo es encontrar el hash de la cuenta \"administrador\".', 'e10adc3949ba59abbe56e057f20f883e', ' OR 1=1 --', NULL, 1, 'SqliBasadoEnErrorObtenerPassAdmin.php', 3, 1),
(32, 'Rce-Obtener-User-Apache', 'El sistema es vulnerable a la Ejecución Remota de Comandos. Debes conocer cual es el usuario del Sistema Operativo que utiliza Apache?', 'www-data', 'Comandos comunes: whoami, ls, id, groups', NULL, 1, 'RceObtenerUserApache.php', 2, 1),
(33, '¿Qué vulnerabilidad implica engañar al navegador del usuario para enviar solicitudes no deseadas?', '¿Qué vulnerabilidad implica engañar al navegador del usuario para enviar solicitudes no deseadas?<br><br><br>\n\n<div>\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n    <label for=\"opt1\">Clickjacking</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\">CSRF</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n    <label for=\"opt2\">IDOR</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\">XSS</label>\n</div>\n\n\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'b', NULL, NULL, 2, NULL, 2, 1),
(34, '¿Qué directorio oculto suele exponer todo el código fuente si no se protege?', '¿Qué directorio oculto suele exponer todo el código fuente si no se protege?<br><br><br>\r\n\r\n<div>\r\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\r\n    <label for=\"opt1\">public/</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"c\" />\r\n    <label for=\"opt3\">.git</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\r\n    <label for=\"opt2\">.env</label>\r\n</div>\r\n\r\n</fieldset>\r\n\r\n<br>\r\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'c', NULL, NULL, 2, NULL, 2, 1),
(35, '¿Qué patrón de texto se usa en ataques de Path Traversal?', '¿Qué patrón de texto se usa en ataques de Path Traversal?<br><br><br>\r\n\r\n<div>\r\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\r\n    <label for=\"opt1\"><<<</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"b\" />\r\n    <label for=\"opt3\">:::</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"c\" />\r\n    <label for=\"opt2\">../</label>\r\n</div>\r\n\r\n</fieldset>\r\n\r\n<br>\r\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'c', NULL, NULL, 2, NULL, 1, 1),
(36, '¿Qué protocolo se utiliza para traducir nombres de dominio a IPs?', '¿Qué protocolo se utiliza para traducir nombres de dominio a IPs?<br><br><br>\n\n<div>\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n    <label for=\"opt1\">ARP</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n    <label for=\"opt2\">DHCP</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\">DNS</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt4\" name=\"respuesta\" value=\"d\" />\n    <label for=\"opt4\">SNMP</label>\n</div>\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'c', NULL, NULL, 2, NULL, 1, 1),
(37, '¿Qué firewall de Linux permite crear reglas con iptables facilmente?', '¿Qué firewall de Linux permite crear reglas con iptables facilmente?<br><br><br>\n\n<div>\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n    <label for=\"opt1\">nftables</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n    <label for=\"opt2\">iptables</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\">ufw</label>\n</div>\n\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'c', NULL, NULL, 2, NULL, 1, 1),
(38, '¿Qué hace un WAF?', '¿Qué hace un WAF?<br><br><br>\r\n\r\n<div>\r\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\r\n    <label for=\"opt1\">Protege Aplicaciones Web</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\r\n    <label for=\"opt2\">Cifra la comunicación</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\r\n    <label for=\"opt3\">Ingenieria Social</label>\r\n</div>\r\n\r\n</fieldset>\r\n\r\n<br>\r\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'a', NULL, NULL, 2, NULL, 2, 1),
(39, '¿Qué archivo en Linux contiene información de inicio de sesión fallida?', '¿Qué archivo en Linux contiene información de inicio de sesión fallida?<br><br><br>\r\n\r\n<div>\r\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\r\n    <label for=\"opt1\">/etc/passwd</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\r\n    <label for=\"opt2\">/var/log/auth.log</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\r\n    <label for=\"opt3\">/etc/shadow</label>\r\n</div>\r\n\r\n</fieldset>\r\n\r\n<br>\r\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'b', NULL, NULL, 2, NULL, 2, 1),
(40, '¿Qué servicio detecta patrones de fuerza bruta para bloquear IPs automáticamente?', '¿Qué servicio detecta patrones de fuerza bruta para bloquear IPs automáticamente?<br><br><br>\r\n\r\n<div>\r\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\r\n    <label for=\"opt1\">iptables</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\r\n    <label for=\"opt2\">fail2ban</label>\r\n</div>\r\n<div>\r\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\r\n    <label for=\"opt3\">netcat</label>\r\n</div>\r\n\r\n</fieldset>\r\n\r\n<br>\r\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'b', NULL, NULL, 2, NULL, 3, 1),
(41, '¿Qué comando de Nmap ejecuta los scripts por defecto para detectar servicios y posibles vulnerabilidades conocidas?', '¿Qué comando de Nmap ejecuta los scripts por defecto para detectar servicios y posibles vulnerabilidades conocidas?<br><br><br>\n\n<div>\n    <input type=\"radio\" id=\"opt1\" name=\"respuesta\" value=\"a\" />\n    <label for=\"opt1\">nmap -sV</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt2\" name=\"respuesta\" value=\"b\" />\n    <label for=\"opt2\">nmap -O</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"c\" />\n    <label for=\"opt3\">nmap -sC</label>\n</div>\n<div>\n    <input type=\"radio\" id=\"opt3\" name=\"respuesta\" value=\"d\" />\n    <label for=\"opt3\">nmap -Pn</label>\n</div>\n</fieldset>\n\n<br>\n<button onclick=\"funcionEnviarRespuesta()\" id=\"btnSolucion\">Enviar</button>', 'c', NULL, NULL, 2, NULL, 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audios_paginas`
--
ALTER TABLE `audios_paginas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_audios_paginas_paginas` (`id_pagina`);

--
-- Indices de la tabla `desafios`
--
ALTER TABLE `desafios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_desafios_paginas` (`id_pagina`),
  ADD KEY `fk_desafios_niveles` (`id_nivel`),
  ADD KEY `fk_desafios_estados` (`id_estado`),
  ADD KEY `fk_desafios_vulnerabilidades_o_tematicas` (`id_vulnerabilidad_o_tematica`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_eventos_estados` (`id_estado`);

--
-- Indices de la tabla `generos`
--
ALTER TABLE `generos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_generos_estado` (`id_estado`);

--
-- Indices de la tabla `modalidades`
--
ALTER TABLE `modalidades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_modalidades_estados` (`id_estado`);

--
-- Indices de la tabla `niveles`
--
ALTER TABLE `niveles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `paginas`
--
ALTER TABLE `paginas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_paginas_estado` (`id_estado`),
  ADD KEY `fk_paginas_generos` (`id_genero`);

--
-- Indices de la tabla `roles_usuarios_ctf_vulnerable`
--
ALTER TABLE `roles_usuarios_ctf_vulnerable`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `rutas_paginas`
--
ALTER TABLE `rutas_paginas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_carpetas_paginas_paginas` (`id_pagina`);

--
-- Indices de la tabla `users_ctf_vulnerable`
--
ALTER TABLE `users_ctf_vulnerable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_users_ctf_roles_usuarios_ctf` (`rol`);

--
-- Indices de la tabla `users_ctf_vulnerable_2`
--
ALTER TABLE `users_ctf_vulnerable_2`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios_desafios`
--
ALTER TABLE `usuarios_desafios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios_desafios_desafios` (`id_desafio`),
  ADD KEY `fk_usuarios_desafios_version_paginas_eventos` (`id_version_paginas_eventos`);

--
-- Indices de la tabla `usuarios_gestion`
--
ALTER TABLE `usuarios_gestion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuarios_gestion_estados` (`id_estados`);

--
-- Indices de la tabla `version_paginas_eventos`
--
ALTER TABLE `version_paginas_eventos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_version_paginas_eventos_fk_pagina` (`fk_pagina`),
  ADD KEY `fk_version_paginas_eventos_evento` (`fk_evento`);

--
-- Indices de la tabla `vulnerabilidades_o_tematicas`
--
ALTER TABLE `vulnerabilidades_o_tematicas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_modalidad` (`id_modalidad`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `fk_vulnerabilidades_o_tematicas_niveles` (`id_nivel`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audios_paginas`
--
ALTER TABLE `audios_paginas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `desafios`
--
ALTER TABLE `desafios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=286;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `generos`
--
ALTER TABLE `generos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `modalidades`
--
ALTER TABLE `modalidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `niveles`
--
ALTER TABLE `niveles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `paginas`
--
ALTER TABLE `paginas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT de la tabla `roles_usuarios_ctf_vulnerable`
--
ALTER TABLE `roles_usuarios_ctf_vulnerable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `rutas_paginas`
--
ALTER TABLE `rutas_paginas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users_ctf_vulnerable`
--
ALTER TABLE `users_ctf_vulnerable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users_ctf_vulnerable_2`
--
ALTER TABLE `users_ctf_vulnerable_2`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios_desafios`
--
ALTER TABLE `usuarios_desafios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=319;

--
-- AUTO_INCREMENT de la tabla `usuarios_gestion`
--
ALTER TABLE `usuarios_gestion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `version_paginas_eventos`
--
ALTER TABLE `version_paginas_eventos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT de la tabla `vulnerabilidades_o_tematicas`
--
ALTER TABLE `vulnerabilidades_o_tematicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `audios_paginas`
--
ALTER TABLE `audios_paginas`
  ADD CONSTRAINT `fk_audios_paginas_paginas` FOREIGN KEY (`id_pagina`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `desafios`
--
ALTER TABLE `desafios`
  ADD CONSTRAINT `fk_desafios_estados` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id`),
  ADD CONSTRAINT `fk_desafios_niveles` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_desafios_paginas` FOREIGN KEY (`id_pagina`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_desafios_vulnerabilidades_o_tematicas` FOREIGN KEY (`id_vulnerabilidad_o_tematica`) REFERENCES `vulnerabilidades_o_tematicas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD CONSTRAINT `fk_eventos_estados` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `generos`
--
ALTER TABLE `generos`
  ADD CONSTRAINT `fk_generos_estado` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `modalidades`
--
ALTER TABLE `modalidades`
  ADD CONSTRAINT `fk_modalidades_estados` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `paginas`
--
ALTER TABLE `paginas`
  ADD CONSTRAINT `fk_paginas_estado` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_paginas_generos` FOREIGN KEY (`id_genero`) REFERENCES `generos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `rutas_paginas`
--
ALTER TABLE `rutas_paginas`
  ADD CONSTRAINT `fk_carpetas_paginas_paginas` FOREIGN KEY (`id_pagina`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `users_ctf_vulnerable`
--
ALTER TABLE `users_ctf_vulnerable`
  ADD CONSTRAINT `fk_users_ctf_roles_usuarios_ctf` FOREIGN KEY (`rol`) REFERENCES `roles_usuarios_ctf_vulnerable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios_desafios`
--
ALTER TABLE `usuarios_desafios`
  ADD CONSTRAINT `fk_usuarios_desafios_desafios` FOREIGN KEY (`id_desafio`) REFERENCES `desafios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuarios_desafios_version_paginas_eventos` FOREIGN KEY (`id_version_paginas_eventos`) REFERENCES `version_paginas_eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios_gestion`
--
ALTER TABLE `usuarios_gestion`
  ADD CONSTRAINT `fk_usuarios_gestion_estados` FOREIGN KEY (`id_estados`) REFERENCES `estados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `version_paginas_eventos`
--
ALTER TABLE `version_paginas_eventos`
  ADD CONSTRAINT `fk_version_paginas_eventos_evento` FOREIGN KEY (`fk_evento`) REFERENCES `eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_version_paginas_eventos_fk_pagina` FOREIGN KEY (`fk_pagina`) REFERENCES `paginas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `vulnerabilidades_o_tematicas`
--
ALTER TABLE `vulnerabilidades_o_tematicas`
  ADD CONSTRAINT `fk_vulnerabilidades_o_tematicas_niveles` FOREIGN KEY (`id_nivel`) REFERENCES `niveles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vulnerabilidades_o_tematicas_ibfk_1` FOREIGN KEY (`id_modalidad`) REFERENCES `modalidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `vulnerabilidades_o_tematicas_ibfk_2` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
