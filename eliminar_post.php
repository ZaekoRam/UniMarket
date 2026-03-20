<?php
session_start();
// Si no hay sesión, lo botamos
if (!isset($_SESSION['usuario_id'])) {
    die("Error: No autenticado");
}

$conexion = mysqli_connect("localhost", "root", "", "sistema_login");
$post_id = mysqli_real_escape_string($conexion, $_POST['post_id']);
$mi_id = $_SESSION['usuario_id'];
$mi_rol = $_SESSION['rol'];

// 1. Preguntarle a la base de datos quién es el dueño de este post
$query = "SELECT usuario_id FROM publicaciones WHERE id = '$post_id'";
$resultado = mysqli_query($conexion, $query);

if ($fila = mysqli_fetch_assoc($resultado)) {
    $autor_del_post = $fila['usuario_id'];

    // 2. LA MAGIA: ¿Soy el dueño del post o soy admin?
    if ($mi_id == $autor_del_post || $mi_rol == 'admin') {
        
        // ¡Sí tiene permiso! Procedemos a borrar
        $sql_delete = "DELETE FROM publicaciones WHERE id = '$post_id'";
        if (mysqli_query($conexion, $sql_delete)) {
            echo "ok";
        } else {
            echo "Error al borrar en BD";
        }
    } else {
        // No es suyo y no es admin
        echo "Error: No tienes permiso para eliminar este post.";
    }
} else {
    echo "Error: El post no existe.";
}

mysqli_close($conexion);
?>