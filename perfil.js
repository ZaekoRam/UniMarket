// ========== TEMA Y TRADUCCIONES ==========
const themeToggleInput = document.getElementById("themeToggleInput");
const themeText = document.getElementById("themeText");

const translationsPerfil = {
  es: {
    inicio: "Inicio", perfil: "Perfil", mensajes: "Mensajes", notificaciones: "Notificaciones",
    configuracion: "Configuración", cerrarSesion: "Cerrar sesión", darkMode: "Dark Mode", lightMode: "Light Mode",
    miRincon: "Mi rincón en UniMarket", perfilTitulo: "Perfil", editarPerfil: "Editar perfil",
    guardar: "Guardar", cancelar: "Cancelar", sobreMi: "Sobre mí", misGustos: "Mis gustos",
    libroPerfil: "Libro de perfil", presentate: "Una zona estilo retro para presentarte",
    perfilGuardado: "¡Perfil guardado y subido a la base de datos! 🚀", errorGuardar: "Error al guardar: ",
    nombre: "Nombre", usuario: "@usuario", bioCorta: "Bio corta", tagsSeparados: "Tags separados por coma",
    carrera: "Carrera", campus: "Campus", emprendimientos: "Emprendimientos", estado: "Estado",
    mood: "Mood", moodLabel: "Mood:", colorFavorito: "Color favorito", colorFavoritoLabel: "Color favorito:",
    metaActual: "Meta actual", metaActualLabel: "Meta actual:", estilo: "Estilo", estiloLabel: "Estilo:",
    gustosSeparados: "Gustos separados por coma", placeholderTags: "retro web, uni vibes, creative",
    editandoPerfil: "Editando perfil", tags: "Tags", tagsHelp: "Separados por coma",
    detalles: "Detalles", vibraActual: "Vibra actual", enLinea: "En línea",desconectado: "Desconectado", enviarMensaje: "Enviar mensaje",
  },
  en: {
    inicio: "Home", perfil: "Profile", mensajes: "Messages", notificaciones: "Notifications",
    configuracion: "Settings", cerrarSesion: "Log out", darkMode: "Dark Mode", lightMode: "Light Mode",
    miRincon: "My corner in UniMarket", perfilTitulo: "Profile", editarPerfil: "Edit profile",
    guardar: "Save", cancelar: "Cancel", sobreMi: "About me", misGustos: "My likes",
    libroPerfil: "Profile book", presentate: "A retro-style zone to introduce yourself",
    perfilGuardado: "Profile saved and uploaded to the database! 🚀", errorGuardar: "Error saving: ",
    nombre: "Name", usuario: "@username", bioCorta: "Short bio", tagsSeparados: "Tags separated by comma",
    carrera: "Major", campus: "Campus", emprendimientos: "Entrepreneurships", estado: "Status",
    mood: "Mood", moodLabel: "Mood:", colorFavorito: "Favorite color", colorFavoritoLabel: "Favorite color:",
    metaActual: "Current goal", metaActualLabel: "Current goal:", estilo: "Style", estiloLabel: "Style:",
    gustosSeparados: "Likes separated by comma", placeholderTags: "retro web, uni vibes, creative",
    editandoPerfil: "Editing profile", tags: "Tags", tagsHelp: "Separated by commas",
    detalles: "Details", vibraActual: "Current vibe", enLinea: "Online",     desconectado: "Offline",enviarMensaje: "Send message",
  }
};

function getLang() { return localStorage.getItem("lang") || "es"; }
function t(key) { return translationsPerfil[getLang()]?.[key] || key; }

function aplicarIdioma() {
  const lang = getLang();
  document.querySelectorAll("[data-i18n]").forEach(el => {
    const key = el.dataset.i18n;
    if (translationsPerfil[lang]?.[key]) el.textContent = translationsPerfil[lang][key];
  });
  document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
    const key = el.dataset.i18nPlaceholder;
    if (translationsPerfil[lang]?.[key]) el.placeholder = translationsPerfil[lang][key];
  });
  actualizarTextoTema();
}
function actualizarTextoTema() {
  if (themeText) themeText.textContent = document.body.classList.contains("light-mode") ? t("lightMode") : t("darkMode");
}
function aplicarTema(modo) {
  const isLight = modo === "light";
  document.body.classList.toggle("light-mode", isLight);
  themeToggleInput.checked = isLight;
  actualizarTextoTema();
}
const temaGuardado = localStorage.getItem("theme") || "dark";
aplicarTema(temaGuardado);
themeToggleInput.addEventListener("change", () => {
  const nuevoTema = themeToggleInput.checked ? "light" : "dark";
  localStorage.setItem("theme", nuevoTema);
  aplicarTema(nuevoTema);
});

