@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@php( $siteLogo = \App\Models\Setting::get('site_logo') )
@php( $siteName = \App\Models\Setting::get('site_name', env('APP_NAME', 'Prodi')) )
@php( $siteAbbr = \App\Models\Setting::get('site_abbreviation', $siteName) )
@php( $siteLogoUrl = $siteLogo ? asset('storage/' . $siteLogo) : asset(config('adminlte.logo_img', 'vendor/adminlte/dist/img/AdminLTELogo.png')) )

<a href="{{ $dashboard_url }}"
    @if($layoutHelper->isLayoutTopnavEnabled())
        class="navbar-brand {{ config('adminlte.classes_brand') }}"
    @else
        class="brand-link {{ config('adminlte.classes_brand') }}"
    @endif>

    {{-- Small brand logo --}}
    <img src="{{ $siteLogoUrl }}"
         alt="{{ $siteName }}"
         class="{{ config('adminlte.logo_img_class', 'brand-image img-circle elevation-3') }}"
         style="opacity:.8">

    {{-- Brand text --}}
    <span class="brand-text font-weight-light {{ config('adminlte.classes_brand_text') }}">
        {!! $siteAbbr !!}
    </span>

</a>
