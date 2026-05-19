// ========== TEMA Y TRADUCCIONES ==========

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
    cargandoPerfil: "Cargando tu perfil...",
ejCarrera: "Ej: Ingeniería en Software",
ejCampus: "Ej: Campus Central",
ejEmprendimiento: "Ej: Mi Startup",
ejEstado: "Ej: Emprendiendo",
ejSobreMi: "Cuéntanos sobre ti...",
ejMood: "Ej: Creativo",
ejColor: "Ej: Azul eléctrico",
ejMeta: "Ej: Lanzar mi app",
ejEstilo: "Ej: Minimalista",
ejBio: "Una breve descripción sobre ti...",
ejTags: "retro web, uni vibes, creative",
ejGustos: "Gustos separados por coma...",
compartirPerfil: "Compartir perfil",
sinTags: "Sin tags",
sinGustos: "Sin gustos",
sinCarrera: "Sin carrera.",
sinCampus: "Sin campus.",
sinEmprendimientos: "Sin emprendimientos.",
sinEstado: "Sin estado.",
sinDescripcion: "Sin descripción.",
sinMood: "Sin mood.",
sinColor: "Sin color favorito.",
sinMeta: "Sin meta actual.",
sinEstilo: "Sin estilo.",
fotoActualizada: "Foto de perfil actualizada",
errorFoto: "Error al subir la foto",
noEditarPerfilAjeno: "No puedes editar este perfil",
errorConexionPerfil: "Error de conexión",
enlaceCopiado: "🔗 Enlace copiado al portapapeles",
errorCopiarEnlace: "❌ No se pudo copiar el enlace",
invitado: "Invitado",
invitadoUsuario: "@invitado",
cropTitle: "Recortar foto de perfil",
confirmar: "Confirmar",
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
    cargandoPerfil: "Loading your profile...",
ejCarrera: "e.g., Software Engineering",
ejCampus: "e.g., Central Campus",
ejEmprendimiento: "e.g., My Startup",
ejEstado: "e.g., Entrepreneur",
ejSobreMi: "Tell us about yourself...",
ejMood: "e.g., Creative",
ejColor: "e.g., Electric Blue",
ejMeta: "e.g., Launch my app",
ejEstilo: "e.g., Minimalist",
ejBio: "A short description about you...",
ejTags: "retro web, uni vibes, creative",
ejGustos: "Likes separated by commas...",
compartirPerfil: "Share profile",
sinTags: "No tags",
sinGustos: "No likes",
sinCarrera: "No major.",
sinCampus: "No campus.",
sinEmprendimientos: "No entrepreneurships.",
sinEstado: "No status.",
sinDescripcion: "No description.",
sinMood: "No mood.",
sinColor: "No favorite color.",
sinMeta: "No current goal.",
sinEstilo: "No style.",
fotoActualizada: "Profile picture updated",
errorFoto: "Error uploading photo",
noEditarPerfilAjeno: "You cannot edit this profile",
errorConexionPerfil: "Connection error",
enlaceCopiado: "🔗 Link copied to clipboard",
errorCopiarEnlace: "❌ Could not copy the link",
invitado: "Guest",
invitadoUsuario: "@guest",
cropTitle: "Crop profile picture",
confirmar: "Confirm",
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


// ========== OBTENER PERFIL (acepta ID externo) ==========
async function getProfile(userId = null) {
  let url = 'obtener_perfil.php';
  if (userId) {
    url += '?id=' + userId;
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
    showToast(t("noEditarPerfilAjeno"), "warning");
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
    showToast(t("errorConexionPerfil"), "danger");
  }
}

