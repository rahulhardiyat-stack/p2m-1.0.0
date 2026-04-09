<div class="acc-widget" id="accessibilityWidget">
    <button class="acc-toggle" onclick="toggleAccMenu()" title="Pengaturan Aksesibilitas">
        <i class="fas fa-universal-access"></i>
    </button>

    <div class="acc-menu" id="accMenu">
        <div class="acc-header">
            <i class="fas fa-wheelchair"></i> Aksesibilitas
        </div>
        
        <div class="acc-grid">
            <button class="acc-btn" onclick="toggleAccFeature('grayscale')" id="btn-grayscale">
                <i class="fas fa-adjust"></i>
                <span>Grayscale</span>
            </button>
            <button class="acc-btn" onclick="toggleAccFeature('contrast')" id="btn-contrast">
                <i class="fas fa-circle-half-stroke"></i>
                <span>Kontras Tinggi</span>
            </button>
            <button class="acc-btn" onclick="toggleAccFeature('fontplus')" id="btn-fontplus">
                <i class="fas fa-text-height"></i>
                <span>Perbesar Teks</span>
            </button>
            <button class="acc-btn" onclick="toggleAccFeature('links')" id="btn-links">
                <i class="fas fa-link"></i>
                <span>Tandai Link</span>
            </button>
            <button class="acc-btn" onclick="toggleAccFeature('legible')" id="btn-legible">
                <i class="fas fa-font"></i>
                <span>Font Terbaca</span>
            </button>
            <button class="acc-btn" onclick="toggleAccFeature('tts')" id="btn-tts">
                <i class="fas fa-volume-up"></i>
                <span>Text to Speech</span>
            </button>
            <button class="acc-btn" onclick="toggleAccFeature('dark')" id="btn-dark">
                <i class="fas fa-moon"></i>
                <span>Mode Gelap</span>
            </button>
        </div>

        <button class="acc-reset" onclick="resetAccSettings()">
            <i class="fas fa-undo me-1"></i> Atur Ulang
        </button>
    </div>
</div>

<style>
    .tts-hover-active {
        outline: 2px dashed #2563eb !important;
        outline-offset: 4px !important;
        transition: outline 0.2s ease !important;
    }
    html.dark .tts-hover-active {
        outline-color: #60a5fa !important;
    }

    /* Accessibility Utilities - Core Logic */
    .accessibility-grayscale {
        filter: grayscale(100%) !important;
    }

    .accessibility-high-contrast {
        background: #000 !important;
        color: #fff !important;
    }

    .accessibility-high-contrast *:not(.acc-widget):not(.acc-widget *) {
        background-color: #000 !important;
        color: #ffff00 !important;
        border-color: #ffff00 !important;
        box-shadow: none !important;
    }

    .accessibility-high-contrast a:not(.acc-btn) {
        color: #00ffff !important;
        text-decoration: underline !important;
    }

    .accessibility-font-plus {
        font-size: 1.25rem !important;
    }

    .accessibility-font-plus h1 { font-size: 3.5rem !important; }
    .accessibility-font-plus h2 { font-size: 3rem !important; }
    .accessibility-font-plus h3 { font-size: 2.5rem !important; }
    .accessibility-font-plus p, 
    .accessibility-font-plus span, 
    .accessibility-font-plus li, 
    .accessibility-font-plus a:not(.acc-btn) {
        font-size: 1.2em !important;
    }

    .accessibility-link-highlight a:not(.acc-btn) {
        text-decoration: underline !important;
        font-weight: 800 !important;
        background-color: yellow !important;
        color: black !important;
        padding: 0 2px;
    }

    .accessibility-legible-font *:not(.fa):not(.fas):not(.far):not(.fab) {
        font-family: 'Arial', 'Helvetica', 'Open Sans', sans-serif !important;
    }
    
    /* Ensure widget stays readable in high contrast */
    .accessibility-high-contrast .acc-btn.active {
        background-color: #ffff00 !important;
        color: #000 !important;
        border: 2px solid #fff !important;
    }
</style>

<script>
const accSettings = JSON.parse(localStorage.getItem('acc_settings')) || {
    grayscale: false,
    contrast: false,
    fontplus: false,
    links: false,
    legible: false,
    tts: false
};

// Ensure tts is present in older settings
if (accSettings.tts === undefined) accSettings.tts = false;

