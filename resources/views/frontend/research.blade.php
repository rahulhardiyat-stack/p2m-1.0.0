@extends('layouts.frontend')

@push('title'){{ __('Penelitian') }} - @endpush

@section('content')
<div class="page-header-premium py-5 text-white position-relative overflow-hidden mb-0">
    <div class="page-header-pattern"></div>
    <div class="container py-4 position-relative z-1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2 text-white-50">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">{{ __('Beranda') }}</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">{{ __('Penelitian') }}</li>
            </ol>
        </nav>
        <h1 class="display-4 fw-bold mb-0">{{ __('Hasil Penelitian') }}</h1>
    </div>
    <div class="page-header-logo">
        <img src="{{ asset('storage/'.\App\Models\Setting::get('site_logo')) }}" alt="Logo">
    </div>
</div>

<section class="py-5 bg-white">
    <div class="container">
        <!-- Filter Bar -->
        <div class="filter-card shadow-sm mb-5 p-4 rounded-4 bg-white border border-light">
            <form action="{{ route('research') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Cari Judul / Peneliti</label>
                    <div class="input-group search-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-primary"></i></span>
                        <input type="text" name="q" class="form-control border-start-0" placeholder="Ketik kata kunci..." value="{{ request('q') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Tahun</label>
                    <select name="year" class="form-select select-year" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @php $currentYear = date('Y'); @endphp
                        @for($y = $currentYear; $y >= 2018; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3 d-grid">
                    <button type="submit" class="btn btn-primary rounded-pill fw-bold py-2 shadow-sm">
                        <i class="fas fa-filter me-2 text-white-50"></i> Filter Data
                    </button>
                </div>
                @if(request('q') || request('year'))
                <div class="col-12 mt-2">
                    <a href="{{ route('research') }}" class="text-danger small fw-bold text-decoration-none">
                        <i class="fas fa-times-circle me-1"></i> Reset Filter
                    </a>
                </div>
                @endif
            </form>
        </div>

        <div class="table-responsive shadow-lg rounded-4 overflow-hidden border-0">
            <table class="table table-hover align-middle mb-0 custom-datatable">
                <thead class="bg-primary bg-gradient text-white">
                    <tr>
                        <th class="px-4 py-4" style="width: 50%;">Judul Penelitian</th>
                        <th class="py-4">Peneliti</th>
                        <th class="py-4 text-center">Tahun</th>
                        <th class="px-4 py-4 text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($posts as $post)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box me-3 bg-light text-primary">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="text-dark fw-bold" style="font-size: 0.95rem; line-height: 1.4;">{{ $post->title }}</div>
                            </div>
                        </td>
                        <td class="py-3">
                            <span class="text-secondary small d-block"><i class="fas fa-user-circle me-2 opacity-50"></i>{{ $post->author ?? 'Tutor/Dosen' }}</span>
                        </td>
                        <td class="py-3 text-center">
                            <span class="badge bg-soft-primary px-3 py-2 rounded-pill text-primary fw-bold" style="font-size: 0.8rem;">
                                {{ $post->year ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <a href="{{ route('research.show', $post->slug) }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 hover-up shadow-sm">
                                <i class="fas fa-external-link-alt me-1"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <div class="py-4">
                                <i class="fas fa-database fa-3x text-muted opacity-20 mb-3"></i>
                                <h5 class="text-muted fw-bold">Tidak ada data penelitian ditemukan.</h5>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $posts->links() }}
        </div>
    </div>
</section>
@endsection

@push('css')
<style>
    .filter-card { border-radius: 20px; transition: all 0.3s ease; }
    .filter-card:hover { transform: translateY(-5px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08) !important; }
    .custom-datatable thead th { font-weight: 700; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; border: none; }
    .custom-datatable tbody td { border-color: #f8f9fa; }
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.08); }
    .icon-box { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
    .hover-up { transition: all 0.2s ease; }
    .hover-up:hover { transform: translateY(-2px); }
    .input-group.search-group input { border-radius: 0 12px 12px 0 !important; border-color: #eee; }
    .input-group.search-group .input-group-text { border-radius: 12px 0 0 12px !important; border-color: #eee; }
    .select-year { border-radius: 12px; border-color: #eee; }
</style>
@endpush
