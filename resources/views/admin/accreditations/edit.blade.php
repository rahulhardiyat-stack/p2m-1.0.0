@extends('adminlte::page')

@section('title', __('admin.edit_accreditation'))

@section('content_header')
    <div class="d-flex align-items-center justify-content-between mb-4 mt-2 px-md-4">
        <h1 class="m-0 text-dark font-weight-bold">{{ __('admin.edit_research_service') }}</h1>
        <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.accreditations.index') }}">{{ __('admin.accreditation') }}</a></li>
            <li class="breadcrumb-item active">{{ __('admin.edit') }}</li>
        </ol>
    </div>
@stop

@section('content')
    <div class="container-fluid pb-5">
        <div class="row justify-content-center px-md-4">
            <div class="col-12">
                <form action="{{ route('admin.accreditations.update', $accreditation->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-9 mb-4">
                            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                                <div class="card-header bg-white py-4 px-4 border-bottom">
                                    <h3 class="card-title font-weight-bold text-dark m-0"><i class="fas fa-edit mr-2 text-primary"></i>Informasi Utama</h3>
                                </div>
                                <div class="card-body p-4">
                                    <div class="form-group mb-4">
                                        <label class="form-label font-weight-bold small text-muted text-uppercase mb-2">{{ __('admin.accreditation_study_program') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror shadow-none bg-light border-0 px-3 py-4"
                                               placeholder="Masukkan Nama Prodi/Institusi.." value="{{ old('title', $accreditation->title) }}" required style="border-radius: 12px; font-size: 1.1rem;">
                                        @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-8">
                                            <label class="form-label font-weight-bold small text-muted text-uppercase mb-2"> Nomor SK <span class="text-danger">*</span></label>
                                            <input type="text" name="sk_number" class="form-control form-control-lg @error('no_number') is-invalid @enderror shadow-none bg-light border-0 px-3 py-4"
                                                   placeholder="Masukkan No SK..." value="{{ old('sk_number', $accreditation->sk_number) }}" required style="border-radius: 12px; font-size: 1.1rem;">
                                            @error('no_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small font-weight-bold text-muted mb-2 text-uppercase">jenjang</label>
                                            <select name="degree" class="form-control shadow-none bg-light border-0" style="border-radius: 8px;">
                                                <option value="S1" {{ old('type', $accreditation->degree) == 'S1' ? 'selected' : '' }}>Sarjana</option>
                                                <option value="S2" {{ old('type', $accreditation->degree) == 'S2' ? 'selected' : '' }}>Magister</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-8">
                                            <div class="form-group mb-0">
                                                <label class="form-label font-weight-bold small text-muted text-uppercase mb-2">Tipe</label>
                                                <select name="type" class="form-control shadow-none bg-light border-0 px-3 py-3" style="border-radius: 8px;">
                                                    <option value="program studi" {{ old('type', $accreditation->type) == 'program studi' ? 'selected' : '' }}>program studi</option>
                                                    <option value="institution" {{ old('type', $accreditation->type) == 'institution' ? 'selected' : '' }}>Institusi</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-0">
                                                <label class="small font-weight-bold text-muted mb-2 text-uppercase">Predikat</label>
                                                <select name="status" class="form-control shadow-none bg-light border-0" style="border-radius: 8px;">
                                                    <option value="Baik" {{ old('type', $accreditation->status) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                    <option value="Baik Sekali" {{ old('type', $accreditation->status) == 'Baik Sekali' ? 'selected' : '' }}>Baik Sekali</option>
                                                    <option value="Unggul" {{ old('type', $accreditation->status) == 'Unggul' ? 'selected' : '' }}>Unggul</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <!-- Input Tanggal SK -->
                                        <div class="col-md-6 mb-3">
                                            <label for="issued_date" class="form-label font-weight-bold small text-muted text-uppercase mb-2">Tanggal SK</label>
                                            <input type="datetime-local"
                                                   name="issued_date"
                                                   id="issued_date"
                                                   class="form-control expiredPicker @error('issued_date') is-invalid @enderror"
                                                   placeholder="Pilih Tanggal SK"
                                                   value="{{ old('issued_date', $accreditation->issued_date ?? '') }}">
                                            @error('issued_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Input Tanggal Berakhir -->
                                        <div class="col-md-6 mb-3">
                                            <label for="expired_date" class="form-label font-weight-bold small text-muted text-uppercase mb-2">Tanggal Berakhir SK</label>
                                            <input type="datetime-local"
                                                   name="expired_date"
                                                   id="expired_date"
                                                   class="form-control @error('expired_date') is-invalid @enderror"
                                                   placeholder="Pilih Tanggal Berakhir"
                                                   value="{{ old('expired_date', $accreditation->expired_date ?? '') }}">
                                            @error('expired_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden;">
                                <div class="card-header bg-white py-3 px-3 border-bottom text-center">
                                    <h3 class="card-title font-weight-bold text-dark m-0" style="font-size: 0.95rem;">Pengaturan Data</h3>
                                </div>
                                <div class="card-body p-3">
                                    <div class="form-group mb-4">
                                        <label class="small font-weight-bold text-muted mb-2 text-uppercase">Status Tampil</label>
                                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                                   value="1" {{ old('is_active', 'is_active') ? 'checked' : '' }}>
                                            <label class="custom-control-label font-weight-normal"
                                                   for="is_active">{{ __('admin.active') }} di Website</label>
                                        </div>
                                    </div>

                                    <hr class="border-light">

                                    <div class="form-group mb-4">
                                        <label class="small font-weight-bold text-muted mb-2 text-uppercase">{{ __('admin.accreditation_file') }} (PDF)</label>
                                        @if($accreditation->document_path)
                                            <div class="alert alert-light border small text-muted font-italic mb-2 p-2">
                                                <i class="fas fa-file-pdf mr-1 text-danger"></i> {{ basename($accreditation->document_path) }}
                                            </div>
                                        @endif
                                        <div class="custom-file shadow-none">
                                            <input type="file" name="document_path" class="custom-file-input" id="document_path" accept=".pdf">
                                            <label class="custom-file-label border-0 bg-light" for="file_path">Ubah File...</label>
                                        </div>
                                        <small class="text-muted italic">Maksimal 30MB</small>
                                    </div>
                                </div>
                                <div class="card-footer bg-light p-3 border-0">
                                    <button type="submit" class="btn btn-primary btn-block shadow font-weight-bold rounded-pill mb-2 py-2">
                                        <i class="fas fa-save mr-2"></i> {{ __('admin.save') }} Perubahan
                                    </button>
                                    <a href="{{ route('admin.accreditations.index') }}" class="btn btn-link btn-block btn-sm text-muted font-weight-bold py-0">{{ __('admin.cancel') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @include('admin.partials.media-modal')
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script>
        $(document).ready(function() {
            bsCustomFileInput.init();

            // TinyMCE Editor - Premium Modern Config
            tinymce.init({
                selector: '.content-editor',
                plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                menubar: 'file edit view insert format tools table help',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | numlist bullist | outdent indent | link image media table | charmap emoticons | code fullscreen preview',
                toolbar_sticky: true,
                toolbar_mode: 'sliding',
                height: 500,
                image_title: true,
                automatic_uploads: true,
                promotion: false,
                branding: false,
                images_upload_url: '{{ route("admin.media.upload") }}',
                relative_urls: false,
                remove_script_host: false,
                verify_html: false,
                extended_valid_elements: 'style[*],script[*],iframe[*],div[*],span[*],p[*],a[*],section[*],article[*],footer[*],header[*],nav[*]',
                valid_children: '+body[style|script],+div[style|script],+span[style|script],+p[style|script],+section[style|script],+article[style|script],+header[style|script],+footer[style|script],+aside[style|script],+nav[style|script]',
                file_picker_types: 'image',
                file_picker_callback: function (cb, value, meta) {
                    if (meta.filetype === 'image') {
                        currentInput = null;
                        currentPreview = null;
                        $('#mediaPickerModal').modal('show');
                        if (typeof window.loadMedia === 'function') window.loadMedia();
                        window.tinyMceCallback = function(item) {
                            cb(item.url, { title: item.filename, alt: item.filename });
                        };
                    }
                },
                images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.withCredentials = false;
                    xhr.open('POST', '{{ route("admin.media.upload") }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
                    xhr.upload.onprogress = (e) => { progress(e.loaded / e.total * 100); };
                    xhr.onload = () => {
                        const json = JSON.parse(xhr.responseText);
                        resolve(json.location || json.data[0]);
                    };
                    const formData = new FormData();
                    formData.append('file', blobInfo.blob(), blobInfo.filename());
                    xhr.send(formData);
                }),
                content_style: 'body { font-family:Inter,Helvetica,Arial,sans-serif; font-size:14px; line-height: 1.6; color: #333; }',
                quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            });

            const style = document.createElement('style');
            style.innerHTML = `
            .tox-promotion, .tox-statusbar__branding { display: none !important; }
            .tox-tinymce { border-radius: 12px !important; border: 1px solid #f1f1f1 !important; }
        `;
            document.head.appendChild(style);
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // 1. Inisialisasi Tanggal Berakhir terlebih dahulu
            let expiredPicker = flatpickr(".datetime", {
                locale: "id",
                dateFormat: "Y-m-d",// Format data yang dikirim ke Laravel/Database
                enableTime: true,
                altInput: true,      // Mengubah tampilan input agar lebih human-readable
                altFormat: "j F Y",  // Tampilan ke pengguna (Contoh: 10 Juni 2026)
                allowInput: true
            });

            // 2. Inisialisasi Tanggal SK
            let issuedPicker = flatpickr(".datetime", {
                locale: "id",
                dateFormat: "Y-m-d",
                enableTime: true,
                altInput: true,
                altFormat: "j F Y",
                allowInput: true,
                onChange: function(selectedDates, dateStr, instance) {
                    // Batasi minDate Tanggal Berakhir sesuai Tanggal SK yang dipilih
                    expiredPicker.set("minDate", dateStr);
                }
            });
        });
    </script>
@stop
