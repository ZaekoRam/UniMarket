<?php
session_start();
require 'credenciales.php';

// Si ya hay sesión de administrador, redirigir al dashboard
if (isset($_SESSION['usuario_id']) && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    header('Location: dashboard.html');
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
                    header('Location: dashboard.html');
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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e2a3a, #0f172a);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Botón de regreso */
        .back-button {
            position: fixed;
            top: 20px;
            right: 30px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(5px);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 10px 20px;
            border-radius: 40px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            z-index: 10;
        }

        .back-button:hover {
            background: rgba(255,255,255,0.3);
            transform: translateX(-3px);
        }

        .login-container {
            background: rgba(255,255,255,0.95);
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 420px;
            padding: 40px 35px;
            transition: 0.3s;
            backdrop-filter: blur(8px);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #0f172a;
            font-weight: 600;
            font-size: 1.8rem;
        }

        .login-container h2 i {
            color: #3b82f6;
            margin-right: 8px;
        }

        .input-group {
            margin-bottom: 25px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #1e293b;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            font-size: 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            transition: all 0.2s;
            outline: none;
        }

        .input-group input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
        }

        button[type="submit"] {
            width: 100%;
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 40px;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        button[type="submit"]:hover {
            background: #2563eb;
        }

        .error-message {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
        }

        .admin-icon {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 10px;
            color: #3b82f6;
        }

        footer {
            text-align: center;
            margin-top: 25px;
            font-size: 0.8rem;
            color: #64748b;
        }
    </style>
</head>
<body>
    <a href="/" class="back-button" id="backToHome">
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
                <label><i class="fas fa-user"></i> Usuario</label>
                <input type="text" name="usuario" required autofocus placeholder="Ej: marco">
            </div>
            <div class="input-group">
                <label><i class="fas fa-lock"></i> Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>
            <button type="submit">
                <i class="fas fa-sign-in-alt"></i> Iniciar sesión
            </button>
        </form>
        <footer>
            Acceso restringido solo a administradores.
        </footer>
    </div>

    <!-- Font Awesome (opcional si ya lo usas en otros lados) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>