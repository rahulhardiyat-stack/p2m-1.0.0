<?php

return [
    'title' => env('ADMINLTE_TITLE', env('APP_NAME', 'Website Prodi')),
    'title_prefix' => '',
    'title_postfix' => '',
    'use_ico_only' => false,
    'use_full_favicon' => true,
    'logo' => '<span translate="no"><b>' . explode(' ', env('APP_NAME', 'Website Prodi'))[0] . '</b></span> ' . (implode(' ', array_slice(explode(' ', env('APP_NAME', 'Website Prodi')), 1)) ?: 'Admin'),
    'logo_img' => 'images/logo.png',
    'logo_img_class' => 'brand-image',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => env('APP_NAME', 'Admin Prodi'),
    'auth_logo' => ['enabled' => false],
    'preloader' => ['enabled' => false],
    'skin' => 'blue-light',
    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,
    'classes_body' => 'sidebar-mini layout-fixed',
    'classes_brand' => 'bg-primary',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => 'nav-child-indent',
    'classes_topnav' => 'navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container-fluid',
    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,
    'right_sidebar' => false,

    'menu' => [
        // Navbar items
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'type' => 'darkmode-widget',
            'topnav_right' => true,
        ],
        [
            'type' => 'custom-view',
            'view' => 'admin.partials.language-switcher',
            'topnav_right' => true,
        ],

        ['text' => 'Dashboard', 'url' => 'admin', 'icon' => 'fas fa-tachometer-alt', 'can' => 'view dashboard'],

        ['header' => 'KONTEN WEBSITE'],
        [
            'text' => 'Berita & Artikel',
            'icon' => 'fas fa-newspaper',
            'can' => 'view posts',
            'submenu' => [
                ['text' => 'Semua Artikel', 'url' => 'admin/posts', 'icon' => 'fas fa-list'],
                ['text' => 'Tambah Artikel', 'url' => 'admin/posts/create', 'icon' => 'fas fa-plus'],
                ['text' => 'Kategori', 'url' => 'admin/categories', 'icon' => 'fas fa-tags'],
                ['text' => 'Tag', 'url' => 'admin/tags', 'icon' => 'fas fa-tag'],
            ],
        ],
        ['text' => 'Pengumuman', 'url' => 'admin/announcements', 'icon' => 'fas fa-bullhorn', 'can' => 'view announcements'],
        [
            'text' => 'Halaman Statis',
            'icon' => 'fas fa-file-alt',
            'can' => 'view pages',
            'submenu' => [
                [
                    'text' => 'Semua Halaman',
                    'url' => 'admin/pages',
                    'icon' => 'fas fa-list',
                ],
                [
                    'text' => 'Tambah Halaman',
                    'url' => 'admin/pages/create',
                    'icon' => 'fas fa-plus',
                ],
            ],
        ],
        ['text' => 'Agenda & Event', 'url' => 'admin/events', 'icon' => 'fas fa-calendar-alt', 'can' => 'view events'],
        ['text' => 'Galeri', 'url' => 'admin/gallery', 'icon' => 'fas fa-images', 'can' => 'view gallery'],
        ['text' => 'Slider/Banner', 'url' => 'admin/sliders', 'icon' => 'fas fa-sliders-h', 'can' => 'view sliders'],
        ['text' => 'Testimoni Alumni', 'url' => 'admin/testimonials', 'icon' => 'fas fa-quote-left', 'can' => 'view testimonials'],
        ['text' => 'Mitra Kerjasama', 'url' => 'admin/partners', 'icon' => 'fas fa-handshake', 'can' => 'view sliders'],

        ['header' => 'MODUL HALAMAN'],
        ['text' => 'Manajemen Profil', 'url' => 'admin/settings/profile', 'icon' => 'fas fa-university', 'can' => 'view settings'],
        ['text' => 'Sumber Daya Manusia', 'url' => 'admin/lecturers', 'icon' => 'fas fa-users', 'can' => 'view lecturers'],
        ['text' => 'Kurikulum', 'url' => 'admin/curriculums', 'icon' => 'fas fa-book', 'can' => 'view curriculum'],
        ['text' => 'Kalender Akademik', 'url' => 'admin/academic-calendars', 'icon' => 'fas fa-calendar-check', 'can' => 'view events'],
        ['text' => 'Layanan Akademik', 'url' => 'admin/academic-services', 'icon' => 'fas fa-laptop-house', 'can' => 'view events'],
        ['text' => 'Penelitian & Pengabdian', 'url' => 'admin/research-services', 'icon' => 'fas fa-microscope', 'can' => 'view events'],
        ['text' => 'Dokumen', 'url' => 'admin/documents', 'icon' => 'fas fa-folder-open', 'can' => 'view documents'],
        ['text' => 'FAQs', 'url' => 'admin/faqs', 'icon' => 'fas fa-question-circle', 'can' => 'view pages'],

        ['header' => 'SISTEM'],
        [
            'text' => 'Page Builder',
            'icon' => 'fas fa-tools',
            'submenu' => [
                [
                    'text' => 'Semua Halaman',
                    'url' => 'admin/builder-pages',
                    'icon' => 'fas fa-list',
                ],
                [
                    'text' => 'Tambah Halaman',
                    'url' => 'admin/pages/create?is_builder=1',
                    'icon' => 'fas fa-plus',
                ],
            ],
        ],
        ['text' => 'Media Library', 'url' => 'admin/media', 'icon' => 'fas fa-photo-video', 'can' => 'view media'],
        ['text' => 'Menu Builder', 'url' => 'admin/menus', 'icon' => 'fas fa-bars', 'can' => 'view menus'],
        ['text' => 'Pesan Kontak', 'url' => 'admin/contact-messages', 'icon' => 'fas fa-envelope', 'can' => 'view contact messages'],
        ['text' => 'Kelola User', 'url' => 'admin/users', 'icon' => 'fas fa-users', 'can' => 'view users'],
        ['text' => 'Backup', 'url' => 'admin/backups', 'icon' => 'fas fa-database', 'can' => 'view users'],
        [
            'text' => 'Pengaturan',
            'icon' => 'fas fa-cog',
            'can' => 'view settings',
            'submenu' => [
                ['text' => 'Umum', 'url' => 'admin/settings/general', 'icon' => 'fas fa-wrench'],
                ['text' => 'Kontak', 'url' => 'admin/settings/contact', 'icon' => 'fas fa-phone'],
                ['text' => 'Media Sosial', 'url' => 'admin/settings/social', 'icon' => 'fas fa-share-alt'],
                ['text' => 'SEO', 'url' => 'admin/settings/seo', 'icon' => 'fas fa-search'],
                ['text' => 'Tema', 'url' => 'admin/settings/appearance', 'icon' => 'fas fa-palette'],
                ['text' => 'Akademik', 'url' => 'admin/settings/academic', 'icon' => 'fas fa-graduation-cap'],
                ['text' => 'Info Grafis', 'url' => 'admin/infographics', 'icon' => 'fas fa-chart-pie'],
                ['text' => 'Sponsor', 'url' => 'admin/sponsors', 'icon' => 'fas fa-hand-holding-heart'],
            ],
        ],
        ['text' => 'Panduan Sistem', 'url' => 'admin/guide', 'icon' => 'fas fa-book-open', 'active' => ['admin/guide*']],
    ],

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => 'https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js'],
                ['type' => 'js', 'asset' => false, 'location' => 'https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js'],
                ['type' => 'css', 'asset' => false, 'location' => 'https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css'],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'],
                ['type' => 'css', 'asset' => false, 'location' => 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => 'https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js'],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11'],
                ['type' => 'css', 'asset' => false, 'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css'],
            ],
        ],
        'Toastr' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => false, 'location' => 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js'],
                ['type' => 'css', 'asset' => false, 'location' => 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css'],
            ],
        ],
        'CustomTheme' => [
            'active' => true,
            'files' => [
                ['type' => 'css', 'asset' => true, 'location' => 'css/elegant-admin.css'],
            ],
        ],
        'FlagIconCss' => [
            'active' => true,
            'files' => [
                ['type' => 'css', 'asset' => false, 'location' => 'https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css'],
            ],
        ],
    ],
];
