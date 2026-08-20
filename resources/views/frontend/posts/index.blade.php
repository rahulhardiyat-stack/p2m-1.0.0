@extends('layouts.frontend')

@push('title'){{ __('menu.news_and_articles') }} - @endpush

@section('content')
    {{-- Page Header --}}
    @php
        $pageTitle = __('menu.news_and_articles');
        if(request('category')) {
            $selectedCategory = $categories->firstWhere('slug', request('category')) ?? $categories->firstWhere('id', request('category'));
            if($selectedCategory) $pageTitle = __('menu.category') . ': ' . $selectedCategory->name;
        } elseif(request('tag')) {
            $pageTitle = 'Tag: ' . request('tag');
        } elseif(request('search')) {
            $pageTitle = __('menu.search_results') . ': ' . request('search');
        }
    @endphp
    <section class="page-header-premium mb-5">
        <div class="page-header-pattern"></div>
        <div class="page-header-logo">
            <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}" alt="Logo">
        </div>
        <div class="container position-relative z-10">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb" class="mb-3" data-aos="fade-down">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
                            @if($pageTitle !== __('menu.news_and_articles'))
                                <li class="breadcrumb-item"><a href="{{ route('posts.index') }}">{{ __('menu.news_and_articles') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">{{ __('menu.news_and_articles') }}</li>
                            @endif
                        </ol>
                    </nav>
                    <h1 class="display-4 fw-bold text-white mb-0" data-aos="fade-right">
                        {{ $pageTitle }}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="container pb-5">
        <div class="row g-4">
            
            {{-- Main Content --}}
            <div class="col-lg-8">
                
                {{-- Featured Post --}}
                @if(request('page') <= 1 && !request('category') && !request('tag') && !request('search') && $featuredPosts->count() > 0)
                    @php $featured = $featuredPosts->first(); @endphp
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-5" data-aos="fade-up">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <img src="{{ $featured->featured_image_url }}" alt="{{ $featured->title }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="card-body p-4">
                                    <span class="badge bg-danger rounded-pill mb-3 px-3">Featured</span>
                                    @if($featured->category)
                                        <span class="badge rounded-pill mb-3" style="background-color: {{ $featured->category->color }}">{{ $featured->category->name }}</span>
                                    @endif
                                    <h2 class="h3 fw-bold mb-3">
                                        <a href="{{ route('posts.show', $featured->slug) }}" class="text-dark text-decoration-none">
                                            {{ $featured->title }}
                                        </a>
                                    </h2>
                                    <p class="text-muted mb-4">{{ Str::limit($featured->excerpt ?? strip_tags($featured->content), 150) }}</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <small class="text-muted"><i class="fas fa-calendar-alt me-2"></i>{{ $featured->published_at?->isoFormat('D MMMM YYYY') }}</small>
                                        <a href="{{ route('posts.show', $featured->slug) }}" class="btn btn-primary rounded-pill px-4">
                                            {{ __('menu.read_more') }} <i class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Posts Grid --}}
                <div class="row g-4 mb-5">
                    @forelse($posts as $post)
                        <div class="col-md-6" data-aos="fade-up">
                            <div class="card post-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="position-relative" style="height: 200px;">
                                    <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                    @if($post->category)
                                        <div class="position-absolute bottom-0 start-0 m-3">
                                            <span class="badge rounded-pill" style="background-color: {{ $post->category->color }}">{{ $post->category->name }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body p-4">
                                    <h5 class="card-title fw-bold mb-2">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-dark text-decoration-none">
                                            {{ Str::limit($post->title, 60) }}
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small mb-3">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}</p>
                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                                        <small class="text-muted"><i class="fas fa-clock me-1"></i> {{ $post->published_at?->diffForHumans() }}</small>
                                        <small class="text-muted"><i class="fas fa-eye me-1"></i> {{ number_format($post->views) }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-search fa-4x text-muted opacity-25 mb-3"></i>
                            <h3 class="text-muted">{{ __('menu.no_news') }}</h3>
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary mt-3 rounded-pill">{{ __('menu.see_all_news') }}</a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
                    {{ $posts->links() }}
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="sidebar position-sticky" style="top: 100px;">
                    
                    {{-- Search --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" data-aos="fade-left">
                        <h5 class="fw-bold mb-3">{{ __('menu.search') }}</h5>
                        <form action="{{ route('posts.index') }}" method="GET">
                            {{-- Preserve other filters --}}
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request('tag')) <input type="hidden" name="tag" value="{{ request('tag') }}"> @endif
                            @if(request('sdg')) <input type="hidden" name="sdg" value="{{ request('sdg') }}"> @endif
                            
                            <div class="input-group">
                                <input type="text" name="search" class="form-control rounded-start-pill border-end-0" placeholder="Cari artikel..." value="{{ request('search') }}">
                                <button class="btn btn-primary rounded-end-pill px-4" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- SDGs Filter --}}
                    @php
                        $sdgList = [
                            1 => 'No Poverty', 2 => 'Zero Hunger', 3 => 'Good Health and Well-being',
                            4 => 'Quality Education', 5 => 'Gender Equality', 6 => 'Clean Water and Sanitation',
                            7 => 'Affordable and Clean Energy', 8 => 'Decent Work and Economic Growth',
                            9 => 'Industry, Innovation and Infrastructure', 10 => 'Reduced Inequality',
                            11 => 'Sustainable Cities and Communities', 12 => 'Responsible Consumption and Production',
                            13 => 'Climate Action', 14 => 'Life Below Water', 15 => 'Life on Land',
                            16 => 'Peace and Justice Strong Institutions', 17 => 'Partnerships to achieve the Goal'
                        ];
                    @endphp
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" data-aos="fade-left">
                        <h5 class="fw-bold mb-3">Filter SDGs</h5>
                        <form action="{{ route('posts.index') }}" method="GET" id="sdgFilterForm">
                            {{-- Preserve other filters --}}
                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                            @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                            @if(request('tag')) <input type="hidden" name="tag" value="{{ request('tag') }}"> @endif

                            <select name="sdg" class="form-select rounded-pill" onchange="document.getElementById('sdgFilterForm').submit()">
                                <option value="">Semua SDGs</option>
                                @foreach($sdgList as $num => $desc)
                                    @php $sdgLabel = "SDGs $num - $desc"; @endphp
                                    <option value="{{ $sdgLabel }}" {{ request('sdg') == $sdgLabel ? 'selected' : '' }}>
                                        {{ $sdgLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    {{-- Categories --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" data-aos="fade-left" data-aos-delay="100">
                        <h5 class="fw-bold mb-3">{{ __('menu.category') }}</h5>
                        <div class="list-group list-group-flush">
                            @foreach($categories as $cat)
                                <a href="{{ route('posts.index', array_filter(['category' => $cat->slug, 'search' => request('search'), 'sdg' => request('sdg')])) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center border-0 px-0 {{ (request('category') == $cat->id || request('category') == $cat->slug) ? 'text-primary fw-bold' : '' }}">
                                    {{ $cat->name }}
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">{{ $cat->posts_count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Popular Posts --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" data-aos="fade-left" data-aos-delay="200">
                        <h5 class="fw-bold mb-3">{{ __('menu.popular_posts') }}</h5>
                        <div class="popular-posts">
                            @foreach($featuredPosts->take(4) as $popular)
                                <div class="d-flex gap-3 mb-3">
                                    <img src="{{ $popular->featured_image_url }}" alt="" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-1 fw-bold" style="font-size: .9rem;">
                                            <a href="{{ route('posts.show', $popular->slug) }}" class="text-dark text-decoration-none">
                                                {{ Str::limit($popular->title, 45) }}
                                            </a>
                                        </h6>
                                        <small class="text-muted d-block" style="font-size: .75rem;">
                                            <i class="fas fa-calendar-alt me-1"></i> {{ $popular->published_at?->format('d M Y') }}
                                        </small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="card border-0 shadow-sm rounded-4 p-4" data-aos="fade-left" data-aos-delay="300">
                        <h5 class="fw-bold mb-3">Tags</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <a href="{{ route('posts.index', array_filter(['tag' => $tag->slug, 'search' => request('search'), 'sdg' => request('sdg')])) }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 {{ (request('tag') == $tag->id || request('tag') == $tag->slug) ? 'active' : '' }}">
                                    #{{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
