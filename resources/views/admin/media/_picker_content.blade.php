<div class="row no-gutters">
    @forelse($media as $item)
        <div class="col-lg-2 col-md-3 col-sm-4 col-6 p-1">
            <div class="card h-100 media-picker-item border-transparent shadow-none m-0" 
                 data-url="{{ $item->url }}" 
                 data-id="{{ $item->id }}" 
                 data-path="{{ $item->path }}"
                 style="cursor: pointer;">
                <div class="media-preview text-center bg-light d-flex align-items-center justify-content-center position-relative" style="aspect-ratio: 1/1; overflow: hidden; border: 1px solid #ddd;">
                    @if(str_starts_with($item->mime_type, 'image/'))
                        <img src="{{ $item->url }}" class="img-fluid" style="object-fit: cover; width: 100%; height: 100%;">
                    @elseif($item->mime_type === 'application/pdf')
                        <i class="fas fa-file-pdf fa-3x text-danger"></i>
                    @else
                        <i class="fas fa-file fa-3x text-secondary"></i>
                    @endif
                </div>
                <div class="card-footer p-1 bg-white border-0 text-center">
                    <div class="text-truncate small text-muted" title="{{ $item->original_name }}" style="font-size: 0.65rem;">
                        {{ $item->original_name }}
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="fas fa-search fa-3x text-light mb-3"></i>
            <p class="text-muted">Tidak ada file yang ditemukan di Pustaka Media Anda.</p>
        </div>
    @endforelse
</div>

<div class="d-flex justify-content-center mt-2" id="picker-pagination">
    {{ $media->links() }}
</div>
