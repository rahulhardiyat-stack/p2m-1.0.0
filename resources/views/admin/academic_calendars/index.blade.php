@extends('adminlte::page')

@section('title', 'Kalender Akademik')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1><i class="fas fa-calendar-alt mr-2"></i>Kalender Akademik</h1>
        <a href="{{ route('admin.academic-calendars.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Agenda
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-outline card-info shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-file-pdf mr-2"></i>Softcopy Kalender Akademik (Versi Cetak)</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="row align-items-center">
                        @csrf
                        <input type="hidden" name="group" value="academic">
                        <div class="col-md-6 mb-2">
                            @php $calendarFile = \App\Models\Setting::get('academic_calendar_pdf') @endphp
                            @if($calendarFile)
                                <div class="alert alert-success d-flex align-items-center mb-0 border-0 py-2">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    <div>
                                        File saat ini: <a href="{{ asset('storage/'.$calendarFile) }}" target="_blank" class="font-weight-bold text-dark text-decoration-underline">academic_calendar.pdf</a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning d-flex align-items-center mb-0 border-0 py-2">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <div>Status: <strong>Belum tersedia</strong> (Gunakan form di samping untuk upload)</div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="custom-file">
                                <input type="file" name="academic_calendar_pdf" class="custom-file-input" id="pdfFile" accept=".pdf" required>
                                <label class="custom-file-label" for="pdfFile">Pilih File PDF...</label>
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button type="submit" class="btn btn-info btn-block fw-bold">
                                <i class="fas fa-upload mr-1"></i> Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Filter & Pencarian</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.academic-calendars.index') }}" method="GET" class="row">
                <div class="col-md-4 mb-2">
                    <input type="text" name="search" class="form-control" placeholder="Cari agenda atau tahun..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <select name="academic_year" class="form-control">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year }}" {{ request('academic_year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <select name="semester" class="form-control">
                        <option value="">-- Semua Semester --</option>
                        <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap</option>
                        <option value="Antara" {{ request('semester') == 'Antara' ? 'selected' : '' }}>Antara</option>
                    </select>
                </div>
                <div class="col-md-2 mb-2">
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 50px" class="text-center">#</th>
                            <th>Agenda</th>
                            <th>Tahun Akademik</th>
                            <th>Semester</th>
                            <th>Waktu</th>
                            <th class="text-center">Tipe</th>
                            <th class="text-center">Status</th>
                            <th style="width: 150px" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($calendars as $cal)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($calendars->currentPage()-1) * $calendars->perPage() }}</td>
                                <td>
                                    <div class="fw-bold">{{ $cal->title }}</div>
                                    @if($cal->description)
                                        <small class="text-muted">{{ Str::limit($cal->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>{{ $cal->academic_year }}</td>
                                <td>{{ $cal->semester }}</td>
                                <td>
                                    @if($cal->end_date && $cal->start_date != $cal->end_date)
                                        {{ $cal->start_date->format('d/m/Y') }} - {{ $cal->end_date->format('d/m/Y') }}
                                    @else
                                        {{ $cal->start_date->format('d/m/Y') }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info text-capitalize">{{ $cal->type }}</span>
                                </td>
                                <td class="text-center">
                                    @if($cal->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Non-aktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.academic-calendars.edit', $cal) }}" class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.academic-calendars.destroy', $cal) }}" method="POST" onsubmit="return confirm('Hapus agenda ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada agenda kalender.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $calendars->links() }}
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function () {
        bsCustomFileInput.init();
    });
</script>
@stop