// ========== RENDERIZAR PERFIL ==========
function renderProfile(profile) {
  console.log("🎨 Renderizando perfil con datos:", profile);
  
  // Hero
// Traducir nombre si es "Invitado" o está vacío
let nombreMostrar = profile.nombre;
if (!nombreMostrar || nombreMostrar === "Invitado") {
    nombreMostrar = t("invitado");
}
const displayName = document.getElementById("displayName");
if (displayName) displayName.innerHTML = `<span class="name-gradient">${escapeHtml(nombreMostrar)}</span>`;

// Traducir usuario si es "@invitado" o está vacío
let usuarioMostrar = profile.usuario;
if (!usuarioMostrar || usuarioMostrar === "@invitado") {
    usuarioMostrar = t("invitadoUsuario");
}
const displayUsername = document.getElementById("displayUsername");
if (displayUsername) displayUsername.innerHTML = usuarioMostrar.startsWith('@') ? usuarioMostrar : `@${usuarioMostrar}`;
  if (displayBio) displayBio.innerHTML = `<span class="bio-icon"></span><span>${escapeHtml(profile.bio)}</span>`;

  // Avatar
const avatarMain = document.getElementById("profileAvatarMain");
if (profile.foto_perfil && profile.foto_perfil !== "") {
  avatarMain.innerHTML = `<img src="${profile.foto_perfil}" alt="Foto de perfil" style="width:100%; height:100%; object-fit:cover; border-radius:28px;">`;
} else {
  // Usar el nombre ya traducido (nombreMostrar) para la inicial
  const inicial = nombreMostrar ? nombreMostrar.trim().charAt(0).toUpperCase() : "U";
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
      heroBadges.innerHTML = `<span class="badge-placeholder">${t("sinTags")}</span>`;
    }
  }

  // Sobre mí
  const viewSobreMi = document.getElementById("viewSobreMi");
  if (viewSobreMi) {
    let sobreMiText = profile.sobreMi;
    if (!sobreMiText || sobreMiText === "Sin descripción.") {
      sobreMiText = t("sinDescripcion");
    }
    viewSobreMi.innerHTML = `<p>${escapeHtml(sobreMiText)}</p>`;
  }

  // Detalles con traducción de valores por defecto
  const viewCarrera = document.getElementById("viewCarrera");
  if (viewCarrera) {
    let carrera = profile.carrera;
    if (!carrera || carrera === "Sin carrera.") carrera = t("sinCarrera");
    viewCarrera.innerHTML = escapeHtml(carrera);
  }

  const viewCampus = document.getElementById("viewCampus");
  if (viewCampus) {
    let campus = profile.campus;
    if (!campus || campus === "Sin campus.") campus = t("sinCampus");
    viewCampus.innerHTML = escapeHtml(campus);
  }

  const viewEmprendimientos = document.getElementById("viewEmprendimientos");
  if (viewEmprendimientos) {
    let emp = profile.emprendimientos;
    if (!emp || emp === "Sin emprendimientos.") emp = t("sinEmprendimientos");
    viewEmprendimientos.innerHTML = escapeHtml(emp);
  }

  const viewEstado = document.getElementById("viewEstado");
  if (viewEstado) {
    let estado = profile.estado;
    if (!estado || estado === "Sin estado.") estado = t("sinEstado");
    viewEstado.innerHTML = escapeHtml(estado);
  }

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
      viewGustos.innerHTML = `<span class="tag-item empty-tag">${t("sinGustos")}</span>`;
    }
  }

  // Mood, color, meta, estilo
  const viewMood = document.getElementById("viewMood");
  if (viewMood) {
    let mood = profile.mood;
    if (!mood || mood === "Sin mood.") mood = t("sinMood");
    viewMood.textContent = mood;
  }

  const viewColor = document.getElementById("viewColor");
  if (viewColor) {
    let color = profile.color;
    if (!color || color === "Sin color favorito.") color = t("sinColor");
    viewColor.textContent = color;
  }

  const viewMeta = document.getElementById("viewMeta");
  if (viewMeta) {
    let meta = profile.meta;
    if (!meta || meta === "Sin meta actual.") meta = t("sinMeta");
    viewMeta.textContent = meta;
  }

  const viewEstilo = document.getElementById("viewEstilo");
  if (viewEstilo) {
    let estilo = profile.estilo;
    if (!estilo || estilo === "Sin estilo.") estilo = t("sinEstilo");
    viewEstilo.textContent = estilo;
  }

  // Estado en línea
  const statusBadge = document.getElementById("statusBadge");
  if (statusBadge) {
    const online = profile.is_online == 1;
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
      showToast(t("fotoActualizada"), 'success');
      // Recargar el perfil completo para mostrar la nueva foto
      const nuevoPerfil = await getProfile();
      renderProfile(nuevoPerfil);
      currentProfile = nuevoPerfil;
    } else {
      showToast('Error: ' + data.error, 'error');
    }
  } catch (e) {
    console.error(e);
    showToast(t("errorFoto"), 'error');
  }
}
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
    if (typeof updateCharCounter === 'function') updateCharCounter();
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
// ========== INICIALIZACIÓN ==========
document.addEventListener("DOMContentLoaded", async () => {
  console.log("🚀 Inicializando perfil...");
  aplicarIdioma();

  // 1. Obtener sesión del usuario logueado
  let miUsuarioId = null;
  let miRol = null;
  try {
    const res = await fetch('obtener_sesion.php');
    const data = await res.json();
    miUsuarioId = data.usuario_id;
    miRol = data.rol;
    window.miUsuarioId = miUsuarioId;
    window.miRol = miRol;
  } catch (e) {
    console.error("Error obteniendo sesión", e);
  }

  // 2. Determinar si es perfil propio o ajeno
  const urlParams = new URLSearchParams(window.location.search);
  const perfilUserId = urlParams.get('user_id');
  let esMiPerfil = true;
  if (perfilUserId && miUsuarioId && perfilUserId != miUsuarioId) {
    esMiPerfil = false;
  } else if (!miUsuarioId) {
    esMiPerfil = false; // invitado
  }

  // 3. Obtener el perfil (si hay perfilUserId, usar ese; si no, el propio)
  let profile;
  if (perfilUserId) {
    profile = await getProfile(perfilUserId);
  } else {
    profile = await getProfile(); // obtiene el perfil del usuario logueado
  }
  renderProfile(profile);
  let currentProfile = profile;

  // 4. Funciones de edición (capturan perfilUserId y esMiPerfil)
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
      // Obtener el perfil actual (puede ser el propio o ajeno, pero solo se edita si es propio)
      currentProfile = await getProfile(perfilUserId);
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

    // Función saveProfile local (sin dependencias externas)
    async function saveProfileLocal(profile) {
      try {
        const res = await fetch('actualizar_perfil.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(profile)
        });
        const result = await res.json();
        if (result.success) {
          showToast(t("perfilGuardado"), "success");
        } else {
          showToast(t("errorGuardar") + result.error, "danger");
        }
      } catch (e) {
        console.error(e);
        showToast(t("errorConexionPerfil"), "danger");
      }
    }

    await saveProfileLocal(nuevoPerfil);
    const actualizado = await getProfile(perfilUserId);
    renderProfile(actualizado);
    currentProfile = actualizado;
    setEditMode(false);
  }

  // 5. Subir foto de perfil (solo si es mi perfil)
  async function subirFotoPerfil(file) {
    const formData = new FormData();
    formData.append('foto', file);
    try {
      const res = await fetch('subir_foto_perfil.php', { method: 'POST', body: formData });
      const data = await res.json();
      if (data.success) {
        showToast(t("fotoActualizada"), 'success');
        const nuevoPerfil = await getProfile(perfilUserId);
        renderProfile(nuevoPerfil);
        currentProfile = nuevoPerfil;
      } else {
        showToast('Error: ' + data.error, 'error');
      }
    } catch (e) {
      console.error(e);
      showToast(t("errorFoto"), 'error');
    }
  }

  // 6. Configurar botones según si es mi perfil
  const editBtn = document.getElementById("editBtn");
  const sendMsgBtn = document.getElementById("sendMsgBtn");

  if (esMiPerfil) {
    if (editBtn) editBtn.style.display = "flex";
    if (sendMsgBtn) sendMsgBtn.style.display = "none";

    const saveBtn = document.getElementById("saveBtn");
    const cancelBtn = document.getElementById("cancelBtn");
    if (editBtn) editBtn.addEventListener("click", () => setEditMode(true));
    if (saveBtn) saveBtn.addEventListener("click", guardarCambios);
    if (cancelBtn) cancelBtn.addEventListener("click", () => setEditMode(false));

    // Evento para cambiar foto
    // Evento para cambiar foto (con recorte)
const avatarMain = document.getElementById("profileAvatarMain");
if (avatarMain) {
  avatarMain.style.cursor = "pointer";
  const newAvatar = avatarMain.cloneNode(true);
  avatarMain.parentNode.replaceChild(newAvatar, avatarMain);
  
  // Variables para el modal (las definimos aquí para tener acceso)
  let cropperInstance = null;
  const cropModal = document.getElementById("cropModal");
  const cropImage = document.getElementById("cropImage");
  const cropConfirmBtn = document.getElementById("cropConfirmBtn");
  const cropCancelBtn = document.getElementById("cropCancelBtn");

  newAvatar.addEventListener("click", () => {
    const input = document.createElement("input");
    input.type = "file";
    input.accept = "image/jpeg,image/png,image/gif,image/webp";
    input.onchange = (e) => {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = (ev) => {
          cropImage.src = ev.target.result;
          cropModal.style.display = "flex";
          // Esperar a que la imagen cargue para inicializar Cropper
          cropImage.onload = () => {
            if (cropperInstance) cropperInstance.destroy();
            cropperInstance = new Cropper(cropImage, {
              aspectRatio: 1,
              viewMode: 2,
              movable: true,
              zoomable: true,
              rotatable: false,
              scalable: false,
              background: false,
              autoCropArea: 0.8,
            });
          };
          if (cropImage.complete) cropImage.onload();
        };
        reader.readAsDataURL(file);
      }
    };
    input.click();
  });

  // Cancelar recorte
  cropCancelBtn.addEventListener("click", () => {
    if (cropperInstance) cropperInstance.destroy();
    cropModal.style.display = "none";
  });

  // Confirmar recorte y subir foto
  cropConfirmBtn.addEventListener("click", () => {
    if (cropperInstance) {
      const canvas = cropperInstance.getCroppedCanvas({
        width: 400,
        height: 400,
        imageSmoothingQuality: "high",
      });
      canvas.toBlob((blob) => {
        const croppedFile = new File([blob], "avatar.jpg", { type: "image/jpeg" });
        // Llamar a la función de subida con el archivo recortado
        subirFotoPerfil(croppedFile);
        cropModal.style.display = "none";
        if (cropperInstance) cropperInstance.destroy();
        cropperInstance = null;
      }, "image/jpeg", 0.9);
    }
  });
}
  } else {
    // Perfil ajeno
    if (editBtn) editBtn.style.display = "none";
    if (sendMsgBtn && miUsuarioId) {
      sendMsgBtn.style.display = "flex";
      sendMsgBtn.onclick = () => {
        window.location.href = `mensajes?user_id=${perfilUserId}`;
      };
    } else if (sendMsgBtn) {
      sendMsgBtn.style.display = "none";
    }
    const avatarMain = document.getElementById("profileAvatarMain");
    if (avatarMain) {
      avatarMain.style.cursor = "default";
      const newAvatar = avatarMain.cloneNode(true);
      avatarMain.parentNode.replaceChild(newAvatar, avatarMain);
    }
  }

  // 7. Botón compartir
  const shareBtn = document.getElementById("shareProfileBtn");
  if (shareBtn) {
    shareBtn.addEventListener("click", async () => {
      let urlCompartir = window.location.href;
      if (!perfilUserId && miUsuarioId) {
        const baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        urlCompartir = baseUrl + "?user_id=" + miUsuarioId;
      }
      if (navigator.clipboard && window.isSecureContext) {
        try {
          await navigator.clipboard.writeText(urlCompartir);
          showToast(t("enlaceCopiado"), "success");
        } catch (err) {
          copiarConFallback(urlCompartir);
        }
      } else {
        copiarConFallback(urlCompartir);
      }
    });
  }

  function copiarConFallback(texto) {
    const textarea = document.createElement("textarea");
    textarea.value = texto;
    textarea.style.position = "fixed";
    textarea.style.opacity = "0";
    document.body.appendChild(textarea);
    textarea.select();
    try {
      document.execCommand("copy");
      showToast(t("enlaceCopiado"), "success");
    } catch (err) {
      showToast(t("errorCopiarEnlace"), "error");
    }
    document.body.removeChild(textarea);
  }

  // 8. Actualizar estado en línea cada 30 segundos
  async function actualizarEstadoOnline() {
    let url = 'obtener_perfil.php';
    if (perfilUserId) url += '?id=' + perfilUserId;
    try {
      const res = await fetch(url);
      const data = await res.json();
      if (data.is_online !== undefined) {
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
  // Forzar traducción del botón de confirmar del modal de recorte
const confirmCropBtn = document.getElementById("cropConfirmBtn");
if (confirmCropBtn) confirmCropBtn.textContent = t("confirmar");
window.addEventListener("storage", (e) => {
  if (e.key === "lang") {
    aplicarIdioma();
    const confirmCropBtn = document.getElementById("cropConfirmBtn");
    if (confirmCropBtn) confirmCropBtn.textContent = t("confirmar");
    // También recargar el perfil si es necesario (ya lo haces en otras páginas)
  }
});
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