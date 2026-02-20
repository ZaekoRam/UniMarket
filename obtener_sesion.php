<?php
session_start();
echo json_encode([
    'usuario' => $_SESSION['usuario'] ?? null,
    'rol' => $_SESSION['rol'] ?? null
]);
?>