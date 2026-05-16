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

// 🔥 Corregido: maneja NULL en last_activity y siempre devuelve 0 o 1
$query = "SELECT nombre_completo AS nombre, usuario, bio, tags, carrera, campus, emprendimientos, estado, sobre_mi AS sobreMi, gustos, mood, color, meta, estilo, foto_perfil, last_activity,
          CASE 
              WHEN last_activity IS NOT NULL AND last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 1 
              ELSE 0 
          END AS is_online
          FROM usuarios WHERE id = '$id'";

$resultado = mysqli_query($conexion, $query);

if ($perfil = mysqli_fetch_assoc($resultado)) {
    // Asegurar que is_online sea entero
    $perfil['is_online'] = (int)$perfil['is_online'];
    echo json_encode($perfil);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

mysqli_close($conexion);
?>