// Dark mode state from existing Alpine logic
let isDarkMode = localStorage.getItem('darkMode') === 'true';

// TTS Controls
let ttsTimer = null;
let synth = window.speechSynthesis;

function toggleAccMenu() {
    document.getElementById('accMenu').classList.toggle('open');
}

function toggleAccFeature(feature) {
    if (feature === 'dark') {
        isDarkMode = !isDarkMode;
        localStorage.setItem('darkMode', isDarkMode);
        document.documentElement.classList.toggle('dark', isDarkMode);
    } else {
        accSettings[feature] = !accSettings[feature];
        applyAccFeature(feature, accSettings[feature]);
        saveAccSettings();
    }
    
    updateAccButtons();
    
    // Stop speech if TTS disabled
    if (feature === 'tts' && !accSettings.tts) {
        stopSpeech();
    }
}

function applyAccFeature(feature, active) {
    const body = document.body;
    const classMap = {
        grayscale: 'accessibility-grayscale',
        contrast: 'accessibility-high-contrast',
        fontplus: 'accessibility-font-plus',
        links: 'accessibility-link-highlight',
        legible: 'accessibility-legible-font'
    };

    if (feature === 'tts') return; // Handled via event listeners

    if (active) {
        body.classList.add(classMap[feature]);
    } else {
        body.classList.remove(classMap[feature]);
    }
}

function updateAccButtons() {
    for (const [feature, active] of Object.entries(accSettings)) {
        const btn = document.getElementById(`btn-${feature}`);
        if (btn) {
            if (active) btn.classList.add('active');
            else btn.classList.remove('active');
        }
    }
    // Dark mode button specialized
    const darkBtn = document.getElementById('btn-dark');
    if (darkBtn) {
        if (isDarkMode) darkBtn.classList.add('active');
        else darkBtn.classList.remove('active');
    }
}

function saveAccSettings() {
    localStorage.setItem('acc_settings', JSON.stringify(accSettings));
}

function resetAccSettings() {
    Object.keys(accSettings).forEach(key => {
        if (key === 'dark') return;
        accSettings[key] = false;
        applyAccFeature(key, false);
    });
    
    stopSpeech();
    updateAccButtons();
    saveAccSettings();
}

// --- Text to Speech Implementation ---

function stopSpeech() {
    if (synth) synth.cancel();
    clearTimeout(ttsTimer);
    document.querySelectorAll('.tts-hover-active').forEach(el => el.classList.remove('tts-hover-active'));
}

function speakText(element) {
    if (!accSettings.tts || !synth) return;

    const text = element.innerText || element.textContent;
    if (!text || text.trim().length < 2) return;

    stopSpeech();

    ttsTimer = setTimeout(() => {
        const utterance = new SpeechSynthesisUtterance(text.trim());
        
        // Detect Page Language
        const lang = document.documentElement.lang || 'id-ID';
        utterance.lang = lang;
        
        // Try to find a better matching voice for the language
        const voices = synth.getVoices();
        const voice = voices.find(v => v.lang.startsWith(lang.split('-')[0])) || voices[0];
        if (voice) utterance.voice = voice;

        utterance.rate = 1.0;
        utterance.pitch = 1.0;

        element.classList.add('tts-hover-active');
        
        utterance.onend = () => {
            element.classList.remove('tts-hover-active');
        };

        synth.speak(utterance);
    }, 400); // 400ms delay for intentional hovering
}

// Delegation for TTS Hover
document.addEventListener('mouseover', (e) => {
    if (!accSettings.tts) return;
    
    const target = e.target.closest('p, h1, h2, h3, h4, h5, h6, span, li, a, .card-title, .lead');
    if (target && !target.closest('.acc-widget')) {
        speakText(target);
    }
});

document.addEventListener('mouseout', (e) => {
    if (!accSettings.tts) return;
    clearTimeout(ttsTimer);
});

// Initial Load
document.addEventListener('DOMContentLoaded', () => {
    for (const [feature, active] of Object.entries(accSettings)) {
        if (feature !== 'dark') applyAccFeature(feature, active);
    }
    updateAccButtons();
    
    // Voice cold start (some browsers need this)
    if (synth) synth.getVoices();
});

// Close menu when clicking outside
document.addEventListener('click', (e) => {
    const widget = document.getElementById('accessibilityWidget');
    if (!widget.contains(e.target)) {
        document.getElementById('accMenu').classList.remove('open');
    }
});
</script>