// ========== PERFIL - DATOS POR DEFECTO ==========
const defaultProfile = {
  nombre: "Invitado",
  usuario: "@invitado",
  bio: "Sin biografía.",
  tags: [],
  carrera: "Sin carrera.",
  campus: "Sin campus.",
  emprendimientos: "Sin emprendimientos.",
  estado: "Sin estado.",
  sobreMi: "Sin descripción.",
  gustos: [],
  mood: "Sin mood.",
  color: "Sin color favorito.",
  meta: "Sin meta actual.",
  estilo: "Sin estilo.",
  foto_perfil: null,
  is_online: 0   // ✅ añade esta línea
};

// ========== DETECTAR SI ES PERFIL PROPIO ==========
const urlParams = new URLSearchParams(window.location.search);
const perfilUserId = urlParams.get('user_id');
let esMiPerfil = true;
let miUsuarioId = null;

// Obtener el ID del usuario logueado
// Obtener el ID del usuario logueado
fetch('obtener_sesion.php')
  .then(res => res.json())
  .then(data => {
    miUsuarioId = data.usuario_id;
    const editBtn = document.getElementById('editBtn');
    const sendMsgBtn = document.getElementById('sendMsgBtn');

    // Si el usuario no está logueado (invitado)
    if (!miUsuarioId) {
        esMiPerfil = false;
        if (editBtn) editBtn.style.display = 'none';
        if (sendMsgBtn) sendMsgBtn.style.display = 'none';
        // Ocultar paneles de edición
        document.getElementById('editPanel')?.classList.add('hidden');
        document.getElementById('editDetails')?.classList.add('hidden');
        document.getElementById('editSobreMiWrap')?.classList.add('hidden');
        document.getElementById('editGustosWrap')?.classList.add('hidden');
        document.getElementById('editMoodGrid')?.classList.add('hidden');
        return;
    }

    // Si hay un user_id en URL y es diferente al mío -> perfil ajeno
    if (perfilUserId && perfilUserId != miUsuarioId) {
        esMiPerfil = false;
        // Ocultar botón de editar y mostrar botón de enviar mensaje
        if (editBtn) editBtn.style.display = 'none';
        if (sendMsgBtn) {
            sendMsgBtn.style.display = 'flex';
            // Al hacer clic, redirigir a mensajes con el ID del usuario
            sendMsgBtn.onclick = () => {
                window.location.href = `mensajes?user_id=${perfilUserId}`;
            };
        }
        // Ocultar todos los paneles de edición
        const editPanel = document.getElementById('editPanel');
        if (editPanel) editPanel.classList.add('hidden');
        document.getElementById('editDetails')?.classList.add('hidden');
        document.getElementById('editSobreMiWrap')?.classList.add('hidden');
        document.getElementById('editGustosWrap')?.classList.add('hidden');
        document.getElementById('editMoodGrid')?.classList.add('hidden');
    } else {
        // Es mi propio perfil
        esMiPerfil = true;
        if (editBtn) editBtn.style.display = 'flex';
        if (sendMsgBtn) sendMsgBtn.style.display = 'none';
    }
  })
  .catch(() => {
    // Si hay error (por ejemplo, no hay sesión), asumimos invitado
    miUsuarioId = null;
    esMiPerfil = false;
    const editBtn = document.getElementById('editBtn');
    const sendMsgBtn = document.getElementById('sendMsgBtn');
    if (editBtn) editBtn.style.display = 'none';
    if (sendMsgBtn) sendMsgBtn.style.display = 'none';
    document.getElementById('editPanel')?.classList.add('hidden');
    document.getElementById('editDetails')?.classList.add('hidden');
    document.getElementById('editSobreMiWrap')?.classList.add('hidden');
    document.getElementById('editGustosWrap')?.classList.add('hidden');
    document.getElementById('editMoodGrid')?.classList.add('hidden');
  });

