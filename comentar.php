<?php
require_once 'credenciales.php';
session_start();

// Verificar sesión
if (!isset($_SESSION['usuario_id'])) {
    die("Inicia sesión para comentar.");
}

// Verificar rol: los lectores NO pueden comentar
if ($_SESSION['rol'] === 'lector') {
    die("Tu rol de 'lector' no te permite comentar publicaciones.");
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    die("Error de conexión a la base de datos.");
}

$post_id = mysqli_real_escape_string($conexion, $_POST['post_id']);
$comentario = mysqli_real_escape_string($conexion, $_POST['comentario']);
$usuario_id = $_SESSION['usuario_id'];
$padre_id = (isset($_POST['padre_id']) && $_POST['padre_id'] != '0') ? (int)$_POST['padre_id'] : "NULL";

$sql = "INSERT INTO comentarios (publicacion_id, usuario_id, comentario, padre_id) 
        VALUES ('$post_id', '$usuario_id', '$comentario', $padre_id)";

if (mysqli_query($conexion, $sql)) {
    echo "ok";
} else {
    echo "Error: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>