<?php
session_start();
require 'credenciales.php';

// Si no hay sesión, devolver solo datos básicos vacíos
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'usuario_id' => null,
        'usuario' => null,
        'nombre_completo' => null,
        'rol' => null,
        'foto_perfil' => null
    ]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario = $_SESSION['usuario'] ?? null;
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$rol = $_SESSION['rol'] ?? null;
$foto_perfil = null;

// Conectar a la BD para obtener la foto de perfil
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if ($conexion) {
    $query = "SELECT foto_perfil FROM usuarios WHERE id = $usuario_id";
    $res = mysqli_query($conexion, $query);
    if ($row = mysqli_fetch_assoc($res)) {
        $foto_perfil = $row['foto_perfil'];
    }
    mysqli_close($conexion);
}

// Devolver los datos en JSON
echo json_encode([
    'usuario_id' => $usuario_id,
    'usuario' => $usuario,
    'nombre_completo' => $nombre_completo,
    'rol' => $rol,
    'foto_perfil' => $foto_perfil
]);
?>