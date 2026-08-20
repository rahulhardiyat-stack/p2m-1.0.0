@extends('layouts.frontend')

@push('title')
{{ __('Dosen & Tenaga Kependidikan') }} -
@endpush

@section('content')
<div class="page-header-premium py-5 text-white position-relative overflow-hidden">
    <div class="page-header-pattern"></div>
    <div class="container py-4 position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2 text-white-50">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">{{ __('Beranda') }}</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ __('Dosen & Tendik') }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold mb-0">{{ __('Dosen & Tendik') }}</h1>
                <p class="lead mb-0 text-white-50 mt-2">{{ __('Mengenal lebih dekat para pengajar dan staf profesional kami.') }}</p>
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

<section class="py-5 bg-light">
    <div class="container">
        <!-- Tabs for Dosen and Tendik -->
        <ul class="nav nav-pills nav-justified mb-5 shadow-sm rounded-4 bg-white p-2" id="staffTab" role="tablist" data-aos="fade-up">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-4 fw-bold py-3" id="dosen-tab" data-bs-toggle="pill" data-bs-target="#dosen" type="button" role="tab" aria-controls="dosen" aria-selected="true">
                    <i class="fas fa-chalkboard-teacher me-2"></i> {{ __('Dosen / Pengajar') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-4 fw-bold py-3" id="tendik-tab" data-bs-toggle="pill" data-bs-target="#tendik" type="button" role="tab" aria-controls="tendik" aria-selected="false">
                    <i class="fas fa-user-cog me-2"></i> {{ __('Tenaga Kependidikan') }}
                </button>
            </li>
        </ul>

        <div class="tab-content" id="staffTabContent">
            <!-- Dosen Tab -->
            <div class="tab-pane fade show active" id="dosen" role="tabpanel" aria-labelledby="dosen-tab">
                <div class="row g-4">
                    @forelse($dosen as $item)
                    <div class="col-md-6 col-lg-3" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 50 }}">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover">
                            <div class="position-relative">
                                <img src="{{ $item->photo_url }}" class="card-img-top" alt="{{ $item->name }}" style="height: 300px; object-fit: cover;">
                                <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white">
                                    <h6 class="fw-bold mb-0">{{ $item->name }}</h6>
                                    <small class="opacity-75">{{ $item->academic_title }}</small>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <p class="text-primary small fw-bold mb-2"><i class="fas fa-briefcase me-1"></i> {{ $item->position ?? __('Dosen') }}</p>
                                <div class="text-secondary small mb-3">
                                    <i class="fas fa-microscope me-1"></i> {{ \Illuminate\Support\Str::limit($item->expertise, 50) }}
                                </div>
                                <a href="{{ route('lecturers.show', $item->id) }}" class="btn btn-outline-primary btn-sm w-100 rounded-pill">{{ __('Lihat Profil') }}</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">{{ __('Data dosen belum tersedia.') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Tendik Tab -->
            <div class="tab-pane fade" id="tendik" role="tabpanel" aria-labelledby="tendik-tab">
                <div class="row g-4">
                    @forelse($tendik as $item)
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover">
                            <div class="position-relative">
                                <img src="{{ $item->photo_url }}" class="card-img-top" alt="{{ $item->name }}" style="height: 300px; object-fit: cover;">
                                <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-gradient-dark text-white">
                                    <h6 class="fw-bold mb-0">{{ $item->name }}</h6>
                                </div>
                            </div>
                            <div class="card-body p-3 text-center">
                                <p class="text-primary small fw-bold mb-0">{{ $item->position ?? __('Staf') }}</p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">{{ __('Data tenaga kependidikan belum tersedia.') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .rotate-n-15 { transform: rotate(-15deg); }
    .transition-hover { transition: all 0.3s ease; }
    .transition-hover:hover { transform: translateY(-5px); box-shadow: 0 1rem 2rem rgba(0,0,0,0.1) !important; }
    .bg-gradient-dark {
        background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 100%);
    }
    .nav-pills .nav-link.active {
        background-color: var(--bs-primary);
        color: white;
    }
    .nav-pills .nav-link {
        color: var(--bs-secondary);
    }
</style>
@endpush
