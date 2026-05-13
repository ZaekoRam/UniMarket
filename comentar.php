<?php
require_once 'credenciales.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.html?msg=" . urlencode("❌ Inicia sesión para comentar.") . "&type=error");
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
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=" . urlencode("💬 Comentario agregado correctamente.") . "&type=success");
} else {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?msg=" . urlencode("❌ Error al agregar comentario: " . mysqli_error($conexion)) . "&type=error");
}
mysqli_close($conexion);
?>