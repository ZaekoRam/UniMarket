<?php
require 'credenciales.php';
$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);

$nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre_completo']); 
$usuario         = mysqli_real_escape_string($conexion, $_POST['usuario']); 
$num             = mysqli_real_escape_string($conexion, $_POST['num']);     
$cuenta          = mysqli_real_escape_string($conexion, $_POST['cuenta']);  
$password_plana  = $_POST['password']; 
$rol             = 'lector'; 

// Validaciones
if (strlen(trim($nombre_completo)) < 3) {
    header("Location: index.html?msg=" . urlencode("❌ El nombre completo debe tener al menos 3 letras.") . "&type=error");
    exit();
}
if (!filter_var($cuenta, FILTER_VALIDATE_EMAIL)) {
    header("Location: index.html?msg=" . urlencode("❌ El correo electrónico no es válido.") . "&type=error");
    exit();
}
if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[\W])/', $password_plana)) {
    header("Location: index.html?msg=" . urlencode("⚠️ Contraseña débil. Debe incluir: mayúscula, minúscula y carácter especial.") . "&type=warning");
    exit();
}
if (preg_match('/\s/', $usuario)) {
    header("Location: index.html?msg=" . urlencode("❌ El nombre de usuario no puede tener espacios.") . "&type=error");
    exit();
}

$password_hasheada = password_hash($password_plana, PASSWORD_DEFAULT); 
$codigo_verificacion = rand(100000, 999999);

// Verificar duplicados
$check_query = "SELECT * FROM usuarios WHERE num = '$num' OR cuenta = '$cuenta' OR usuario = '$usuario'";
$check_result = mysqli_query($conexion, $check_query);
if (mysqli_num_rows($check_result) > 0) {
    header("Location: index.html?msg=" . urlencode("❌ El usuario, correo o número de cuenta ya existen.") . "&type=error");
    exit();
}

// Insertar usuario
$sql = "INSERT INTO usuarios (nombre_completo, usuario, num, cuenta, password, rol, codigo_verificacion, verificado) 
        VALUES ('$nombre_completo', '$usuario', '$num', '$cuenta', '$password_hasheada', '$rol', '$codigo_verificacion', 0)";

if (mysqli_query($conexion, $sql)) {
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $mi_correo; 
        $mail->Password   = $mi_password_correo; 
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('unibot1940@gmail.com', 'UniMarket Oficial');
        $mail->addAddress($cuenta, $nombre_completo);
        $mail->AddEmbeddedImage('img/unibot_verificacion.png', 'unibot_logo');

        $mail->isHTML(true);
        $mail->Subject = 'Codigo de Verificacion - UniMarket';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f4f4f4; border-radius: 10px;'>
                <img src='cid:unibot_logo' alt='Unibot Verificación' style='width: 150px; margin-bottom: 15px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);'>
                <h2 style='color: #0f8f87;'>¡Hola $nombre_completo!</h2>
                <p style='color: #333; font-size: 16px;'>Gracias por unirte a <strong>UniMarket</strong>. Tu código para activar tu cuenta es:</p>
                <h1 style='color: #39c5bb; font-size: 40px; letter-spacing: 5px; background: #fff; padding: 10px 20px; border-radius: 8px; display: inline-block; border: 2px dashed #39c5bb; margin: 10px 0;'>
                    $codigo_verificacion
                </h1>
                <p style='color: #777; font-size: 12px; margin-top: 20px;'>Si no solicitaste este código, ignora este correo.</p>
            </div>
        ";

        $mail->send();
        header("Location: index.html?verificar=" . urlencode($cuenta) . "&msg=" . urlencode("✅ ¡Registro casi listo! Te hemos enviado un código de 6 dígitos a tu correo.") . "&type=success");
        exit();
    } catch (Exception $e) {
        header("Location: index.html?msg=" . urlencode("❌ Error al enviar el correo. Contacta al administrador.") . "&type=error");
        exit();
    }
} else {
    header("Location: index.html?msg=" . urlencode("❌ Error en la Base de Datos: " . mysqli_error($conexion)) . "&type=error");
    exit();
}
mysqli_close($conexion);
?>