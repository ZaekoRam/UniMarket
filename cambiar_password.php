<?php
session_start();
require 'credenciales.php';
if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}
$id = $_SESSION['usuario_id'];
$passActual = $_POST['password_actual'] ?? '';
$newPass = $_POST['password_nueva'] ?? '';
if (strlen($newPass) < 8) {
    echo json_encode(['success' => false, 'error' => 'La nueva contraseña debe tener al menos 8 caracteres']);
    exit;
}
if (!preg_match('/[A-Z]/', $newPass)) {
    echo json_encode(['success' => false, 'error' => 'La nueva contraseña debe contener al menos una mayúscula']);
    exit;
}
if (!preg_match('/[a-z]/', $newPass)) {
    echo json_encode(['success' => false, 'error' => 'La nueva contraseña debe contener al menos una minúscula']);
    exit;
}
if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $newPass)) {
    echo json_encode(['success' => false, 'error' => 'La nueva contraseña debe contener al menos un carácter especial (!@#$%^&* etc.)']);
    exit;
}
}
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$sql = "SELECT password FROM usuarios WHERE id = $id";
$res = mysqli_query($conexion, $sql);
$row = mysqli_fetch_assoc($res);
if (!password_verify($passActual, $row['password'])) {
    echo json_encode(['success' => false, 'error' => 'Contraseña actual incorrecta']);
    exit;
}
$hashed = password_hash($newPass, PASSWORD_DEFAULT);
$update = "UPDATE usuarios SET password = '$hashed' WHERE id = $id";
if (mysqli_query($conexion, $update)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conexion)]);
}
mysqli_close($conexion);
?>