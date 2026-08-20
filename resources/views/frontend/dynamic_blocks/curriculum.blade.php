@php
    $curriculums = \App\Models\Curriculum::where('is_active', true)
        ->orderBy('semester')
        ->orderBy('code')
        ->get()
        ->groupBy('semester');
@endphp

<div class="dynamic-block-container curriculum-block">
    @if($curriculums->count() > 0)
        @foreach($curriculums as $semester => $items)
            <div class="semester-group mb-4">
                <h4 class="fw-bold text-primary mb-3">Semester {{ $semester }}</h4>
                <div class="table-responsive">
                    <table class="table table-hover border">
                        <thead class="bg-light">
                            <tr>
                                <th width="150">Kode</th>
                                <th>Mata Kuliah</th>
                                <th width="80" class="text-center">SKS</th>
                                <th width="120">Tipe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td class="fw-bold">{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td class="text-center">{{ $item->credits }}</td>
                                    <td>
                                        <span class="badge {{ $item->type === 'wajib' ? 'bg-primary' : 'bg-secondary' }}">
                                            {{ ucfirst($item->type) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <p class="text-muted italic">Data kurikulum belum tersedia.</p>
    @endif
</div>
