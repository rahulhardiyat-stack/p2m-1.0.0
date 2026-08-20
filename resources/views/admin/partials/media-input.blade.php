<div class="form-group media-input-wrapper" id="media-input-{{ $name }}">
    <label>{{ $label ?? 'Gambar' }}</label>
    <div class="input-group">
        <input type="text" name="{{ $name }}" class="form-control media-target" value="{{ $value ?? '' }}" id="input-{{ $name }}" placeholder="Pilih atau upload file...">
        <div class="input-group-append">
            <button class="btn btn-info btn-browse-media" type="button" data-input="#input-{{ $name }}" data-preview="#preview-{{ $name }}">
                <i class="fas fa-folder-open mr-1"></i> Pilih dari Galeri
            </button>
        </div>
    </div>
    <div class="mt-2 preview-container" id="preview-{{ $name }}">
        @if(!empty($value))
            @php 
                $url = str_starts_with($value, 'http') ? $value : asset('storage/' . $value);
            @endphp
            <div class="position-relative d-inline-block">
                <img src="{{ $url }}" class="img-thumbnail" style="max-height: 150px;">
                <button type="button" class="btn btn-danger btn-xs position-absolute btn-remove-media" style="top:-10px; right:-10px; border-radius: 50%;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @else
            <div class="text-muted small italic">Belum ada gambar dipilih.</div>
        @endif
    </div>
</div>
