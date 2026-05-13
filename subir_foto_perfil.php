<?php
session_start();
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit();
}

$id = $_SESSION['usuario_id'];
$carpeta = 'uploads/avatars/';
if (!file_exists($carpeta)) mkdir($carpeta, 0777, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $archivo = $_FILES['foto'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($extension, $permitidas)) {
        echo json_encode(['success' => false, 'error' => 'Formato no permitido']);
        exit();
    }
    $nombreSeguro = 'avatar_' . $id . '_' . time() . '.' . $extension;
    $rutaDestino = $carpeta . $nombreSeguro;
    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        $conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
        $rutaRelativa = $rutaDestino;
        $query = "UPDATE usuarios SET foto_perfil = '$rutaRelativa' WHERE id = $id";
        mysqli_query($conexion, $query);
        echo json_encode(['success' => true, 'url' => $rutaRelativa]);
        mysqli_close($conexion);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al mover archivo']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Solicitud inválida']);
}
?>