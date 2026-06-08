<?php
session_start();
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$token = $_GET['token'] ?? '';

if (empty($token)) {
    die("Token no válido.");
}

// Buscar token activo y no expirado
$stmt = mysqli_prepare($conexion, "SELECT id, usuario FROM usuarios WHERE token_recuperacion = ? AND token_expira > NOW()");
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("El enlace ha expirado o es inválido. Solicita uno nuevo desde <a href='recuperar.php'>aquí</a>.");
}

// Guardar en sesión que el usuario está autorizado para restablecer
$_SESSION['reset_user_id'] = $user['id'];
$_SESSION['reset_token'] = $token;

// Redirigir al formulario HTML
header("Location: restablecer");
exit;
?>