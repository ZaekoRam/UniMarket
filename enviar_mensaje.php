<?php
session_start();
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(["status" => "error", "msg" => "No autenticado"]);
    exit();
}

if ($_SESSION['rol'] === 'lector') {
    echo json_encode(["status" => "error", "msg" => "Los lectores no pueden enviar mensajes."]);
    exit();
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
$mi_id = $_SESSION['usuario_id'];
$destinatario_id = (int)$_POST['destinatario_id'];

// Verificar que el destinatario no sea lector
$check = mysqli_query($conexion, "SELECT rol FROM usuarios WHERE id = $destinatario_id");
$dest_rol = mysqli_fetch_assoc($check)['rol'] ?? '';
if ($dest_rol === 'lector') {
    echo json_encode(["status" => "error", "msg" => "No puedes enviar mensajes a un lector."]);
    exit();
}

// Procesar imagen si viene
$tipo = 'texto';
$contenido = '';

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    $archivo = $_FILES['imagen'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    $permitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($extension, $permitidas)) {
        $nombreSeguro = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $ruta = 'uploads/mensajes/' . $nombreSeguro;
        if (!is_dir('uploads/mensajes')) mkdir('uploads/mensajes', 0777, true);
        if (move_uploaded_file($archivo['tmp_name'], $ruta)) {
            $tipo = 'imagen';
            $contenido = $ruta;
        } else {
            echo json_encode(["status" => "error", "msg" => "Error al guardar la imagen."]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "msg" => "Formato de imagen no permitido."]);
        exit();
    }
} else {
    // Mensaje de texto
    $contenido = trim(mysqli_real_escape_string($conexion, $_POST['mensaje'] ?? ''));
    if (empty($contenido)) {
        echo json_encode(["status" => "error", "msg" => "El mensaje no puede estar vacío."]);
        exit();
    }
}

$sql = "INSERT INTO mensajes (remitente_id, destinatario_id, mensaje, tipo) 
        VALUES ('$mi_id', '$destinatario_id', '$contenido', '$tipo')";
if (mysqli_query($conexion, $sql)) {
    $mensaje_id = mysqli_insert_id($conexion);

    // 🔔 NOTIFICACIÓN AL DESTINATARIO
    $sql_noti = "INSERT INTO notificaciones (usuario_id, tipo, emisor_id, referencia_id) 
                 VALUES ($destinatario_id, 'mensaje', $mi_id, $mensaje_id)";
    mysqli_query($conexion, $sql_noti);

    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error", "msg" => "Error al guardar mensaje."]);
}
mysqli_close($conexion);
?>