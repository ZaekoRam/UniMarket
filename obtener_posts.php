<?php
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// Consulta que cuenta likes/dislikes y agrupa comentarios
$sql = "SELECT p.*, 
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'like') as total_likes,
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'dislike') as total_dislikes,
        (SELECT GROUP_CONCAT(comentario SEPARATOR '||') FROM comentarios c WHERE c.publicacion_id = p.id) as lista_comentarios
        FROM publicaciones p 
        ORDER BY p.fecha DESC";

$res = mysqli_query($conexion, $sql);
$posts = [];

while($row = mysqli_fetch_assoc($res)) {
    // Convertimos la cadena de comentarios separada por || en un array de JS
    $row['comentarios'] = $row['lista_comentarios'] ? explode('||', $row['lista_comentarios']) : [];
    $posts[] = $row;
}

echo json_encode($posts);
?>