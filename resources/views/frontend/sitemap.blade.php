<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url><loc>{{ route('about') }}</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('academic') }}</loc><changefreq>monthly</changefreq><priority>0.8</priority></url>
    <url><loc>{{ route('lecturers.index') }}</loc><changefreq>monthly</changefreq><priority>0.7</priority></url>
    <url><loc>{{ route('posts.index') }}</loc><changefreq>daily</changefreq><priority>0.9</priority></url>
    <url><loc>{{ route('events.index') }}</loc><changefreq>weekly</changefreq><priority>0.7</priority></url>
    <url><loc>{{ route('gallery.index') }}</loc><changefreq>weekly</changefreq><priority>0.6</priority></url>
    <url><loc>{{ route('contact.index') }}</loc><changefreq>yearly</changefreq><priority>0.5</priority></url>

    {{-- Posts --}}
    @foreach($posts as $post)
    <url>
        <loc>{{ route('posts.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach

    {{-- Events --}}
    @foreach($events as $event)
    <url>
        <loc>{{ route('events.show', $event->slug) }}</loc>
        <lastmod>{{ $event->updated_at->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach
</urlset>
