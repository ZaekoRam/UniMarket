<?php
session_start();
require 'credenciales.php';

// ========== 1. PROCESAR FORMULARIO ==========
$mensaje = '';
$tipo_mensaje = 'error';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    
    if (empty($correo)) {
        $mensaje = "❌ Por favor ingresa tu correo electrónico.";
        $tipo_mensaje = "error";
    } else {
        // Conectar a la base de datos
        $conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
        if (!$conexion) {
            $mensaje = "❌ Error de conexión a la base de datos.";
            $tipo_mensaje = "error";
        } else {
            // Buscar usuario por correo
            $correo_escape = mysqli_real_escape_string($conexion, $correo);
            $query = "SELECT id, usuario, nombre_completo FROM usuarios WHERE cuenta = '$correo_escape'";
            $result = mysqli_query($conexion, $query);
            
            if (mysqli_num_rows($result) === 0) {
                $mensaje = "❌ No existe una cuenta con ese correo electrónico.";
                $tipo_mensaje = "error";
            } else {
                $user = mysqli_fetch_assoc($result);
                $usuario_id = $user['id'];
                $usuario_nombre = $user['nombre_completo'];
                $usuario_login = $user['usuario'];
                
                // Generar token único (64 caracteres)
                $token = bin2hex(random_bytes(32));
                $expiracion = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                
                // Guardar token en la base de datos
                $update = "UPDATE usuarios SET token_recuperacion = '$token', token_expira = '$expiracion' WHERE id = $usuario_id";
                if (mysqli_query($conexion, $update)) {
                    // Enviar correo
                    require 'PHPMailer/src/Exception.php';
                    require 'PHPMailer/src/PHPMailer.php';
                    require 'PHPMailer/src/SMTP.php';
                    
                    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = $mi_correo; // tu correo
                        $mail->Password   = $mi_password_correo; // tu contraseña de aplicación
                        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
                        $mail->Port       = 465;
                        
                        $mail->setFrom('unibot1940@gmail.com', 'UniMarket Oficial');
                        $mail->addAddress($correo, $usuario_nombre);
                        
                        $enlace = "http://" . $_SERVER['HTTP_HOST'] . "/restablecer.php?token=" . $token;
                        
                        $mail->isHTML(true);
                        $mail->Subject = 'Recuperación de contraseña - UniMarket';
                        $mail->Body    = "
                            <div style='font-family: Arial, sans-serif; text-align: center; padding: 20px; background-color: #f4f4f4; border-radius: 10px;'>
                                <h2 style='color: #0f8f87;'>¡Hola $usuario_nombre!</h2>
                                <p style='color: #333; font-size: 16px;'>Recibimos una solicitud para restablecer tu contraseña.</p>
                                <p style='color: #333; font-size: 16px;'>Haz clic en el siguiente enlace para crear una nueva contraseña (válido por 10 minutos):</p>
                                <a href='$enlace' style='display: inline-block; background: #39c5bb; color: #082016; padding: 12px 24px; text-decoration: none; border-radius: 30px; font-weight: 800; margin: 20px 0;'>Restablecer contraseña</a>
                                <p style='color: #777; font-size: 12px;'>Si no solicitaste este cambio, ignora este correo.</p>
                                <hr style='margin: 20px 0;'>
                                <p style='color: #999; font-size: 11px;'>El enlace expirará en 10 minutos.</p>
                            </div>
                        ";
                        
                        $mail->send();
                        $mensaje = "✅ Se ha enviado un enlace de recuperación a tu correo electrónico. Revisa tu bandeja de entrada (y spam).";
                        $tipo_mensaje = "success";
                    } catch (Exception $e) {
                        $mensaje = "❌ Error al enviar el correo. Intenta nuevamente más tarde.";
                        $tipo_mensaje = "error";
                    }
                } else {
                    $mensaje = "❌ Error al generar el enlace de recuperación.";
                    $tipo_mensaje = "error";
                }
            }
            mysqli_close($conexion);
        }
    }
    
    // Redirigir con mensaje
    header("Location: recuperar.php?msg=" . urlencode($mensaje) . "&type=" . $tipo_mensaje);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña · UniMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg-main: linear-gradient(135deg, #051312, #081f1d, #03100f);
            --accent: #39c5bb;
            --accent-2: #66fff0;
            --accent-gradient: linear-gradient(135deg, #66fff0, #39c5bb);
            --text: #eafffb;
            --muted: #9bded7;
            --panel: rgba(10, 24, 23, 0.88);
            --panel-soft: rgba(255, 255, 255, 0.04);
            --shadow: 0 18px 45px rgba(0, 0, 0, 0.45);
            --radius: 28px;
            --border-glow: rgba(102, 255, 240, 0.25);
            --error-red: #ff3366;
            --success-green: #39c5bb;
        }
        body.light-mode {
            --bg-main: linear-gradient(135deg, #dffcf9, #c9f6f0, #eefefd);
            --accent: #39c5bb;
            --accent-2: #0f8f87;
            --text: #10211c;
            --muted: #4f7c76;
            --panel: rgba(236, 250, 248, 0.88);
            --panel-soft: rgba(6, 27, 26, 0.05);
            --shadow: 0 18px 45px rgba(20, 70, 50, 0.12);
            --border-glow: rgba(15, 143, 135, 0.25);
            --error-red: #e63950;
        }
        body {
            font-family: 'Space Grotesk', 'Inter', sans-serif;
            background: var(--bg-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            overflow-x: hidden;
            transition: background 0.5s ease, color 0.5s ease;
        }
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(0deg, rgba(0, 0, 0, 0.08) 0px, rgba(0, 0, 0, 0.08) 2px, transparent 2px, transparent 6px);
            pointer-events: none;
            z-index: 997;
            animation: scanlines 8s linear infinite;
        }
        @keyframes scanlines {
            from { background-position: 0 0; }
            to { background-position: 0 20px; }
        }
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(57, 197, 187, 0.08) 0%, transparent 60%);
            pointer-events: none;
            z-index: 996;
            animation: bgPulse 4s ease-in-out infinite;
        }
        @keyframes bgPulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }
        .reset-card {
            position: relative;
            z-index: 10;
            background: var(--panel);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glow);
            border-radius: var(--radius);
            padding: 48px 40px;
            max-width: 480px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: cardReveal 0.6s ease;
        }
        @keyframes cardReveal {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .reset-card:hover {
            border-color: var(--accent);
            box-shadow: var(--shadow), 0 0 40px rgba(57, 197, 187, 0.2);
            transform: translateY(-5px);
        }
        .key-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, rgba(57, 197, 187, 0.15), rgba(102, 255, 240, 0.05));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: var(--accent);
            border: 2px solid rgba(57, 197, 187, 0.3);
            animation: iconPulse 2s ease-in-out infinite;
        }
        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(57, 197, 187, 0.4); }
            50% { box-shadow: 0 0 0 15px rgba(57, 197, 187, 0); }
        }
        h1 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(135deg, var(--accent-2), var(--accent));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 12px;
        }
        .subtitle {
            color: var(--muted);
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        .input-group {
            position: relative;
            margin-bottom: 24px;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--accent-2);
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: monospace;
        }
        .input-wrapper {
            position: relative;
            width: 100%;
        }
        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 16px;
            z-index: 2;
            pointer-events: none;
        }
        .input-wrapper input {
            width: 100%;
            padding: 16px 50px 16px 45px;
            background: var(--panel-soft);
            border: 2px solid rgba(102, 255, 240, 0.15);
            border-radius: 50px;
            color: var(--text);
            font-size: 16px;
            font-family: monospace;
            transition: all 0.3s;
            outline: none;
        }
        .input-wrapper input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(57, 197, 187, 0.15);
        }
        .btn-update {
            position: relative;
            width: 100%;
            padding: 16px;
            background: var(--accent-gradient);
            border: none;
            border-radius: 50px;
            color: #082016;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s;
            margin-top: 16px;
        }
        .btn-update::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        .btn-update:hover::before {
            left: 100%;
        }
        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px var(--accent);
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--muted);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            padding: 8px 16px;
            border-radius: 40px;
            background: rgba(255, 255, 255, 0.03);
            margin-top: 24px;
        }
        .back-link:hover {
            color: var(--accent-2);
            background: rgba(57, 197, 187, 0.1);
            gap: 12px;
        }
        .home-chip {
            position: fixed;
            top: 20px;
            left: 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 22px;
            border-radius: 999px;
            background: linear-gradient(180deg, #39c5bb, #0f8f87);
            border: 1px solid rgba(102, 255, 240, 0.25);
            color: #ffffff;
            text-decoration: none;
            font-weight: 800;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .22);
            backdrop-filter: blur(12px);
            transition: all 0.3s;
            z-index: 100;
        }
        .home-chip:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, .28), 0 0 22px rgba(102, 255, 240, 0.30);
        }
        .toast-container {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            text-align: center;
        }
        .toast {
            padding: 12px 24px;
            background: var(--panel);
            border: 1px solid var(--accent);
            border-radius: 50px;
            color: var(--accent-2);
            font-family: monospace;
            font-size: 14px;
            backdrop-filter: blur(10px);
            animation: toastSlide 0.3s ease;
        }
        @keyframes toastSlide {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 550px) {
            .reset-card { padding: 32px 24px; }
            h1 { font-size: 26px; }
            .key-icon { width: 65px; height: 65px; font-size: 32px; }
            .home-chip { padding: 8px 16px; font-size: 12px; }
        }
    </style>
