// glitch-toast.js
const glitchSymbols = ['#', '@', '$', '%', '&', '*', '?', '!', '=', '+', '~', '|', '/', '\\', '[', ']', '{', '}', '(', ')', '<', '>', '_', '-'];

function getRandomSymbol() {
    return glitchSymbols[Math.floor(Math.random() * glitchSymbols.length)];
}

function revealTextWithGlitch(element, originalMessage, duration = 1500) {
    const chars = [];
    for (let i = 0; i < originalMessage.length; i++) chars.push(originalMessage[i]);
    const totalChars = chars.length;
    const startTime = Date.now();
    const revealInterval = duration / totalChars;
    element.innerHTML = '';
    for (let i = 0; i < totalChars; i++) {
        if (chars[i] === ' ') {
            const spaceSpan = document.createElement('span');
            spaceSpan.className = 'glitch-char';
            spaceSpan.style.setProperty('--i', i);
            spaceSpan.textContent = ' ';
            spaceSpan.setAttribute('data-original', ' ');
            element.appendChild(spaceSpan);
        } else {
            const span = document.createElement('span');
            span.className = 'glitch-char';
            span.style.setProperty('--i', i);
            span.textContent = getRandomSymbol();
            span.setAttribute('data-original', chars[i]);
            element.appendChild(span);
        }
    }
    let currentRevealed = 0;
    function revealNext() {
        if (currentRevealed >= totalChars) return;
        const elapsed = Date.now() - startTime;
        const expectedRevealed = Math.min(totalChars, Math.floor(elapsed / revealInterval));
        if (expectedRevealed > currentRevealed) {
            for (let i = currentRevealed; i < expectedRevealed && i < totalChars; i++) {
                const span = element.children[i];
                if (span) {
                    const originalChar = span.getAttribute('data-original');
                    span.textContent = originalChar;
                    span.style.animation = 'none';
                    span.offsetHeight;
                    span.style.animation = 'glitchReveal 0.05s steps(2, end) forwards';
                }
            }
            currentRevealed = expectedRevealed;
        }
        if (currentRevealed < totalChars) requestAnimationFrame(revealNext);
    }
    requestAnimationFrame(revealNext);
}

function showNotification(message, type = 'info') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 12px;';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.className = `notification-toast ${type}`;
    const icons = {
        success: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`,
        error: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`,
        warning: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`,
        info: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
    };
    const textSpan = document.createElement('span');
    textSpan.className = 'notification-text';
    toast.innerHTML = `
        <div class="notification-content">
            <div class="notification-icon">${icons[type]}</div>
            <div class="notification-text-wrapper"></div>
        </div>
        <div class="notification-close">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" width="14" height="14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
        <div class="notification-progress"></div>
    `;
    const textWrapper = toast.querySelector('.notification-text-wrapper');
    textWrapper.appendChild(textSpan);
    container.appendChild(toast);
    revealTextWithGlitch(textSpan, message, 1500);
    toast.querySelector('.notification-close').onclick = () => {
        toast.style.animation = 'fadeOut 0.2s ease forwards';
        setTimeout(() => toast.remove(), 200);
    };
    setTimeout(() => {
        if (toast.parentElement) {
            toast.style.animation = 'fadeOut 0.2s ease forwards';
            setTimeout(() => toast.remove(), 200);
        }
    }, 3500);
}

// Procesar notificaciones desde la URL
function processUrlNotification() {
    const urlParams = new URLSearchParams(window.location.search);
    const msg = urlParams.get('msg');
    const type = urlParams.get('type');
    if (msg) {
        showNotification(decodeURIComponent(msg), type || 'info');
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
    }
}
document.addEventListener('DOMContentLoaded', processUrlNotification);