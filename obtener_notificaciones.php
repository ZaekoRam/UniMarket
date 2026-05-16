<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}
$mi_id = $_SESSION['usuario_id'];

$sql = "SELECT n.*, 
               u.usuario as emisor_usuario, 
               u.nombre_completo as emisor_nombre,
               u.foto_perfil as emisor_foto,
               n.post_id,
               CASE n.tipo
                   WHEN 'mensaje' THEN (SELECT mensaje FROM mensajes WHERE id = n.referencia_id)
                   WHEN 'like'    THEN (SELECT texto FROM publicaciones WHERE id = n.referencia_id)
                   WHEN 'comentario' THEN (SELECT comentario FROM comentarios WHERE id = n.referencia_id)
                   WHEN 'respuesta'  THEN (SELECT comentario FROM comentarios WHERE id = n.referencia_id)
               END as preview
        FROM notificaciones n
        JOIN usuarios u ON n.emisor_id = u.id
        WHERE n.usuario_id = $mi_id
        ORDER BY n.creado DESC
        LIMIT 50";

$res = mysqli_query($conexion, $sql);
$notificaciones = mysqli_fetch_all($res, MYSQLI_ASSOC);
echo json_encode($notificaciones);
mysqli_close($conexion);
?>