// ========== OBTENER PERFIL (acepta ID externo) ==========
async function getProfile() {
  let url = 'obtener_perfil.php';
  if (perfilUserId) {
    url += '?id=' + perfilUserId;
  }
  console.log("🔄 Cargando perfil desde", url);
  try {
    const res = await fetch(url);
    const data = await res.json();
    console.log("📦 Datos recibidos:", data);
    if (data.error) {
      console.warn("⚠️ Error de servidor:", data.error);
      return defaultProfile;
    }
    if (typeof data.tags === 'string' && data.tags) data.tags = data.tags.split(',');
    if (typeof data.gustos === 'string' && data.gustos) data.gustos = data.gustos.split(',');
    return { ...defaultProfile, ...data };
  } catch (e) {
    console.error("❌ Error al obtener perfil:", e);
    return defaultProfile;
  }
}

// ========== GUARDAR PERFIL (solo si es mi perfil) ==========
async function saveProfile(profile) {
  if (!esMiPerfil) {
    console.warn("No puedes editar un perfil ajeno");
    showToast("No puedes editar este perfil", "warning");
    return;
  }
  console.log("💾 Guardando perfil en actualizar_perfil.php...", profile);
  try {
    const res = await fetch('actualizar_perfil.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(profile)
    });
    const result = await res.json();
    console.log("✅ Respuesta del servidor:", result);
    if (result.success) {
      showToast(t("perfilGuardado"), "success");
    } else {
      showToast(t("errorGuardar") + result.error, "danger");
    }
  } catch (e) {
    console.error("❌ Error de conexión:", e);
    showToast("Error de conexión", "danger");
  }
}

// ========== RENDERIZAR PERFIL ==========
function renderProfile(profile) {
  console.log("🎨 Renderizando perfil con datos:", profile);
  // Hero
  const displayName = document.getElementById("displayName");
  if (displayName) displayName.innerHTML = `<span class="name-gradient">${escapeHtml(profile.nombre)}</span>`;
  const displayUsername = document.getElementById("displayUsername");
  if (displayUsername) displayUsername.innerHTML = `@${profile.usuario.replace('@', '')}`;
  const displayBio = document.getElementById("displayBio");
  if (displayBio) displayBio.innerHTML = `<span class="bio-icon"></span><span>${escapeHtml(profile.bio)}</span>`;

  // Avatar: si hay foto, mostrarla; si no, mostrar iniciales
  const avatarMain = document.getElementById("profileAvatarMain");
  if (profile.foto_perfil && profile.foto_perfil !== "") {
    avatarMain.innerHTML = `<img src="${profile.foto_perfil}" alt="Foto de perfil" style="width:100%; height:100%; object-fit:cover; border-radius:28px;">`;
  } else {
    const inicial = profile.nombre ? profile.nombre.trim().charAt(0).toUpperCase() : "U";
    avatarMain.innerHTML = `<span id="avatarInitial">${inicial}</span>`;
  }

  // Hero badges (tags)
  const heroBadges = document.getElementById("heroBadges");
  if (heroBadges) {
    heroBadges.innerHTML = "";
    if (profile.tags && profile.tags.length) {
      profile.tags.forEach(tag => {
        const badge = document.createElement("span");
        badge.className = "hero-badge";
        badge.textContent = tag;
        heroBadges.appendChild(badge);
      });
    } else {
      heroBadges.innerHTML = '<span class="badge-placeholder">Sin tags</span>';
    }
  }

  // Sobre mí
  const viewSobreMi = document.getElementById("viewSobreMi");
  if (viewSobreMi) viewSobreMi.innerHTML = `<p>${escapeHtml(profile.sobreMi)}</p>`;

  // Detalles
  const viewCarrera = document.getElementById("viewCarrera");
  if (viewCarrera) viewCarrera.innerHTML = escapeHtml(profile.carrera);
  const viewCampus = document.getElementById("viewCampus");
  if (viewCampus) viewCampus.innerHTML = escapeHtml(profile.campus);
  const viewEmprendimientos = document.getElementById("viewEmprendimientos");
  if (viewEmprendimientos) viewEmprendimientos.innerHTML = escapeHtml(profile.emprendimientos);
  const viewEstado = document.getElementById("viewEstado");
  if (viewEstado) viewEstado.innerHTML = escapeHtml(profile.estado);

  // Gustos (nube de tags)
  const viewGustos = document.getElementById("viewGustos");
  if (viewGustos) {
    viewGustos.innerHTML = "";
    if (profile.gustos && profile.gustos.length) {
      profile.gustos.forEach(g => {
        const span = document.createElement("span");
        span.className = "tag-item";
        span.textContent = g;
        viewGustos.appendChild(span);
      });
    } else {
      viewGustos.innerHTML = '<span class="tag-item empty-tag">Sin gustos.</span>';
    }
  }

  // Mood, color, meta, estilo
  const viewMood = document.getElementById("viewMood");
  if (viewMood) viewMood.textContent = profile.mood;
  const viewColor = document.getElementById("viewColor");
  if (viewColor) viewColor.textContent = profile.color;
  const viewMeta = document.getElementById("viewMeta");
  if (viewMeta) viewMeta.textContent = profile.meta;
  const viewEstilo = document.getElementById("viewEstilo");
  if (viewEstilo) viewEstilo.textContent = profile.estilo;
  // Actualizar estado en línea / desconectado
  // Actualizar estado en línea / desconectado usando el campo is_online del servidor
const statusBadge = document.getElementById("statusBadge");
if (statusBadge) {
    const online = profile.is_online == 1;   // ✅ viene del servidor
    const dot = statusBadge.querySelector(".status-dot");
    const textSpan = statusBadge.querySelector("span:not(.status-dot)");
    if (online) {
        dot.style.background = "#2ecc71";
        dot.style.animation = "pulse-dot 2s ease-in-out infinite";
        if (textSpan) textSpan.textContent = t("enLinea");
    } else {
        dot.style.background = "#7f8c8d";
        dot.style.animation = "none";
        if (textSpan) textSpan.textContent = t("desconectado");
    }
}
  // Ocultar loader y mostrar contenido
  const loader = document.getElementById('profileLoader');
  const content = document.getElementById('profileContent');
  if (loader && content) {
    loader.style.display = 'none';
    content.style.display = 'block';
  }
}

