<?php
// Script para migrar la base de datos a la nueva estructura de múltiples imágenes
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

echo "🔧 Iniciando migración a múltiples imágenes...<br><br>";

// 1. Crear tabla publicacion_media
$sql1 = "CREATE TABLE IF NOT EXISTS `publicacion_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `publicacion_id` int(11) NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `tipo` varchar(50) DEFAULT 'image',
  `fecha_creacion` timestamp DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `publicacion_id` (`publicacion_id`),
  FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if (mysqli_query($conexion, $sql1)) {
    echo "✅ Tabla 'publicacion_media' creada exitosamente<br>";
} else {
    echo "⚠️ Error al crear tabla o ya existe: " . mysqli_error($conexion) . "<br>";
}

// 2. Migrar datos existentes
$sql2 = "INSERT INTO `publicacion_media` (publicacion_id, nombre_archivo, tipo)
SELECT id, imagen, 'image' FROM `publicaciones` 
WHERE imagen IS NOT NULL AND imagen != ''
ON DUPLICATE KEY UPDATE nombre_archivo=VALUES(nombre_archivo)";

if (mysqli_query($conexion, $sql2)) {
    $affected = mysqli_affected_rows($conexion);
    echo "✅ $affected archivos migrados exitosamente<br>";
} else {
    echo "⚠️ Error al migrar datos: " . mysqli_error($conexion) . "<br>";
}

echo "<br>🎉 ¡Migración completada!<br>";
echo "<a href='menu.html'>Volver al inicio</a>";

mysqli_close($conexion);
?>
