@extends('layouts.frontend')

@push('title'){{ __('FAQs') }} - @endpush

@push('styles')
<style>
/* ── HERO ── */
.faq-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #1a56a0 100%);
    position: relative;
    overflow: hidden;
    padding: 120px 0 80px;
}
.faq-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-deco {
    position: absolute; right: 0; top: 50%;
    transform: translateY(-50%);
    font-size: 18rem;
    opacity: .04;
    color: #fff;
    pointer-events: none;
}

/* ── FAQ STYLES ── */
.faq-container {
    padding: 4rem 0;
    background: #f8fafc;
}
.faq-accordion .card {
    border: none;
    border-radius: 12px;
    margin-bottom: 1rem;
    box-shadow: 0 4px 20px rgba(0,0,0,.04);
}
.faq-accordion .card-header {
    background: #fff;
    border-radius: 12px !important;
    border: none;
    padding: 0;
}
.faq-accordion .btn-link {
    width: 100%;
    text-align: left;
    padding: 1.25rem 1.5rem;
    color: #1e293b;
    font-weight: 600;
    font-size: 1.1rem;
    text-decoration: none;
    position: relative;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.faq-accordion .btn-link:focus { box-shadow: none; }
.faq-accordion .btn-link[aria-expanded="true"] { color: #1a56a0; }
.faq-accordion .btn-link .icon {
    font-size: 1.25rem;
    color: #64748b;
    transition: transform .3s;
}
.faq-accordion .btn-link[aria-expanded="true"] .icon {
    transform: rotate(180deg);
    color: #1a56a0;
}
.faq-accordion .card-body {
    padding: 0 1.5rem 1.5rem;
    color: #475569;
    line-height: 1.7;
}

</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<div class="page-header-premium py-5 text-white position-relative overflow-hidden">
    <div class="page-header-pattern"></div>
    <div class="container py-4 position-relative z-1">
        <div class="row align-items-center">
            <div class="col-lg-8" data-aos="fade-up">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2 text-white-50">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50">{{ __('Beranda') }}</a></li>
                        <li class="breadcrumb-item active text-white" aria-current="page">{{ __('FAQs') }}</li>
                    </ol>
                </nav>
                <h1 class="display-4 fw-bold mb-0">Frequently Asked Questions</h1>
                <p class="lead mt-3">{{ __('Pertanyaan yang sering diajukan seputar program studi kami.') }}</p>
            </div>
        </div>
    </div>
    <div class="page-header-logo">
        @php 
            $logoWhite = \App\Models\Setting::get('site_logo_white');
            $logoMain = \App\Models\Setting::get('site_logo');
            $displayLogo = (!$logoWhite || $logoWhite == 'images/logo_white.png') ? $logoMain : $logoWhite;
        @endphp
        @if($displayLogo)
            <img src="{{ asset('storage/'.$displayLogo) }}" alt="Logo">
        @endif
    </div>
</div>

{{-- ── FAQs ── --}}
<section class="faq-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8" data-aos="fade-up">
                @if($faqs->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-question-circle fa-4x text-muted mb-3" style="opacity: .2;"></i>
                        <h4 class="text-muted">Belum ada FAQ yang tersedia.</h4>
                    </div>
                @else
                    <div class="accordion faq-accordion" id="faqAccordion">
                        @foreach($faqs as $faq)
                            <div class="card">
                                <div class="card-header" id="faqHeading{{ $faq->id }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link {{ $loop->first ? '' : 'collapsed' }}" type="button" data-toggle="collapse" data-target="#faqCollapse{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faqCollapse{{ $faq->id }}">
                                            {{ $faq->question }}
                                            <i class="fas fa-chevron-down icon"></i>
                                        </button>
                                    </h2>
                                </div>

                                <div id="faqCollapse{{ $faq->id }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="faqHeading{{ $faq->id }}" data-parent="#faqAccordion">
                                    <div class="card-body">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
