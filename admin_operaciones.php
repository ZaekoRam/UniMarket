<?php
session_start();
require 'credenciales.php';

// Verificar sesión y rol 'admin'
if (!isset($_SESSION['usuario_id']) || $_SESSION['rol'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'msg' => 'Acceso denegado']);
    exit;
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    die(json_encode(['status' => 'error', 'msg' => 'Error de conexión DB']));
}

$accion = $_POST['accion'] ?? $_GET['accion'] ?? '';
header('Content-Type: application/json');

switch ($accion) {
    case 'listar_usuarios':
        $res = mysqli_query($conexion, "SELECT id, usuario, nombre_completo, rol FROM usuarios");
        $users = mysqli_fetch_all($res, MYSQLI_ASSOC);
        echo json_encode($users);
        break;

    case 'crear_usuario':
        $usuario = trim($_POST['usuario'] ?? '');
        $nombre_completo = trim($_POST['nombre_completo'] ?? '');
        $num = trim($_POST['num'] ?? '');
        $cuenta = trim($_POST['cuenta'] ?? '');
        $rol = $_POST['rol'] ?? 'lector';
        $password_plano = bin2hex(random_bytes(5));
        $hash = password_hash($password_plano, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conexion, "INSERT INTO usuarios (usuario, nombre_completo, num, cuenta, PASSWORD, rol) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssssss", $usuario, $nombre_completo, $num, $cuenta, $hash, $rol);
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'ok', 'msg' => 'Usuario creado', 'temp_pass' => $password_plano]);
        } else {
            echo json_encode(['status' => 'error', 'msg' => 'Error: ' . mysqli_error($conexion)]);
        }
        mysqli_stmt_close($stmt);
        break;

    case 'actualizar_rol':
        $id = intval($_POST['id']);
        $nuevoRol = $_POST['rol'];
        $stmt = mysqli_prepare($conexion, "UPDATE usuarios SET rol = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "si", $nuevoRol, $id);
        $ok = mysqli_stmt_execute($stmt);
        echo json_encode(['status' => $ok ? 'ok' : 'error']);
        mysqli_stmt_close($stmt);
        break;

    case 'eliminar_usuario':
        $id = intval($_POST['id']);
        if ($id == $_SESSION['usuario_id']) {
            echo json_encode(['status' => 'error', 'msg' => 'No puedes eliminarte a ti mismo']);
            break;
        }
        $stmt = mysqli_prepare($conexion, "DELETE FROM usuarios WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        $ok = mysqli_stmt_execute($stmt);
        echo json_encode(['status' => $ok ? 'ok' : 'error']);
        mysqli_stmt_close($stmt);
        break;

    // ========== NUEVAS ACCIONES PARA GESTIÓN DE PUBLICACIONES ==========
    case 'listar_posts_pendientes':
        $res = mysqli_query($conexion, "SELECT p.id, p.texto, p.imagen, p.fecha, u.usuario, u.nombre_completo 
                                        FROM publicaciones p 
                                        LEFT JOIN usuarios u ON p.usuario_id = u.id 
                                        WHERE p.estado = 'pendiente' 
                                        ORDER BY p.fecha DESC");
        $pendientes = mysqli_fetch_all($res, MYSQLI_ASSOC);
        echo json_encode($pendientes);
        break;

    case 'aprobar_post':
        $post_id = intval($_POST['post_id']);
        $stmt = mysqli_prepare($conexion, "UPDATE publicaciones SET estado = 'aprobado' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        $ok = mysqli_stmt_execute($stmt);
        echo json_encode(['status' => $ok ? 'ok' : 'error']);
        mysqli_stmt_close($stmt);
        break;

    case 'rechazar_post':
        $post_id = intval($_POST['post_id']);
        $stmt = mysqli_prepare($conexion, "UPDATE publicaciones SET estado = 'rechazado' WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $post_id);
        $ok = mysqli_stmt_execute($stmt);
        echo json_encode(['status' => $ok ? 'ok' : 'error']);
        mysqli_stmt_close($stmt);
        break;

    default:
        echo json_encode(['status' => 'error', 'msg' => 'Acción no válida']);
}
mysqli_close($conexion);
?>