<?php
session_start();
require 'credenciales.php';

// Si ya hay sesión de administrador, redirigir al dashboard
if (isset($_SESSION['usuario_id']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    header('Location: dashboard');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($usuario) && !empty($password)) {
        $conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
        if (!$conexion) {
            $error = 'Error de conexión a la base de datos.';
        } else {
            // Buscar usuario por nombre de usuario
            $stmt = mysqli_prepare($conexion, "SELECT id, usuario, PASSWORD, rol FROM usuarios WHERE usuario = ?");
            mysqli_stmt_bind_param($stmt, "s", $usuario);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user['PASSWORD'])) {
                // Verificar que el rol sea 'admin'
                if ($user['rol'] === 'admin') {
                    $_SESSION['usuario_id'] = $user['id'];
                    $_SESSION['rol'] = $user['rol'];
                    header('Location: dashboard');
                    exit;
                } else {
                    $error = 'Acceso denegado: No tienes permisos de administrador.';
                }
            } else {
                $error = 'Usuario o contraseña incorrectos.';
            }
            mysqli_close($conexion);
        }
    } else {
        $error = 'Por favor, completa todos los campos.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Administrador</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
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
            --shadow: 0 18px 45px rgba(0, 0, 0, 0.45);
            --radius: 28px;
            --border-glow: rgba(102, 255, 240, 0.25);
            --error-red: #ff3366;
            --input-bg: rgba(255, 255, 255, 0.05);
            --input-border: rgba(57, 197, 187, 0.2);
        }
        body.light-mode {
            --bg-main: linear-gradient(135deg, #dffcf9, #c9f6f0, #eefefd);
            --accent: #39c5bb;
            --accent-2: #0f8f87;
            --text: #10211c;
            --muted: #4f7c76;
            --panel: rgba(236, 250, 248, 0.88);
            --shadow: 0 18px 45px rgba(20, 70, 50, 0.12);
            --border-glow: rgba(15, 143, 135, 0.25);
            --error-red: #e63950;
            --input-bg: rgba(0, 0, 0, 0.04);
            --input-border: rgba(57, 197, 187, 0.35);
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
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: repeating-linear-gradient(0deg, rgba(0,0,0,0.08) 0px, rgba(0,0,0,0.08) 2px, transparent 2px, transparent 6px);
            pointer-events: none;
            z-index: 997;
            animation: scanlines 8s linear infinite;
        }
        @keyframes scanlines {
            from { background-position: 0 0; }
            to   { background-position: 0 20px; }
        }
        body::after {
            content: '';
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: radial-gradient(circle at 50% 50%, rgba(57,197,187,0.08) 0%, transparent 60%);
            pointer-events: none;
            z-index: 996;
            animation: bgPulse 4s ease-in-out infinite;
        }
        @keyframes bgPulse {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50%       { opacity: 1;   transform: scale(1.05); }
        }

        /* Botón de regreso */
        .back-button {
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
            font-size: 0.9rem;
            box-shadow: 0 10px 25px rgba(0,0,0,.22);
            backdrop-filter: blur(12px);
            transition: all 0.3s;
            z-index: 1000;
        }
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(0,0,0,.28), 0 0 22px rgba(102,255,240,0.30);
        }

        /* Card principal */
        .login-container {
            position: relative;
            z-index: 10;
            background: var(--panel);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-glow);
            border-radius: var(--radius);
            padding: 48px 40px;
            max-width: 460px;
            width: 90%;
            text-align: center;
            box-shadow: var(--shadow);
            animation: cardReveal 0.6s ease;
        }
        @keyframes cardReveal {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Ícono superior */
        .admin-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(57, 197, 187, 0.12);
            border: 1px solid var(--border-glow);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: var(--accent);
            margin: 0 auto 20px;
            animation: iconPulse 3s ease-in-out infinite;
        }
        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(57,197,187,0.3); }
            50%       { box-shadow: 0 0 0 12px rgba(57,197,187,0); }
        }

        .login-container h2 {
            color: var(--text);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 30px;
            letter-spacing: -0.5px;
        }

        /* Grupos de input */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }
        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: var(--muted);
            text-transform: uppercase;
        }
        .input-wrapper {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 0.95rem;
            pointer-events: none;
        }
        .input-group input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 14px;
            color: var(--text);
            font-size: 15px;
            font-family: monospace;
            transition: all 0.3s;
            outline: none;
        }
        .input-group input::placeholder { color: var(--muted); opacity: 0.6; }
        .input-group input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(57,197,187,0.15);
        }

        /* Botón submit */
        button[type="submit"] {
            position: relative;
            width: 100%;
            padding: 16px;
            background: var(--accent-gradient);
            border: none;
            border-radius: 50px;
            color: #082016;
            font-weight: 800;
            font-size: 16px;
            font-family: 'Space Grotesk', sans-serif;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s;
            margin-top: 10px;
        }
        button[type="submit"]::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        button[type="submit"]:hover::before { left: 100%; }
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 25px var(--accent);
        }

        /* Mensaje de error */
        .error-message {
            background: rgba(255, 51, 102, 0.1);
            border: 1px solid rgba(255, 51, 102, 0.35);
            color: var(--error-red);
            padding: 12px 16px;
            border-radius: 14px;
            margin-bottom: 20px;
            font-size: 0.88rem;
            text-align: center;
        }

        footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.78rem;
            color: var(--muted);
            opacity: 0.7;
        }

        @media (max-width: 550px) {
            .login-container { padding: 32px 24px; }
            .login-container h2 { font-size: 1.5rem; }
            .admin-icon { width: 65px; height: 65px; font-size: 1.8rem; }
            .back-button { padding: 8px 16px; font-size: 0.8rem; }
        }
    </style>
</head>
<body>
    <a href="/" class="back-button">
        <i class="fas fa-arrow-left"></i> Regresar a la página principal
    </a>

    <div class="login-container">
        <div class="admin-icon">
            <i class="fas fa-shield-alt"></i>
        </div>
        <h2>Panel de Administración</h2>

        <?php if (!empty($error)): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="input-group">
                <label>👤 Usuario</label>
                <div class="input-wrapper">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="usuario" required autofocus placeholder="Ej: marco">
                </div>
            </div>
            <div class="input-group">
                <label>🔒 Contraseña</label>
                <div class="input-wrapper">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" required placeholder="••••••••">
                </div>
            </div>
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Iniciar sesión
            </button>
        </form>
        <footer>
            Acceso restringido solo a administradores.
        </footer>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        // Sincronizar tema con el resto del sitio
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
                document.body.classList.toggle("light-mode", e.newValue === "light");
            }
        });
    </script>
</body>
</html>