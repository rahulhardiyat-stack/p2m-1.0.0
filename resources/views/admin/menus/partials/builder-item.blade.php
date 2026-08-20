@foreach($items as $item)
    <li class="dd-item dd3-item" 
        data-id="{{ $item->id }}" 
        data-label="{{ $item->label }}" 
        data-url="{{ $item->url }}" 
        data-route_name="{{ $item->route_name }}" 
        data-icon="{{ $item->icon }}" 
        data-target="{{ $item->target }}">
        
        <div class="dd-handle dd3-handle"><i class="fas fa-arrows-alt"></i></div>
        <div class="dd3-content">
            <span class="item-icon-preview mr-2">
                @if($item->icon)
                    <i class="{{ $item->icon }}"></i>
                @endif
            </span>
            <span class="item-label-preview font-weight-bold">{{ $item->label }}</span>
            <span class="item-url-preview text-muted small ml-2 float-right mt-1">
                @if($item->route_name)
                    Route: {{ $item->route_name }}
                @else
                    {{ $item->url }}
                @endif
            </span>
            
            <div class="float-right mr-3 item-actions mt-1">
                <!-- Edit Button -->
                <a href="javascript:void(0)" class="text-primary btn-edit-item mr-2" title="Edit Item">
                    <i class="fas fa-edit"></i>
                </a>
                <!-- Delete Button -->
                <a href="javascript:void(0)" class="text-danger btn-delete-item" title="Hapus Item">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </div>

        @if($item->children->count() > 0)
            <ol class="dd-list">
                @include('admin.menus.partials.builder-item', ['items' => $item->children])
            </ol>
        @endif
    </li>
@endforeach
