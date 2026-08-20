@extends('layouts.frontend')

@push('title')
{{ __('Galeri Foto & Video') }} -
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
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ __('Galeri') }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold mb-0">{{ __('Galeri Foto & Video') }}</h1>
                <p class="lead mb-0 text-white-50 mt-2">{{ __('Dokumentasi berbagai kegiatan dan fasilitas Program Studi.') }}</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0" data-aos="fade-left">
                <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                    <a href="{{ route('gallery.index') }}" class="btn btn-{{ !request('type') ? 'light' : 'outline-light border-0' }} px-4">{{ __('Semua') }}</a>
                    <a href="{{ route('gallery.index', ['type' => 'photo']) }}" class="btn btn-{{ request('type') === 'photo' ? 'light' : 'outline-light border-0' }} px-4">{{ __('Foto') }}</a>
                    <a href="{{ route('gallery.index', ['type' => 'video']) }}" class="btn btn-{{ request('type') === 'video' ? 'light' : 'outline-light border-0' }} px-4">{{ __('Video') }}</a>
                </div>
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
        <div class="row g-4" id="gallery-grid">
            @forelse($items as $item)
            @php
                $photoExists = $item->type === 'photo'
                    && $item->file_path
                    && \Illuminate\Support\Facades\Storage::disk('public')->exists($item->file_path);
                $itemUrl = $item->type === 'video'
                    ? ($item->youtube_url ?: 'https://www.youtube.com/watch?v=' . $item->youtube_id)
                    : ($photoExists ? asset('storage/' . $item->file_path) : null);
            @endphp
            <div class="col-sm-6 col-md-4 col-lg-3" data-aos="zoom-in" data-aos-delay="{{ $loop->iteration * 50 }}">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden gallery-card">
                    <div class="position-relative overflow-hidden">
                        @if($item->type === 'video')
                        <img src="https://img.youtube.com/vi/{{ $item->youtube_id }}/mqdefault.jpg" class="card-img-top transition-zoom" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="fas fa-play-circle fa-4x text-white opacity-75"></i>
                        </div>
                        @else
                            @if($photoExists)
                                <img src="{{ $itemUrl }}" class="card-img-top transition-zoom" alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="gallery-image-placeholder d-flex flex-column align-items-center justify-content-center text-center">
                                    <i class="fas fa-image mb-2"></i>
                                    <span>Foto tidak tersedia</span>
                                </div>
                            @endif
                        @endif

                        <div class="gallery-overlay d-flex flex-column justify-content-end p-3">
                            <h6 class="text-white fw-bold mb-1">{{ $item->title }}</h6>
                            <small class="text-white-50">{{ $item->album ?: ($item->type === 'video' ? __('Video') : __('Foto')) }}</small>
                            @if($itemUrl)
                                <a href="{{ $itemUrl }}" class="stretched-link gallery-lightbox" data-type="{{ $item->type }}" title="{{ $item->title }}"></a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-images fa-4x text-muted opacity-20"></i>
                </div>
                <h4 class="text-muted">{{ __('Belum ada koleksi galeri.') }}</h4>
            </div>
            @endforelse
        </div>

        <div class="mt-5 d-flex justify-content-center">
            {{ $items->links() }}
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .rotate-n-15 { transform: rotate(-15deg); }
    .gallery-card { cursor: pointer; }
    .transition-zoom { transition: transform 0.5s ease; }
    .gallery-card:hover .transition-zoom { transform: scale(1.1); }
    .gallery-image-placeholder {
        background: linear-gradient(135deg, #e2e8f0, #f8fafc);
        color: #64748b;
        height: 200px;
        font-size: 0.85rem;
        font-weight: 700;
    }
    .gallery-image-placeholder i {
        font-size: 2rem;
        opacity: .7;
    }
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0) 70%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .gallery-card:hover .gallery-overlay { opacity: 1; }
</style>
@endpush
