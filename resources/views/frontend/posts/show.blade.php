@extends('layouts.frontend')

@push('title'){{ $post->title }} - @endpush

@section('content')
    {{-- Article Header --}}
    <section class="page-header-premium mb-5" style="background-image: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.65)), url('{{ $post->featured_image_url }}'); background-size: cover; background-position: center; border-bottom: none !important;">
        <div class="page-header-pattern"></div>
        <div class="page-header-logo">
            <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}" alt="Logo">
        </div>
        <div class="container position-relative z-10 py-4">
            <div class="text-center" data-aos="zoom-in">
                <nav aria-label="breadcrumb" class="mb-4 d-flex justify-content-center">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">{{ __('menu.news_and_articles') }}</a></li>
                        @if($post->category)
                            <li class="breadcrumb-item"><a href="{{ route('posts.index', ['category' => $post->category->slug]) }}">{{ $post->category->name }}</a></li>
                        @endif
                    </ol>
                </nav>
                
                @if($post->category)
                    <span class="badge-theme-pill mb-4 d-inline-block">
                        {{ $post->category->name }}
                    </span>
                @endif
                
                <h1 class="display-3 fw-bold text-white mb-4 letter-spacing-n1" style="line-height:1.1;">{{ $post->title }}</h1>
                
                <div class="article-meta-row d-flex justify-content-center align-items-center gap-4 flex-wrap text-white">
                    <div class="d-flex align-items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=D4AF37&color=fff" class="rounded-circle border border-white border-2" width="40">
                        <span class="fw-bold">{{ $post->user->name }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 opacity-90">
                        <i class="far fa-calendar-alt text-theme-accent"></i>
                        <span>{{ $post->published_at?->isoFormat('D MMMM YYYY') }}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 opacity-90">
                        <i class="far fa-eye text-theme-accent"></i>
                        <span>{{ number_format($post->views) }} {{ __('views') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container pb-5">
        <div class="row g-5">
            {{-- Main Content --}}
            <div class="col-12">
                <article class="article-content bg-white p-4 p-md-5 rounded-4 shadow-sm" data-aos="fade-up">


                    
                    {{-- Excerpt --}}
                    @if($post->excerpt)
                        <div class="lead fw-semibold text-muted mb-5 ps-4 border-start border-primary border-4">
                            {{ $post->excerpt }}
                        </div>
                    @endif

                    {{-- Body --}}
                    <div class="post-body lh-lg" style="font-size: 1.1rem; text-align: justify;">
                        @safeHtml($post->content)
                    </div>

                    {{-- Tags --}}
                    @if($post->tags->count() > 0)
                        <div class="mt-5 pt-4 border-top">
                            <h6 class="fw-bold mb-3"><i class="fas fa-tags me-2"></i>Topik Terkait:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($post->tags as $tag)
                                    <span class="badge bg-light text-dark border rounded-pill px-3 py-2">#{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- SDGs --}}
                    @if($post->sdgs && (!is_array($post->sdgs) ? json_decode($post->sdgs, true) : $post->sdgs))
                        @php
                            $sdgsArray = !is_array($post->sdgs) ? json_decode($post->sdgs, true) : $post->sdgs;
                        @endphp
                        @if(count($sdgsArray) > 0)
                            <div class="mt-4 pt-4 border-top">
                                <h6 class="fw-bold mb-3"><i class="fas fa-globe me-2 text-success"></i>Keterkaitan SDGs:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($sdgsArray as $sdg)
                                        <span class="badge bg-success bg-gradient bg-opacity-75 rounded-pill px-3 py-2" style="font-size: 0.85rem;"><i class="fas fa-leaf me-1"></i> {{ $sdg }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    {{-- Share --}}
                    @php
                        $shareUrl = request()->fullUrl();
                        $shareTitle = $post->title;
                        $shareText = $shareTitle . ' ' . $shareUrl;
                        $shareLinks = [
                            [
                                'label' => 'Facebook',
                                'icon' => 'fab fa-facebook-f',
                                'color' => 'text-primary',
                                'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($shareUrl),
                            ],
                            [
                                'label' => 'X',
                                'icon' => 'fab fa-x-twitter',
                                'color' => 'text-dark',
                                'url' => 'https://twitter.com/intent/tweet?url=' . urlencode($shareUrl) . '&text=' . urlencode($shareTitle),
                            ],
                            [
                                'label' => 'WhatsApp',
                                'icon' => 'fab fa-whatsapp',
                                'color' => 'text-success',
                                'url' => 'https://api.whatsapp.com/send?text=' . urlencode($shareText),
                            ],
                            [
                                'label' => 'Telegram',
                                'icon' => 'fab fa-telegram',
                                'color' => 'text-info',
                                'url' => 'https://t.me/share/url?url=' . urlencode($shareUrl) . '&text=' . urlencode($shareTitle),
                            ],
                            [
                                'label' => 'LinkedIn',
                                'icon' => 'fab fa-linkedin-in',
                                'color' => 'text-primary',
                                'url' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . urlencode($shareUrl),
                            ],
                        ];
                    @endphp
                    <div class="mt-5 p-4 bg-light rounded-4">
                        <span class="fw-bold d-block mb-3">Bagikan Artikel:</span>
                        <div class="share-buttons d-flex align-items-center flex-wrap gap-2">
                            @foreach($shareLinks as $share)
                                <a href="{{ $share['url'] }}" target="_blank" rel="noopener" class="btn btn-white border shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2" aria-label="Bagikan ke {{ $share['label'] }}" title="{{ $share['label'] }}">
                                    <i class="{{ $share['icon'] }} {{ $share['color'] }}"></i>
                                    <span class="fw-semibold">{{ $share['label'] }}</span>
                                </a>
                            @endforeach
                            <button type="button" class="btn btn-white border shadow-sm rounded-pill px-3 py-2 d-inline-flex align-items-center gap-2 copy-article-link" data-share-url="{{ $shareUrl }}">
                                <i class="fas fa-link text-secondary"></i>
                                <span class="fw-semibold">Salin Tautan</span>
                            </button>
                        </div>
                    </div>
                </article>

                {{-- Related Posts --}}
                @if($related->count() > 0)
                    <div class="related-posts mt-5" data-aos="fade-up">
                        <h4 class="fw-bold mb-4">{{ __('menu.news_and_articles') }} Lainnya</h4>
                        <div class="row g-4">
                            @foreach($related as $rel)
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                        <div style="height: 120px;">
                                            <img src="{{ $rel->featured_image_url }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                        </div>
                                        <div class="card-body p-3">
                                            <h6 class="fw-bold small mb-2">
                                                <a href="{{ route('posts.show', $rel->slug) }}" class="text-dark text-decoration-none">
                                                    {{ Str::limit($rel->title, 45) }}
                                                </a>
                                            </h6>
                                            <small class="text-muted d-block" style="font-size: .75rem;">
                                                <i class="fas fa-calendar-alt me-1"></i> {{ $rel->published_at?->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            </div>
        </div>
    </div>
@endsection

@push('css')
<style>
    .post-body {
        position: relative;
    }
    
    /* Pastikan seluruh elemen block di dalam artikel (termasuk visual grid/kustom) */
    /* tidak menabrak/slide ke bawah floated author/navigation card di layar desktop */
    @media (min-width: 992px) {
        .post-body > * {
            display: flow-root;
            clear: none;
        }
    }
    
    .post-body p:has(img), .post-body p > img {
        text-align: center;
        clear: both;
    }

    .post-body img, .post-body figure.image > img { 
        max-width: 100% !important; 
        height: auto !important; 
        object-fit: contain;
        border-radius: 1rem; 
        margin: 1.5rem auto !important; 
        display: block !important;
        float: none !important;
    }
    
    .post-body figure.image { 
        width: 100% !important; 
        margin: 1.5rem auto !important; 
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        float: none !important;
    }
    .post-body figure.image figcaption {
        text-align: center;
        color: #6c757d;
        font-size: 0.875em;
        margin-top: -0.5rem;
        margin-bottom: 1rem;
    }
    .post-body blockquote { 
        border-left: 5px solid #0d6efd; 
        padding: 1rem 2rem; 
        background: #f8f9fa; 
        font-style: italic; 
        border-radius: 0 1rem 1rem 0; 
    }
</style>
@endpush

@push('scripts')
<script>
document.querySelectorAll('.copy-article-link').forEach((button) => {
    button.addEventListener('click', async () => {
        const url = button.dataset.shareUrl;
        const label = button.querySelector('span');
        const originalText = label.textContent;

        try {
            await navigator.clipboard.writeText(url);
            label.textContent = 'Tautan Disalin';
            setTimeout(() => {
                label.textContent = originalText;
            }, 1800);
        } catch (error) {
            window.prompt('Salin tautan artikel:', url);
        }
    });
});
</script>
@endpush
