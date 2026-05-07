<?php
session_start();
require 'credenciales.php';

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

// 🔥 SOLO POSTS CON ESTADO 'aprobado'
$sql = "SELECT p.*, u.nombre_completo AS nombre_autor,
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'like') as total_likes,
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'dislike') as total_dislikes,
        (SELECT tipo FROM reacciones r WHERE r.publicacion_id = p.id AND r.usuario_id = '$usuario_id' LIMIT 1) as mi_reaccion
        FROM publicaciones p 
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.estado = 'aprobado'
        ORDER BY p.fecha DESC";

$res = mysqli_query($conexion, $sql);
$posts = [];

while ($row = mysqli_fetch_assoc($res)) {
    $post_id = $row['id'];

    $sql_comentarios = "SELECT c.id, c.comentario, c.padre_id, u.nombre_completo AS nombre_autor 
                        FROM comentarios c 
                        LEFT JOIN usuarios u ON c.usuario_id = u.id 
                        WHERE c.publicacion_id = '$post_id' 
                        ORDER BY c.id ASC";

    $com_res = mysqli_query($conexion, $sql_comentarios);
    $lista_comentarios = [];
    while ($com = mysqli_fetch_assoc($com_res)) {
        $lista_comentarios[] = $com;
    }

    $row['comentarios_data'] = $lista_comentarios;
    $posts[] = $row;
}

echo json_encode($posts);
mysqli_close($conexion);
?>