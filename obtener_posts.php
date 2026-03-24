<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// Obtenemos el ID del usuario actual
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

// 👇 EL CAMBIO ESTÁ AQUÍ: Cambiamos u.usuario por u.nombre_completo
$sql = "SELECT p.*, u.nombre_completo AS nombre_autor,
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'like') as total_likes,
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'dislike') as total_dislikes,
        (SELECT tipo FROM reacciones r WHERE r.publicacion_id = p.id AND r.usuario_id = '$usuario_id' LIMIT 1) as mi_reaccion
        FROM publicaciones p 
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";

$res = mysqli_query($conexion, $sql);
$posts = [];

while($row = mysqli_fetch_assoc($res)) {
    $post_id = $row['id'];
    
    $com_res = mysqli_query($conexion, "SELECT id, comentario, padre_id FROM comentarios WHERE publicacion_id = '$post_id' ORDER BY id ASC");
    $lista_comentarios = [];
    while($com = mysqli_fetch_assoc($com_res)) {
        $lista_comentarios[] = $com;
    }
    
    $row['comentarios_data'] = $lista_comentarios;
    $posts[] = $row;
}

echo json_encode($posts);
mysqli_close($conexion);
?>