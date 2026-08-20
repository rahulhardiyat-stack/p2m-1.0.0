@php
    $lecturers = \App\Models\Lecturer::where('is_active', true)
        ->orderBy('order')
        ->orderBy('name')
        ->get();
@endphp

<div class="dynamic-block-container lecturers-block">
    @if($lecturers->count() > 0)
        <div class="row g-4">
            @foreach($lecturers as $lecturer)
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm lecturer-card text-center p-3">
                        <div class="lecturer-img-wrapper mx-auto mb-3" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 3px solid #e8f0fe;">
                            @if($lecturer->photo)
                                <img src="{{ asset('storage/'.$lecturer->photo) }}" alt="{{ $lecturer->name }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                    <i class="fas fa-user-tie fa-3x text-secondary opacity-25"></i>
                                </div>
                            @endif
                        </div>
                        <h5 class="fw-bold mb-1" style="font-size: 1rem;">{{ $lecturer->name }}</h5>
                        <p class="text-primary small mb-2">{{ $lecturer->title ?: 'Dosen' }}</p>
                        <p class="text-muted small mb-0">{{ $lecturer->expertise }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted italic">Data dosen belum tersedia.</p>
    @endif
</div>
