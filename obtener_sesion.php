<?php
session_start();
require 'credenciales.php';

<<<<<<< HEAD
=======
// Si no hay sesión, devolver solo datos básicos vacíos
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([
        'usuario_id' => null,
        'usuario' => null,
        'nombre_completo' => null,
        'rol' => null,
<<<<<<< HEAD
        'foto_perfil' => null,
        'cuenta' => null
=======
        'foto_perfil' => null
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
    ]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$usuario = $_SESSION['usuario'] ?? null;
$nombre_completo = $_SESSION['nombre_completo'] ?? null;
$rol = $_SESSION['rol'] ?? null;
$foto_perfil = null;
<<<<<<< HEAD
$cuenta = null;

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if ($conexion) {
    $query = "SELECT foto_perfil, cuenta FROM usuarios WHERE id = $usuario_id";
    $res = mysqli_query($conexion, $query);
    if ($row = mysqli_fetch_assoc($res)) {
        $foto_perfil = $row['foto_perfil'];
        $cuenta = $row['cuenta'];
=======

// Conectar a la BD para obtener la foto de perfil
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if ($conexion) {
    $query = "SELECT foto_perfil FROM usuarios WHERE id = $usuario_id";
    $res = mysqli_query($conexion, $query);
    if ($row = mysqli_fetch_assoc($res)) {
        $foto_perfil = $row['foto_perfil'];
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
    }
    mysqli_close($conexion);
}

<<<<<<< HEAD
=======
// Devolver los datos en JSON
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
echo json_encode([
    'usuario_id' => $usuario_id,
    'usuario' => $usuario,
    'nombre_completo' => $nombre_completo,
    'rol' => $rol,
<<<<<<< HEAD
    'foto_perfil' => $foto_perfil,
    'cuenta' => $cuenta
=======
    'foto_perfil' => $foto_perfil
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
]);
?>