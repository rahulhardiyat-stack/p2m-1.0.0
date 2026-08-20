@extends('adminlte::page')

@section('title', __('admin.gallery'))

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0 text-dark font-weight-bold">{{ __('admin.gallery') }}</h1>
        <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">{{ __('admin.gallery') }}</li>
        </ol>
    </div>
@stop

@section('content')
<div class="container-fluid pt-5 pb-5">
    <div class="row justify-content-end mt-4 px-lg-4">
        <div class="col-12" style="max-width: 78%; flex: 0 0 78% !important; margin-right: 3rem !important;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filter Card -->
    <div class="card card-gallery mb-4 border-0 shadow-sm" style="border-radius: 16px;">
        <div class="card-body p-3">
            <form method="GET" class="row align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm mb-0">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" placeholder="{{ __('admin.search_gallery') }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="type" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">{{ __('admin.filter_type') ?? 'Semua Tipe' }}</option>
                        <option value="photo" {{ request('type') == 'photo' ? 'selected' : '' }}>Photo</option>
                        <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Video</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-sm btn-block shadow-sm">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.gallery.index') }}" class="btn btn-light btn-sm btn-block text-muted">
                        <i class="fas fa-undo mr-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="card card-gallery shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header border-0 bg-white py-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div>
                        <h3 class="card-title font-weight-bold text-dark m-0" style="font-size: 1.25rem;">
                            <i class="fas fa-images mr-2 text-primary"></i>{{ __('admin.gallery') }}
                        </h3>
                        <p class="text-muted small mb-0 mt-1">Kelola album foto dan video konten website</p>
                    </div>

                    {{-- Bulk Delete Button (Super Admin & Admin Only) --}}
                    @if(auth()->user()->hasRole(['Super Admin', 'Admin Prodi']))
                    <form id="bulk-delete-form" action="{{ route('admin.gallery.mass-destroy') }}" method="POST" class="ml-4" style="display: none;">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus item galeri yang dipilih?')">
                            <i class="fas fa-trash-alt mr-1"></i> Hapus Terpilih (<span id="selected-count">0</span>)
                        </button>
                    </form>
                    @endif
                </div>
                <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary shadow-sm hover-up rounded-pill px-4 py-2">
                    <i class="fas fa-plus mr-2"></i> {{ __('admin.add_gallery') }}
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-gallery align-middle mb-0">
                    <thead>
                        <tr>
                            @if(auth()->user()->hasRole(['Super Admin', 'Admin Prodi']))
                            <th class="px-4 py-3" width="40">
                                <div class="custom-control custom-checkbox ml-1">
                                    <input type="checkbox" class="custom-control-input" id="select-all">
                                    <label class="custom-control-label" for="select-all"></label>
                                </div>
                            </th>
                            @endif
                            <th class="{{ auth()->user()->hasRole(['Super Admin', 'Admin Prodi']) ? '' : 'px-4' }} py-3" width="120">{{ __('admin.image') }}</th>
                            <th class="py-3">{{ __('admin.gallery_title') }}</th>
                            <th class="py-3" width="180">{{ __('admin.gallery_category') }}</th>
                            <th class="py-3 text-center" width="120">{{ __('admin.status') }}</th>
                            <th class="px-4 py-3 text-right" width="120">{{ __('admin.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($galleries as $gallery)
                        <!-- Row Content -->
                        <tr>
                            @if(auth()->user()->hasRole(['Super Admin', 'Admin Prodi']))
                            <td class="px-4 py-3">
                                <div class="custom-control custom-checkbox ml-1">
                                    <input type="checkbox" name="ids[]" value="{{ $gallery->id }}" form="bulk-delete-form" class="custom-control-input gallery-checkbox" id="check-{{ $gallery->id }}">
                                    <label class="custom-control-label" for="check-{{ $gallery->id }}"></label>
                                </div>
                            </td>
                            @endif
                            <td class="{{ auth()->user()->hasRole(['Super Admin', 'Admin Prodi']) ? '' : 'px-4' }} py-3">
                                <div class="clickable-preview" onclick="showMediaDetailsByPath('{{ $gallery->file_path }}')" style="cursor:pointer">
                                    @if($gallery->type === 'photo')
                                        <div class="gallery-thumb-wrapper shadow-sm">
                                            <img src="{{ asset('storage/' . $gallery->file_path) }}" alt="" class="img-fluid rounded shadow-xs">
                                        </div>
                                    @else
                                        <div class="gallery-thumb-wrapper shadow-sm youtube-thumb" style="background: #000;">
                                            <img src="https://img.youtube.com/vi/{{ $gallery->youtube_id }}/default.jpg" style="opacity: 0.7;" class="img-fluid rounded">
                                            <i class="fab fa-youtube text-danger play-icon"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark mb-1 clickable-preview" onclick="showMediaDetailsByPath('{{ $gallery->file_path }}')" style="cursor:pointer; font-weight: 600; font-size: 0.95rem;">
                                        {{ $gallery->title }}
                                    </span>
                                    <div class="d-flex align-items-center">
                                        <small class="text-muted mr-3"><i class="fas fa-folder-open mr-1"></i> {{ $gallery->album ?? 'Ungrouped' }}</small>
                                        <small class="text-muted"><i class="fas fa-calendar-alt mr-1"></i> {{ $gallery->created_at->format('d M Y') }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($gallery->type === 'photo')
                                    <span class="badge badge-soft-info px-3 py-2 rounded-pill">
                                        <i class="fas fa-camera mr-1"></i> Photo
                                    </span>
                                @else
                                    <span class="badge badge-soft-danger px-3 py-2 rounded-pill">
                                        <i class="fab fa-youtube mr-1"></i> Video
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 text-center">
                                @if($gallery->is_active)
                                    <span class="badge badge-soft-success px-3 py-2 rounded-pill shadow-xs border-0">
                                        <i class="fas fa-check-circle mr-1"></i> {{ __('admin.active') }}
                                    </span>
                                @else
                                    <span class="badge badge-soft-secondary px-3 py-2 rounded-pill">
                                        <i class="fas fa-times-circle mr-1"></i> {{ __('admin.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.gallery.edit', $gallery) }}" class="btn btn-action btn-soft-primary" title="{{ __('admin.edit') }}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.gallery.destroy', $gallery) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-action btn-soft-danger" title="{{ __('admin.delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasRole(['Super Admin', 'Admin Prodi']) ? '6' : '5' }}" class="text-center text-muted py-5">
                                <i class="fas fa-images fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">{{ __('admin.no_gallery') }}</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted font-italic">
                    {{ __('admin.showing') }} {{ $galleries->firstItem() ?? 0 }}-{{ $galleries->lastItem() ?? 0 }} {{ __('admin.of') }} {{ $galleries->total() }}
                </small>
                <div>
                    {{ $galleries->withQueryString()->links() }}
                </div>
            </div>
        </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
</div> <!-- end container -->

<style>
    /* Force content offset from sidebar if needed, matching other pages */
    @media (min-width: 992px) {
        .content {
            /* padding-left: 0; Standardize with others */
        }
    }

    .card-gallery {
        border-radius: 16px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04) !important;
        overflow: hidden;
    }
    .table-gallery thead th {
        background: #fdfdfd;
        border-bottom: 2px solid #f1f5f9;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        color: #888;
        padding-top: 15px !important;
        padding-bottom: 15px !important;
    }
    .table-gallery td {
        vertical-align: middle !important;
    }
    .gallery-thumb-wrapper {
        width: 80px;
        height: 55px;
        overflow: hidden;
        border-radius: 8px;
        position: relative;
        background: #f0f0f0;
    }
    .gallery-thumb-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }
    .gallery-thumb-wrapper:hover img {
        transform: scale(1.1);
    }
    .youtube-thumb .play-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 1.2rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    .badge-soft-info { background: rgba(23, 162, 184, 0.1); color: #17a2b8; border: 1px solid rgba(23, 162, 184, 0.1); }
    .badge-soft-danger { background: rgba(220, 53, 69, 0.1); color: #dc3545; border: 1px solid rgba(220, 53, 69, 0.1); }
    .badge-soft-success { background: rgba(40, 167, 69, 0.1); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.1); }
    .badge-soft-secondary { background: rgba(108, 117, 125, 0.1); color: #6c757d; border: 1px solid rgba(108, 117, 125, 0.1); }
    
    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s;
        border: 1px solid transparent;
    }
    .btn-soft-primary { background: rgba(0, 123, 255, 0.05); color: #007bff; }
    .btn-soft-primary:hover { background: #007bff; color: #fff; }
    .btn-soft-danger { background: rgba(220, 53, 69, 0.05); color: #dc3545; }
    .btn-soft-danger:hover { background: #dc3545; color: #fff; }
    
    .hover-up { transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); }
    .hover-up:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(0,0,0,0.1) !important; }
    
    .shadow-xs { box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .gap-2 { gap: 0.5rem; }
    
    /* Responsive adjustment */
    @media (max-width: 768px) {
        .card-header .d-flex { flex-direction: column; gap: 1rem; align-items: flex-start !important; }
        .card-header .btn { width: 100%; }
        .content { padding-left: 10px !important; padding-right: 10px !important; }
    }
</style>
@include('admin.partials.media-detail-modal')

@section('js')
<script>
    $(document).ready(function() {
        const selectAll = $('#select-all');
        const checkboxes = $('.gallery-checkbox');
        const bulkDeleteForm = $('#bulk-delete-form');
        const selectedCount = $('#selected-count');

        function updateBulkButton() {
            const checkedCount = $('.gallery-checkbox:checked').length;
            selectedCount.text(checkedCount);
            if (checkedCount > 0) {
                bulkDeleteForm.fadeIn(200);
            } else {
                bulkDeleteForm.fadeOut(200);
            }
        }

        selectAll.on('change', function() {
            checkboxes.prop('checked', $(this).prop('checked'));
            updateBulkButton();
        });

        checkboxes.on('change', function() {
            if (!$(this).prop('checked')) {
                selectAll.prop('checked', false);
            }
            if ($('.gallery-checkbox:checked').length === checkboxes.length && checkboxes.length > 0) {
                selectAll.prop('checked', true);
            }
            updateBulkButton();
        });
    });
</script>

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
      URL Media disalin ke clipboard!
    </div>
  </div>
</div>
@stop
