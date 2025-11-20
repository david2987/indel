-- Script para crear las tablas de usuarios, grupos y relación
-- Ejecutar este script en MySQL/MariaDB después de crear la base de datos

USE `indel`;

-- Tabla de grupos
CREATE TABLE IF NOT EXISTS `grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `ultimo_acceso` datetime DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_activo` (`activo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de relación usuarios_grupos (muchos a muchos)
CREATE TABLE IF NOT EXISTS `usuarios_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `fecha_asignacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_grupo` (`usuario_id`, `grupo_id`),
  KEY `fk_usuario` (`usuario_id`),
  KEY `fk_grupo` (`grupo_id`),
  CONSTRAINT `fk_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_grupo` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar grupos por defecto
INSERT INTO `grupos` (`nombre`, `descripcion`, `activo`) VALUES
('Administradores', 'Grupo con acceso completo al sistema', 1),
('Editores', 'Grupo con permisos de edición', 1),
('Visualizadores', 'Grupo con permisos de solo lectura', 1);

-- Insertar usuario administrador por defecto
-- Contraseña: admin123 (hash bcrypt)
INSERT INTO `usuarios` (`username`, `email`, `password`, `nombre`, `apellido`, `activo`) VALUES
('admin', 'admin@indel.com.ar', '$2y$12$RgGc37aCzLMfaJzkd55leunHhaNLo0jcW3wTOv7yMjq9Gl7gHmvPG', 'Administrador', 'Sistema', 1);

-- Asignar usuario admin al grupo Administradores
INSERT INTO `usuarios_grupos` (`usuario_id`, `grupo_id`) 
SELECT u.id, g.id 
FROM usuarios u, grupos g 
WHERE u.username = 'admin' AND g.nombre = 'Administradores';

