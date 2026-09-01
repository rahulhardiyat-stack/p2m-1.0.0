@extends('layouts.frontend')

@push('title'){{ $page->title }} -
@endpush

@section('content')
    @if($page->is_builder)
        <div class="page-builder-content">
            @safeHtml($processedContent)
        </div>
    @else
        <div class="page-header-premium py-5 text-white position-relative overflow-hidden">
            <div class="page-header-pattern"></div>
            <div class="container py-4 position-relative z-1">
                <div class="row align-items-center">
                    <div class="col-lg-8" data-aos="fade-up">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2 text-white-50">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none"><i class="fas fa-home me-1"></i>{{ __('Beranda') }}</a></li>
                                <li class="breadcrumb-item active text-white" aria-current="page">{{ $page->title }}</li>
                            </ol>
                        </nav>
                        <h1 class="display-4 fw-bold mb-0">{{ $page->title }}</h1>
                    </div>
                </div>
            </div>
            <div class="page-header-logo">
                @php $logo = \App\Models\Setting::get('site_logo') @endphp
                @if($logo)
                    <img src="{{ asset('storage/'.$logo) }}" alt="Logo">
                @endif
            </div>
        </div>

        <section class="py-5 bg-white">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10" data-aos="fade-up">
                        @if($page->featured_image)
                            <div class="mb-5 text-center">
                                <img src="{{ asset('storage/' . $page->featured_image) }}" alt="{{ $page->title }}" class="img-fluid rounded-4 shadow-sm w-100" style="max-height: 500px; object-fit: cover;">
                            </div>
                        @endif
                    </div>

                    @if(isset($documents))
                        <section class="py-5 bg-white">
                            <div class="container">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                                    <div>
                                        <h2 class="h4 fw-bold mb-1">Dokumen {{ $documentCategory?->name ?? $page->title }}</h2>
                                        <p class="text-muted mb-0">Menampilkan dokumen publik pada kategori ini.</p>
                                    </div>
                                    @if($documentCategory)
                                        <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill">
                            <i class="fas fa-tag me-1"></i> {{ $documentCategory->name }}
                        </span>
                                    @endif
                                </div>

                                <div class="table-responsive shadow-lg rounded-4 overflow-hidden border-0">
                                    <table class="table table-hover align-middle mb-0 custom-datatable">
                                        <thead class="bg-dark bg-gradient text-white">
                                        <tr>
                                            <th class="px-4 py-4" style="width: 45%;">Nama Dokumen</th>
                                            <th class="py-4">Info</th>
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
                                                            <p class="text-muted small mb-0 line-clamp-1">{{ $doc->description ?: 'Dokumen resmi Pusat Penjaminan Mutu' }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="small text-muted mt-1">
                                                        <i class="fas fa-hdd me-1 opacity-50"></i> {{ number_format($doc->file_size / 1024, 1) }} KB |
                                                        <i class="fas fa-calendar-alt me-1 opacity-50"></i> {{ $doc->created_at->format('d/m/Y') }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 text-end">
                                                    <!--tambah pratinjau-->
                                                    @php
                                                        $fileUrl = asset('storage/'.$doc->file_path);
                                                        $docExt = strtolower($doc->file_type);
                                                        $previewType = in_array($docExt, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : ($docExt === 'pdf' ? 'pdf' : 'unknown');
                                                    @endphp
                                                    <div class="d-flex justify-content-end gap-2 flex-wrap">
                                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 hover-up shadow-sm" data-bs-toggle="modal" data-bs-target="#documentPreviewModal" data-file-url="{{ $fileUrl }}" data-file-name="{{ $doc->title }}" data-file-type="{{ $previewType }}">
                                                            <i class="fas fa-eye me-1"></i> Lihat
                                                        </button>
                                                        <a href="{{ route('documents.public-download', $doc->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 hover-up shadow-sm">
                                                            <i class="fas fa-download me-1"></i> Unduh
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
                                                        <h5 class="text-muted fw-bold">Belum ada dokumen di kategori ini.</h5>
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
                    @endif

                    <div class="page-content bg-white p-4 p-lg-5 rounded-4 shadow-sm border">
                        <div class="text-dark leading-relaxed">
                            @safeHtml($processedContent)
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-primary rounded-pill px-5">
                            <i class="fas fa-arrow-left me-2"></i> {{ __('Kembali') }}
                        </a>
                    </div>
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




    @endif
@endsection

@push('styles')
    <style>
        {!! $builderCss !!}
    .leading-relaxed { line-height: 1.8; }
        .page-content { font-size: 1.15rem; }
        .page-content p { margin-bottom: 1.5rem; }
        .page-content img { max-width: 100%; height: auto; border-radius: 1rem; margin-top: 1rem; margin-bottom: 2rem; }
        .page-content h2, .page-content h3, .page-content h4 { font-weight: 700; color: #1e3c72; margin-top: 2rem; margin-bottom: 1rem; }
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
