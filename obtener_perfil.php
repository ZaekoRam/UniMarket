<?php
session_start();
require 'credenciales.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['error' => 'No autenticado']);
        exit();
    }
    $id = $_SESSION['usuario_id'];
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    echo json_encode(['error' => 'Error de conexión']);
    exit();
}

<<<<<<< HEAD
// Incluimos la columna mostrar_estado y aplicamos la lógica:
// is_online = 1 solo si last_activity < 5 minutos Y mostrar_estado = 1
$query = "SELECT 
            nombre_completo AS nombre, 
            usuario, 
            bio, 
            tags, 
            carrera, 
            campus, 
            emprendimientos, 
            estado, 
            sobre_mi AS sobreMi, 
            gustos, 
            mood, 
            color, 
            meta, 
            estilo, 
            foto_perfil, 
            last_activity,
            mostrar_estado,
            CASE 
                WHEN mostrar_estado = 1 AND last_activity IS NOT NULL AND last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 1 
                ELSE 0 
            END AS is_online
          FROM usuarios 
          WHERE id = '$id'";
=======
// 🔥 Corregido: maneja NULL en last_activity y siempre devuelve 0 o 1
$query = "SELECT nombre_completo AS nombre, usuario, bio, tags, carrera, campus, emprendimientos, estado, sobre_mi AS sobreMi, gustos, mood, color, meta, estilo, foto_perfil, last_activity,
          CASE 
              WHEN last_activity IS NOT NULL AND last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 1 
              ELSE 0 
          END AS is_online
          FROM usuarios WHERE id = '$id'";
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f

$resultado = mysqli_query($conexion, $query);

if ($perfil = mysqli_fetch_assoc($resultado)) {
<<<<<<< HEAD
    // Convertir a entero por seguridad
    $perfil['is_online'] = (int)$perfil['is_online'];
    $perfil['mostrar_estado'] = isset($perfil['mostrar_estado']) ? (int)$perfil['mostrar_estado'] : 1;
=======
    // Asegurar que is_online sea entero
    $perfil['is_online'] = (int)$perfil['is_online'];
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
    echo json_encode($perfil);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

mysqli_close($conexion);
?>