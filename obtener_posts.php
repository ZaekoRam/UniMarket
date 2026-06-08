<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    echo json_encode(['error' => 'Error de conexión']);
    exit;
}

// Asegurar que las tablas de tags existen
mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    creado TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
mysqli_query($conexion, "CREATE TABLE IF NOT EXISTS post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES publicaciones(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
)");

$usuario_id = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : 0;
$filtro_tag = isset($_GET['tag']) ? trim($_GET['tag']) : '';

if (!empty($filtro_tag)) {
    // Filtrar por tag específico
    $sql = "SELECT p.*, u.nombre_completo AS nombre_autor, u.foto_perfil,
            (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'like') as total_likes,
            (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'dislike') as total_dislikes,
            (SELECT tipo FROM reacciones r WHERE r.publicacion_id = p.id AND r.usuario_id = $usuario_id LIMIT 1) as mi_reaccion
            FROM publicaciones p
            INNER JOIN post_tags pt ON p.id = pt.post_id
            INNER JOIN tags t ON pt.tag_id = t.id
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            WHERE t.nombre = '$filtro_tag'
            ORDER BY p.fecha DESC";
} else {
    // Todos los posts
    $sql = "SELECT p.*, u.nombre_completo AS nombre_autor, u.foto_perfil,
            (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'like') as total_likes,
            (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'dislike') as total_dislikes,
            (SELECT tipo FROM reacciones r WHERE r.publicacion_id = p.id AND r.usuario_id = $usuario_id LIMIT 1) as mi_reaccion
            FROM publicaciones p 
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            ORDER BY p.fecha DESC";
}

$res = mysqli_query($conexion, $sql);
if (!$res) {
    echo json_encode(['error' => 'Error en consulta: ' . mysqli_error($conexion)]);
    exit;
}

$posts = [];
while ($row = mysqli_fetch_assoc($res)) {
    $post_id = $row['id'];
    
    // Comentarios - AHORA CON c.usuario_id
    $sql_comentarios = "SELECT c.id, c.comentario, c.padre_id, c.usuario_id, u.nombre_completo AS nombre_autor 
                        FROM comentarios c 
                        LEFT JOIN usuarios u ON c.usuario_id = u.id 
                        WHERE c.publicacion_id = $post_id 
                        ORDER BY c.id ASC";
    $com_res = mysqli_query($conexion, $sql_comentarios);
    $lista_comentarios = [];
    while ($com = mysqli_fetch_assoc($com_res)) {
        $lista_comentarios[] = $com;
    }
    $row['comentarios_data'] = $lista_comentarios;
    
    // Tags
    $sql_tags = "SELECT t.nombre FROM tags t
                 INNER JOIN post_tags pt ON t.id = pt.tag_id
                 WHERE pt.post_id = $post_id";
    $tags_res = mysqli_query($conexion, $sql_tags);
    $tags = [];
    while ($tag = mysqli_fetch_assoc($tags_res)) {
        $tags[] = $tag['nombre'];
    }
    $row['tags'] = $tags;
    
    $posts[] = $row;
}

echo json_encode($posts);
mysqli_close($conexion);
?>