// global.js - Versión completa (tamaño de texto + tema + modal logout)
(function() {
    // ========== TAMAÑO DE TEXTO ==========
    function aplicarTamanioTexto() {
        const size = localStorage.getItem("fontSize") || "medium";
        let baseSize = "16px";
        if (size === "small") baseSize = "13px";
        if (size === "medium") baseSize = "16px";
        if (size === "large") baseSize = "20px";
        document.documentElement.style.fontSize = baseSize;
    }
    aplicarTamanioTexto();
    window.addEventListener("storage", (e) => {
        if (e.key === "fontSize") aplicarTamanioTexto();
    });

    // ========== TEMA CLARO/OSCURO ==========
    function aplicarTema() {
        const theme = localStorage.getItem("theme") || "dark";
        if (theme === "light") {
            document.body.classList.add("light-mode");
        } else {
            document.body.classList.remove("light-mode");
        }
    }
    aplicarTema();
    window.addEventListener("storage", (e) => {
        if (e.key === "theme") aplicarTema();
    });

    // ========== TRADUCCIONES GLOBALES PARA EL MODAL ==========
    const globalTranslations = {
        es: {
            cerrarSesionTitulo: "Cerrar sesión",
            confirmarLogout: "¿Seguro que deseas cerrar la sesión?",
            cancelar: "Cancelar",
            confirmar: "Confirmar"
        },
        en: {
            cerrarSesionTitulo: "Log out",
            confirmarLogout: "Are you sure you want to log out?",
            cancelar: "Cancel",
            confirmar: "Confirm"
        }
    };

    function getGlobalLang() {
        return localStorage.getItem("lang") || "es";
    }

    function tGlobal(key) {
        const lang = getGlobalLang();
        return globalTranslations[lang]?.[key] || key;
    }

    function actualizarIdiomaModal() {
        const modal = document.getElementById('globalLogoutModal');
        if (!modal) return;
        const title = modal.querySelector('h3');
        const message = modal.querySelector('p');
        const cancelBtn = modal.querySelector('#globalCancelLogoutBtn');
        const confirmBtn = modal.querySelector('#globalConfirmLogoutBtn');
        if (title) title.textContent = tGlobal('cerrarSesionTitulo');
        if (message) message.textContent = tGlobal('confirmarLogout');
        if (cancelBtn) cancelBtn.textContent = tGlobal('cancelar');
        if (confirmBtn) confirmBtn.textContent = tGlobal('confirmar');
    }

    // ========== MODAL DE CIERRE DE SESIÓN GLOBAL ==========
    function injectLogoutModal() {
        if (document.getElementById('globalLogoutModal')) return;

        const modalHTML = `
            <div id="globalLogoutModal" class="modal-overlay-global" style="display: none;">
                <div class="modal-content-global">
                    <h3>${tGlobal('cerrarSesionTitulo')}</h3>
                    <p>${tGlobal('confirmarLogout')}</p>
                    <div class="modal-buttons-global">
                        <button class="modal-btn-global cancel" id="globalCancelLogoutBtn">${tGlobal('cancelar')}</button>
                        <button class="modal-btn-global confirm" id="globalConfirmLogoutBtn">${tGlobal('confirmar')}</button>
                    </div>
                </div>
            </div>
            <style>
                .modal-overlay-global {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    backdrop-filter: blur(8px);
                    display: none;
                    justify-content: center;
                    align-items: center;
                    z-index: 10000;
                }
                .modal-content-global {
                    background: var(--panel-strong);
                    border: 1px solid var(--accent);
                    border-radius: 28px;
                    padding: 28px;
                    width: 90%;
                    max-width: 450px;
                    text-align: center;
                    animation: modalGlow 0.3s ease-out;
                    box-shadow: 0 0 40px rgba(57, 197, 187, 0.3);
                }
                .modal-content-global h3 {
                    margin: 0 0 8px;
                    font-size: 26px;
                    color: var(--accent);
                }
                .modal-content-global p {
                    margin: 0 0 20px;
                    color: var(--muted);
                }
                .modal-buttons-global {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                }
                .modal-btn-global {
                    padding: 10px 24px;
                    border-radius: 40px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: 0.2s;
                    border: none;
                }
                .modal-btn-global.cancel {
                    background: transparent;
                    border: 1px solid var(--border);
                    color: var(--text);
                }
                .modal-btn-global.confirm {
                    background: linear-gradient(135deg, #66fff0, #39c5bb);
                    color: #082016;
                    font-weight: 800;
                }
                .modal-btn-global:hover {
                    transform: translateY(-2px);
                }
                @keyframes modalGlow {
                    from { opacity: 0; transform: scale(0.95); }
                    to { opacity: 1; transform: scale(1); }
                }
            </style>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);

        const modal = document.getElementById('globalLogoutModal');
        const confirmBtn = document.getElementById('globalConfirmLogoutBtn');
        const cancelBtn = document.getElementById('globalCancelLogoutBtn');

        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.style.display = 'none';
        });
        cancelBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
        confirmBtn.addEventListener('click', () => {
            window.location.href = 'cerrar_sesion.php';
        });
    }

    function attachLogoutListeners() {
        const logoutLinks = document.querySelectorAll('.menu-item[href*="cerrar_sesion.php"], a[href*="cerrar_sesion.php"]');
        logoutLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const modal = document.getElementById('globalLogoutModal');
                if (modal) modal.style.display = 'flex';
            });
        });
    }

    // Inicializar modal y eventos cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            injectLogoutModal();
            attachLogoutListeners();
            // Escuchar cambios de idioma para actualizar el modal
            window.addEventListener('storage', (e) => {
                if (e.key === 'lang') actualizarIdiomaModal();
            });
        });
    } else {
        injectLogoutModal();
        attachLogoutListeners();
        window.addEventListener('storage', (e) => {
            if (e.key === 'lang') actualizarIdiomaModal();
        });
    }

    // ========== MONITOREO DE IDIOMA EN LA MISMA PESTAÑA ==========
let ultimoIdioma = getGlobalLang();
setInterval(() => {
    const idiomaActual = getGlobalLang();
    if (idiomaActual !== ultimoIdioma) {
        ultimoIdioma = idiomaActual;
        actualizarIdiomaModal();
    }
}, 300); // revisa cada 300ms
})();