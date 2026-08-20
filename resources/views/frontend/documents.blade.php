@extends('layouts.frontend')

@push('title'){{ __('Arsip Dokumen') }} - @endpush

@section('content')
<div class="page-header-premium py-5 text-white position-relative overflow-hidden mb-0">
    <div class="page-header-pattern"></div>
    <div class="container py-4 position-relative z-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 text-white-50">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">{{ __('Beranda') }}</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ __('Arsip Dokumen') }}</li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold mb-0">{{ __('Arsip & Dokumen') }}</h1>
    </div>
    <div class="page-header-logo">
        <img src="{{ asset('storage/'.\App\Models\Setting::get('site_logo')) }}" alt="Logo">
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container">
        <!-- Filter Bar -->
        <div class="filter-card shadow-sm mb-5 p-4 rounded-4 bg-white border border-light">
            <form action="{{ route('documents') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Cari Dokumen</label>
                    <div class="input-group search-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-primary"></i></span>
                        <input type="text" name="q" class="form-control border-start-0" placeholder="Nama dokumen atau deskripsi..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Kategori</label>
                    <select name="category" class="form-select border-start-0">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm">
                        <i class="fas fa-filter me-2 opacity-50"></i> Filter Dokumen
                    </button>
                </div>
                @if(request('q') || request('category'))
                <div class="col-12 mt-2 text-center">
                    <a href="{{ route('documents') }}" class="text-danger small fw-bold text-decoration-none">
                        <i class="fas fa-times-circle me-1"></i> Reset Filter
                    </a>
                </div>
                @endif
            </form>
        </div>

        <div class="table-responsive shadow-lg rounded-4 overflow-hidden border-0">
            <table class="table table-hover align-middle mb-0 custom-datatable">
                <thead class="bg-dark bg-gradient text-white">
                    <tr>
                        <th class="px-4 py-4" style="width: 45%;">Nama Dokumen</th>
                        <th class="py-4">Kategori & Info</th>
                        <th class="px-4 py-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($documents as $doc)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 bg-light text-primary shadow-sm">
                                    @php
                                        $icon = 'fas fa-file-alt';
                                        $ext = strtolower($doc->file_type);
                                        if($ext == 'pdf') $icon = 'fas fa-file-pdf';
                                        elseif(in_array($ext, ['doc', 'docx'])) $icon = 'fas fa-file-word';
                                        elseif(in_array($ext, ['xls', 'xlsx'])) $icon = 'fas fa-file-excel';
                                        elseif(in_array($ext, ['zip', 'rar'])) $icon = 'fas fa-file-archive';
                                        elseif(in_array($ext, ['jpg', 'jpeg', 'png'])) $icon = 'fas fa-file-image';
                                    @endphp
                                    <i class="{{ $icon }}"></i>
                                </div>
                                <div>
                                    <div class="text-dark fw-bold" style="font-size: 1rem;">{{ $doc->title }}</div>
                                    <p class="text-muted small mb-0 line-clamp-1">{{ $doc->description ?: 'Dokumen resmi Program Studi.' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill mb-1">
                                <i class="fas fa-tag me-1"></i> {{ $doc->category?->name ?? 'Tanpa Kategori' }}
                            </span>
                            <div class="small text-muted mt-1">
                                <i class="fas fa-hdd me-1 opacity-50"></i> {{ number_format($doc->file_size / 1024, 1) }} KB | 
                                <i class="fas fa-calendar-alt me-1 opacity-50"></i> {{ $doc->created_at->format('d/m/Y') }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <!--add pratinjau-->
                            @php
                                $fileUrl = asset('storage/'.$doc->file_path);
                                $docExt = strtolower($doc->file_type);
                                $previewType = in_array($docExt, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : ($docExt === 'pdf' ? 'pdf' : 'unknown');
                            @endphp
                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 hover-up shadow-sm" data-bs-toggle="modal" data-bs-target="#documentPreviewModal" data-file-url="{{ $fileUrl }}" data-file-name="{{ $doc->title }}" data-file-type="{{ $previewType }}">
                                    <i class="fas fa-eye me-1"></i> Lihat
                                </button>
                                <a href="{{ route('documents.public-download', $doc->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 hover-up shadow-sm">
                                    <i class="fas fa-download me-1"></i> Download
                                </a>
                            </div>
                            <!---->
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-5">
                            <div class="py-4">
                                <i class="fas fa-folder-open fa-3x text-muted opacity-20 mb-3"></i>
                                <h5 class="text-muted fw-bold">Belum ada dokumen yang tersedia.</h5>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $documents->links() }}
        </div>
    </div>
</section>

<div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
            <div class="modal-header border-0 bg-light py-3 px-4 d-flex align-items-center justify-content-between">
                <h5 class="modal-title fw-bold text-dark" id="documentPreviewModalLabel">Pratinjau Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="box-shadow: none;"></button>
            </div>
            <div class="modal-body p-0 bg-dark d-flex align-items-center justify-content-center" style="min-height: 400px; max-height: 80vh; overflow: auto;">
                <div id="documentPreviewContent" class="w-100 h-100 d-flex align-items-center justify-content-center p-3"></div>
            </div>
            <div class="modal-footer border-0 bg-light py-3 px-4 d-flex justify-content-between align-items-center">
                <span class="text-muted small fw-bold" id="documentPreviewFooterName">Nama Dokumen</span>
                <a href="#" id="documentPreviewDownloadBtn" download class="btn btn-primary rounded-pill px-4 btn-sm fw-bold shadow-sm">
                    <i class="fas fa-download me-1"></i> Unduh Dokumen
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .filter-card { border-radius: 20px; transition: all 0.3s ease; }
    .filter-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08) !important; }
    .custom-datatable thead th { font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; border: none; }
    .custom-datatable tbody td { border-color: #f8f9fa; }
    .icon-box { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
    .hover-up { transition: all 0.2s ease; }
    .hover-up:hover { transform: translateY(-2px); }
    .input-group.search-group input { border-radius: 0 12px 12px 0 !important; border-color: #eee; }
    .input-group.search-group .input-group-text { border-radius: 12px 0 0 12px !important; border-color: #eee; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .bg-soft-primary { background-color: rgba(var(--bs-primary-rgb), 0.1); }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const documentPreviewModal = document.getElementById('documentPreviewModal');
        if (!documentPreviewModal) return;

        documentPreviewModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const fileUrl = button.getAttribute('data-file-url');
            const fileName = button.getAttribute('data-file-name');
            const fileType = button.getAttribute('data-file-type');
            const contentContainer = document.getElementById('documentPreviewContent');
            const footerName = document.getElementById('documentPreviewFooterName');
            const downloadBtn = document.getElementById('documentPreviewDownloadBtn');

            footerName.textContent = fileName;
            downloadBtn.setAttribute('href', fileUrl);
            contentContainer.innerHTML = '<div class="text-white py-5"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';

            if (fileType === 'pdf') {
                contentContainer.innerHTML = `<iframe src="${fileUrl}" class="w-100" style="height: 70vh; border: none; border-radius: 8px;"></iframe>`;
            } else if (fileType === 'image') {
                contentContainer.innerHTML = `<img src="${fileUrl}" alt="${fileName}" class="img-fluid" style="max-height: 70vh; object-fit: contain; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">`;
            } else {
                contentContainer.innerHTML = `<div class="text-center text-white p-5"><i class="fas fa-file-alt fa-3x mb-3"></i><h5 class="fw-bold mb-2">Pratinjau tidak tersedia</h5><p class="mb-0 text-white-50">Silakan unduh dokumen untuk membuka file ini.</p></div>`;
            }
        });
    });
</script>
@endpush
