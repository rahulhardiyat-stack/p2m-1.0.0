@php
    $hasChildren = $item->children->count() > 0;
    $isActive = request()->url() == $item->resolved_url;
    $isRoot = ($depth ?? 0) === 0;
@endphp

@if($isRoot)
    @if($hasChildren)
        <li class="nav-item dropdown">
            <a class="nav-link main-nav-link dropdown-toggle px-3 {{ $isActive ? 'active text-primary' : '' }} {{ $item->css_class }}"
               href="#"
               id="menuDrop{{ $item->id }}"
               data-bs-toggle="dropdown"
               aria-expanded="false">
                @if($item->icon) <i class="{{ $item->icon }} main-nav-icon me-1"></i> @endif
                {{ $item->label }}
            </a>
            <ul class="dropdown-menu shadow-lg border-0 elegant-dropdown" aria-labelledby="menuDrop{{ $item->id }}">
                @foreach($item->children as $child)
                    @include('frontend.partials.nav-menu-item', ['item' => $child, 'depth' => 1])
                @endforeach
            </ul>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link main-nav-link px-3 {{ $isActive ? 'active text-primary' : '' }} {{ $item->css_class }}"
               href="{{ $item->resolved_url }}"
               target="{{ $item->target }}">
                @if($item->icon) <i class="{{ $item->icon }} main-nav-icon me-1"></i> @endif
                {{ $item->label }}
            </a>
        </li>
    @endif
@else
    @if($hasChildren)
        <li class="dropdown-submenu dropend">
            <a class="dropdown-item elegant-dropdown-item py-2 dropdown-toggle {{ $isActive ? 'active' : '' }} {{ $item->css_class }}"
               href="#"
               id="menuDrop{{ $item->id }}"
               data-menu-submenu-toggle="true"
               aria-expanded="false">
                @if($item->icon) <i class="{{ $item->icon }} me-2 text-primary"></i> @endif
                {{ $item->label }}
            </a>
            <ul class="dropdown-menu shadow-lg border-0 elegant-dropdown" aria-labelledby="menuDrop{{ $item->id }}">
                @foreach($item->children as $child)
                    @include('frontend.partials.nav-menu-item', ['item' => $child, 'depth' => $depth + 1])
                @endforeach
            </ul>
        </li>
    @else
        <li>
            <a class="dropdown-item elegant-dropdown-item py-2 {{ $isActive ? 'active' : '' }} {{ $item->css_class }}"
               href="{{ $item->resolved_url }}"
               target="{{ $item->target }}">
                @if($item->icon) <i class="{{ $item->icon }} me-2 text-primary"></i> @endif
                {{ $item->label }}
            </a>
        </li>
    @endif
@endif
