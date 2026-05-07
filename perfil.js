const themeToggleInput = document.getElementById("themeToggleInput");
const themeText = document.getElementById("themeText");

// ==========================================
// 🌐 SISTEMA DE TRADUCCIÓN
// ==========================================
const translationsPerfil = {
  es: {
    inicio: "Inicio", perfil: "Perfil", mensajes: "Mensajes",
    notificaciones: "Notificaciones", configuracion: "Configuración",
    cerrarSesion: "Cerrar sesión", darkMode: "Dark Mode", lightMode: "Light Mode",
    editarPerfil: "Editar perfil", editandoPerfil: "Editando perfil",
    guardar: "Guardar cambios", cancelar: "Cancelar",
    sobreMi: "Sobre mí", detalles: "Detalles", misGustos: "Mis gustos",
    vibraActual: "Vibra actual", enLinea: "En línea",
    perfilGuardado: "¡Perfil guardado con éxito! 🚀",
    errorGuardar: "Error al guardar: ",
    bioCorta: "Bio corta", tagsSeparados: "Tags separados por coma",
    carrera: "Carrera", campus: "Campus", emprendimientos: "Emprendimientos",
    estado: "Estado", mood: "Mood", colorFavorito: "Color favorito",
    metaActual: "Meta actual", estilo: "Estilo",
    sinPublicaciones: "Aún no hay publicaciones",
    cuandoPublique: "Cuando publiques algo, aparecerá aquí."
  },
  en: {
    inicio: "Home", perfil: "Profile", mensajes: "Messages",
    notificaciones: "Notifications", configuracion: "Settings",
    cerrarSesion: "Log out", darkMode: "Dark Mode", lightMode: "Light Mode",
    editarPerfil: "Edit profile", editandoPerfil: "Editing profile",
    guardar: "Save changes", cancelar: "Cancel",
    sobreMi: "About me", detalles: "Details", misGustos: "My likes",
    vibraActual: "Current vibe", enLinea: "Online",
    perfilGuardado: "Profile saved! 🚀",
    errorGuardar: "Error saving: ",
    bioCorta: "Short bio", tagsSeparados: "Tags by comma",
    carrera: "Major", campus: "Campus", emprendimientos: "Startups",
    estado: "Status", mood: "Mood", colorFavorito: "Favorite color",
    metaActual: "Current goal", estilo: "Style",
    sinPublicaciones: "No posts yet",
    cuandoPublique: "When you publish, it will appear here."
  }
};

function getLang() { return localStorage.getItem("lang") || "es"; }
function tPerfil(key) {
  const lang = getLang();
  return translationsPerfil[lang]?.[key] || key;
}

function aplicarIdiomaPerfil() {
  document.querySelectorAll("[data-i18n]").forEach(el => {
    const key = el.dataset.i18n;
    if (translationsPerfil[getLang()]?.[key]) {
      el.textContent = translationsPerfil[getLang()][key];
    }
  });
  actualizarTextoTema();
}

function actualizarTextoTema() {
  if (themeText) {
    const isLight = document.body.classList.contains("light-mode");
    themeText.textContent = isLight ? tPerfil("lightMode") : tPerfil("darkMode");
  }
}

// ==========================================
// 🌓 TEMA
// ==========================================
function aplicarTema(modo) {
  const isLight = modo === "light";
  document.body.classList.toggle("light-mode", isLight);
  if (themeToggleInput) themeToggleInput.checked = isLight;
  actualizarTextoTema();
}

const temaGuardado = localStorage.getItem("theme") || "dark";
aplicarTema(temaGuardado);

if (themeToggleInput) {
  themeToggleInput.addEventListener("change", () => {
    const nuevoTema = themeToggleInput.checked ? "light" : "dark";
    localStorage.setItem("theme", nuevoTema);
    aplicarTema(nuevoTema);
  });
}

// ==========================================
// ✨ PARTÍCULAS
// ==========================================
function createParticles() {
  const container = document.getElementById("particlesBg");
  if (!container) return;
  container.innerHTML = "";
  for (let i = 0; i < 25; i++) {
    const p = document.createElement("div");
    p.className = "particle";
    p.style.cssText = `
      width:${Math.random()*5+2}px; height:${Math.random()*5+2}px;
      left:${Math.random()*100}%; animation-duration:${Math.random()*18+8}s;
      animation-delay:${Math.random()*12}s;
    `;
    container.appendChild(p);
  }
}