// ========== RELLENAR INPUTS DE EDICIÓN ==========
function fillEditInputs(profile) {
  const inputBio = document.getElementById("inputBio");
  if (inputBio) inputBio.value = profile.bio;
  const inputTags = document.getElementById("inputTags");
  if (inputTags) inputTags.value = profile.tags ? profile.tags.join(", ") : "";
  const inputCarrera = document.getElementById("inputCarrera");
  if (inputCarrera) inputCarrera.value = profile.carrera;
  const inputCampus = document.getElementById("inputCampus");
  if (inputCampus) inputCampus.value = profile.campus;
  const inputEmprendimientos = document.getElementById("inputEmprendimientos");
  if (inputEmprendimientos) inputEmprendimientos.value = profile.emprendimientos;
  const inputEstado = document.getElementById("inputEstado");
  if (inputEstado) inputEstado.value = profile.estado;
const inputSobreMi = document.getElementById("inputSobreMi");
if (inputSobreMi) {
  inputSobreMi.value = profile.sobreMi;
  // Actualizar contador después de asignar el valor
  if (typeof updateCharCounter === 'function') {
    updateCharCounter();
  }
}
  const inputGustos = document.getElementById("inputGustos");
  if (inputGustos) inputGustos.value = profile.gustos ? profile.gustos.join(", ") : "";
  const inputMood = document.getElementById("inputMood");
  if (inputMood) inputMood.value = profile.mood;
  const inputColor = document.getElementById("inputColor");
  if (inputColor) inputColor.value = profile.color;
  const inputMeta = document.getElementById("inputMeta");
  if (inputMeta) inputMeta.value = profile.meta;
  const inputEstilo = document.getElementById("inputEstilo");
  if (inputEstilo) inputEstilo.value = profile.estilo;
}

// ========== MODO EDICIÓN (solo si es mi perfil) ==========
let currentProfile = null;

