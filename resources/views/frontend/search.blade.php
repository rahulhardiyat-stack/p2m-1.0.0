@extends('layouts.frontend')

@push('title')
{{ __('Hasil Pencarian') }} -
@endpush

@section('content')
<section class="page-header-premium mb-0">
    <div class="page-header-pattern"></div>
    <div class="page-header-logo">
        <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}" alt="Logo">
    </div>
    <div class="container position-relative z-10 py-5">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">{{ __('menu.search_results') }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold text-white mb-3">
                    {{ __('menu.search_results') }}
                </h1>
                <p class="lead text-white-50 mb-0">
                    {{ __('Menampilkan hasil untuk: ') }} <span class="text-primary fw-bold">"{{ $q }}"</span>
                </p>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0" data-aos="fade-left">
                <form action="{{ route('search') }}" method="GET" class="search-page-form">
                    <div class="input-group input-group-lg shadow-lg rounded-pill overflow-hidden bg-white p-1">
                        <span class="input-group-text bg-white border-0 text-muted ps-4">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-0 px-3" 
                               placeholder="{{ __('menu.search_placeholder') }}" 
                               value="{{ $q }}" required minlength="3">
                        <button class="btn btn-primary rounded-pill px-4 ms-2" type="submit">
                            {{ __('Cari') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-10">
                @if(strlen($q) < 3)
                    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 text-center">
                        <i class="fas fa-exclamation-triangle fa-2x mb-3 d-block"></i>
                        <h5>{{ __('Kata kunci pencarian terlalu pendek.') }}</h5>
                        <p class="mb-0 text-secondary">{{ __('Mohon masukkan minimal 3 karakter untuk melakukan pencarian.') }}</p>
                    </div>
                @else
                    @forelse($results as $post)
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 transition-hover" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 50 }}">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="{{ $post->featured_image_url }}" class="img-fluid h-100 w-100" alt="{{ $post->title }}" style="object-fit: cover; min-height: 200px;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill small">{{ $post->category->name ?? __('Berita') }}</span>
                                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $post->published_at->format('d M Y') }}</small>
                                    </div>
                                    <h4 class="card-title fw-bold mb-2">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-dark text-decoration-none">
                                            {{ $post->title }}
                                        </a>
                                    </h4>
                                    <p class="card-text text-secondary mb-3 small line-clamp-2">
                                        {{ $post->excerpt }}
                                    </p>
                                    <a href="{{ route('posts.show', $post->slug) }}" class="btn btn-link text-primary p-0 fw-bold text-decoration-none small">
                                        {{ __('Baca Selengkapnya') }} <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-search-minus fa-4x text-muted opacity-20"></i>
                        </div>
                        <h4 class="text-muted">{{ __('Tidak ditemukan hasil untuk ') }} "{{ $q }}"</h4>
                        <p class="text-secondary">{{ __('Coba gunakan kata kunci lain yang lebih umum.') }}</p>
                    </div>
                    @endforelse

                    <div class="mt-5 d-flex justify-content-center">
                        {{ $results->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .rotate-n-15 { transform: rotate(-15deg); }
    .bg-primary-subtle { background-color: rgba(12, 121, 14, 0.1); }
    .border-primary-subtle { border-color: rgba(12, 121, 14, 0.2) !important; }
    .transition-hover { transition: all 0.3s ease; }
    .transition-hover:hover { transform: translateX(5px); box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.1) !important; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush
