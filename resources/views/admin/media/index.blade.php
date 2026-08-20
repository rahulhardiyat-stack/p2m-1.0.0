@extends('adminlte::page')

@section('title', 'Media Library')

@section('content')
<div class="container-fluid pt-3" id="media-manager">
    <div class="card shadow-sm mb-3">
        <div class="card-body py-3 d-flex justify-content-between align-items-center">
            <h3 class="card-title m-0 font-weight-bold text-primary"><i class="fas fa-images me-2"></i>Media Library</h3>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-modal="upload">
                <i class="fas fa-upload me-1"></i> Upload File
            </button>
        </div>
    </div>
    
    <div class="row mb-3">
        <div class="col-md-3">
            <select class="form-control" name="folder" id="folder-filter">
                <option value="">Semua Folder</option>
                <option value="/">Root (/)</option>
                @foreach($folders as $f)
                    <option value="{{ $f }}" {{ request('folder') == $f ? 'selected' : '' }}>{{ $f }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-control" name="type" id="type-filter">
                <option value="">Semua Tipe File</option>
                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Gambar (JPG, PNG, dll)</option>
                <option value="application/pdf" {{ request('type') == 'application/pdf' ? 'selected' : '' }}>Dokumen PDF</option>
                <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="search" id="search-filter" placeholder="Cari nama file..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-secondary w-100" id="btn-filter">Filter</button>
        </div>
    </div>

    <div class="row" id="media-grid">
        @forelse($media as $item)
            <div class="col-md-2 col-sm-4 col-6 mb-4 media-item-container">
                <div class="card h-100 media-card position-relative">
                    <div class="position-absolute p-1" style="right:0; top:0; z-index: 10;">
                        <form action="{{ route('admin.media.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus file ini permanen?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-xs rounded-circle shadow-sm" title="Hapus"><i class="fas fa-times"></i></button>
                        </form>
                    </div>
                    
                    <div class="media-preview text-center bg-light d-flex align-items-center justify-content-center" style="height: 150px; overflow: hidden; cursor:pointer;" onclick="showMediaDetails({{ $item->id }})">
                        @if(str_starts_with($item->mime_type, 'image/'))
                            <img src="{{ $item->url }}" alt="{{ $item->original_name }}" class="img-fluid" style="object-fit: contain; width: 100%; height: 100%;">
                        @elseif(str_starts_with($item->mime_type, 'video/'))
                            <i class="fas fa-film fa-4x text-secondary"></i>
                        @elseif($item->mime_type === 'application/pdf')
                            <i class="fas fa-file-pdf fa-4x text-danger"></i>
                        @else
                            <i class="fas fa-file fa-4x text-secondary"></i>
                        @endif
                    </div>
                    <div class="card-body p-2 text-center" style="font-size: 0.8rem;">
                        <div class="text-truncate px-1 fw-bold" title="{{ $item->original_name }}"><strong>{{ $item->original_name }}</strong></div>
                        <div class="text-muted mb-1 small">{{ $item->size_formatted }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="fas fa-folder-open fa-3x mb-3"></i>
                <h5>Belum ada file media</h5>
                <p>Silakan upload file baru menggunakan tombol di atas.</p>
            </div>
        @endforelse
    </div>

    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $media->links() }}
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload File Media</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="upload-form" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Simpan ke Folder</label>
                        <div class="col-sm-9">
                            <input type="text" name="folder" class="form-control" placeholder="Contoh: berita, kegiatan, umum" value="/">
                            <small class="text-muted">Kosongkan atau isi '/' untuk folder utama.</small>
                        </div>
                    </div>
                    
                    <div class="form-group border border-primary border-dashed rounded p-5 text-center bg-light" id="drop-zone">
                        <i class="fas fa-cloud-upload-alt fa-4x text-primary mb-3"></i>
                        <h5>Pilih atau tarik file ke sini</h5>
                        <p class="text-muted mb-4">Dukung JPG, PNG, PDF, & File lainnya (Maks 10MB/file)</p>
                        
                        <div class="custom-file" style="max-width: 300px; margin: 0 auto;">
                            <input type="file" name="files[]" class="custom-file-input" id="fileInput" multiple required>
                            <label class="custom-file-label text-left" for="fileInput">Pilih banyak file...</label>
                        </div>
                    </div>

                    <div id="upload-progress" class="d-none mt-3">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                        </div>
                        <div class="text-center mt-2 small text-muted" id="upload-status">Sedang mengupload...</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btn-upload"><i class="fas fa-upload me-1"></i> Upload Sekarang</button>
            </div>
        </div>
    </div>
</div>

@include('admin.partials.media-detail-modal')

<!-- Copy Alert Toast -->
<div class="position-fixed p-3" style="z-index: 9999; bottom: 0; right: 0;">
  <div id="copyToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
    <div class="toast-header bg-success text-white">
      <i class="fas fa-check-circle mr-2"></i>
      <strong class="mr-auto">Berhasil!</strong>
      <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      URL Gambar disalin ke clipboard!
    </div>
  </div>
</div>
@stop

@section('css')
<style>
    .media-card { transition: all 0.2s ease-in-out; }
    .media-card:hover { transform: translateY(-5px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important; border-color: #007bff; }
    .border-dashed { border-style: dashed !important; border-width: 2px !important; }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Handle filter changes
        $('#btn-filter').click(function() {
            let folder = $('#folder-filter').val();
            let type = $('#type-filter').val();
            let search = $('#search-filter').val();
            
            let url = new URL(window.location.href);
            url.searchParams.set('folder', folder);
            url.searchParams.set('type', type);
            url.searchParams.set('search', search);
            url.searchParams.delete('page'); // reset page
            
            window.location.href = url.toString();
        });

        // Allow enter on search box
        $('#search-filter').keypress(function(e) {
            if(e.which == 13) $('#btn-filter').click();
        });

        // Setup upload modal
        $('[data-modal="upload"]').click(function() {
            $('#uploadModal').modal('show');
        });

        // Custom file input label update
        $('#fileInput').on('change',function(){
            var files = [];
            for (var i = 0; i < $(this)[0].files.length; i++) {
                files.push($(this)[0].files[i].name);
            }
            if(files.length > 1) {
                $(this).next('.custom-file-label').html(files.length + ' file dipilih');
            } else if (files.length == 1) {
                $(this).next('.custom-file-label').html(files[0]);
            } else {
                $(this).next('.custom-file-label').html('Pilih banyak file...');
            }
        });

        // Drag and drop support
        var dropZone = document.getElementById('drop-zone');
        var fileInput = document.getElementById('fileInput');

        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            dropZone.classList.add('bg-white');
            dropZone.classList.remove('bg-light');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            dropZone.classList.remove('bg-white');
            dropZone.classList.add('bg-light');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            dropZone.classList.remove('bg-white');
            dropZone.classList.add('bg-light');
            
            if(e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                $(fileInput).trigger('change');
            }
        });

        // AJAX Upload
        $('#btn-upload').click(function() {
            if(fileInput.files.length === 0) {
                alert('Pilih minimal satu file!');
                return;
            }

            var formData = new FormData($('#upload-form')[0]);
            
            $('#upload-progress').removeClass('d-none');
            $('#btn-upload').prop('disabled', true);
            $('.custom-file-input').prop('disabled', true);

            $.ajax({
                url: $('#upload-form').attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                            $('.progress-bar').css('width', percentComplete + '%');
                            $('.progress-bar').html(percentComplete + '%');
                            $('#upload-status').text('Mengupload ' + percentComplete + '%... Mohon tunggu.');
                        }
                    }, false);
                    return xhr;
                },
                success: function(response) {
                    $('#upload-status').text(response.message).removeClass('text-muted').addClass('text-success font-weight-bold');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    var msg = 'Upload gagal!';
                    if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                    $('#upload-status').text(msg).removeClass('text-muted').addClass('text-danger font-weight-bold');
                    $('#btn-upload').prop('disabled', false);
                    $('.custom-file-input').prop('disabled', false);
                }
            });
        });
    });
</script>
@stop
