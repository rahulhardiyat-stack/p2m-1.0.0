@extends('layouts.frontend')

@push('title'){{ __('FAQs') }} - @endpush

@push('styles')
<style>
/* ── HERO ── */
.faq-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #1a56a0 100%);
    position: relative;
    overflow: hidden;
    padding: 120px 0 120px;
}
.faq-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero-deco {
    position: absolute; right: 0; top: 50%;
    transform: translateY(-50%);
    font-size: 20rem;
    opacity: .03;
    color: #fff;
    pointer-events: none;
}

/* ── FAQ CONTAINER ── */
.faq-container {
    padding: 0 0 5rem;
    background: #f8fafc;
    background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
    background-size: 24px 24px;
}

/* ── SEARCH AREA ── */
.faq-search-wrapper {
    position: relative;
    max-width: 650px;
    margin: -3.5rem auto 3rem;
    z-index: 10;
}
.faq-search-wrapper input {
    width: 100%;
    padding: 1.25rem 1.75rem 1.25rem 3.5rem;
    border-radius: 50px;
    border: 1px solid rgba(255,255,255,0.8);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08), 0 5px 15px rgba(0,0,0,0.03);
    font-size: 1.1rem;
    transition: all 0.3s ease;
    background: #fff;
    color: #334155;
}
.faq-search-wrapper input:focus {
    outline: none;
    box-shadow: 0 20px 40px rgba(26,86,160,0.12), 0 0 0 4px rgba(26,86,160,0.1);
    border-color: #1a56a0;
}
.faq-search-wrapper i.search-icon {
    position: absolute;
    left: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 1.25rem;
    transition: color 0.3s ease;
}
.faq-search-wrapper input:focus + i.search-icon {
    color: #1a56a0;
}

/* ── ACCORDION ── */
.faq-accordion .card {
    border: none;
    border-radius: 16px;
    margin-bottom: 1.25rem;
    box-shadow: 0 4px 15px rgba(0,0,0,.03);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    background: #fff;
    overflow: hidden;
}
.faq-accordion .card:hover {
    box-shadow: 0 12px 30px rgba(26,86,160,.08);
    transform: translateY(-3px);
}
.faq-accordion .card-header {
    background: transparent;
    border: none;
    padding: 0;
}
.faq-accordion .btn-link {
    width: 100%;
    text-align: left;
    padding: 1.5rem 1.75rem;
    color: #1e293b;
    font-weight: 700;
    font-size: 1.15rem;
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}
.faq-accordion .btn-link:focus { box-shadow: none; outline: none; }
.faq-accordion .btn-link:not(.collapsed) { 
    color: #1a56a0; 
    border-left-color: #1a56a0;
    background: linear-gradient(90deg, rgba(26,86,160,0.03) 0%, transparent 100%);
}
.faq-accordion .icon-wrapper {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
}
.faq-accordion .btn-link:not(.collapsed) .icon-wrapper {
    background: #1a56a0;
    color: white;
    transform: rotate(180deg);
}
.faq-accordion .btn-link.collapsed .icon-wrapper i {
    color: #64748b;
    transition: color 0.3s ease;
}
.faq-accordion .card-body {
    padding: 0 1.75rem 1.75rem 1.75rem;
    color: #475569;
    line-height: 1.8;
    font-size: 1.05rem;
    border-left: 4px solid #1a56a0;
    background: linear-gradient(90deg, rgba(26,86,160,0.03) 0%, transparent 100%);
}

.no-results {
    display: none;
    text-align: center;
    padding: 3rem 1rem;
}
.no-results i {
    font-size: 3rem;
    color: #cbd5e1;
    margin-bottom: 1rem;
}
</style>
@endpush

@section('content')

{{-- ── HERO ── --}}
<div class="page-header-premium py-5 text-white position-relative overflow-hidden" style="padding-bottom: 7rem !important;">
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
                <p class="lead mt-3">{{ __('Temukan jawaban untuk pertanyaan yang sering diajukan seputar program studi kami.') }}</p>
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
        
        {{-- Live Search Box --}}
        @if(!$faqs->isEmpty())
        <div class="faq-search-wrapper" data-aos="fade-up" data-aos-delay="100">
            <input type="text" id="faqSearchInput" placeholder="Cari pertanyaan Anda di sini..." autocomplete="off">
            <i class="fas fa-search search-icon"></i>
        </div>
        @endif

        <div class="row justify-content-center mt-4">
            <div class="col-lg-9" data-aos="fade-up" data-aos-delay="200">
                @if($faqs->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-question-circle fa-4x text-muted mb-3" style="opacity: .2;"></i>
                        <h4 class="text-muted">Belum ada FAQ yang tersedia.</h4>
                    </div>
                @else
                    <div class="accordion faq-accordion" id="faqAccordion">
                        @foreach($faqs as $faq)
                            <div class="card faq-item" data-question="{{ strtolower($faq->question) }}" data-answer="{{ strtolower(strip_tags($faq->answer)) }}">
                                <div class="card-header" id="faqHeading{{ $faq->id }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $faq->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="faqCollapse{{ $faq->id }}">
                                            <span>{{ $faq->question }}</span>
                                            <div class="icon-wrapper">
                                                <i class="fas fa-chevron-down"></i>
                                            </div>
                                        </button>
                                    </h2>
                                </div>

                                <div id="faqCollapse{{ $faq->id }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="faqHeading{{ $faq->id }}" data-bs-parent="#faqAccordion">
                                    <div class="card-body">
                                        {!! nl2br(e($faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="no-results" id="noResultsBlock">
                        <i class="fas fa-search-minus"></i>
                        <h5>Tidak ada hasil yang ditemukan.</h5>
                        <p class="text-muted">Coba gunakan kata kunci pencarian yang lain.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('faqSearchInput');
        const faqItems = document.querySelectorAll('.faq-item');
        const noResultsBlock = document.getElementById('noResultsBlock');

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                let hasResults = false;

                faqItems.forEach(item => {
                    const question = item.getAttribute('data-question');
                    const answer = item.getAttribute('data-answer');
                    
                    if (question.includes(query) || answer.includes(query)) {
                        item.style.display = 'block';
                        hasResults = true;
                    } else {
                        item.style.display = 'none';
                    }
                });

                if (hasResults) {
                    noResultsBlock.style.display = 'none';
                } else {
                    noResultsBlock.style.display = 'block';
                }
            });
        }
    });
</script>
@endpush
