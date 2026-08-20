<div class="card card-primary card-outline shadow-sm">
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            @foreach($groups as $k => $label)
                <li class="nav-item">
                    <a href="{{ $k == 'infographics' ? route('admin.infographics.index') : ($k == 'sponsor' ? route('admin.sponsors.index') : route('admin.settings.group', $k)) }}" class="nav-link {{ ($group ?? '') == $k || (request()->routeIs('admin.infographics.*') && $k == 'infographics') || (request()->routeIs('admin.sponsors.*') && $k == 'sponsor') ? 'active' : '' }}">
                        @if($k == 'general') <i class="fas fa-wrench fa-fw me-2"></i>
                        @elseif($k == 'profile') <i class="fas fa-university fa-fw me-2"></i>
                        @elseif($k == 'contact') <i class="fas fa-phone fa-fw me-2"></i>
                        @elseif($k == 'social') <i class="fas fa-share-alt fa-fw me-2"></i>
                        @elseif($k == 'seo') <i class="fas fa-search fa-fw me-2"></i>
                        @elseif($k == 'appearance') <i class="fas fa-palette fa-fw me-2"></i>
                        @elseif($k == 'infographics') <i class="fas fa-chart-pie fa-fw me-2"></i>
                        @elseif($k == 'sponsor') <i class="fas fa-hand-holding-heart fa-fw me-2"></i>
                        @else <i class="fas fa-cog fa-fw me-2"></i>
                        @endif
                        {{ $label }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>
