<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('admin.edit_page') }} | Page Builder</title>
    
    <!-- GrapesJS CSS/JS -->
    <link rel="stylesheet" href="https://unpkg.com/grapesjs/dist/css/grapes.min.css">
    <script src="https://unpkg.com/grapesjs"></script>
    <script src="https://unpkg.com/grapesjs-preset-webpage"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic"></script>
    <script src="https://unpkg.com/grapesjs-plugin-forms"></script>
    <script src="https://unpkg.com/grapesjs-custom-code"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body, html { height: 100%; margin: 0; overflow: hidden; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* GrapesJS Overall Theme (Elementor Style Light Mode) */
        .gjs-one-bg { background-color: #ffffff; }
        .gjs-two-bg { background-color: #f8f9fa; }
        .gjs-three-bg { background-color: #ffffff; }
        .gjs-four-bg { background-color: #e2e8f0; }
        /* Fix text and icon colors for light theme */
        .gjs-one-color { color: #333333 !important; fill: #333333 !important; }
        .gjs-two-color { color: #555555 !important; fill: #555555 !important; }
        .gjs-three-color { color: #777777 !important; fill: #777777 !important; }
        .gjs-four-color, .gjs-four-color-h:hover { color: #0073aa !important; fill: #0073aa !important; }
        
        /* Ensure SVGs and overall text are visible */
        .gjs-editor { color: #333333; }
        .gjs-block svg { fill: #555555; width: 35px; height: 35px; }
        
        /* Sidebar Layout - Fix Missing Wrapper */
        .gjs-editor-cont { overflow: hidden; background-color: #f1f3f5; }
        .gjs-pn-views-container {
            left: 0;
            right: auto !important;
            border-right: 1px solid #e0e0e0;
            width: 320px !important; 
            box-shadow: 2px 0 10px rgba(0,0,0,0.02);
            z-index: 10;
            height: 100%;
            background-color: #ffffff;
        }
        .gjs-pn-views {
            left: 0;
            right: auto !important;
            width: 320px !important;
            border-bottom: 1px solid #e0e0e0;
            background-color: #ffffff;
        }
        .gjs-cv-canvas {
            width: calc(100% - 320px) !important;
            left: 320px !important;
            background-color: #f1f3f5;
            height: 100%;
        }

        /* Top Panel Enhancements */
        .gjs-pn-commands {
            left: 320px !important;
            width: calc(100% - 320px) !important;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            background: #ffffff;
            border-bottom: 1px solid #e0e0e0;
            z-index: 5;
        }
        
        .gjs-pn-btn { font-size: 15px; padding: 12px; color: #555; }
        .gjs-pn-btn.gjs-pn-active { color: #0073aa; /* Accent color like WP/Elementor */ }

        .panel-devices {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
        }

        .panel-actions {
            display: flex;
            gap: 8px;
        }

        /* Custom Buttons Styling */
        .action-btn {
            padding: 8px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            font-size: 13px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            margin-left: 10px;
        }
        .btn-success { background: #39b54a; color: white; } /* Elementor green-ish */
        .btn-success:hover { background: #2f9d3a; transform: translateY(-1px); }
        .btn-secondary { background: #e0e0e0; color: #333; }
        .btn-secondary:hover { background: #cccccc; transform: translateY(-1px); }
        
        /* Adjusting GrapesJS UI elements (Style & Trait Panels) */
        .gjs-pn-panel { font-size: 13px; color: #333; }
        .gjs-sm-sector .gjs-sm-title { font-size: 14px; padding: 12px 15px; background-color: #f8f9fa; color: #333; border-bottom: 1px solid #e0e0e0; font-weight: 600; text-transform: capitalize; }
        .gjs-sm-property { font-size: 13px; margin-bottom: 15px; padding: 0 10px; }
        .gjs-sm-property__label { font-size: 12px; margin-bottom: 6px; font-weight: 500; color: #555; text-transform: capitalize; }
        .gjs-field { font-size: 13px; line-height: 1.6; padding: 4px; background: #ffffff; border: 1px solid #dcdcdc; border-radius: 3px; }
        .gjs-field input, .gjs-field select, .gjs-field textarea { font-size: 13px; color: #333; padding: 6px; }
        .gjs-field-color-picker { border-color: #dcdcdc; }

        /* Block Manager (Elementor Look - Exact) */
        .gjs-block {
            width: 46%;
            margin: 2%;
            padding: 25px 5px 20px;
            border-radius: 3px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #ffffff;
            border: 1px solid #e1e3e6;
            display: inline-flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100px;
            cursor: move;
            box-shadow: none;
            box-sizing: border-box;
        }
        .gjs-block:hover { 
            background: #ffffff; 
            border-color: #c9ccd0;
            box-shadow: 0 1px 5px rgba(0,0,0,0.06); 
            transform: translateY(-1px);
        }
        .gjs-block-label { font-size: 13px !important; margin-top: 12px !important; font-weight: 400 !important; color: #6d7882 !important; text-align: center; }
        .gjs-block svg { fill: #6d7882; width: 32px; height: 32px; }
        .gjs-block i { color: #6d7882; font-size: 28px; }
        
        .gjs-category-title {
            background-color: #ffffff;
            padding: 15px 15px 10px;
            font-weight: 600;
            text-transform: capitalize;
            font-size: 13px;
            color: #1f2124;
            border-bottom: 1px solid #e0e0e0;
            border-top: none;
            margin-top: 10px;
        }
        
        .gjs-blocks-c { padding: 10px; background-color: #ffffff; /* ensure white bg behind blocks */ }

        /* Asset Manager Responsive styling */
        .gjs-am-file-uploader { width: 100% !important; height: 150px !important; border: 2px dashed #b0c4de !important; background: #f8f9fa; }
        .gjs-am-assets-cont { display: flex; flex-wrap: wrap; gap: 10px; padding: 10px; background: #ffffff; }
        .gjs-am-asset { width: 23% !important; border-radius: 4px !important; overflow: hidden !important; border: 1px solid #e0e0e0 !important; }
        .gjs-am-asset-image { height: 100px !important; background-size: cover !important; }
        .gjs-mdl-container { width: 70% !important; max-width: 900px !important; background: #ffffff; color: #333; }
        .gjs-mdl-header { border-bottom: 1px solid #e0e0e0; color: #333; }
        .gjs-mdl-title { color: #333; }
    </style>
</head>
<body>
    <div id="gjs"></div>

    <script>
        const editor = grapesjs.init({
            container: '#gjs',
            height: '100%',
            fromElement: true,
            storageManager: false,
            plugins: ['gjs-preset-webpage', 'gjs-blocks-basic', 'grapesjs-plugin-forms', 'grapesjs-custom-code'],
            pluginsOpts: {
                'gjs-preset-webpage': {
                    modalImportTitle: 'Import HTML',
                    modalImportLabel: 'Paste your HTML/CSS here',
                },
                'gjs-blocks-basic': { flexGrid: true },
                'grapesjs-plugin-forms': {},
            },
            assetManager: {
                upload: '{{ route('admin.media.upload') }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                params: { _token: '{{ csrf_token() }}' },
                uploadName: 'file',
                onResponse(res) {
                    if (res.data) return res.data;
                    if (res.error) alert('Upload Gagal: ' + res.error);
                    return res;
                },
                assets: [
                    @foreach(\App\Models\Media::latest()->take(40)->get() as $media)
                    { src: '{{ asset('storage/'.$media->path) }}', name: '{{ $media->original_name }}' },
                    @endforeach
                ],
                autoAdd: 1,
                openAssetsOnDrop: 1,
                dropzone: 1,
                modalTitle: 'Pilih Gambar',
                inputPlaceholder: 'http://url-gambar.com/gambar.jpg',
                addBtnText: 'Tambah Gambar',
            },
            deviceManager: {
                devices: [
                    { name: 'Desktop', width: '' },
                    { name: 'Tablet', width: '768px', widthMedia: '768px' },
                    { name: 'Mobile', width: '320px', widthMedia: '480px' },
                ]
            }
        });

        // Setup Custom Panels
        const pn = editor.Panels;

        // Ensure Panels are visible
        editor.on('load', () => {
            // Force the Blocks panel to open by default
            const openBlocksBtn = pn.getButton('views', 'open-blocks');
            if (openBlocksBtn) {
                openBlocksBtn.set('active', 1);
            }
        });

        // Add Device Switcher
        pn.addPanel({
            id: 'devices',
            el: '.panel-devices',
            buttons: [
                { id: 'device-desktop', active: true, className: 'fa fa-desktop', command: 'set-device-desktop', attributes: { title: 'Desktop' } },
                { id: 'device-tablet', className: 'fa fa-tablet-alt', command: 'set-device-tablet', attributes: { title: 'Tablet' } },
                { id: 'device-mobile', className: 'fa fa-mobile-alt', command: 'set-device-mobile', attributes: { title: 'Mobile' } },
            ],
        });

        // Update Commands for Devices
        editor.Commands.add('set-device-desktop', { run: ed => ed.setDevice('Desktop') });
        editor.Commands.add('set-device-tablet', { run: ed => ed.setDevice('Tablet') });
        editor.Commands.add('set-device-mobile', { run: ed => ed.setDevice('Mobile') });

        // Add Save/Back Buttons to Top Panel
        pn.addButton('options', [
            {
                id: 'undo',
                className: 'fa fa-undo',
                command: 'core:undo',
                attributes: { title: 'Undo' }
            },
            {
                id: 'redo',
                className: 'fa fa-redo',
                command: 'core:redo',
                attributes: { title: 'Redo' }
            },
            {
                id: 'preview',
                className: 'fa fa-eye',
                command: 'preview',
                attributes: { title: 'Preview' }
            },
            {
                id: 'open-assets',
                className: 'fa fa-photo-video',
                command: 'open-assets',
                attributes: { title: 'Media Library' }
            },
            {
                id: 'clear-all',
                className: 'fa fa-trash',
                command: 'canvas-clear',
                attributes: { title: 'Clear All' }
            }
        ]);

        // Clear Canvas Command
        editor.Commands.add('canvas-clear', {
            run: ed => {
                if(confirm('Apakah Anda yakin ingin menghapus semua konten?')) {
                    ed.DomComponents.clear();
                    ed.CssComposer.clear();
                }
            }
        });

        // Add Custom Action Buttons (Save/Back)
        const topPanel = pn.getPanel('commands');
        topPanel.set('appendContent', `
            <div class="panel-actions">
                <button onclick="savePage()" class="action-btn btn-success">
                    <i class="fas fa-save"></i> {{ __('admin.save') }}
                </button>
                <a href="{{ route('admin.pages.builder-index') }}" class="action-btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> {{ __('admin.back') }}
                </a>
            </div>
        `);

        // Dynamic Blocks Implementation
        const bm = editor.BlockManager;
        const dynamicBlocks = [
            { id: 'curriculum', label: 'Kurikulum', icon: 'fas fa-book' },
            { id: 'lecturers', label: 'Dosen/SDM', icon: 'fas fa-user-graduate' },
            { id: 'research', label: 'Penelitian', icon: 'fas fa-flask' },
            { id: 'community', label: 'Pengabdian', icon: 'fas fa-hand-holding-heart' },
            { id: 'profile', label: 'Visi Misi', icon: 'fas fa-university' },
        ];

        dynamicBlocks.forEach(block => {
            bm.add(block.id, {
                label: `
                    <div style="text-align: center;">
                        <i class="${block.icon} fa-2x mb-2" style="color: #4fd1c5;"></i><br>
                        <span class="gjs-block-label" style="font-size: 13px; font-weight: 500;">${block.label}</span>
                    </div>
                `,
                category: 'Dynamic Content',
                content: `<div class="dynamic-block" data-type="${block.id}" style="padding: 40px 20px; border: 2px dashed #4fd1c5; text-align: center; background: #ebf8ff; color: #2c5282; border-radius: 12px; margin: 20px 0;">
                    <i class="${block.icon} fa-3x mb-3"></i><br>
                    <h3 style="margin:0;">[ DATA DINAMIS: ${block.label} ]</h3>
                    <p style="font-size: 14px; opacity: 0.8;">Konten ini akan otomatis terisi data terbaru dari sistem saat halaman diakses.</p>
                </div>`,
                attributes: { title: `Masukkan ${block.label}` }
            });
        });

        // Add Standard Image Block for better UX
        bm.add('image-plus', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-image fa-2x mb-2" style="color: #ed64a6;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 500;">Gambar Baru</span>
                </div>
            `,
            category: 'Basic Content',
            content: {
                type: 'image',
                style: { width: '100%', height: 'auto', 'min-height': '200px', background: '#edf2f7' },
                activeOnRender: 1, // Opens asset manager immediately
            }
        });

        bm.add('text-plus', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-font fa-2x mb-2" style="color: #667eea;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 500;">Teks Kustom</span>
                </div>
            `,
            category: 'Basic Content',
            content: {
                tagName: 'div',
                type: 'text',
                content: '<p style="padding: 10px;">Klik dua kali di sini untuk mengedit teks...</p>',
                style: { width: '100%', height: 'auto' }
            }
        });

        // Custom HTML/CSS/JS Block using the grapesjs-custom-code plugin
        bm.add('custom-code-plus', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-file-code fa-2x mb-2" style="color: #4299e1;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 500;">HTML/CSS/JS</span>
                </div>
            `,
            category: 'Basic Content',
            content: {
                type: 'custom-code',
            }
        });

        // Code Snippet Block (For displaying code)
        bm.add('code-snippet-plus', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-terminal fa-2x mb-2" style="color: #718096;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 500;">Code Block</span>
                </div>
            `,
            category: 'Basic Content',
            content: {
                type: 'text',
                content: '<pre style="background: #2d3748; color: #e2e8f0; padding: 15px; border-radius: 8px; overflow-x: auto;"><code>// Tulis kode Anda di sini\nconsole.log("Hello World");</code></pre>',
            }
        });

        // --- NEW GENERAL ELEMENTOR BLOCKS ---

        // Image Box Block
        bm.add('image-box', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-id-card fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Image Box</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="image-box" style="text-align: center; padding: 20px;">
                    <img src="https://via.placeholder.com/150" style="max-width: 100%; border-radius: 5px; margin-bottom: 15px;" alt="Image Box"/>
                    <h3 style="margin-bottom: 10px; color: #333; font-size: 18px;">Ini adalah Judul</h3>
                    <p style="color: #777; font-size: 14px; line-height: 1.6;">Deskripsi singkat mengenai gambar atau konten di atas. Klik dua kali untuk mengubah teks ini sesuai kebutuhan Anda.</p>
                </div>
            `
        });

        // Icon Box Block
        bm.add('icon-box', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-star fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Icon Box</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="icon-box" style="text-align: center; padding: 20px;">
                    <div style="margin-bottom: 15px; color: #0073aa;">
                        <i class="fas fa-star fa-4x"></i>
                    </div>
                    <h3 style="margin-bottom: 10px; color: #333; font-size: 18px;">Ini adalah Judul Ikon</h3>
                    <p style="color: #777; font-size: 14px; line-height: 1.6;">Deskripsi singkat mengenai ikon di atas. Klik di sini untuk mengganti teks.</p>
                </div>
            `
        });

        // Alert Block
        bm.add('alert-box', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-exclamation-circle fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Alert</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="alert-box" style="background-color: #d1ecf1; border-left: 4px solid #17a2b8; padding: 15px 20px; border-radius: 4px; display: flex; align-items: center; margin: 15px 0;">
                    <i class="fas fa-info-circle fa-lg" style="color: #0c5460; margin-right: 15px;"></i>
                    <div style="color: #0c5460; font-size: 15px; font-family: sans-serif;">
                        <strong>Perhatian!</strong> Ini adalah pesan peringatan atau informasi penting pengingat untuk pengguna.
                    </div>
                </div>
            `
        });

        // Tabs Block
        bm.add('tabs-block', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-folder fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Tabs</span>
                </div>
            `,
            category: 'General',
            content: `
                <div data-gjs-type="custom-tabs" class="tabs-wrapper" style="font-family: sans-serif; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                    <div class="tab-links" style="display: flex; background: #f8f9fa; border-bottom: 1px solid #ddd;">
                        <button class="tab-link active" onclick="openTab(event, 'tab1', this)" style="background: white; border: none; outline: none; padding: 14px 24px; cursor: pointer; font-weight: bold; color: #333; border-bottom: 2px solid #0073aa; flex: 1; text-align: left;">Tab #1</button>
                        <button class="tab-link" onclick="openTab(event, 'tab2', this)" style="background: transparent; border: none; outline: none; padding: 14px 24px; cursor: pointer; font-weight: normal; color: #666; border-bottom: 2px solid transparent; flex: 1; text-align: left;">Tab #2</button>
                    </div>
                    <div class="tab-content" id="tab1" style="display: block; padding: 20px; background: white; color: #444;">
                        <h3>Konten Tab 1</h3>
                        <p>Ini adalah area teks untuk Tab 1. Anda bisa menggantinya dengan mengklik dua kali pada teks ini.</p>
                    </div>
                    <div class="tab-content" id="tab2" style="display: none; padding: 20px; background: white; color: #444;">
                        <h3>Konten Tab 2</h3>
                        <p>Ini adalah area teks untuk Tab 2. Anda bisa mengubah isinya sesuai kebutuhan.</p>
                    </div>
                    <script>
                        function openTab(evt, tabId, btn) {
                            const wrapper = btn.closest('.tabs-wrapper');
                            const tabContents = wrapper.querySelectorAll('.tab-content');
                            tabContents.forEach(tab => tab.style.display = 'none');
                            
                            const tabLinks = wrapper.querySelectorAll('.tab-link');
                            tabLinks.forEach(link => {
                                link.className = link.className.replace(' active', '');
                                link.style.background = 'transparent';
                                link.style.fontWeight = 'normal';
                                link.style.borderBottom = '2px solid transparent';
                                link.style.color = '#666';
                            });
                            
                            wrapper.querySelector('#' + tabId).style.display = 'block';
                            btn.className += ' active';
                            btn.style.background = 'white';
                            btn.style.fontWeight = 'bold';
                            btn.style.borderBottom = '2px solid #0073aa';
                            btn.style.color = '#333';
                        }
                    <\/script>
                </div>
            `
        });

        // Accordion Block
        bm.add('accordion-block', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-bars fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Accordion</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="accordion-wrapper" style="font-family: sans-serif; margin-bottom: 20px; border-top: 1px solid #ddd; border-left: 1px solid #ddd; border-right: 1px solid #ddd; border-radius: 4px; overflow: hidden;">
                    
                    <div class="accordion-item" style="border-bottom: 1px solid #ddd;">
                        <button class="accordion-btn" onclick="toggleAccordion(this)" style="width: 100%; text-align: left; padding: 15px 20px; font-size: 15px; font-weight: 600; background: #fff; color: #333; border: none; cursor: pointer; display: flex; justify-content: space-between; align-items: center; outline: none;">
                            <span>Pertanyaan atau Judul Akordion 1</span>
                            <span class="acc-icon" style="font-size: 18px; color: #0073aa;">+</span>
                        </button>
                        <div class="accordion-panel" style="background: white; overflow: hidden; display: none; padding: 0 20px 20px 20px; color: #555; line-height: 1.6;">
                            <p>Ini adalah jawaban atau isi konten dari akordion pertama. Teks ini akan tersembunyi secara default dan muncul ketika judul akordion diklik.</p>
                        </div>
                    </div>

                    <div class="accordion-item" style="border-bottom: 1px solid #ddd;">
                        <button class="accordion-btn" onclick="toggleAccordion(this)" style="width: 100%; text-align: left; padding: 15px 20px; font-size: 15px; font-weight: 600; background: #fff; color: #333; border: none; cursor: pointer; display: flex; justify-content: space-between; align-items: center; outline: none;">
                            <span>Pertanyaan atau Judul Akordion 2</span>
                            <span class="acc-icon" style="font-size: 18px; color: #0073aa;">+</span>
                        </button>
                        <div class="accordion-panel" style="background: white; overflow: hidden; display: none; padding: 0 20px 20px 20px; color: #555; line-height: 1.6;">
                            <p>Konten untuk akordion kedua. Anda bisa mengedit teks ini sesuka Anda setelah meletakkan elemen ini di halaman.</p>
                        </div>
                    </div>

                    <script>
                        function toggleAccordion(btn) {
                            const panel = btn.nextElementSibling;
                            const icon = btn.querySelector('.acc-icon');
                            if (panel.style.display === "block") {
                                panel.style.display = "none";
                                icon.innerHTML = "+";
                                icon.style.color = "#0073aa";
                            } else {
                                panel.style.display = "block";
                                icon.innerHTML = "-";
                                icon.style.color = "#333";
                            }
                        }
                    <\/script>
                </div>
            `
        });

        // Image Carousel
        bm.add('image-carousel', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-images fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Image Carousel</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="image-carousel" style="display: flex; gap: 10px; overflow-x: auto; padding: 15px; background: #f8f9fa; border: 1px solid #ddd; border-radius: 4px;">
                    <img src="https://via.placeholder.com/200x150?text=Slide+1" style="height: 150px; border-radius: 4px; flex-shrink: 0;" alt="Slide 1"/>
                    <img src="https://via.placeholder.com/200x150?text=Slide+2" style="height: 150px; border-radius: 4px; flex-shrink: 0;" alt="Slide 2"/>
                    <img src="https://via.placeholder.com/200x150?text=Slide+3" style="height: 150px; border-radius: 4px; flex-shrink: 0;" alt="Slide 3"/>
                </div>
            `
        });

        // Basic Gallery
        bm.add('basic-gallery', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-th fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Basic Gallery</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="basic-gallery" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; padding: 15px;">
                    <img src="https://via.placeholder.com/300x200?text=Gallery+1" style="width: 100%; height: auto; border-radius: 4px;" alt="Gallery 1"/>
                    <img src="https://via.placeholder.com/300x200?text=Gallery+2" style="width: 100%; height: auto; border-radius: 4px;" alt="Gallery 2"/>
                    <img src="https://via.placeholder.com/300x200?text=Gallery+3" style="width: 100%; height: auto; border-radius: 4px;" alt="Gallery 3"/>
                    <img src="https://via.placeholder.com/300x200?text=Gallery+4" style="width: 100%; height: auto; border-radius: 4px;" alt="Gallery 4"/>
                    <img src="https://via.placeholder.com/300x200?text=Gallery+5" style="width: 100%; height: auto; border-radius: 4px;" alt="Gallery 5"/>
                    <img src="https://via.placeholder.com/300x200?text=Gallery+6" style="width: 100%; height: auto; border-radius: 4px;" alt="Gallery 6"/>
                </div>
            `
        });

        // Icon List
        bm.add('icon-list', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-list-ul fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Icon List</span>
                </div>
            `,
            category: 'General',
            content: `
                <ul class="icon-list" style="list-style: none; padding: 0; font-family: sans-serif; color: #555; line-height: 2;">
                    <li><i class="fas fa-check-circle" style="color: #39b54a; margin-right: 10px;"></i> Item Daftar Ikon 1</li>
                    <li><i class="fas fa-check-circle" style="color: #39b54a; margin-right: 10px;"></i> Item Daftar Ikon 2</li>
                    <li><i class="fas fa-check-circle" style="color: #39b54a; margin-right: 10px;"></i> Item Daftar Ikon 3</li>
                </ul>
            `
        });

        // Counter
        bm.add('counter-block', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-sort-numeric-up fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Counter</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="counter-block" style="text-align: center; font-family: sans-serif; padding: 20px;">
                    <div style="font-size: 48px; font-weight: bold; color: #0073aa; margin-bottom: 5px;">100</div>
                    <div style="font-size: 16px; color: #555; text-transform: uppercase;">Judul Counter</div>
                </div>
            `
        });

        // Progress Bar
        bm.add('progress-bar', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-tasks fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Progress Bar</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="progress-wrapper" style="font-family: sans-serif; margin-bottom: 20px;">
                    <span style="display: block; margin-bottom: 5px; font-weight: 600; color: #333; font-size: 14px;">Keterampilan (Skill) <span style="float: right;">80%</span></span>
                    <div style="background-color: #e9ecef; border-radius: 4px; overflow: hidden; height: 15px;">
                        <div style="width: 80%; background-color: #0073aa; height: 100%; transition: width 0.6s ease;"></div>
                    </div>
                </div>
            `
        });

        // Testimonial
        bm.add('testimonial-block', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-comment-alt fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Testimonial</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="testimonial-block" style="text-align: center; padding: 30px; background: #fdfdfd; border: 1px solid #eee; border-radius: 8px;">
                    <p style="font-size: 16px; font-style: italic; color: #555; line-height: 1.6; margin-bottom: 20px;">"Pelayanan sangat memuaskan, desain yang diberikan juga sangat elegan dan sesuai dengan yang diharapkan. Kualitas luar biasa!"</p>
                    <img src="https://via.placeholder.com/80" style="border-radius: 50%; width: 60px; height: 60px; object-fit: cover; margin-bottom: 15px;" alt="User"/>
                    <h4 style="margin: 0 0 5px; color: #333; font-size: 16px;">John Doe</h4>
                    <span style="color: #777; font-size: 13px;">CEO, Perusahaan XYZ</span>
                </div>
            `
        });

        // Social Icons
        bm.add('social-icons', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-share-alt fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Social Icons</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="social-icons" style="text-align: center; padding: 15px;">
                    <a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #3b5998; color: white; border-radius: 50%; margin: 0 5px; text-decoration: none;"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #1da1f2; color: white; border-radius: 50%; margin: 0 5px; text-decoration: none;"><i class="fab fa-twitter"></i></a>
                    <a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #e1306c; color: white; border-radius: 50%; margin: 0 5px; text-decoration: none;"><i class="fab fa-instagram"></i></a>
                    <a href="#" style="display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; background: #0077b5; color: white; border-radius: 50%; margin: 0 5px; text-decoration: none;"><i class="fab fa-linkedin-in"></i></a>
                </div>
            `
        });

        // SoundCloud
        bm.add('soundcloud', {
            label: `
                <div style="text-align: center;">
                    <i class="fab fa-soundcloud fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">SoundCloud</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="soundcloud-wrapper" style="padding: 20px; text-align: center; background: #f1f3f5; border: 2px dashed #ccc; border-radius: 5px; color: #666;">
                    <i class="fab fa-soundcloud fa-3x mb-2" style="color: #ff5500;"></i>
                    <p style="margin:0;">SoundCloud Embed Placeholder</p>
                </div>
            `
        });

        // Shortcode
        bm.add('shortcode', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-code fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Shortcode</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="shortcode-wrapper" style="padding: 20px; text-align: center; background: #f8f9fa; border: 1px dotted #888; border-radius: 3px; color: #444; font-family: monospace;">
                    [masukkan_shortcode_disini]
                </div>
            `
        });

        // HTML
        bm.add('html-block', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-file-code fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">HTML</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="html-wrapper">
                    <!-- Masukkan kode HTML khusus di sini -->
                    <p>Contoh HTML Blok. Edit kode untuk mengubahnya.</p>
                </div>
            `
        });

        // Menu Anchor
        bm.add('menu-anchor', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-anchor fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Menu Anchor</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="menu-anchor-wrapper" id="anchor-point" style="padding: 10px; background: #eef2f5; border: 1px dashed #aaa; color: #555; text-align: center; font-size: 12px;">
                    <i class="fas fa-anchor"></i> Menu Anchor (Tak Terlihat di Frontend)
                </div>
            `
        });

        // Sidebar
        bm.add('sidebar', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-columns fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Sidebar</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="sidebar-wrapper" style="padding: 20px; background: #f4f6f8; border-left: 3px solid #0073aa; min-height: 200px;">
                    <h4 style="margin-top: 0; color: #333;">Sidebar Placeholder</h4>
                    <ul style="padding-left: 20px; color: #555;">
                        <li>Kategori 1</li>
                        <li>Kategori 2</li>
                        <li>Artikel Terbaru</li>
                    </ul>
                </div>
            `
        });

        // Read More
        bm.add('read-more', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-grip-lines fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Read More</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="read-more-wrapper" style="text-align: center; margin: 20px 0;">
                    <hr style="border: 0; border-top: 1px dashed #ccc; margin: 10px 0;">
                    <span style="background: #fff; padding: 0 10px; color: #888; font-size: 12px; position: relative; top: -20px; font-family: sans-serif;">Batas Read More</span>
                </div>
            `
        });

        // Rating
        bm.add('rating', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-star-half-alt fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Rating</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="rating-wrapper" style="color: #f39c12; font-size: 20px; text-align: center; padding: 15px;">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="far fa-star"></i>
                    <span style="color: #555; font-size: 14px; margin-left: 10px; font-family: sans-serif;">(4/5)</span>
                </div>
            `
        });

        // Feedzy RSS Feeds
        bm.add('feedzy-rss', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-rss fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Feedzy RSS Feeds</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="feedzy-rss-wrapper" style="padding: 20px; text-align: center; background: #fff5eb; border: 2px dashed #f39c12; border-radius: 5px; color: #d35400;">
                    <i class="fas fa-rss fa-3x mb-2"></i>
                    <p style="margin:0;">Feedzy RSS Feeds Placeholder</p>
                </div>
            `
        });

        // Text Path
        bm.add('text-path', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-route fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Text Path</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="text-path-wrapper" style="padding: 30px; text-align: center;">
                    <svg viewBox="0 0 400 100" width="100%" height="auto">
                        <path id="curve" d="M 50,50 Q 200,0 350,50" fill="transparent" stroke="#ddd" />
                        <text font-family="sans-serif" font-size="20">
                            <textPath href="#curve" startOffset="50%" text-anchor="middle" fill="#0073aa">
                                Teks Mengikuti Jalur (Text Path)
                            </textPath>
                        </text>
                    </svg>
                </div>
            `
        });

        // Smart Slider
        bm.add('smart-slider', {
            label: `
                <div style="text-align: center;">
                    <i class="fas fa-images fa-2x mb-2" style="color: #6d7882;"></i><br>
                    <span class="gjs-block-label" style="font-size: 13px; font-weight: 400; color: #6d7882;">Smart Slider</span>
                </div>
            `,
            category: 'General',
            content: `
                <div class="smart-slider-wrapper" style="padding: 40px; text-align: center; background: #e3f2fd; border: 2px dashed #1976d2; border-radius: 5px; color: #1565c0;">
                    <i class="fas fa-layer-group fa-3x mb-2"></i>
                    <p style="margin:0;">[Smart Slider 3 Placeholder]</p>
                </div>
            `
        });

        // Load Content
        @php $data = json_decode($page->builder_data, true); @endphp
        @if($data)
            editor.setComponents({!! $data['components'] !!});
            editor.setStyle({!! $data['styles'] !!});
        @else
            editor.setComponents(`{!! $page->content !!}`);
        @endif

        // Save Function
        window.savePage = function() {
            const html = editor.getHtml();
            const css = editor.getCss();
            const components = JSON.stringify(editor.getComponents());
            const styles = JSON.stringify(editor.getStyle());

            axios.post('{{ route('admin.pages.save-builder', $page) }}', {
                html: html,
                css: css,
                components: components,
                styles: styles
            }, {
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            }).then(response => {
                alert('Halaman berhasil disimpan!');
            }).catch(error => {
                console.error(error);
                alert('Gagal menyimpan halaman.');
            });
        };
    </script>
</body>
</html>