// ==========================================
// 📋 DATOS POR DEFECTO
// ==========================================
const defaultProfile = {
  nombre: "Invitado", usuario: "@invitado", bio: "Sin biografía.",
  tags: [], carrera: "Sin carrera.", campus: "Sin campus.",
  emprendimientos: "Sin emprendimientos.", estado: "Sin estado.",
  sobreMi: "Sin descripción.", gustos: [],
  mood: "Sin mood.", color: "Sin color favorito.",
  meta: "Sin meta actual.", estilo: "Sin estilo."
};

// ==========================================
// 🔄 OBTENER PERFIL
// ==========================================
async function getProfile() {
  try {
    const res = await fetch('obtener_perfil.php');
    const data = await res.json();
    if (data.error) return defaultProfile;
    if (typeof data.tags === 'string' && data.tags) data.tags = data.tags.split(',');
    if (typeof data.gustos === 'string' && data.gustos) data.gustos = data.gustos.split(',');
    return { ...defaultProfile, ...data };
  } catch (e) {
    console.error("Error perfil:", e);
    return defaultProfile;
  }
}

// ==========================================
// 💾 GUARDAR PERFIL
// ==========================================
async function saveProfile(profile) {
  try {
    const res = await fetch('actualizar_perfil.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(profile)
    });
    const result = await res.json();
    if (result.success) showToast(tPerfil("perfilGuardado"), "success");
    else showToast(tPerfil("errorGuardar") + (result.error || ''), "error");
  } catch (e) {
    showToast("Error de conexión", "error");
  }
}

// ==========================================
// 🎨 RENDERIZAR
// ==========================================
function renderProfile(p) {
  const dn = document.getElementById("displayName");
  const du = document.getElementById("displayUsername");
  const db = document.getElementById("displayBio");
  const ai = document.getElementById("avatarInitial");
  const hb = document.getElementById("heroBadges");
  const vs = document.getElementById("viewSobreMi");
  const vc = document.getElementById("viewCarrera");
  const vca = document.getElementById("viewCampus");
  const ve = document.getElementById("viewEmprendimientos");
  const ves = document.getElementById("viewEstado");
  const vg = document.getElementById("viewGustos");
  const vm = document.getElementById("viewMood");
  const vco = document.getElementById("viewColor");
  const vme = document.getElementById("viewMeta");
  const ves2 = document.getElementById("viewEstilo");

  if (dn) { const ns = dn.querySelector('.name-gradient'); if (ns) ns.textContent = p.nombre; else dn.textContent = p.nombre; }
  if (du) du.textContent = p.usuario;
  if (db) { const bs = db.querySelector('span:last-child'); if (bs) bs.textContent = p.bio; }
  if (ai && p.nombre) ai.textContent = p.nombre.trim().charAt(0).toUpperCase();
  
  if (hb) {
    hb.innerHTML = "";
    if (p.tags?.length && p.tags[0]) {
      p.tags.forEach(t => { if (t.trim()) { const s = document.createElement("span"); s.className = "tag-item"; s.textContent = t.trim(); hb.appendChild(s); } });
    }
  }

  if (vs) { const pp = vs.querySelector('p'); if (pp) pp.textContent = p.sobreMi; }
  if (vc) vc.textContent = p.carrera;
  if (vca) vca.textContent = p.campus;
  if (ve) ve.textContent = p.emprendimientos;
  if (ves) ves.textContent = p.estado;

  if (vg) {
    vg.innerHTML = "";
    if (p.gustos?.length && p.gustos[0]) {
      p.gustos.forEach(g => { if (g.trim()) { const s = document.createElement("span"); s.className = "tag-item"; s.textContent = g.trim(); vg.appendChild(s); } });
    } else {
      vg.innerHTML = '<span class="tag-item empty-tag">Sin gustos.</span>';
    }
  }

  if (vm) vm.textContent = p.mood;
  if (vco) vco.textContent = p.color;
  if (vme) vme.textContent = p.meta;
  if (ves2) ves2.textContent = p.estilo;
}

