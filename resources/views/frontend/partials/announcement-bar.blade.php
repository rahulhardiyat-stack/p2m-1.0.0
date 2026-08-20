@php
$announcements = \App\Models\Announcement::where('is_published', true)
    ->where(fn($q) => $q->whereNull('expire_date')->orWhere('expire_date', '>=', today()))
    ->latest()->take(5)->get();
@endphp

@if($announcements->count() > 0)
<div class="elegant-announcement-bar py-3 shadow position-relative" data-aos="fade-up">
    <div class="container d-flex align-items-center">
        <span class="badge rounded-pill announcement-badge shadow me-3 px-4 py-2 flex-shrink-0 z-10 position-relative">
{{--            <i class="fas fa-bullhorn me-1"></i> --}}
            {{ __('menu.announcement') }}
        </span>

        <div class="announcement-content-wrapper overflow-hidden flex-grow-1 position-relative">
            <div class="{{ $announcements->count() > 1 ? 'marquee-content d-flex' : 'd-flex align-items-center justify-content-center' }}">
                @foreach($announcements as $announcement)
                    <div class="announcement-item d-inline-flex align-items-center mx-4 flex-shrink-0">
                        <a href="{{ route('announcements.show', $announcement->slug) }}" class="text-decoration-none announcement-title fw-bold">
                            {{ $announcement->title }}
                        </a>
                        <span class="d-none d-md-inline announcement-excerpt ms-2">
                             {{ Str::limit(strip_tags($announcement->content), 80) }}
                        </span>
                        @if($announcements->count() > 1 && !$loop->last)
                            <span class="mx-4 text-primary opacity-50">&bull;</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    .elegant-announcement-bar {
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); /* Biru sangat muda dan bersih */
        border-top: 3px solid #0284c7; /* Garis aksen biru laut */
        border-bottom: 1px solid rgba(14, 165, 233, 0.1);
        font-family: 'Poppins', sans-serif;
        font-size: 1.1rem; /* Ukuran font lebih besar */
        transition: all 10.5s ease;
        z-index: 50; /* Z-index tinggi agar di atas slider */
        position: relative;
        overflow: hidden;
    }
    .announcement-badge {
        background: linear-gradient(135deg, #006316, #849A3B); /* Merah ruby mewah */
        color: white;
        font-weight: 600;
        letter-spacing: 1px;
        font-size: 0.9rem; /* Badge lebih besar */
        text-transform: uppercase;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .announcement-title {
        color: #006316; /* Warna teks sangat gelap dan tegas */
        transition: color 0.2s;
        font-size: 1.1rem;
    }
    .announcement-title:hover {
        color: #849A3B;
        text-decoration: underline !important;
    }
    .announcement-excerpt {
        color: #000000 !important;
        font-weight: 600;
        font-size: 1.1rem;
    }

    /* Marquee Animation Constraints */
    .marquee-content {
        animation: scrollText 45s linear infinite;
        white-space: nowrap;
        width: max-content;
    }
    .announcement-content-wrapper:hover .marquee-content {
        animation-play-state: paused;
    }

    @keyframes scrollText {
        0% { transform: translateX(50%); }
        100% { transform: translateX(-100%); }
    }

    /* Dark Mode Adjustments */
    .dark .elegant-announcement-bar {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-top: 3px solid #38bdf8;
        border-bottom: 1px solid rgba(147, 197, 253, 0.1);
    }
    .dark .announcement-badge {
        background: linear-gradient(135deg, #f43f5e, #e11d48);
    }
    .dark .announcement-title {
        color: #f8fafc;
    }
    .dark .announcement-title:hover {
        color: #7dd3fc;
    }
    .dark .announcement-excerpt {
        color: #94a3b8 !important;
    }
</style>
@endif
