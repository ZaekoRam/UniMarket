<?php
session_start();
require 'credenciales.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit();
}

$mi_id = (int)$_SESSION['usuario_id'];
$mi_rol = $_SESSION['rol'] ?? '';

if ($mi_rol === 'lector') {
    echo json_encode([]);
    exit();
}

$conexion = mysqli_connect($host_db, $user_db, $pass_db, $name_db);
if (!$conexion) {
    echo json_encode([]);
    exit();
}

<<<<<<< HEAD
$sql = "SELECT u.id, u.nombre_completo, u.usuario, u.foto_perfil, u.mostrar_estado,
=======
$sql = "SELECT u.id, u.nombre_completo, u.usuario, u.foto_perfil,
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
        (SELECT mensaje FROM mensajes 
         WHERE (remitente_id = $mi_id AND destinatario_id = u.id)
            OR (remitente_id = u.id AND destinatario_id = $mi_id)
         ORDER BY fecha DESC LIMIT 1) as ultimo_mensaje,
        (SELECT fecha FROM mensajes 
         WHERE (remitente_id = $mi_id AND destinatario_id = u.id)
            OR (remitente_id = u.id AND destinatario_id = $mi_id)
         ORDER BY fecha DESC LIMIT 1) as ultima_fecha,
        CASE 
<<<<<<< HEAD
            WHEN u.mostrar_estado = 1 AND u.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 1
=======
            WHEN u.last_activity > DATE_SUB(NOW(), INTERVAL 5 MINUTE) THEN 1
>>>>>>> bcc9edcc113f84b9ae2a0f9fb0f254c375b5c30f
            ELSE 0
        END as is_online
        FROM usuarios u
        WHERE u.id != $mi_id AND u.rol != 'lector'
        ORDER BY ultima_fecha DESC, u.nombre_completo ASC";

$resultado = mysqli_query($conexion, $sql);
$contactos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
echo json_encode($contactos);
mysqli_close($conexion);
?>