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
    const totalUsersSpan = document.getElementById("total-users-count");

    const translations = {
        es: {
            inicio: "Inicio",
            usuarios: "Usuarios",
            publicaciones: "Publicaciones",
            panelControl: "Panel de Control",
            nuevoUsuario: "Nuevo Usuario",
            exportarDatos: "Exportar Datos",
            volverMenu: "Volver al menú",
            cerrarSesion: "Cerrar sesión",
            gestionUsuarios: "Gestión de Usuarios",
            gestionaUsuarios: "Administra los miembros de la plataforma, busca por nombre y edita sus roles de acceso.",
            buscarUsuario: "Buscar usuario...",
            graficosUsuarios: "Gráfico de Usuarios",
            monitoreo24h: "Monitoreo de tráfico activo de las últimas 24 horas.",
            estadoServidor: "Estado del Servidor",
            usoCpu: "Uso de CPU",
            memoriaRam: "Memoria RAM",
            espacioDisco: "Espacio en Disco",
            usuariosConectados: "Usuarios Conectados",
            vsAyer: "+12% vs ayer",
            usuariosTotales: "Usuarios Totales",
            esteMes: "+5% este mes",
            visitasHoy: "Visitas Hoy",
            vsAyer2: "-2% vs ayer",
            graficoDinamico: "[Gráfico Dinámico: 150 Conectados / 340 Totales]",
            id: "ID",
            nombre: "Nombre",
            correo: "Correo",
            rolActual: "Rol Actual",
            cambiarRol: "Cambiar Rol",
            acciones: "Acciones",
            descPublicaciones: "Publicaciones",
            usuario: "Usuario",
            contenido: "Contenido",
            archivos: "Archivos",
            fecha: "Fecha",
            accion: "Acción",
            cargandoPublicaciones: "Cargando publicaciones...",
            anadirNuevoUsuario: "Añadir Nuevo Usuario",
            nombreDeUsuario: "Nombre de Usuario",
            ejemploJuan: "Ej: Juan Pérez",
            rol: "Rol",
            admin: "Administrador",
            creador: "Creador",
            lector: "Lector",
            cancelar: "Cancelar",
            crearUsuario: "Crear Usuario",
            eliminar: "Eliminar",
            desbanear: "Desbanear",
            banear: "Banear",
            sinArchivos: "Sin archivos"
        },
        en: {
            inicio: "Home",
            usuarios: "Users",
            publicaciones: "Posts",
            panelControl: "Control Panel",
            nuevoUsuario: "New User",
            exportarDatos: "Export Data",
            volverMenu: "Back to Menu",
            cerrarSesion: "Log out",
            gestionUsuarios: "User Management",
            gestionaUsuarios: "Manage platform members, search by name and edit their access roles.",
            buscarUsuario: "Search user...",
            graficosUsuarios: "User Chart",
            monitoreo24h: "Monitor active traffic from the last 24 hours.",
            estadoServidor: "Server Status",
            usoCpu: "CPU Usage",
            memoriaRam: "RAM Usage",
            espacioDisco: "Disk Space",
            usuariosConectados: "Connected Users",
            vsAyer: "+12% vs yesterday",
            usuariosTotales: "Total Users",
            esteMes: "+5% this month",
            visitasHoy: "Visits Today",
            vsAyer2: "-2% vs yesterday",
            graficoDinamico: "[Dynamic Chart: 150 Connected / 340 Total]",
            id: "ID",
            nombre: "Name",
            correo: "Email",
            rolActual: "Current Role",
            cambiarRol: "Change Role",
            acciones: "Actions",
            descPublicaciones: "Posts",
            usuario: "User",
            contenido: "Content",
            archivos: "Files",
            fecha: "Date",
            accion: "Action",
            cargandoPublicaciones: "Loading posts...",
            anadirNuevoUsuario: "Add New User",
            nombreDeUsuario: "Username",
            ejemploJuan: "Ex: John Doe",
            rol: "Role",
            admin: "Administrator",
            creador: "Creator",
            lector: "Reader",
            cancelar: "Cancel",
            crearUsuario: "Create User",
            eliminar: "Delete",
            desbanear: "Unban",
            banear: "Ban",
            sinArchivos: "No files"
        }
    };

    function getStoredLang() {
        return localStorage.getItem("lang") || "es";
    }

    function tDash(key) {
        const lang = getStoredLang();
        return translations[lang] && translations[lang][key] ? translations[lang][key] : key;
    }

    function applyDashboardLanguage() {
        const lang = getStoredLang();
        document.documentElement.lang = lang;

        document.querySelectorAll("[data-i18n]").forEach(el => {
            const key = el.dataset.i18n;
            if (translations[lang] && translations[lang][key]) {
                el.textContent = translations[lang][key];
            }
        });

        document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
            const key = el.getAttribute("data-i18n-placeholder");
            if (translations[lang] && translations[lang][key]) {
                el.placeholder = translations[lang][key];
            }
        });

        if (userSearch) {
            userSearch.placeholder = tDash("buscarUsuario");
        }

        const activeTabText = document.querySelector(".nav-link.active .nav-text")?.textContent;
        pageTitle.textContent = activeTabText || tDash("panelControl");
    }

    function applyDashboardTheme() {
        const theme = localStorage.getItem("theme") || "dark";
        document.body.classList.toggle("light-mode", theme === "light");
    }

    applyDashboardTheme();
    applyDashboardLanguage();

    // Escuchar cambios de idioma y tema desde otras páginas (menu.html, etc)
    window.addEventListener("storage", (event) => {
        if (event.key === "lang") {
            applyDashboardLanguage();
        } else if (event.key === "theme") {
            applyDashboardTheme();
        }
    });

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

    function updatePubBadge(count) {
        const badge = document.getElementById("pub-badge");
        if (!badge) return;
        badge.textContent = Number.isFinite(count) ? String(count) : "0";
    }

    function updatePublicacionesHeading(count) {
        const title = document.getElementById("page-title");
        if (!title) return;
        // Sólo actualizamos el título si estamos en la pestaña de publicaciones,
        // ya que este heading es global y podría sobreescribir "Inicio"
        const activeTab = document.querySelector(".nav-link.active");
        if (activeTab && activeTab.getAttribute("data-target") === "publicaciones") {
            title.textContent = `${tDash('publicaciones')}: ${Number.isFinite(count) ? count : 0}`;
        }
    }

    // ==================== USUARIOS ====================

    async function cargarUsuarios() {
        try {
            const resp = await fetch('admin_operaciones.php?accion=listar_usuarios');
            const data = await resp.json();
            if (Array.isArray(data)) {
                users = data;
                renderUsersTable(users);
                if (totalUsersSpan) totalUsersSpan.textContent = users.length;
            } else {
                showToast(tDash('errorCargarUsuarios'), "danger");
            }
        } catch (error) {
            showToast(tDash('errorRed') + ": " + error.message, "danger");
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

            // Columna de acciones: botón eliminar + (si es lector) botón desbanear
            let accionesHtml = `<button class="btn btn-sm btn-danger delete-user-btn" data-id="${user.id}"><i class="fas fa-trash-alt"></i> ${tDash('eliminar')}</button>`;
            if (user.rol === 'lector') {
                accionesHtml += `<button class="btn btn-sm btn-success desbanear-user-btn" data-id="${user.id}" style="margin-left: 8px;"><i class="fas fa-undo-alt"></i> ${tDash('desbanear')}</button>`;
            }

            row.innerHTML = `
                <td>${user.id}</td>
                <td><strong>${escapeHtml(user.usuario)}</strong><br><small>${escapeHtml(user.nombre_completo || '')}</small></td>
                <td><span class="badge-role ${roleClass}">${tDash(user.rol)}</span></td>
                <td>
                    <select class="change-role-select" data-id="${user.id}">
                        <option value="admin" ${user.rol === 'admin' ? 'selected' : ''}>${tDash('admin')}</option>
                        <option value="creador" ${user.rol === 'creador' ? 'selected' : ''}>${tDash('creador')}</option>
                        <option value="lector" ${user.rol === 'lector' ? 'selected' : ''}>${tDash('lector')}</option>
                    </select>
                </td>
                <td class="actions-cell">
                    ${accionesHtml}
                </td>
            `;
            usersTableBody.appendChild(row);
        });

        // Eventos para cambiar rol
        document.querySelectorAll(".change-role-select").forEach(select => {
            select.addEventListener("change", async (e) => {
                const userId = e.target.getAttribute("data-id");
                const newRole = e.target.value;
                await actualizarRol(userId, newRole);
            });
        });

        // Eventos para eliminar usuario
        document.querySelectorAll(".delete-user-btn").forEach(btn => {
            btn.addEventListener("click", async (e) => {
                const userId = btn.getAttribute("data-id");
                await eliminarUsuario(userId);
            });
        });

        // Eventos para desbanear usuario
        document.querySelectorAll(".desbanear-user-btn").forEach(btn => {
            btn.addEventListener("click", async (e) => {
                const userId = btn.getAttribute("data-id");
                await desbanearUsuario(userId);
            });
        });
    }

    async function desbanearUsuario(userId) {
        if (!confirm(tDash('confirmarDesbanear'))) return;
        const formData = new FormData();
        formData.append('accion', 'desbanear_usuario');
        formData.append('id', userId);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast(tDash('usuarioDesbaneado'), "success");
                cargarUsuarios();
            } else {
                showToast(result.msg || tDash('errorDesbanear'), "danger");
            }
        } catch (error) {
            showToast(tDash('errorRed'), "danger");
        }
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
                showToast(`${tDash('rolActualizado')} ${tDash(newRole)}`, "success");
                cargarUsuarios();
            } else {
                showToast(tDash('errorActualizarRol'), "danger");
            }
        } catch (error) {
            showToast(tDash('errorRed'), "danger");
        }
    }

    async function eliminarUsuario(userId) {
        if (!confirm(tDash('confirmarEliminarUsuario'))) return;
        const formData = new FormData();
        formData.append('accion', 'eliminar_usuario');
        formData.append('id', userId);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast(tDash('usuarioEliminado'), "danger");
                cargarUsuarios();
            } else {
                showToast(result.msg || tDash('errorEliminar'), "danger");
            }
        } catch (error) {
            showToast(tDash('errorRed'), "danger");
        }
    }

    // Crear usuario (adaptado a tu modal actual)
    addUserForm.addEventListener("submit", async (e) => {
        e.preventDefault();
        const usuario = document.getElementById("newUserName").value.trim();
        const nombre_completo = document.getElementById("newUserFullName")?.value.trim() || usuario;
        const num = document.getElementById("newUserNum")?.value.trim() || "00000";
        const cuenta = document.getElementById("newUserEmail")?.value.trim() || "correo@ejemplo.com";
        const rol = document.getElementById("newUserRole").value;
        if (!usuario) {
            showToast(tDash('usuarioObligatorio'), "danger");
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
                showToast(`${tDash('usuarioCreado')} ${result.temp_pass || tDash('cambiarDespues')}`, "success");
                closeModal();
                cargarUsuarios();
            } else {
                showToast("Error: " + result.msg, "danger");
            }
        } catch (error) {
            showToast(tDash('errorRed'), "danger");
        }
    });

    function closeModal() {
        addUserModal.classList.remove("active");
        addUserForm.reset();
    }
    openAddUserBtn.addEventListener("click", () => addUserModal.classList.add("active"));
    closeModalElements.forEach(el => el.addEventListener("click", closeModal));

    userSearch.addEventListener("input", (e) => {
        const term = e.target.value.toLowerCase();
        const filtered = users.filter(u => u.usuario.toLowerCase().includes(term) || (u.nombre_completo && u.nombre_completo.toLowerCase().includes(term)));
        renderUsersTable(filtered);
    });

    document.getElementById("btnExport")?.addEventListener("click", async () => {
        const btn = document.getElementById("btnExport");
        if (btn) btn.disabled = true;
        try {
            await exportUsersToCsv();
        } finally {
            if (btn) btn.disabled = false;
        }
    });

    function valueToString(value) {
        if (value === null || value === undefined) return '';
        if (typeof value === 'object') return JSON.stringify(value);
        return String(value);
    }

    function csvEscape(value) {
        const text = valueToString(value);
        if (/[",\r\n]/.test(text)) {
            return `"${text.replace(/"/g, '""')}"`;
        }
        return text;
    }

    function buildCsvFromUsers(userArray) {
        if (!Array.isArray(userArray) || userArray.length === 0) return '';
        const headers = Array.from(new Set(userArray.flatMap(user => Object.keys(user))));
        const csvRows = [headers.join(",")];
        userArray.forEach(user => {
            const row = headers.map(field => csvEscape(user[field]));
            csvRows.push(row.join(","));
        });
        return csvRows.join("\r\n");
    }

    function downloadFile(filename, content, type = 'text/csv;charset=utf-8;') {
        const blob = new Blob([content], { type });
        const url = URL.createObjectURL(blob);
        const anchor = document.createElement('a');
        anchor.href = url;
        anchor.download = filename;
        document.body.appendChild(anchor);
        anchor.click();
        document.body.removeChild(anchor);
        URL.revokeObjectURL(url);
    }

    async function exportUsersToCsv() {
        if (!users.length) {
            await cargarUsuarios();
        }
        if (!users.length) {
            showToast(tDash('noDatosExportar'), "danger");
            return;
        }
        const csv = buildCsvFromUsers(users);
        const fileName = `usuarios_export_${new Date().toISOString().slice(0, 10)}.csv`;
        downloadFile(fileName, csv);
        showToast(`${tDash('descargaIniciada')} ${fileName}`, "success");
    }

    // ==================== MODERACIÓN DE PUBLICACIONES ====================

    async function cargarTodosLosPosts() {
        const tbody = document.getElementById("allPostsBody");
        if (!tbody) return;
        try {
            const resp = await fetch('admin_operaciones.php?accion=listar_todos_posts');
            const data = await resp.json();
            updatePubBadge(data.length);
            updatePublicacionesHeading(data.length);
            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6">No hay publicaciones</td></tr>`;
                return;
            }
            tbody.innerHTML = "";
            data.forEach(post => {
                let archivosHtml = tDash('sinArchivos');
                if (post.imagen) {
                    const archivos = post.imagen.split(",").slice(0, 2);
                    archivosHtml = archivos.map(a => `<span style="font-size:12px">📎 ${a}</span>`).join(" ");
                    if (post.imagen.split(",").length > 2) archivosHtml += " …";
                }
                const roleClass = post.rol === "admin" ? "admin" : (post.rol === "creador" ? "creator" : "reader");
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${post.id}</td>
                    <td>
                        <strong>${escapeHtml(post.usuario)}</strong><br>
                        <small>${escapeHtml(post.nombre_completo || '')}</small><br>
                        <span class="badge-role ${roleClass}">${tDash(post.rol)}</span>
                    </td>
                    <td>${escapeHtml(post.texto ? post.texto.substring(0, 100) : '')}${post.texto && post.texto.length > 100 ? '…' : ''}</td>
                    <td>${archivosHtml}</td>
                    <td>${new Date(post.fecha).toLocaleString()}</td>
                    <td class="actions-cell">
                        <button class="btn btn-sm btn-danger eliminar-publicacion" data-id="${post.id}" style="margin-right: 8px;"><i class="fas fa-trash-alt"></i> ${tDash('eliminar')}</button>
                        <button class="btn btn-sm btn-warning banear-usuario" data-id="${post.usuario_id}" data-nombre="${escapeHtml(post.usuario)}" style="background-color: #ff9800; border-color: #ff9800;"><i class="fas fa-hammer"></i> ${tDash('banear')}</button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            // Eventos para botones de eliminar publicación
            document.querySelectorAll(".eliminar-publicacion").forEach(btn => {
                btn.addEventListener("click", () => {
                    const postId = btn.getAttribute("data-id");
                    eliminarPublicacion(postId);
                });
            });
            // Eventos para botones de banear usuario
            document.querySelectorAll(".banear-usuario").forEach(btn => {
                btn.addEventListener("click", () => {
                    const userId = btn.getAttribute("data-id");
                    const userName = btn.getAttribute("data-nombre");
                    banearUsuario(userId, userName);
                });
            });
        } catch (error) {
            console.error(error);
            updatePubBadge(0);
            updatePublicacionesHeading(0);
            tbody.innerHTML = `<tr><td colspan="6">${tDash('errorCargarPublicaciones')}</td></tr>`;
            showToast(tDash('errorCargarPublicaciones'), "danger");
        }
    }

    // Nueva función: eliminar publicación individual
    async function eliminarPublicacion(postId) {
        if (!confirm(tDash('confirmarEliminarPub'))) return;
        const formData = new FormData();
        formData.append('accion', 'eliminar_publicacion');
        formData.append('post_id', postId);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast(tDash('pubEliminada'), "success");
                cargarTodosLosPosts(); // recargar tabla
            } else {
                showToast(result.msg || tDash('errorEliminar'), "danger");
            }
        } catch (error) {
            showToast(tDash('errorRed'), "danger");
        }
    }

    async function banearUsuario(userId, userName) {
        if (!confirm(tDash('confirmarBanear'))) {
            return;
        }
        const formData = new FormData();
        formData.append('accion', 'banear_usuario');
        formData.append('id', userId);
        try {
            const resp = await fetch('admin_operaciones.php', { method: 'POST', body: formData });
            const result = await resp.json();
            if (result.status === 'ok') {
                showToast(tDash('usuarioBaneado'), "danger");
                cargarTodosLosPosts();    // recargar lista de posts
                cargarUsuarios();         // actualizar tabla de usuarios (cambió el rol)
            } else {
                showToast(result.msg || tDash('errorEliminar'), "danger");
            }
        } catch (error) {
            showToast(tDash('errorRed'), "danger");
        }
    }

    // Navegación: al hacer clic en la pestaña de publicaciones, cargar todos los posts
    const postsNavLink = document.querySelector('.nav-link[data-target="publicaciones"]');
    if (postsNavLink) {
        postsNavLink.addEventListener("click", () => {
            cargarTodosLosPosts();
        });
    }
    // Cargar la cantidad de peticiones al inicio y preparar la pestaña si se abre después
    cargarTodosLosPosts();

    // Navegación original (sidebar)
    navLinks.forEach(link => {
        link.addEventListener("click", (e) => {
            e.preventDefault();
            navLinks.forEach(l => l.classList.remove("active"));
            link.classList.add("active");
            const target = link.getAttribute("data-target");
            tabContents.forEach(tab => tab.classList.remove("active"));
            const targetTab = document.getElementById(target);
            if (targetTab) targetTab.classList.add("active");
            
            if (target === "publicaciones") {
                const badge = document.getElementById("pub-badge");
                const count = badge ? badge.textContent : "0";
                pageTitle.textContent = `${tDash('publicaciones')}: ${count}`;
            } else {
                const titleText = link.querySelector('.nav-text')?.textContent.trim() || link.textContent.trim();
                pageTitle.textContent = titleText;
            }
            
            if (window.innerWidth <= 768) sidebar.classList.remove("active");
        });
    });
    menuToggle.addEventListener("click", () => sidebar.classList.toggle("active"));

    // Inicializar
    cargarUsuarios();
});

