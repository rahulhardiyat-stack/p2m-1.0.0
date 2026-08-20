@extends('layouts.frontend')

@push('title')
{{ __('Agenda & Kegiatan') }} -
@endpush

@section('content')
<section class="page-header-premium mb-0">
    <div class="page-header-pattern"></div>
    <div class="page-header-logo">
        <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}" alt="Logo">
    </div>
    <div class="container position-relative z-10 py-4">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-right">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Agenda & Kegiatan') }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold text-white mb-0">
                    {{ __('menu.agenda') }} & {{ __('menu.events') }}
                </h1>
                <p class="lead text-white-50 mt-2 mb-0">{{ __('Ikuti berbagai kegiatan menarik yang kami selenggarakan.') }}</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" data-aos="fade-left">
                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                    <a href="{{ route('events.index') }}" class="btn btn-{{ request('status') !== 'past' ? 'light' : 'outline-light border-0' }} px-4">{{ __('Mendatang') }}</a>
                    <a href="{{ route('events.index', ['status' => 'past']) }}" class="btn btn-{{ request('status') === 'past' ? 'light' : 'outline-light border-0' }} px-4">{{ __('Selesai') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            @forelse($events as $event)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-hover">
                    <div class="position-relative">
                        <img src="{{ $event->featured_image_url }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-0 start-0 m-3 d-flex flex-column align-items-center bg-white rounded-3 shadow-sm p-2 text-center" style="width: 60px;">
                            <span class="d-block fw-bold text-primary fs-4">{{ $event->start_date->format('d') }}</span>
                            <span class="d-block text-uppercase small text-muted">{{ $event->start_date->format('M') }}</span>
                        </div>
                        @if($event->is_free)
                        <div class="badge bg-success position-absolute top-0 end-0 m-3 shadow-sm">{{ __('Gratis') }}</div>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center text-muted small mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i> {{ \Illuminate\Support\Str::limit($event->location, 30) }}
                        </div>
                        <h5 class="card-title fw-bold mb-3">
                            <a href="{{ route('events.show', $event->slug) }}" class="text-dark text-decoration-none stretched-link">
                                {{ $event->title }}
                            </a>
                        </h5>
                        <div class="d-flex align-items-center text-muted small mt-3">
                            <i class="far fa-clock me-2"></i> {{ $event->start_date->format('H:i') }} - {{ $event->end_date ? $event->end_date->format('H:i') : __('Selesai') }}
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 p-4 pt-0">
                        <div class="d-grid">
                            <a href="{{ route('events.show', $event->slug) }}" class="btn btn-outline-primary rounded-pill">{{ __('Daftar Sekarang') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="far fa-calendar-times fa-4x text-muted opacity-20"></i>
                </div>
                <h4 class="text-muted">{{ __('Belum ada agenda yang dijadwalkan.') }}</h4>
            </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $events->links() }}
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .rotate-n-15 { transform: rotate(-15deg); }
    .transition-hover { transition: all 0.3s ease; }
    .transition-hover:hover { transform: translateY(-5px); box-shadow: 0 1rem 2rem rgba(0,0,0,0.1) !important; }
</style>
@endpush
