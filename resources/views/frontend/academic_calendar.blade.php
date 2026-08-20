@extends('layouts.frontend')

@push('title')
{{ __('menu.calendar') }} -
@endpush

@section('content')
<section class="page-header-premium mb-0">
    <div class="page-header-pattern"></div>
    <div class="page-header-logo">
        <img src="{{ asset('storage/' . \App\Models\Setting::get('site_logo')) }}" alt="Logo">
    </div>
    <div class="container position-relative z-10 py-5">
        <div class="row align-items-center">
            <div class="col-lg-12 text-center" data-aos="fade-up">
                <nav aria-label="breadcrumb" class="d-flex justify-content-center mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('menu.home') }}</a></li>
                        <li class="breadcrumb-item active text-white-50" aria-current="page">{{ __('menu.calendar') }}</li>
                    </ol>
                </nav>
                <h1 class="display-3 fw-bold text-white mb-3">
                    {{ __('menu.calendar') }}
                </h1>
                <p class="lead text-white-50 mx-auto" style="max-width: 800px;">
                    {{ __('Informasi lengkap mengenai jadwal kegiatan akademik, pendaftaran, perkuliahan, hingga ujian dalam satu tahun akademik.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        @forelse($calendars as $year => $semesters)
            <div class="mb-5" data-aos="fade-up">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary text-white rounded-4 px-4 py-2 shadow-sm me-3">
                        <h4 class="mb-0 fw-bold">TA {{ $year }}</h4>
                    </div>
                    <div class="flex-grow-1 border-bottom" style="height: 1px; opacity: 0.1;"></div>
                </div>

                <div class="row g-4">
                    @foreach($semesters as $semesterName => $items)
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 transition-hover">
                                <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0 fw-bold text-dark">Semester {{ $semesterName }}</h5>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                                        {{ $items->count() }} Kegiatan
                                    </span>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @foreach($items as $item)
                                            <li class="list-group-item py-3 px-4 border-0 border-bottom">
                                                <div class="d-flex">
                                                    <div class="calendar-date-box text-center me-3" style="min-width: 70px;">
                                                        <div class="small fw-bold text-muted text-uppercase">{{ $item->start_date->translatedFormat('M') }}</div>
                                                        <div class="h3 mb-0 fw-bold text-primary">{{ $item->start_date->format('d') }}</div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold text-dark mb-1">{{ $item->title }}</h6>
                                                        <p class="small text-secondary mb-2">
                                                            @if($item->end_date && $item->start_date != $item->end_date)
                                                                <i class="far fa-clock me-1"></i> {{ $item->start_date->translatedFormat('d M Y') }} - {{ $item->end_date->translatedFormat('d M Y') }}
                                                            @else
                                                                <i class="far fa-calendar-check me-1"></i> {{ $item->start_date->translatedFormat('d M Y') }}
                                                            @endif
                                                        </p>
                                                        @if($item->description)
                                                            <div class="small text-muted">{{ $item->description }}</div>
                                                        @endif
                                                    </div>
                                                    @if($item->color)
                                                        <div class="ms-2" style="width: 4px; border-radius: 4px; background-color: {{ $item->color }}; opacity: 0.6;"></div>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="row justify-content-center py-5">
                <div class="col-lg-6 text-center" data-aos="fade-up">
                    <div class="mb-4">
                        <i class="fas fa-calendar-times fa-4x text-muted opacity-25"></i>
                    </div>
                    <h4 class="text-secondary fw-bold">Belum Ada Data Kalender</h4>
                    <p class="text-muted">Kalender akademik untuk periode ini sedang dalam tahap penyusunan. Silakan cek kembali nanti.</p>
                </div>
            </div>
        @endforelse

        <div class="row mt-5" data-aos="fade-up">
            <div class="col-lg-12">
                <div class="card border-0 shadow-lg rounded-4 p-4 p-lg-5 bg-navy-gradient text-white">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h3 class="fw-bold mb-3">Versi Cetak Kalender Akademik</h3>
                            <p class="opacity-75 mb-0">Butuh salinan fisik kalender akademik? Anda dapat mengunduh versi PDF resmi yang telah ditandatangani di sini.</p>
                        </div>
                        <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
                            @php $calendarFile = \App\Models\Setting::get('academic_calendar_pdf') @endphp
                            @if($calendarFile)
                                <a href="{{ asset('storage/'.$calendarFile) }}" target="_blank" class="btn btn-gold btn-lg rounded-pill px-5 shadow animate-pulse">
                                    <i class="fas fa-file-pdf me-2"></i> Download PDF
                                </a>
                            @else
                                <span class="badge bg-white text-dark py-3 px-4 rounded-pill opacity-50">Belum Tersedia</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .bg-primary-subtle { background-color: rgba(0, 180, 216, 0.1); }
    .border-primary-subtle { border-color: rgba(0, 180, 216, 0.2) !important; }
    
    .bg-navy-gradient {
        background: linear-gradient(135deg, #0a192f 0%, #112240 100%);
    }

    .calendar-date-box {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 10px;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .transition-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.1) !important;
    }

    .btn-gold {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        color: #0c1c38;
        border: none;
        font-weight: 700;
    }
    
    .btn-gold:hover {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: #0c1c38;
        transform: translateY(-2px);
    }

    .animate-pulse {
        animation: pulse shadow 2s infinite;
    }

    @keyframes pulse-shadow {
        0% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0.7); }
        70% { box-shadow: 0 0 0 15px rgba(251, 191, 36, 0); }
        100% { box-shadow: 0 0 0 0 rgba(251, 191, 36, 0); }
    }
</style>
@endpush
