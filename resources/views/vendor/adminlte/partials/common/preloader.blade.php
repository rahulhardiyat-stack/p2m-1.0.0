@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

<div class="{{ $preloaderHelper->makePreloaderClasses() }}" style="{{ $preloaderHelper->makePreloaderStyle() }}">

    @hasSection('preloader')

        {{-- Use a custom preloader content --}}
        @yield('preloader')

    @else

        {{-- Use the default preloader content --}}
        @php
            $siteLogo = \App\Models\Setting::get('site_logo');
            $siteName = \App\Models\Setting::get('site_name', env('APP_NAME', 'Prodi'));
            $siteLogoUrl = $siteLogo ? asset('storage/' . $siteLogo) : asset(config('adminlte.preloader.img.path', 'vendor/adminlte/dist/img/AdminLTELogo.png'));
        @endphp
        <img src="{{ $siteLogoUrl }}"
             class="img-circle {{ config('adminlte.preloader.img.effect', 'animation__shake') }}"
             alt="{{ $siteName }}"
             width="{{ config('adminlte.preloader.img.width', 60) }}"
             height="{{ config('adminlte.preloader.img.height', 60) }}"
             style="animation-iteration-count:infinite;">

    @endif

</div>
