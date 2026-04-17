<?php
require 'credenciales.php';
$conexion = mysqli_connect("localhost", "root", "", "sistema_login");

// 1. Recoger los datos
$nombre_completo = mysqli_real_escape_string($conexion, $_POST['nombre_completo']); 
$usuario         = mysqli_real_escape_string($conexion, $_POST['usuario']); 
$num             = mysqli_real_escape_string($conexion, $_POST['num']);     
$cuenta          = mysqli_real_escape_string($conexion, $_POST['cuenta']);  
$password_plana  = $_POST['password']; 
$rol             = 'lector'; 

// ==========================================
// 🛡️ INICIO DE VALIDACIONES
// ==========================================

// 🔠 VALIDACIÓN DE NOMBRE COMPLETO (Mínimo 3 letras)
if (strlen(trim($nombre_completo)) < 3) {
    echo "<script>
            alert('¡El nombre completo debe tener al menos 3 letras, twin!');
            window.history.back();
          </script>";
    exit();
}

// 📧 VALIDACIÓN DE CORREO (Debe tener @ y formato válido)
if (!filter_var($cuenta, FILTER_VALIDATE_EMAIL)) {
    echo "<script>
            alert('El correo electrónico no es válido. Asegúrate de incluir el @ y un dominio.');
            window.history.back();
          </script>";
    exit();
}

// 🔐 VALIDACIÓN DE CONTRASEÑA (Mayúscula, minúscula y carácter especial)
if (!preg_match('/(?=.*[a-z])(?=.*[A-Z])(?=.*[\W])/', $password_plana)) {
    echo "<script>
            alert('Contraseña débil. Debe incluir: una mayúscula, una minúscula y un carácter especial.');
            window.history.back();
          </script>";
    exit();
}

// 🛑 CANDADO ANTI-ESPACIOS EN USUARIO
if (preg_match('/\s/', $usuario)) {
    echo "<script>
            alert('El nombre de usuario no puede tener espacios.');
            window.history.back();
          </script>";
    exit();
}

// ==========================================
// 🛡️ FIN DE VALIDACIONES
// ==========================================

// 🔒 HASHEO DE LA CONTRASEÑA
$password_hasheada = password_hash($password_plana, PASSWORD_DEFAULT); 

// 🎲 GENERAR CÓDIGO DE VERIFICACIÓN DE 6 DÍGITOS
$codigo_verificacion = rand(100000, 999999);

// 2. Revisar si ya existen en la base de datos
$check_query = "SELECT * FROM usuarios WHERE num = '$num' OR cuenta = '$cuenta' OR usuario = '$usuario'";
$check_result = mysqli_query($conexion, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo "<script>
            alert('¡Aguanta! El usuario, correo o número de cuenta ya existen.');
            window.location='Login.html';
          </script>";
    exit();
}

// 3. Inserción (Aquí ya mandamos el código y marcamos verificado como 0)
$sql = "INSERT INTO usuarios (nombre_completo, usuario, num, cuenta, password, rol, codigo_verificacion, verificado) 
        VALUES ('$nombre_completo', '$usuario', '$num', '$cuenta', '$password_hasheada', '$rol', '$codigo_verificacion', 0)";

if (mysqli_query($conexion, $sql)) {
    
    // ==========================================
    // 📧 INICIA CONFIGURACIÓN DE PHPMAILER
    // ==========================================
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Configuración del servidor de Gmail
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // 👇 AQUÍ PON TU CORREO DE GMAIL Y LA CONTRASEÑA DE 16 LETRAS
        $mail->Username   = $mi_correo; 
        $mail->Password   = $mi_password; 
        
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        // Destinatarios
        $mail->setFrom('unibot1940@gmail.com', 'UniMarket Oficial'); // 👈 Pon tu correo otra vez aquí
        $mail->addAddress($cuenta, $nombre_completo); // Se lo mandamos al que se está registrando

        // 1️⃣ INCRUSTAR LA IMAGEN AL CORREO (Asegúrate de tener la imagen en la carpeta 'img')
        $mail->AddEmbeddedImage('img/unibot_verificacion.png', 'unibot_logo');

        // Contenido del correo 
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
        
        // Si el correo se envía, lo mandamos a la pantalla para meter el código
        // Si el correo se envía, lo mandamos a la pantalla para meter el código
        echo "<script>
            alert('¡Registro casi listo! Te hemos enviado un código de 6 dígitos a tu correo.');
            window.location='Login.html?verificar=' + encodeURIComponent('$cuenta');
        </script>";

    } catch (Exception $e) {
        // Si el correo falla, le avisamos
        echo "Error al enviar el correo. Por favor contacta al administrador. Info: {$mail->ErrorInfo}";
    }

} else {
    echo "Error en la Base de Datos: " . mysqli_error($conexion);
}
?>