@extends('layouts.frontend')

@push('title')
{{ __('Hubungi Kami') }} -
@endpush

@section('content')
<div class="page-header-premium py-5 text-white position-relative overflow-hidden">
    <div class="page-header-pattern"></div>
    <div class="container py-4 position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2 text-white-50">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">{{ __('Beranda') }}</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ __('Hubungi Kami') }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold mb-0">{{ __('Hubungi Kami') }}</h1>
                <p class="lead mb-0 text-white-50 mt-2">{{ __('Kami siap melayani pertanyaan dan masukan Anda.') }}</p>
            </div>
        </div>
    </div>
    <div class="page-header-logo">
        @php $logo = \App\Models\Setting::get('site_logo') @endphp
        @if($logo)
            <img src="{{ asset('storage/'.$logo) }}" alt="Logo">
        @endif
    </div>
</div>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Info -->
            <div class="col-lg-5" data-aos="fade-right">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 h-100">
                    <h3 class="fw-bold mb-4 text-primary">{{ __('Informasi Kontak') }}</h3>
                    
                    <div class="d-flex mb-4">
                        <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                            <i class="fas fa-map-marker-alt fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ __('Alamat Kantor') }}</h6>
                            <p class="text-secondary small mb-0">{{ \App\Models\Setting::get('contact_address', 'Jl. Universitas No. 1, Kota') }}</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                            <i class="fas fa-phone-alt fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ __('Telepon / WhatsApp') }}</h6>
                            <p class="text-secondary small mb-0">{{ \App\Models\Setting::get('contact_phone', '+62 812 3456 7890') }}</p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <div class="bg-primary-subtle text-primary p-3 rounded-4 me-3">
                            <i class="fas fa-envelope fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">{{ __('Email Resmi') }}</h6>
                            <p class="text-secondary small mb-0">{{ \App\Models\Setting::get('contact_email', 'prodi@university.ac.id') }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="fw-bold mb-3">{{ __('Ikuti Kami') }}</h5>
                    <div class="d-flex gap-2">
                        @foreach([
                            'facebook' => 'fab fa-facebook-f', 
                            'instagram' => 'fab fa-instagram', 
                            'twitter' => 'fab fa-x-twitter', 
                            'youtube' => 'fab fa-youtube', 
                            'tiktok' => 'fab fa-tiktok',
                            'linkedin' => 'fab fa-linkedin-in'
                        ] as $key => $icon)
                            @php $url = \App\Models\Setting::get("social_{$key}") @endphp
                            @if($url)
                            <a href="{{ $url }}" class="btn btn-outline-primary rounded-circle" target="_blank" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;"><i class="{{ $icon }}"></i></a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-left">
                <div class="card border-0 shadow-sm rounded-4 p-4 p-lg-5 h-100">
                    <h3 class="fw-bold mb-4">{{ __('Kirim Pesan') }}</h3>
                    
                    @if(session('success'))
                    <div class="alert alert-success border-0 rounded-4 p-3 mb-4 shadow-sm">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">{{ __('Nama Lengkap') }}</label>
                                <input type="text" name="name" class="form-control rounded-3 py-2 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('Nama Anda') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">{{ __('Alamat Email') }}</label>
                                <input type="email" name="email" class="form-control rounded-3 py-2 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@example.com" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">{{ __('Subjek') }}</label>
                                <input type="text" name="subject" class="form-control rounded-3 py-2 @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="{{ __('Tujuan pesan') }}" required>
                                @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">{{ __('Pesan') }}</label>
                                <textarea name="message" class="form-control rounded-3 py-2 @error('message') is-invalid @enderror" rows="5" placeholder="{{ __('Tuliskan pesan Anda di sini...') }}" required>{{ old('message') }}</textarea>
                                @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold shadow-sm">
                                    {{ __('Kirim Sekarang') }} <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if($map = \App\Models\Setting::get('contact_maps'))
        <div class="row mt-5" data-aos="fade-up">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="min-height: 400px;">
                    <style>
                        iframe { width: 100% !important; height: 100% !important; min-height: 400px; border: 0; }
                    </style>
                    {!! $map !!}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('styles')
<style>
    .rotate-n-15 { transform: rotate(-15deg); }
    .bg-primary-subtle { background-color: rgba(12, 121, 14, 0.1); }
    .form-control:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.25rem rgba(12, 121, 14, 0.1);
    }
</style>
@endpush
