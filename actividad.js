// actividad.js

// ========== ACTUALIZAR ÚLTIMA ACTIVIDAD (cada 30 segundos) ==========
function actualizarActividad() {
    fetch('actualizar_actividad.php')
        .catch(err => console.error('Error al actualizar actividad', err));
}

// Iniciar el intervalo cuando la página esté lista
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        actualizarActividad();
        setInterval(actualizarActividad, 30000);
    });
} else {
    actualizarActividad();
    setInterval(actualizarActividad, 30000);
}

// ========== CONTADOR DE USUARIOS ACTIVOS ==========
function actualizarContadorActivos() {
    const badge = document.getElementById('activeUsersCount');
    if (!badge) return;
    
    fetch('usuarios_activos.php')
        .then(res => res.text())
        .then(count => {
            badge.innerText = count;
        })
        .catch(err => console.error('Error al obtener activos', err));
}

// Actualizar contador cada 30 segundos (o menos)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        actualizarContadorActivos();
        setInterval(actualizarContadorActivos, 30000);
    });
} else {
    actualizarContadorActivos();
    setInterval(actualizarContadorActivos, 30000);
}