async function setEditMode(editing) {
  if (!esMiPerfil) return;
  const editPanel = document.getElementById("editPanel");
  const editDetails = document.getElementById("editDetails");
  const editSobreMiWrap = document.getElementById("editSobreMiWrap");
  const editGustosWrap = document.getElementById("editGustosWrap");
  const editMoodGrid = document.getElementById("editMoodGrid");
  const viewDetails = document.getElementById("viewDetails");
  const viewSobreMi = document.getElementById("viewSobreMi");
  const viewGustos = document.getElementById("viewGustos");
  const editBtn = document.getElementById("editBtn");
  const saveBtn = document.getElementById("saveBtn");
  const cancelBtn = document.getElementById("cancelBtn");

  if (editing) {
    currentProfile = await getProfile();
    fillEditInputs(currentProfile);
    if (editPanel) editPanel.classList.remove("hidden");
    if (editDetails) editDetails.classList.remove("hidden");
    if (editSobreMiWrap) editSobreMiWrap.classList.remove("hidden");
    if (editGustosWrap) editGustosWrap.classList.remove("hidden");
    if (editMoodGrid) editMoodGrid.classList.remove("hidden");
    if (viewDetails) viewDetails.classList.add("hidden");
    if (viewSobreMi) viewSobreMi.classList.add("hidden");
    if (viewGustos) viewGustos.classList.add("hidden");
    if (editBtn) editBtn.classList.add("hidden");
    if (saveBtn) saveBtn.classList.remove("hidden");
    if (cancelBtn) cancelBtn.classList.remove("hidden");
  } else {
    if (editPanel) editPanel.classList.add("hidden");
    if (editDetails) editDetails.classList.add("hidden");
    if (editSobreMiWrap) editSobreMiWrap.classList.add("hidden");
    if (editGustosWrap) editGustosWrap.classList.add("hidden");
    if (editMoodGrid) editMoodGrid.classList.add("hidden");
    if (viewDetails) viewDetails.classList.remove("hidden");
    if (viewSobreMi) viewSobreMi.classList.remove("hidden");
    if (viewGustos) viewGustos.classList.remove("hidden");
    if (editBtn) editBtn.classList.remove("hidden");
    if (saveBtn) saveBtn.classList.add("hidden");
    if (cancelBtn) cancelBtn.classList.add("hidden");
  }
}

async function guardarCambios() {
  if (!esMiPerfil) return;
  const nuevoPerfil = {
    nombre: currentProfile?.nombre || defaultProfile.nombre,
    usuario: currentProfile?.usuario || defaultProfile.usuario,
    bio: document.getElementById("inputBio")?.value.trim() || defaultProfile.bio,
    tags: (document.getElementById("inputTags")?.value || "").split(",").map(t => t.trim()).filter(t => t !== ""),
    carrera: document.getElementById("inputCarrera")?.value.trim() || defaultProfile.carrera,
    campus: document.getElementById("inputCampus")?.value.trim() || defaultProfile.campus,
    emprendimientos: document.getElementById("inputEmprendimientos")?.value.trim() || defaultProfile.emprendimientos,
    estado: document.getElementById("inputEstado")?.value.trim() || defaultProfile.estado,
    sobreMi: document.getElementById("inputSobreMi")?.value.trim() || defaultProfile.sobreMi,
    gustos: (document.getElementById("inputGustos")?.value || "").split(",").map(g => g.trim()).filter(g => g !== ""),
    mood: document.getElementById("inputMood")?.value.trim() || defaultProfile.mood,
    color: document.getElementById("inputColor")?.value.trim() || defaultProfile.color,
    meta: document.getElementById("inputMeta")?.value.trim() || defaultProfile.meta,
    estilo: document.getElementById("inputEstilo")?.value.trim() || defaultProfile.estilo
  };
  if (nuevoPerfil.tags.length === 0) nuevoPerfil.tags = defaultProfile.tags;
  if (nuevoPerfil.gustos.length === 0) nuevoPerfil.gustos = defaultProfile.gustos;

  await saveProfile(nuevoPerfil);
  const actualizado = await getProfile();
  renderProfile(actualizado);
  setEditMode(false);
}

