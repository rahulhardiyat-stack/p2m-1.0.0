@php
    $vision = \App\Models\Setting::get('vision', '');
    $mission = \App\Models\Setting::get('mission', '');
    $aboutInstitution = \App\Models\Setting::get('about_institution', '');
@endphp

<div class="dynamic-block-container profile-block">
    @if($aboutInstitution)
        <div class="about-section mb-4">
            <h4 class="fw-bold text-primary mb-3">Tentang Prodi</h4>
            <p class="text-secondary lh-lg" style="text-align:justify;">
                {!! nl2br(e($aboutInstitution)) !!}
            </p>
        </div>
    @endif

    <div class="row g-4 mt-2">
        @if($vision)
        <div class="col-md-6">
            <div class="p-4 bg-light rounded-4 h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-eye me-2 text-primary"></i> Visi</h5>
                <p class="text-secondary mb-0">{!! nl2br(e($vision)) !!}</p>
            </div>
        </div>
        @endif
        @if($mission)
        <div class="col-md-6">
            <div class="p-4 bg-light rounded-4 h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-bullseye me-2 text-primary"></i> Misi</h5>
                <div class="text-secondary mb-0">{!! nl2br(e($mission)) !!}</div>
            </div>
        </div>
        @endif
    </div>
</div>