</head>
<body>
    <a href="index" class="home-chip">
        <i class="fas fa-arrow-left"></i> Volver al inicio
    </a>

    <div class="reset-card">
        <div class="key-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <h1>Recuperar contraseña</h1>
        <p class="subtitle">Te enviaremos un enlace para restablecer tu contraseña.</p>
        
        <?php if (isset($_GET['msg']) && isset($_GET['type'])): ?>
            <div class="toast" style="margin-bottom: 24px; <?php echo $_GET['type'] === 'success' ? 'border-color: var(--success-green); color: var(--success-green);' : 'border-color: var(--error-red); color: var(--error-red);' ?>">
                <i class="fas <?php echo $_GET['type'] === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?>"></i>
                <?php echo htmlspecialchars($_GET['msg']); ?>
            </div>
        <?php endif; ?>

        <form action="recuperar.php" method="POST">
            <div class="input-group">
                <label>📧 CORREO ELECTRÓNICO</label>
                <div class="input-wrapper">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="correo" placeholder="tu@correo.com" required>
                </div>
            </div>
            <button type="submit" class="btn-update">
                <i class="fas fa-paper-plane"></i> Enviar enlace
            </button>
        </form>

        <a href="index" class="back-link">
            <i class="fas fa-arrow-left"></i> Volver al inicio
        </a>
    </div>

    <div class="toast-container" id="toastContainer"></div>

    <script>
        function applySavedTheme() {
            const savedTheme = localStorage.getItem("theme");
            if (savedTheme === "light") {
                document.body.classList.add("light-mode");
            } else {
                document.body.classList.remove("light-mode");
            }
        }
        applySavedTheme();
        window.addEventListener("storage", (e) => {
            if (e.key === "theme") {
                if (e.newValue === "light") {
                    document.body.classList.add("light-mode");
                } else {
                    document.body.classList.remove("light-mode");
                }
            }
        });

        function processUrlMessage() {
            const urlParams = new URLSearchParams(window.location.search);
            const msg = urlParams.get('msg');
            const type = urlParams.get('type');
            if (msg) {
                const container = document.getElementById("toastContainer");
                const toast = document.createElement("div");
                toast.className = "toast";
                toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'}"></i> ${decodeURIComponent(msg)}`;
                container.appendChild(toast);
                setTimeout(() => toast.remove(), 5000);
                const newUrl = window.location.pathname;
                window.history.replaceState({}, document.title, newUrl);
            }
        }
        processUrlMessage();
    </script>
</body>
</html>