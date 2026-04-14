@extends('adminlte::page')

@section('title', 'Edit Media Galeri')

@section('content_header')
    <h1><i class="fas fa-edit me-2"></i>Edit Media Galeri</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.gallery.update', $gallery) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Judul Media <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $gallery->title) }}" required>
                            @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>Tipe Media <span class="text-danger">*</span></label>
                            <select name="type" id="media_type" class="form-control @error('type') is-invalid @enderror" required>
                                <option value="photo" {{ old('type', $gallery->type) == 'photo' ? 'selected' : '' }}>Foto / Gambar</option>
                                <option value="video" {{ old('type', $gallery->type) == 'video' ? 'selected' : '' }}>Video (YouTube)</option>
                            </select>
                            @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            <small class="text-warning"><i class="fas fa-exclamation-triangle"></i> Mengubah tipe akan menghapus file/URL sebelumnya.</small>
                        </div>

                        <!-- Photo Input -->
                        <div class="form-group" id="photo_input_group" style="display: {{ old('type', $gallery->type) == 'photo' ? 'block' : 'none' }};">
                            @if($gallery->type == 'photo' && $gallery->file_path)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="" class="img-thumbnail" style="max-height: 150px">
                                </div>
                            @endif
                            <label>Upload File Foto Baru (Opsional)</label>
                            <div class="custom-file">
                                <input type="file" name="file_path" class="custom-file-input @error('file_path') is-invalid @enderror" id="customFile" accept="image/*">
                                <label class="custom-file-label" for="customFile">Pilih foto</label>
                            </div>
                            @error('file_path')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, PNG. Maks: 10MB.</small>
                        </div>

                        <!-- Video Input -->
                        <div class="form-group" id="video_input_group" style="display: {{ old('type', $gallery->type) == 'video' ? 'block' : 'none' }};">
                            @if($gallery->type == 'video' && $gallery->youtube_id)
                                <div class="mb-2">
                                    <iframe width="280" height="157" src="https://www.youtube.com/embed/{{ $gallery->youtube_id }}" frameborder="0" allowfullscreen></iframe>
                                </div>
                            @endif
                            <label>URL Video YouTube <span class="text-danger">*</span></label>
                            <input type="url" name="youtube_url" id="youtube_url_input" class="form-control @error('youtube_url') is-invalid @enderror" value="{{ old('youtube_url', $gallery->youtube_url) }}" placeholder="Contoh: https://www.youtube.com/watch?v=...">
                            @error('youtube_url')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>Keterangan Tambahan / Caption (Opsional)</label>
                            <textarea name="caption" class="form-control @error('caption') is-invalid @enderror content-editor" rows="5">{{ old('caption', $gallery->caption) }}</textarea>
                            @error('caption')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Grup Album (Opsional)</label>
                                <input type="text" name="album" class="form-control @error('album') is-invalid @enderror" value="{{ old('album', $gallery->album) }}" placeholder="Contoh: Wisuda 2024">
                                @error('album')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tautkan ke Agenda/Event (Opsional)</label>
                                <select name="event_id" class="form-control @error('event_id') is-invalid @enderror">
                                    <option value="">-- Tidak ditautkan --</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}" {{ old('event_id', $gallery->event_id) == $event->id ? 'selected' : '' }}>{{ $event->title }}</option>
                                    @endforeach
                                </select>
                                @error('event_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Urutan Penampilan (Opsional)</label>
                                <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', $gallery->order) }}" min="0">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Status</label>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Tampilkan di Publik</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.gallery.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('admin.partials.media-modal')
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
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
            autosave_ask_before_unload: true,
            height: 450,
            image_title: true,
            automatic_uploads: true,
            promotion: false,
            branding: false,
            images_upload_url: '{{ route("admin.media.upload") }}',
            relative_urls: false,
            remove_script_host: false,
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
                    if (xhr.status === 403) { reject({ message: 'HTTP Error: ' + xhr.status, remove: true }); return; }
                    if (xhr.status < 200 || xhr.status >= 300) { reject('HTTP Error: ' + xhr.status); return; }
                    const json = JSON.parse(xhr.responseText);
                    if (!json || (typeof json.location != 'string' && (!json.data || !json.data[0]))) {
                        reject('Invalid JSON: ' + xhr.responseText); return;
                    }
                    resolve(json.location || json.data[0]);
                };
                xhr.onerror = () => { reject('Image upload failed. Code: ' + xhr.status); };
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            }),
            content_style: 'body { font-family:Inter,Helvetica,Arial,sans-serif; font-size:14px; line-height: 1.6; color: #333; }',
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_class: 'mceNonEditable',
            contextmenu: 'link image table',
        });

        // CSS to hide the "Upgrade" button and clean up UI
        const style = document.createElement('style');
        style.innerHTML = `
            .tox-promotion, .tox-statusbar__branding { display: none !important; }
            .tox-tinymce { border-radius: 8px !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1) !important; }
            .tox .tox-toolbar, .tox .tox-toolbar__overflow, .tox .tox-toolbar__primary { background-color: #f8fafc !important; }
        `;
        document.head.appendChild(style);
        
        function toggleMediaInputs() {
            var type = $('#media_type').val();
            if (type === 'photo') {
                $('#photo_input_group').show();
                $('#video_input_group').hide();
                $('#youtube_url_input').removeAttr('required');
            } else {
                $('#photo_input_group').hide();
                $('#video_input_group').show();
                $('#youtube_url_input').attr('required', 'required');
            }
        }
        
        $('#media_type').change(toggleMediaInputs);
        toggleMediaInputs();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
@stop
