<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require 'credenciales.php';

$ruta_phpmailer = __DIR__ . '/PHPMailer/src/PHPMailer.php';
if (!file_exists($ruta_phpmailer)) {
    header("Location: recuperar.php?msg=" . urlencode("❌ PHPMailer no encontrado. Contacta al administrador.") . "&type=error");
    exit();
}

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    header("Location: recuperar.php?msg=" . urlencode("❌ Error de conexión a la base de datos.") . "&type=error");
    exit();
}

$email = trim($_POST['email'] ?? '');
if (empty($email)) {
    header("Location: recuperar.php?msg=" . urlencode("❌ Ingresa un correo válido.") . "&type=error");
    exit();
}

$stmt = mysqli_prepare($conexion, "SELECT id, usuario FROM usuarios WHERE cuenta = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: recuperar.php?msg=" . urlencode("❌ No existe cuenta con ese correo.") . "&type=error");
    exit();
}

$token = bin2hex(random_bytes(32));
$expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));
$update = mysqli_prepare($conexion, "UPDATE usuarios SET token_recuperacion = ?, token_expira = ? WHERE id = ?");
mysqli_stmt_bind_param($update, "ssi", $token, $expiracion, $user['id']);
if (!mysqli_stmt_execute($update)) {
    header("Location: recuperar.php?msg=" . urlencode("❌ Error al guardar el token.") . "&type=error");
    exit();
}

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $mi_correo;
    $mail->Password   = $mi_password_correo;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom($mi_correo, 'UniMarket');
    $mail->addAddress($email, $user['usuario']);

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $dominio = $protocol . $_SERVER['HTTP_HOST'];
    $enlace = $dominio . "/restablecer.php?token=" . $token;

    $mail->isHTML(true);
    $mail->Subject = 'Restablece tu contraseña en UniMarket';
    $mail->Body    = "
        <h2>Hola, {$user['usuario']}</h2>
        <p>Recibimos una solicitud para restablecer tu contraseña.</p>
        <a href='$enlace'>Click aquí para restablecer</a>
        <p>El enlace expira en 1 hora.</p>
    ";

    $mail->send();
    header("Location: recuperar.php?msg=" . urlencode("✅ Revisa tu correo. Te enviamos un enlace para recuperar tu contraseña.") . "&type=success");
    exit();
} catch (Exception $e) {
    header("Location: recuperar.php?msg=" . urlencode("❌ Error al enviar correo: " . $mail->ErrorInfo) . "&type=error");
    exit();
}
?>