// ==========================================
// ✏️ LLENAR INPUTS
// ==========================================
function fillInputs(p) {
  const setVal = (id, val) => { const el = document.getElementById(id); if (el) el.value = val; };
  setVal("inputBio", p.bio);
  setVal("inputTags", Array.isArray(p.tags) ? p.tags.join(", ") : "");
  setVal("inputSobreMi", p.sobreMi);
  setVal("inputGustos", Array.isArray(p.gustos) ? p.gustos.join(", ") : "");
  setVal("inputCarrera", p.carrera);
  setVal("inputCampus", p.campus);
  setVal("inputEmprendimientos", p.emprendimientos);
  setVal("inputEstado", p.estado);
  setVal("inputMood", p.mood);
  setVal("inputColor", p.color);
  setVal("inputMeta", p.meta);
  setVal("inputEstilo", p.estilo);
}

// ==========================================
// 🔄 TOGGLE EDICIÓN
// ==========================================
async function setEditMode(editing) {
  const editPanel = document.getElementById("editPanel");
  const editBtn = document.getElementById("editBtn");
  const editDetails = document.getElementById("editDetails");
  const editSobreMi = document.getElementById("editSobreMiWrap");
  const editGustos = document.getElementById("editGustosWrap");
  const editMood = document.getElementById("editMoodGrid");
  const viewDetails = document.getElementById("viewDetails");
  const viewSobreMi = document.getElementById("viewSobreMi");
  const viewGustos = document.getElementById("viewGustos");
  const viewMood = document.getElementById("viewMoodGrid");

  const toggle = (el, show) => { if (el) el.classList.toggle("hidden", !show); };

  if (editing) {
    toggle(editPanel, true);
    toggle(editDetails, true);
    toggle(editSobreMi, true);
    toggle(editGustos, true);
    toggle(editMood, true);
    toggle(viewDetails, false);
    toggle(viewSobreMi, false);
    toggle(viewGustos, false);
    toggle(viewMood, false);
    if (editBtn) editBtn.style.display = "none";
    fillInputs(await getProfile());
  } else {
    toggle(editPanel, false);
    toggle(editDetails, false);
    toggle(editSobreMi, false);
    toggle(editGustos, false);
    toggle(editMood, false);
    toggle(viewDetails, true);
    toggle(viewSobreMi, true);
    toggle(viewGustos, true);
    toggle(viewMood, true);
    if (editBtn) editBtn.style.display = "";
  }
}

// ==========================================
// 🎯 EVENTOS
// ==========================================
document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("editBtn")?.addEventListener("click", () => setEditMode(true));
  document.getElementById("cancelBtn")?.addEventListener("click", () => setEditMode(false));
  
  document.getElementById("saveBtn")?.addEventListener("click", async () => {
    const gv = (id) => document.getElementById(id)?.value?.trim() || "";
    const nuevo = {
      bio: gv("inputBio") || defaultProfile.bio,
      tags: gv("inputTags").split(",").map(t => t.trim()).filter(Boolean),
      carrera: gv("inputCarrera") || defaultProfile.carrera,
      campus: gv("inputCampus") || defaultProfile.campus,
      emprendimientos: gv("inputEmprendimientos") || defaultProfile.emprendimientos,
      estado: gv("inputEstado") || defaultProfile.estado,
      sobreMi: gv("inputSobreMi") || defaultProfile.sobreMi,
      gustos: gv("inputGustos").split(",").map(g => g.trim()).filter(Boolean),
      mood: gv("inputMood") || defaultProfile.mood,
      color: gv("inputColor") || defaultProfile.color,
      meta: gv("inputMeta") || defaultProfile.meta,
      estilo: gv("inputEstilo") || defaultProfile.estilo
    };
    if (!nuevo.tags.length) nuevo.tags = defaultProfile.tags;
    if (!nuevo.gustos.length) nuevo.gustos = defaultProfile.gustos;
    await saveProfile(nuevo);
    renderProfile(await getProfile());
    setEditMode(false);
  });
});

// ==========================================
// 🍞 TOAST
// ==========================================
function showToast(msg, type = "info") {
  const c = document.getElementById("toastContainer");
  if (!c) return;
  const t = document.createElement("div");
  t.className = `profile-toast toast-${type}`;
  t.textContent = msg;
  c.appendChild(t);
  setTimeout(() => { t.style.animation = "toastOut 0.3s ease forwards"; setTimeout(() => t.remove(), 300); }, 3000);
}

// ==========================================
// 🚀 INICIO
// ==========================================
(async () => {
  createParticles();
  aplicarIdiomaPerfil();
  renderProfile(await getProfile());
})();