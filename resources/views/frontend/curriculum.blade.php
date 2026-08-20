@extends('layouts.frontend')

@push('title')
{{ __('menu.curriculum') }} -
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
                        <li class="breadcrumb-item active text-white-50" aria-current="page">{{ __('menu.curriculum') }}</li>
                    </ol>
                </nav>
                <h1 class="display-3 fw-bold text-white mb-3">
                    {{ __('menu.curriculum') }}
                </h1>
                <p class="lead text-white-50 mx-auto" style="max-width: 800px;">
                    {{ __('Struktur kurikulum dirancang untuk membekali mahasiswa dengan kompetensi yang relevan dengan kebutuhan industri dan perkembangan teknologi terkini.') }}
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center" data-aos="fade-up">
                <div class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 mb-3">
                    <i class="fas fa-book-open me-1"></i> Sebaran Mata Kuliah
                </div>
                <h2 class="fw-bold text-dark mb-4">Struktur Mata Kuliah Per Semester</h2>
                <p class="text-secondary">Klik pada tab semester untuk melihat daftar mata kuliah yang ditawarkan.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-12" data-aos="fade-up">
                {{-- Semester Tabs --}}
                <ul class="nav nav-pills nav-fill mb-4 p-2 bg-white rounded-pill shadow-sm border" id="curriculumTabs" role="tablist">
                    @foreach($curriculums as $semester => $items)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold py-3 {{ $loop->first ? 'active' : '' }}" 
                                    id="semester-{{ $semester }}-tab" 
                                    data-bs-toggle="pill" 
                                    data-bs-target="#semester-{{ $semester }}" 
                                    type="button" role="tab">
                                SEMESTER {{ $semester }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content" id="curriculumTabsContent">
                    @foreach($curriculums as $semester => $items)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                             id="semester-{{ $semester }}" 
                             role="tabpanel">
                            
                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-primary text-white">
                                            <tr>
                                                <th class="ps-4 py-4" style="width: 100px;">Kode</th>
                                                <th class="py-4">Mata Kuliah</th>
                                                <th class="py-4 text-center" style="width: 80px;">SKS</th>
                                                <th class="py-4">Tipe</th>
                                                <th class="py-4">Konsentrasi</th>
                                                <th class="pe-4 py-4 text-center" style="width: 150px;">RPS / Silabus</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $course)
                                                <tr>
                                                    <td class="ps-4 py-3 fw-bold text-primary">{{ $course->code }}</td>
                                                    <td class="py-3">
                                                        <div class="fw-bold text-dark">{{ $course->name }}</div>
                                                        @if($course->description)
                                                            <small class="text-muted d-block mt-1">{{ Str::limit($course->description, 100) }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="py-3 text-center fw-bold">{{ $course->credits }}</td>
                                                    <td class="py-3 text-capitalize">
                                                        <span class="badge {{ $course->type == 'wajib' ? 'bg-info' : 'bg-secondary' }} rounded-pill px-3 py-2">
                                                            {{ $course->type }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3">
                                                        {{ $course->concentration ?: '-' }}
                                                    </td>
                                                    <td class="pe-4 py-3 text-center">
                                                        @if($course->rps_file)
                                                            <a href="{{ asset('storage/' . $course->rps_file) }}" target="_blank" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                                                <i class="fas fa-download me-1"></i> PDF
                                                            </a>
                                                        @else
                                                            <span class="text-muted small italic">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light">
                                            <tr>
                                                <td colspan="2" class="ps-4 py-3 fw-bold text-dark">Total SKS Semester {{ $semester }}</td>
                                                <td class="py-3 text-center fw-bold text-primary" style="font-size: 1.1rem;">
                                                    {{ $items->sum('credits') }}
                                                </td>
                                                <td colspan="3"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-lg-12 text-center" data-aos="fade-up">
                <div class="card border-0 shadow-lg rounded-4 p-5 bg-navy-gradient text-white position-relative overflow-hidden">
                    <div class="position-absolute top-0 end-0 p-4 opacity-10">
                        <i class="fas fa-graduation-cap fa-10x"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Butuh Informasi Lebih Lanjut?</h3>
                    <p class="lead mb-4 opacity-75">Detail mengenai mata kuliah prasyarat, kompetensi lulusan, dan panduan akademik lengkap dapat dilihat di Buku Panduan Akademik.</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('academic') }}" class="btn btn-gold btn-lg rounded-pill px-5 shadow">
                            <i class="fas fa-book me-2"></i> Panduan Akademik
                        </a>
                        <a href="{{ route('contact.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-5">
                            Hubungi Prodi
                        </a>
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

    #curriculumTabs .nav-link {
        color: #64748b;
        border: none;
        transition: all 0.3s ease;
    }

    #curriculumTabs .nav-link.active {
        background: linear-gradient(135deg, #0056b3 0%, #00b4d8 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(0, 180, 216, 0.3);
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.85rem;
        border: none;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 86, 179, 0.02) !important;
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
</style>
@endpush
