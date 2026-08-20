{{-- Google Translate Hidden Element --}}
<div class="d-none">
    <div id="google_translate_element_admin"></div>
</div>

{{-- Language Switcher Dropdown --}}
<li class="nav-item dropdown d-flex align-items-center mr-2 notranslate" translate="no">
    <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" data-toggle="dropdown" href="#" title="Change Language" style="padding: 4px 10px;">
        <i class="fas fa-globe" style="font-size: 0.9rem;"></i>
        <span id="adminLangLabel" class="notranslate" translate="no" style="font-size: 0.85rem; font-weight: 700; margin-left: 4px; min-width: 20px; display: inline-block; text-align: center;"></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right shadow border-0 notranslate" translate="no" style="min-width: 160px; border-radius: 12px; margin-top: 10px; padding: 8px 0;">
        <div class="dropdown-header text-uppercase small font-weight-bold pb-2 text-primary" style="letter-spacing: 0.8px; font-size: 0.7rem;">Select Language</div>
        <a class="dropdown-item d-flex align-items-center py-2 px-3" href="javascript:void(0)" onclick="adminChangeTranslate('id')" style="transition: all 0.2s;">
            <span class="badge badge-light border mr-2 text-dark font-weight-bold" style="width: 35px; font-size: 0.75rem; padding: 4px 0; display: inline-block;">ID</span>
            <span class="small font-weight-bold" style="color: #334155;">Indonesia</span>
        </a>
        <a class="dropdown-item d-flex align-items-center py-2 px-3" href="javascript:void(0)" onclick="adminChangeTranslate('en')" style="transition: all 0.2s;">
            <span class="badge badge-light border mr-2 text-dark font-weight-bold" style="width: 35px; font-size: 0.75rem; padding: 4px 0; display: inline-block;">EN</span>
            <span class="small font-weight-bold" style="color: #334155;">English</span>
        </a>
        <a class="dropdown-item d-flex align-items-center py-2 px-3" href="javascript:void(0)" onclick="adminChangeTranslate('ar')" style="transition: all 0.2s;">
            <span class="badge badge-light border mr-2 text-dark font-weight-bold" style="width: 35px; font-size: 0.75rem; padding: 4px 0; display: inline-block;">AR</span>
            <span class="small font-weight-bold" style="color: #334155;">Arabic</span>
        </a>
    </div>
</li>


@push('js')
<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            autoDisplay: false,
            multilanguagePage: true
        }, 'google_translate_element_admin');
    }

    function adminChangeTranslate(lang) {
        if (lang === 'id') {
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + location.hostname + ";";
        } else {
            document.cookie = "googtrans=/id/" + lang + "; path=/;";
            document.cookie = "googtrans=/id/" + lang + "; path=/; domain=" + location.hostname + ";";
        }
        location.reload();
    }

    document.addEventListener("DOMContentLoaded", function () {
        let match = document.cookie.match(/(^| )googtrans=([^;]+)/);
        let currentLang = 'ID';
        if (match) {
            let parts = match[2].split('/');
            if (parts.length > 2 && parts[2]) {
                currentLang = parts[2].toUpperCase();
            }
        }
        let lbl = document.getElementById('adminLangLabel');
        if (lbl) lbl.innerText = currentLang;

        function hideGoogleBar() {
            document.querySelectorAll('.skiptranslate, .goog-te-banner-frame, iframe.goog-te-banner-frame').forEach(function(el) {
                el.style.setProperty('display', 'none', 'important');
                el.style.setProperty('visibility', 'hidden', 'important');
            });
            document.body.style.setProperty('top', '0', 'important');
            document.body.style.setProperty('position', 'static', 'important');
        }

        hideGoogleBar();
        const observer = new MutationObserver(hideGoogleBar);
        observer.observe(document.body, { childList: true, subtree: true });
    });
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
@endpush

@push('css')
<style>
    /* Prevent Google Translate from shifting the layout */
    body { top: 0 !important; position: static !important; }
    .skiptranslate { display: none !important; visibility: hidden !important; }
    .goog-te-banner-frame { display: none !important; visibility: hidden !important; }
    iframe.goog-te-banner-frame { display: none !important; }
    #goog-gt-tt, .goog-te-balloon-frame { display: none !important; }
    .goog-text-highlight { background: none !important; box-shadow: none !important; }
    
    /* Ensure notranslate works and layout remains stable */
    .notranslate { translate: no !important; }
    
    .dropdown-item:hover {
        background-color: #f8fafc !important;
        color: #0284c7 !important;
    }
    .dropdown-item:hover .badge {
        background-color: #e0f2fe !important;
        border-color: #bae6fd !important;
        color: #0369a1 !important;
    }

    @keyframes adminDropFade {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .dropdown-menu { animation: adminDropFade 0.2s ease-out; }
</style>
@endpush
