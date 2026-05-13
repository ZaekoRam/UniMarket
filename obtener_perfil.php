<?php
session_start();
require 'credenciales.php';

// Determinar qué ID de usuario cargar
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // ID pasado por URL (perfil de otro usuario)
} else {
    // Si no hay ID en la URL, cargar el perfil del usuario logueado
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['error' => 'No autenticado']);
        exit();
    }
    $id = $_SESSION['usuario_id'];
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit();
}

$query = "SELECT nombre_completo AS nombre, usuario, bio, tags, carrera, campus, emprendimientos, estado, sobre_mi AS sobreMi, gustos, mood, color, meta, estilo, foto_perfil FROM usuarios WHERE id = '$id'";
$resultado = mysqli_query($conexion, $query);

if ($perfil = mysqli_fetch_assoc($resultado)) {
    echo json_encode($perfil);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

mysqli_close($conexion);
?>