// ========== SUBIR FOTO DE PERFIL (solo si es mi perfil) ==========
async function subirFotoPerfil(file) {
  const formData = new FormData();
  formData.append('foto', file);
  try {
    const res = await fetch('subir_foto_perfil.php', { method: 'POST', body: formData });
    const data = await res.json();
    if (data.success) {
      showToast('Foto de perfil actualizada', 'success');
      // Recargar el perfil completo para mostrar la nueva foto
      const nuevoPerfil = await getProfile();
      renderProfile(nuevoPerfil);
      currentProfile = nuevoPerfil;
    } else {
      showToast('Error: ' + data.error, 'error');
    }
  } catch (e) {
    console.error(e);
    showToast('Error al subir la foto', 'error');
  }
}

// ========== INICIALIZACIÓN ==========
document.addEventListener("DOMContentLoaded", async () => {
  console.log("🚀 Inicializando perfil...");
  aplicarIdioma();
  const profile = await getProfile();
  renderProfile(profile);
  currentProfile = profile;
  
  // Solo asignar eventos si es mi perfil
  if (esMiPerfil) {
    const editBtn = document.getElementById("editBtn");
    const saveBtn = document.getElementById("saveBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    if (editBtn) editBtn.addEventListener("click", () => setEditMode(true));
    if (saveBtn) saveBtn.addEventListener("click", guardarCambios);
    if (cancelBtn) cancelBtn.addEventListener("click", () => setEditMode(false));

    // Evento para cambiar foto de perfil (clic en el avatar)
    const avatarMain = document.getElementById("profileAvatarMain");
    if (avatarMain) {
      avatarMain.style.cursor = "pointer";
      avatarMain.addEventListener("click", () => {
        const input = document.createElement("input");
        input.type = "file";
        input.accept = "image/jpeg,image/png,image/gif,image/webp";
        input.onchange = (e) => {
          if (e.target.files && e.target.files[0]) {
            subirFotoPerfil(e.target.files[0]);
          }
        };
        input.click();
      });
    }
  }
    // Actualizar estado en línea cada 30 segundos
async function actualizarEstadoOnline() {
    let url = 'obtener_perfil.php';
    if (perfilUserId) url += '?id=' + perfilUserId;
    try {
        const res = await fetch(url);
        const data = await res.json();
        if (data.is_online !== undefined) {   // ✅ ahora usamos is_online
            const online = data.is_online == 1;
            const statusBadge = document.getElementById("statusBadge");
            if (statusBadge) {
                const dot = statusBadge.querySelector(".status-dot");
                const textSpan = statusBadge.querySelector("span:not(.status-dot)");
                if (online) {
                    dot.style.background = "#2ecc71";
                    dot.style.animation = "pulse-dot 2s ease-in-out infinite";
                    if (textSpan) textSpan.textContent = t("enLinea");
                } else {
                    dot.style.background = "#7f8c8d";
                    dot.style.animation = "none";
                    if (textSpan) textSpan.textContent = t("desconectado");
                }
            }
        }
    } catch (err) {
        console.error("Error actualizando estado online", err);
    }
}
initCharCounter();
  setInterval(actualizarEstadoOnline, 30000);
});
// ========== CONTADOR DE CARACTERES PARA "SOBRE MÍ" ==========
let updateCharCounter = null; 

function initCharCounter() {
  const textarea = document.getElementById('inputSobreMi');
  const counterSpan = document.querySelector('#editSobreMiWrap .char-counter');
  if (!textarea || !counterSpan) return;

  const max = 500;

  function updateCounter() {
    const length = textarea.value.length;
    counterSpan.textContent = `${length}/${max}`;
    if (length > max) {
      counterSpan.style.color = '#ff7d92';
      textarea.value = textarea.value.slice(0, max);
      counterSpan.textContent = `${max}/${max}`;
    } else {
      counterSpan.style.color = '';
    }
  }

  // Guardamos la función para usarla después
  updateCharCounter = updateCounter;

  textarea.addEventListener('input', updateCounter);
  updateCounter(); // inicial
}
// ========== UTILIDADES ==========
function escapeHtml(str) {
  if (!str) return '';
  return str.replace(/[&<>]/g, function(m) {
    if (m === '&') return '&amp;';
    if (m === '<') return '&lt;';
    if (m === '>') return '&gt;';
    return m;
  });
}

function showToast(message, type = "success") {
  const container = document.getElementById("toastContainer");
  if (!container) return;
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  const icon = type === "success" ? "fa-check-circle" : "fa-exclamation-triangle";
  toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3500);
}