<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    echo json_encode(['error' => 'Error de conexión: ' . mysqli_connect_error()]);
    exit;
}

$usuario_id = isset($_SESSION['usuario_id']) ? (int)$_SESSION['usuario_id'] : 0;

// 🔥 Verificar si la columna foto_perfil existe
$check_col = mysqli_query($conexion, "SHOW COLUMNS FROM usuarios LIKE 'foto_perfil'");
$columna_existe = mysqli_num_rows($check_col) > 0;

if (!$columna_existe) {
    echo json_encode(['error' => 'La columna foto_perfil no existe en la tabla usuarios. Ejecuta: ALTER TABLE usuarios ADD COLUMN foto_perfil VARCHAR(255) DEFAULT NULL;']);
    exit;
}

$sql = "SELECT p.*, u.nombre_completo AS nombre_autor, u.foto_perfil,
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'like') as total_likes,
        (SELECT COUNT(*) FROM reacciones r WHERE r.publicacion_id = p.id AND r.tipo = 'dislike') as total_dislikes,
        (SELECT tipo FROM reacciones r WHERE r.publicacion_id = p.id AND r.usuario_id = $usuario_id LIMIT 1) as mi_reaccion
        FROM publicaciones p 
        LEFT JOIN usuarios u ON p.usuario_id = u.id
        ORDER BY p.fecha DESC";

$res = mysqli_query($conexion, $sql);
if (!$res) {
    echo json_encode(['error' => 'Error en consulta: ' . mysqli_error($conexion)]);
    exit;
}

$posts = [];
while ($row = mysqli_fetch_assoc($res)) {
    $post_id = $row['id'];
    $sql_comentarios = "SELECT c.id, c.comentario, c.padre_id, u.nombre_completo AS nombre_autor 
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
    $posts[] = $row;
}

echo json_encode($posts);
mysqli_close($conexion);
?>