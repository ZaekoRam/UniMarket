<?php
session_start();
// Si no hay sesión, no mandamos nada
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['error' => 'No autenticado']);
    exit();
}

$conexion = mysqli_connect("localhost", "root", "", "sistema_login");
$id = $_SESSION['usuario_id'];

// Buscamos los datos. 'nombre_completo' lo mandamos como 'nombre' para que tu JS lo entienda igual.
$query = "SELECT nombre_completo AS nombre, usuario, bio, tags, carrera, campus, emprendimientos, estado, sobre_mi AS sobreMi, gustos, mood, color, meta, estilo FROM usuarios WHERE id = '$id'";
$resultado = mysqli_query($conexion, $query);

if ($perfil = mysqli_fetch_assoc($resultado)) {
    echo json_encode($perfil);
} else {
    echo json_encode(['error' => 'Usuario no encontrado']);
}

mysqli_close($conexion);
?>