// ==================== FUNCIONES AUXILIARES ====================

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function (m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// Mock peticiones de rol (demo, se mantienen igual)
window.approveRequest = function (name, targetRole, rowId) {
    const row = document.getElementById(rowId);
    if (row) row.remove();
    const badge = document.getElementById("req-badge");
    if (badge) { let val = parseInt(badge.textContent); if (val > 1) badge.textContent = val - 1; else badge.style.display = "none"; }
    showToast(`Petición aprobada: ${name} ahora es ${targetRole} (demo)`, "success");
};
window.rejectRequest = function (rowId) {
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
// ==================== STATS EN TIEMPO REAL ====================

let chartInstance = null;

function actualizarStatsInicio() {
    // --- Usuarios conectados ---
    fetch('usuarios_activos.php')
        .then(r => r.text())
        .then(n => {
            const el = document.getElementById('active-users-count');
            if (el) el.textContent = n;
        })
        .catch(() => {});

    // --- Visitas hoy ---
    fetch('visitas_hoy.php')
        .then(r => r.text())
        .then(n => {
            const el = document.getElementById('visits-today-count');
            if (el) el.textContent = parseInt(n).toLocaleString();
        })
        .catch(() => {});

    // --- Gráfico conectados vs desconectados ---
    fetch('stats_grafico.php')
        .then(r => r.json())
        .then(data => {
            const canvas = document.getElementById('usuariosChart');
            if (!canvas) return;

            // También actualiza el total de la card "Usuarios Totales"
            const totalEl = document.getElementById('total-users-count');
            if (totalEl) totalEl.textContent = data.totales;

            const chartData = {
                labels: ['Conectados', 'Desconectados'],
                datasets: [{
                    data: [data.conectados, data.desconectados],
                    backgroundColor: ['#35dcd4', '#444c5e'],
                    borderColor:     ['#35dcd4', '#444c5e'],
                    borderWidth: 1
                }]
            };

            if (chartInstance) {
                // Actualizar datos sin redibujar desde cero
                chartInstance.data.datasets[0].data = [data.conectados, data.desconectados];
                chartInstance.update();
            } else {
                chartInstance = new Chart(canvas, {
                    type: 'doughnut',
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { color: '#cdd6f4', font: { size: 13 } }
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx => ` ${ctx.label}: ${ctx.parsed}`
                                }
                            }
                        }
                    }
                });
            }
        })
        .catch(() => {});
}

