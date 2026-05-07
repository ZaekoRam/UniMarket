document.addEventListener("DOMContentLoaded", () => {
    // Elementos del DOM
    const navLinks = document.querySelectorAll(".nav-link");
    const tabContents = document.querySelectorAll(".tab-content");
    const pageTitle = document.getElementById("page-title");
    const menuToggle = document.getElementById("menuToggle");
    const sidebar = document.getElementById("sidebar");
    const openAddUserBtn = document.getElementById("openAddUserModal");
    const addUserModal = document.getElementById("addUserModal");
    const closeModalElements = [document.getElementById("closeModal"), document.getElementById("cancelModal")];
    const addUserForm = document.getElementById("addUserForm");
    const userSearch = document.getElementById("userSearch");
    const usersTableBody = document.querySelector("#usersTable tbody");
    const configForm = document.getElementById("configForm");
    const totalUsersSpan = document.getElementById("total-users-count");

    let users = [];

    // Toast
    function showToast(message, type = "info") {
        const toastContainer = document.getElementById("toastContainer");
        const toast = document.createElement("div");
        toast.className = `toast ${type}`;
        let icon = "fa-info-circle";
        if (type === "success") icon = "fa-check-circle";
        if (type === "danger") icon = "fa-exclamation-triangle";
        toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
        toastContainer.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = "slideIn 0.3s ease reverse";
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // === Gestión de usuarios (igual que antes) ===
    async function cargarUsuarios() {
        try {
            const resp = await fetch('admin_operaciones.php?accion=listar_usuarios');
            const data = await resp.json();
            if (Array.isArray(data)) {
                users = data;
                renderUsersTable(users);
                if (totalUsersSpan) totalUsersSpan.textContent = users.length;
            } else {
                showToast("Error al cargar usuarios", "danger");
            }
        } catch (error) {
            showToast("Error de red: " + error.message, "danger");
        }
    }

    function renderUsersTable(dataArray) {
        if (!usersTableBody) return;
        usersTableBody.innerHTML = "";
        if (dataArray.length === 0) {
            usersTableBody.innerHTML = `<tr><td colspan="5" style="text-align:center">No hay usuarios</td></tr>`;
            return;
        }
        dataArray.forEach(user => {
            const row = document.createElement("tr");
            let roleClass = "reader";
            if (user.rol === "admin") roleClass = "admin";
            else if (user.rol === "creador") roleClass = "creator";

            row.innerHTML = `
                <td>${user.id}</td>
                <td><strong>${escapeHtml(user.usuario)}</strong><br><small>${escapeHtml(user.nombre_completo || '')}</small></td>
                <td><span class="badge-role ${roleClass}">${user.rol}</span></td>
                <td>
                    <select class="change-role-select" data-id="${user.id}">
                        <option value="admin" ${user.rol === 'admin' ? 'selected' : ''}>Administrador</option>
                        <option value="creador" ${user.rol === 'creador' ? 'selected' : ''}>Creador</option>
                        <option value="lector" ${user.rol === 'lector' ? 'selected' : ''}>Lector</option>
                    </select>
                </td>
                <td class="actions-cell">
                    <button class="btn btn-sm btn-danger delete-user-btn" data-id="${user.id}"><i class="fas fa-trash-alt"></i></button>
                </td>
            `;
            usersTableBody.appendChild(row);
        });

        document.querySelectorAll(".change-role-select").forEach(select => {
            select.addEventListener("change", async (e) => {
                const userId = e.target.getAttribute("data-id");
                const newRole = e.target.value;
                await actualizarRol(userId, newRole);
            });
        });

        document.querySelectorAll(".delete-user-btn").forEach(btn => {
            btn.addEventListener("click", async (e) => {
                const userId = btn.getAttribute("data-id");
                await eliminarUsuario(userId);
            });
        });
    }

    async function actualizarRol(userId, newRole) {
        const formData = new FormData();
        formData.append('accion', 'actualizar_rol');
        formData.append('id', userId);
        formData.append('rol', newRole);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast(`Rol actualizado a ${newRole}`, "success");
                cargarUsuarios();
            } else {
                showToast("Error al actualizar rol", "danger");
            }
        } catch (error) {
            showToast("Error de red", "danger");
        }
    }

    async function eliminarUsuario(userId) {
        if (!confirm("¿Eliminar este usuario permanentemente?")) return;
        const formData = new FormData();
        formData.append('accion', 'eliminar_usuario');
        formData.append('id', userId);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast("Usuario eliminado", "danger");
                cargarUsuarios();
            } else {
                showToast(result.msg || "Error al eliminar", "danger");
            }
        } catch (error) {
            showToast("Error de red", "danger");
        }
    }

    // Crear usuario (los campos extra deben existir en el modal, pero si no, adapta)
    addUserForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const usuario = document.getElementById("newUserName").value.trim();
        // Si no tienes estos campos en el modal, ajusta o comenta
        const nombre_completo = document.getElementById("newUserFullName")?.value.trim() || usuario;
        const num = document.getElementById("newUserNum")?.value.trim() || "00000";
        const cuenta = document.getElementById("newUserEmail")?.value.trim() || "correo@ejemplo.com";
        const rol = document.getElementById("newUserRole").value;
        if (!usuario) {
            showToast("El nombre de usuario es obligatorio", "danger");
            return;
        }
        const formData = new FormData();
        formData.append('accion', 'crear_usuario');
        formData.append('usuario', usuario);
        formData.append('nombre_completo', nombre_completo);
        formData.append('num', num);
        formData.append('cuenta', cuenta);
        formData.append('rol', rol);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast(`Usuario ${usuario} creado. Contraseña temporal: ${result.temp_pass || 'cambiar después'}`, "success");
                closeModal();
                cargarUsuarios();
            } else {
                showToast("Error: " + result.msg, "danger");
            }
        } catch (error) {
            showToast("Error de red", "danger");
        }
    });

    function closeModal() {
        addUserModal.classList.remove("active");
        addUserForm.reset();
    }
    openAddUserBtn.addEventListener("click", () => addUserModal.classList.add("active"));
    closeModalElements.forEach(el => el.addEventListener("click", closeModal));

    // Búsqueda local
    userSearch.addEventListener("input", (e) => {
        const term = e.target.value.toLowerCase();
        const filtered = users.filter(u => u.usuario.toLowerCase().includes(term) || (u.nombre_completo && u.nombre_completo.toLowerCase().includes(term)));
        renderUsersTable(filtered);
    });

    // Configuración (simulada)
    configForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const maintenance = document.getElementById("maintenanceMode").checked;
        showToast(`Ajustes guardados (simulado). Mantenimiento: ${maintenance ? 'ON' : 'OFF'}`, "info");
    });

    document.getElementById("btnExport")?.addEventListener("click", () => {
        showToast("Exportando usuarios a CSV... (simulado)", "info");
        setTimeout(() => showToast("Exportado (simulado)", "success"), 1000);
    });

    // === NUEVAS FUNCIONES PARA PUBLICACIONES PENDIENTES ===
    let postsPendientes = [];

    async function cargarPostsPendientes() {
        try {
            const resp = await fetch('admin_operaciones.php?accion=listar_posts_pendientes');
            const data = await resp.json();
            if (Array.isArray(data)) {
                postsPendientes = data;
                renderPostsPendientesTable(postsPendientes);
                actualizarBadgePublicaciones(postsPendientes.length);
            } else {
                renderPostsPendientesTable([]);
                actualizarBadgePublicaciones(0);
                if (data.msg) showToast(data.msg, "danger");
            }
        } catch (error) {
            console.error("Error cargando posts pendientes:", error);
            renderPostsPendientesTable([]);
            actualizarBadgePublicaciones(0);
            showToast("Error al cargar publicaciones pendientes", "danger");
        }
    }

    function renderPostsPendientesTable(posts) {
        const tbody = document.getElementById("pubRequestsBody");
        if (!tbody) return;
        if (posts.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5">No hay publicaciones pendientes de revisión</td></tr>`;
            return;
        }
        tbody.innerHTML = "";
        posts.forEach(post => {
            const row = document.createElement("tr");
            // Previsualización de archivos
            let archivosHtml = "Sin archivos";
            if (post.imagen) {
                const archivos = post.imagen.split(",").slice(0, 2);
                archivosHtml = archivos.map(a => `<span style="font-size:12px">📎 ${a}</span>`).join(" ");
                if (post.imagen.split(",").length > 2) archivosHtml += " …";
            }
            row.innerHTML = `
                <td><strong>${escapeHtml(post.usuario)}</strong><br><small>${escapeHtml(post.nombre_completo || '')}</small></td>
                <td>${escapeHtml(post.texto ? post.texto.substring(0, 120) : '')}${post.texto && post.texto.length > 120 ? '…' : ''}</td>
                <td>${archivosHtml}</td>
                <td>${new Date(post.fecha).toLocaleString()}</td>
                <td class="actions-cell">
                    <button class="btn btn-sm btn-success aprobar-post" data-id="${post.id}"><i class="fas fa-check"></i> Aprobar</button>
                    <button class="btn btn-sm btn-danger rechazar-post" data-id="${post.id}"><i class="fas fa-times"></i> Rechazar</button>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Asignar eventos a los nuevos botones
        document.querySelectorAll(".aprobar-post").forEach(btn => {
            btn.addEventListener("click", () => aprobarPost(btn.getAttribute("data-id")));
        });
        document.querySelectorAll(".rechazar-post").forEach(btn => {
            btn.addEventListener("click", () => rechazarPost(btn.getAttribute("data-id")));
        });
    }

    async function aprobarPost(postId) {
        const formData = new FormData();
        formData.append('accion', 'aprobar_post');
        formData.append('post_id', postId);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast(`Publicación #${postId} aprobada ✅`, "success");
                cargarPostsPendientes(); // recargar lista
            } else {
                showToast("Error al aprobar", "danger");
            }
        } catch (error) {
            showToast("Error de red", "danger");
        }
    }

    async function rechazarPost(postId) {
        const formData = new FormData();
        formData.append('accion', 'rechazar_post');
        formData.append('post_id', postId);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast(`Publicación #${postId} rechazada ❌`, "danger");
                cargarPostsPendientes();
            } else {
                showToast("Error al rechazar", "danger");
            }
        } catch (error) {
            showToast("Error de red", "danger");
        }
    }

    function actualizarBadgePublicaciones(cantidad) {
        const badge = document.getElementById("pub-badge");
        if (badge) {
            badge.textContent = cantidad;
            badge.style.display = cantidad > 0 ? "inline-block" : "none";
        }
    }

    // === Navegación: al hacer clic en "Petición de Publicación", cargar los posts pendientes ===
    const pubNavLink = document.querySelector('.nav-link[data-target="peticion-publicacion"]');
    if (pubNavLink) {
        pubNavLink.addEventListener("click", () => {
            cargarPostsPendientes();
        });
    }

    // También cargar al inicio si la pestaña activa es esa (por si se recarga con esa pestaña)
    if (document.querySelector('#peticion-publicacion').classList.contains('active')) {
        cargarPostsPendientes();
    }

    // Navegación original
    navLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove("active"));
            link.classList.add("active");
            const target = link.getAttribute("data-target");
            tabContents.forEach(tab => tab.classList.remove("active"));
            const targetTab = document.getElementById(target);
            if (targetTab) targetTab.classList.add("active");
            pageTitle.textContent = link.textContent.trim();
            if (window.innerWidth <= 768) sidebar.classList.remove("active");
        });
    });
    menuToggle.addEventListener("click", () => sidebar.classList.toggle("active"));

    // Inicializar tabla de usuarios
    cargarUsuarios();
});

// Funciones auxiliares globales (para los mock de peticiones de rol)
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Mock peticiones de rol (siguen siendo demo)
window.approveRequest = function(name, targetRole, rowId) {
    const row = document.getElementById(rowId);
    if (row) row.remove();
    const badge = document.getElementById("req-badge");
    if (badge) { let val = parseInt(badge.textContent); if (val > 1) badge.textContent = val - 1; else badge.style.display = "none"; }
    showToast(`Petición aprobada: ${name} ahora es ${targetRole} (demo)`, "success");
};
window.rejectRequest = function(rowId) {
    const row = document.getElementById(rowId);
    if (row) row.remove();
    const badge = document.getElementById("req-badge");
    if (badge) { let val = parseInt(badge.textContent); if (val > 1) badge.textContent = val - 1; else badge.style.display = "none"; }
    showToast("Petición denegada (demo)", "danger");
};

function showToast(message, type) {
    const container = document.getElementById("toastContainer");
    if (!container) return;
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    let icon = type === "success" ? "fa-check-circle" : (type === "danger" ? "fa-exclamation-triangle" : "fa-info-circle");
    toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 3500);
}