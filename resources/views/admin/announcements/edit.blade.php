@extends('adminlte::page')

@section('title', 'Edit Pengumuman')

@section('content_header')
    <h1><i class="fas fa-edit me-2"></i>Edit Pengumuman</h1>
@stop

@section('plugins.Summernote', true)

@section('content')
<div class="container-fluid">
    <div class="card">
        <form action="{{ route('admin.announcements.update', $announcement) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Judul Pengumuman <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $announcement->title) }}" required>
                            @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>Isi/Konten <span class="text-danger">*</span></label>
                            <textarea name="content" class="form-control summernote @error('content') is-invalid @enderror" required>{{ old('content', $announcement->content) }}</textarea>
                            @error('content')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card card-outline card-info shadow-sm">
                            <div class="card-header"><h3 class="card-title">Pengaturan</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Prioritas <span class="text-danger">*</span></label>
                                    <select name="priority" class="form-control @error('priority') is-invalid @enderror" required>
                                        <option value="low" {{ old('priority', $announcement->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                                        <option value="normal" {{ old('priority', $announcement->priority) == 'normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="high" {{ old('priority', $announcement->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                                        <option value="urgent" {{ old('priority', $announcement->priority) == 'urgent' ? 'selected' : '' }}>Penting/Mendesak</option>
                                    </select>
                                    @error('priority')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>



                                <div class="form-group">
                                    <label>Batas Waktu (Opsional)</label>
                                    <input type="date" name="expire_date" class="form-control @error('expire_date') is-invalid @enderror" value="{{ old('expire_date', $announcement->expire_date?->format('Y-m-d')) }}">
                                    @error('expire_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                    <small class="text-muted">Kosongkan jika pengumuman berlaku selamanya.</small>
                                </div>

                                <div class="form-group">
                                    <label>Lampiran File (Opsional)</label>
                                    @if($announcement->attachment)
                                        <div class="mb-2">
                                            <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank" class="badge badge-info"><i class="fas fa-eye"></i> Lihat Lampiran Saat Ini</a>
                                        </div>
                                    @endif
                                    <div class="custom-file">
                                        <input type="file" name="attachment" class="custom-file-input @error('attachment') is-invalid @enderror" id="customFile">
                                        <label class="custom-file-label" for="customFile">Pilih file baru (opsional)</label>
                                    </div>
                                    <div id="file-feedback" class="mt-2 d-none">
                                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle me-1"></i> File dipilih: <span id="file-name-display"></span></span>
                                    </div>
                                    @error('attachment')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file.</small>
                                </div>

                                <hr>
                                
                                <div class="form-group mb-0">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" {{ old('is_published', $announcement->is_published) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_published">Status Publikasi</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style', 'bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Custom File Input
        bsCustomFileInput.init();

        // Fallback for file input label
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
                $('#file-name-display').text(fileName);
                $('#file-feedback').removeClass('d-none');
            } else {
                $(this).siblings('.custom-file-label').removeClass("selected").html('Pilih file baru (opsional)');
                $('#file-feedback').addClass('d-none');
            }
        });
    });
</script>
@stop
