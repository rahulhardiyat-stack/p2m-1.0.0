@extends('layouts.frontend')

@push('title'){{ __('Akreditasi') }} - @endpush

@section('content')
    <div class="page-header-premium py-5 text-white position-relative overflow-hidden mb-0">
        <div class="page-header-pattern"></div>
        <div class="container py-4 position-relative z-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-2 text-white-50">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">{{ __('Beranda') }}</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">{{ __('Pengabdian Masyarakat') }}</li>
                </ol>
            </nav>
            <h1 class="display-4 fw-bold mb-0">{{ __('Akreditasi') }}</h1>
        </div>
        <div class="page-header-logo">
            <img src="{{ asset('storage/'.\App\Models\Setting::get('site_logo')) }}" alt="Logo">
        </div>
    </div>

    <section class="py-5 bg-white">
        <div class="container">
            <!-- Filter Bar -->
            <div class="filter-card shadow-sm mb-5 p-4 rounded-4 bg-white border border-light">
                <form action="{{ route('accreditation') }}" method="GET" class="row g-3 align-items-end">

                    {{-- filter by keyword --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Cari Prodi....</label>
                        <div class="input-group search-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-success"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" placeholder="Ketik kata kunci..." value="{{ request('search') }}">
                        </div>
                    </div>

                    {{-- filter by jurusan --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Jurusan</label>
                        <select name="faculty_id" class="form-select select-year" onchange="this.form.submit()">
                            <option value="">-- Semua Jurusan --</option>
                            @foreach($faculties as $faculty)
                                <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                                    {{ $faculty->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- filter by status akreditasi --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Status Akreditasi</label>
                        <select name="status" class="form-select select-year" onchange="this.form.submit()">
                            <option value="">-- Semua Status --</option>
                            <option value="Unggul" {{ request('status') == 'Unggul' ? 'selected' : '' }}>Unggul</option>
                            <option value="Baik Sekali" {{ request('status') == 'Baik Sekali' ? 'selected' : '' }}>Baik Sekali</option>
                            <option value="Baik" {{ request('status') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        </select>
                    </div>

                    {{-- filter by masa berlaku --}}
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Masa Berlaku</label>
                        <select name="validity" class="form-select select-year" onchange="this.form.submit()">
                            <option value="">-- Semua --</option>
                            <option value="active" {{ request('validity') == 'active' ? 'selected' : '' }}>Masih Berlaku</option>
                            <option value="expiring_soon" {{ request('validity') == 'expiring_soon' ? 'selected' : '' }}>Segera Berakhir</option>
                            <option value="expired" {{ request('validity') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
                        </select>
                    </div>

                    {{-- filter submit --}}
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-success rounded-pill fw-bold py-2 shadow-sm">
                            <i class="fas fa-filter me-2 text-white-50"></i> Filter
                        </button>
                    </div>
                    @if(request('search') || request('faculty_id') || request('accreditations_id') || request('validity'))
                        <div class="col-12 mt-2">
                            <a href="{{ route('accreditation') }}" class="text-danger small fw-bold text-decoration-none">
                                <i class="fas fa-times-circle me-1"></i> Reset Filter
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="table-responsive shadow-lg rounded-4 overflow-hidden border-0">
                <table class="table table-hover align-middle mb-0 custom-datatable">
                    <thead class="bg-success bg-gradient text-white">
                    <tr>
                        <th class="px-4 py-4 " style="width: 5%;">No</th>
                        <th class="py-4">Program Studi/Institusi</th>
                        <th class="py-4 text-center">Status</th>
                        <th class="py-4 text-center">Tanggal</th>
                        <th class="py-4 text-center">Berlaku Hingga</th>
                        <th class="py-4 text-center">Status Berlaku</th>
                        <th class="px-4 py-4 text-center">Dokumen</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white">
                    @forelse($accreditations as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box me-3 text-success">
                                        {{ $loop->iteration }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-bold" style="font-size: 0.95rem; line-height: 1.4;">
                                    {{ $item->title }}
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-soft-success px-3 py-2 rounded-pill text-success fw-bold"
                                      style="background-color: rgba(40, 167, 69, 0.08); font-size: 0.8rem;">
                                    {{ $item->status ?? '-' }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="text-secondary small d-block"><i class="fas me-2 opacity-50"></i>
                                    {{ $item->issued_date->format('Y-m-d')}}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="text-secondary small d-block"><i class="fas me-2 opacity-50"></i>
                                    {{ $item->expired_date->format('Y-m-d')}}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                @if($item->status_label == 'Kadaluarsa')
                                    <span class="badge bg-soft-danger px-3 py-2 rounded-pill text-danger fw-bold"
                                          style="background-color: #fce8e6; color: #a80000; font-size: 0.8rem;">
                                             <i class="fas fa-times-circle mr-1"></i> {{ $item->status_label }}
                                    </span>
                                @elseif($item->remaining_days <= 180)
                                    <span class="badge badge-soft-warning px-2 py-1"
                                        style="background-color: rgba(220, 53, 69, 0.1); color: #FF6D00CC; font-size: 0.8rem; ">
                                        <i class="fas fa-check-circle mr-1"></i>
                                            {{ $item->status_label }}
                                        </span>
                                @else
                                    <span class="badge bg-soft-success px-3 py-2 rounded-pill text-success fw-bold"
                                          style="background-color: rgba(40, 167, 69, 0.08); font-size: 0.8rem;">
                                           <i class="fas fa-check-circle mr-1"></i>  {{ $item->status_label }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('research.show', $item->status) }}" class="btn btn-sm btn-outline-success rounded-pill px-4 hover-up shadow-sm">
                                    <i class="fas fa-external-link-alt me-1"></i> Unduh
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-database fa-3x text-muted opacity-20 mb-3"></i>
                                    <h5 class="text-muted fw-bold">Tidak ada data  ditemukan.</h5>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5 d-flex justify-content-center">
                {{ $accreditations->links() }}
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
        .bg-soft-success { background-color: rgba(40, 167, 69, 0.08); font-size: 0.8rem; }
        .badge-soft-warning { background-color: rgba(220, 53, 69, 0.1); color: #FF6D00CC; font-size: 0.8rem; }
        .icon-box { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; }
        .hover-up { transition: all 0.2s ease; }
        .hover-up:hover { transform: translateY(-2px); }
        .input-group.search-group input { border-radius: 0 12px 12px 0 !important; border-color: #eee; }
        .input-group.search-group .input-group-text { border-radius: 12px 0 0 12px !important; border-color: #eee; }
        .select-year { border-radius: 12px; border-color: #eee; }
        .bg-soft-danger { background-color: #fce8e6; color: #a80000; font-size: 0.8rem; }
    </style>
@endpush
