<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    die("Error: No autenticado");
}

$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// Recibimos los datos del JS
$post_id = mysqli_real_escape_string($conexion, $_POST['post_id']);
$nuevo_texto = mysqli_real_escape_string($conexion, $_POST['texto']);
// Si también guardas la URL, descomenta la siguiente línea:
// $url = mysqli_real_escape_string($conexion, $_POST['url']);

$mi_id = $_SESSION['usuario_id'];
$mi_rol = $_SESSION['rol'];

// 1. Verificamos quién es el dueño
$query = "SELECT usuario_id FROM publicaciones WHERE id = '$post_id'";
$resultado = mysqli_query($conexion, $query);

if ($fila = mysqli_fetch_assoc($resultado)) {
    $autor_del_post = $fila['usuario_id'];

    // 2. ¿Soy el dueño o soy admin?
    if ($mi_id == $autor_del_post || $mi_rol == 'admin') {
        
        // ¡Sí! Actualizamos el post
        // Si usas URL, cambia el SET a: SET texto = '$nuevo_texto', url = '$url'
        $sql_update = "UPDATE publicaciones SET texto = '$nuevo_texto' WHERE id = '$post_id'";
        
        if (mysqli_query($conexion, $sql_update)) {
            echo "ok";
        } else {
            echo "Error al guardar en BD: " . mysqli_error($conexion);
        }
    } else {
        echo "Error: ¡No intentes hackear, no es tu post! 🚫";
    }
} else {
    echo "Error: El post no existe.";
}

mysqli_close($conexion);
?>