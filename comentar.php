<?php
require_once 'credenciales.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index?msg=" . urlencode("❌ Inicia sesión para comentar.") . "&type=error");
    exit();
}

if ($_SESSION['rol'] === 'lector') {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=" . urlencode("⚠️ Tu rol de lector no te permite comentar publicaciones.") . "&type=warning");
    exit();
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=" . urlencode("❌ Error de conexión a la base de datos.") . "&type=error");
    exit();
}

$post_id = mysqli_real_escape_string($conexion, $_POST['post_id']);
$comentario = mysqli_real_escape_string($conexion, $_POST['comentario']);
$usuario_id = $_SESSION['usuario_id'];
$padre_id = (isset($_POST['padre_id']) && $_POST['padre_id'] != '0') ? (int)$_POST['padre_id'] : "NULL";

$sql = "INSERT INTO comentarios (publicacion_id, usuario_id, comentario, padre_id) 
        VALUES ('$post_id', '$usuario_id', '$comentario', $padre_id)";

if (mysqli_query($conexion, $sql)) {
    $comentario_id = mysqli_insert_id($conexion);

    // 🔔 NOTIFICACIÓN AL AUTOR DEL POST
    $query_autor_post = "SELECT usuario_id FROM publicaciones WHERE id = '$post_id'";
    $res_autor = mysqli_query($conexion, $query_autor_post);
    $autor_post = mysqli_fetch_assoc($res_autor)['usuario_id'];
    if ($autor_post != $usuario_id) {
        $tipo_noti = ($padre_id !== "NULL") ? 'respuesta' : 'comentario';
        $sql_noti = "INSERT INTO notificaciones (usuario_id, tipo, emisor_id, referencia_id, post_id) 
                     VALUES ($autor_post, '$tipo_noti', $usuario_id, $comentario_id, $post_id)";
        mysqli_query($conexion, $sql_noti);
    }

    // 🔔 NOTIFICACIÓN AL AUTOR DEL COMENTARIO PADRE (si es respuesta)
    if ($padre_id !== "NULL") {
        $query_padre = "SELECT usuario_id FROM comentarios WHERE id = $padre_id";
        $res_padre = mysqli_query($conexion, $query_padre);
        $autor_padre = mysqli_fetch_assoc($res_padre)['usuario_id'];
        if ($autor_padre != $usuario_id && $autor_padre != $autor_post) {
            $sql_noti_resp = "INSERT INTO notificaciones (usuario_id, tipo, emisor_id, referencia_id, post_id) 
                              VALUES ($autor_padre, 'respuesta', $usuario_id, $comentario_id, $post_id)";
            mysqli_query($conexion, $sql_noti_resp);
        }
    }

    header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=" . urlencode("💬 Comentario agregado correctamente.") . "&type=success");
} else {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=" . urlencode("❌ Error al agregar comentario: " . mysqli_error($conexion)) . "&type=error");
}
mysqli_close($conexion);
?>