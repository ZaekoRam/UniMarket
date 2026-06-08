<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

if (!isset($_SESSION['usuario_id'])) die("0");

$post_id = $_POST['post_id'];
$usuario_id = $_SESSION['usuario_id'];
$tipo = $_POST['tipo'];

$check = mysqli_query($conexion, "SELECT id, tipo FROM reacciones WHERE publicacion_id = '$post_id' AND usuario_id = '$usuario_id'");

if (mysqli_num_rows($check) > 0) {
    $row = mysqli_fetch_assoc($check);
    if ($row['tipo'] == $tipo) {
        mysqli_query($conexion, "DELETE FROM reacciones WHERE publicacion_id = '$post_id' AND usuario_id = '$usuario_id'");
    } else {
        mysqli_query($conexion, "UPDATE reacciones SET tipo = '$tipo' WHERE publicacion_id = '$post_id' AND usuario_id = '$usuario_id'");
    }
} else {
    mysqli_query($conexion, "INSERT INTO reacciones (publicacion_id, usuario_id, tipo) VALUES ('$post_id', '$usuario_id', '$tipo')");

    if ($tipo === 'like') {
        $query_autor = "SELECT usuario_id FROM publicaciones WHERE id = '$post_id'";
        $res_autor = mysqli_query($conexion, $query_autor);
        $autor_post = mysqli_fetch_assoc($res_autor)['usuario_id'];
        if ($autor_post != $usuario_id) {
            $check_noti = "SELECT id FROM notificaciones 
                           WHERE usuario_id = $autor_post 
                             AND tipo = 'like' 
                             AND emisor_id = $usuario_id 
                             AND referencia_id = $post_id";
            $res_check = mysqli_query($conexion, $check_noti);
            if (mysqli_num_rows($res_check) == 0) {
                $sql_noti = "INSERT INTO notificaciones (usuario_id, tipo, emisor_id, referencia_id, post_id) 
                             VALUES ($autor_post, 'like', $usuario_id, $post_id, $post_id)";
                mysqli_query($conexion, $sql_noti);
            }
        }
    }
}

$conteo = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT COUNT(*) as total FROM reacciones WHERE publicacion_id = '$post_id' AND tipo = '$tipo'"));
echo $conteo['total'];
?>