// Arrancar al cargar y refrescar cada 30 s (sincronizado con actividad.js)
document.addEventListener('DOMContentLoaded', () => {
    actualizarStatsInicio();
    setInterval(actualizarStatsInicio, 30000);
});

// ==================== ÚLTIMOS CONECTADOS ====================

function tiempoRelativo(fechaStr) {
    const ahora = new Date();
    const fecha = new Date(fechaStr);
    const diff = Math.floor((ahora - fecha) / 1000);
    if (diff < 60) return 'hace un momento';
    if (diff < 3600) return `hace ${Math.floor(diff / 60)} min`;
    if (diff < 86400) return `hace ${Math.floor(diff / 3600)} h`;
    return `hace ${Math.floor(diff / 86400)} días`;
}

function actualizarUltimosConectados() {
    const lista = document.getElementById('ultimos-conectados-lista');
    if (!lista) return;

    fetch('ultimos_conectados.php')
        .then(r => r.json())
        .then(usuarios => {
            if (!usuarios.length) {
                lista.innerHTML = '<span style="color:var(--text-muted);font-size:13px;">Sin actividad reciente</span>';
                return;
            }
            lista.innerHTML = usuarios.map(u => {
                const online = u.is_online == 1;
                const foto = u.foto_perfil
                     ? `<img src="${u.foto_perfil}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid ${online ? '#35dcd4' : '#444c5e'}">`
                    : `<div style="width:36px;height:36px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:15px;border:2px solid ${online ? '#35dcd4' : '#444c5e'}">${(u.nombre_completo || u.usuario || '?')[0].toUpperCase()}</div>`;

                const dot = u.is_online == 1
                    ? '<span style="width:8px;height:8px;border-radius:50%;background:#35dcd4;display:inline-block;margin-right:5px;"></span>'
                    : '<span style="width:8px;height:8px;border-radius:50%;background:#444c5e;display:inline-block;margin-right:5px;"></span>';

                return `
                    <div style="display:flex;align-items:center;gap:12px;">
                        ${foto}
                        <div style="flex:1;min-width:0;">
                            <div style="font-weight:600;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${u.nombre_completo || u.usuario}</div>
                            <div style="font-size:11px;color:var(--text-muted);margin-top:2px;">${dot}${u.is_online == 1? 'En línea' : tiempoRelativo(u.last_activity)}</div>
                        </div>
                    </div>`;
            }).join('');
        })
        .catch(() => {});
}

// Arrancar y refrescar cada 30s
document.addEventListener('DOMContentLoaded', () => {
    actualizarUltimosConectados();
    setInterval(actualizarUltimosConectados, 30000);
});