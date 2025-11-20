-- Base de datos para el sistema de promociones INDEL
-- Ejecutar este script en MySQL/MariaDB para crear la tabla

CREATE DATABASE IF NOT EXISTS `indel_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `indel_db`;

-- Tabla de promociones
CREATE TABLE IF NOT EXISTS `promociones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `orden` int(11) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_activo` (`activo`),
  KEY `idx_fecha_fin` (`fecha_fin`),
  KEY `idx_orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar algunos datos de ejemplo (opcional)
INSERT INTO `promociones` (`titulo`, `descripcion`, `imagen`, `fecha_inicio`, `fecha_fin`, `activo`, `orden`) VALUES
('Promoción Especial', 'Descripción de la promoción especial', 'promocion1.jpg', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 1, 1),
('Oferta de Verano', 'Gran oferta de verano en equipos industriales', 'promocion2.jpg', CURDATE(), DATE_ADD(CURDATE(), INTERVAL 60 DAY), 1, 2);

-- Tabla de noticias
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `resumen` text DEFAULT NULL,
  `contenido` longtext DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_publicacion` date DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `orden` int(11) DEFAULT 0,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_noticias_activo` (`activo`),
  KEY `idx_noticias_fecha_publicacion` (`fecha_publicacion`),
  KEY `idx_noticias_orden` (`orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Datos de ejemplo para noticias (opcional)
INSERT INTO `noticias` (`titulo`, `resumen`, `contenido`, `imagen`, `fecha_publicacion`, `activo`, `orden`) VALUES
('Nueva alianza estratégica', 'Firmamos una nueva alianza para ampliar nuestro catálogo.', 'Contenido completo de la noticia de la nueva alianza estratégica.', 'noticia1.jpg', CURDATE(), 1, 1),
('Cobertura en medios', 'Nuestros productos fueron destacados en la prensa nacional.', 'Contenido completo sobre la cobertura en medios y entrevistas realizadas.', 'noticia2.jpg', DATE_ADD(CURDATE(), INTERVAL -7 DAY), 1, 2);

