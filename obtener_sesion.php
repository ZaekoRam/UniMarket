<?php
session_start();
echo json_encode([
    'usuario_id' => $_SESSION['usuario_id'] ?? null, 
    'usuario' => $_SESSION['usuario'] ?? null,
    'rol' => $_SESSION['rol'] ?? null
]);
?>