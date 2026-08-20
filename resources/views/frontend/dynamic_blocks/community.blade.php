@php
    $posts = \App\Models\Post::with(['category', 'user'])
        ->published()
        ->byType('community')
        ->latest('published_at')
        ->limit(6)
        ->get();
@endphp

<div class="dynamic-block-container community-block">
    @if($posts->count() > 0)
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        <div class="row g-0">
                            @if($post->featured_image)
                            <div class="col-4">
                                <img src="{{ asset('storage/'.$post->featured_image) }}" class="h-100 w-100" style="object-fit: cover;" alt="{{ $post->title }}">
                            </div>
                            @endif
                            <div class="{{ $post->featured_image ? 'col-8' : 'col-12' }}">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-2">
                                        <a href="{{ route('posts.show', $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                            {{ \Illuminate\Support\Str::limit($post->title, 60) }}
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 80) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted italic">Data pengabdian masyarakat belum tersedia.</p>
    @endif
</div>
