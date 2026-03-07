<?php
session_start();
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// Obtenemos el ID del usuario actual para saber cuáles son SUS reacciones
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

$sql = "SELECT p.*, 
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'like') as total_likes,
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'dislike') as total_dislikes,
        (SELECT tipo FROM reacciones r WHERE r.publicacion_id = p.id AND r.usuario_id = '$usuario_id' LIMIT 1) as mi_reaccion
        FROM publicaciones p ORDER BY p.fecha DESC";

$res = mysqli_query($conexion, $sql);
$posts = [];

while($row = mysqli_fetch_assoc($res)) {
    $post_id = $row['id'];
    
    // Obtenemos los comentarios de este post
    $com_res = mysqli_query($conexion, "SELECT id, comentario, padre_id FROM comentarios WHERE publicacion_id = '$post_id' ORDER BY id ASC");
    $lista_comentarios = [];
    while($com = mysqli_fetch_assoc($com_res)) {
        $lista_comentarios[] = $com;
    }
    
    $row['comentarios_data'] = $lista_comentarios;
    $posts[] = $row;
}

header('Content-Type: application/json');
echo json_encode($posts);
?>