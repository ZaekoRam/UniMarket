-- Crear tabla para almacenar múltiples archivos multimedia por publicación
CREATE TABLE IF NOT EXISTS `publicacion_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publicacion_id` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT 'image',
  `fecha_creacion` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `publicacion_id` (`publicacion_id`),
  FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Migrar datos existentes de publicaciones.imagen a la nueva tabla
INSERT INTO `publicacion_media` (publicacion_id, nombre_archivo, tipo)
SELECT id, imagen, 'image' FROM `publicaciones` 
WHERE imagen IS NOT NULL AND imagen != '';

-- Opcionalmente, puedes mantener la columna imagen para compatibilidad, o eliminarla después
-- ALTER TABLE `publicaciones` DROP COLUMN `imagen`;
