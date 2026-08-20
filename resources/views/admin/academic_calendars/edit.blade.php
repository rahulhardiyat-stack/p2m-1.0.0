@extends('adminlte::page')

@section('title', 'Edit Agenda Kalender')

@section('content_header')
    <h1><i class="fas fa-edit mr-2"></i>Edit Agenda Kalender</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-warning card-outline">
                <form action="{{ route('admin.academic-calendars.update', $academicCalendar) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Agenda/Kegiatan <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $academicCalendar->title) }}" required placeholder="Contoh: Registrasi Mahasiswa Baru">
                            @error('title')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tahun Akademik <span class="text-danger">*</span></label>
                                <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror" value="{{ old('academic_year', $academicCalendar->academic_year) }}" required placeholder="Contoh: 2024/2025">
                                @error('academic_year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Semester <span class="text-danger">*</span></label>
                                <select name="semester" class="form-control @error('semester') is-invalid @enderror" required>
                                    <option value="Ganjil" {{ old('semester', $academicCalendar->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                                    <option value="Genap" {{ old('semester', $academicCalendar->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                                    <option value="Antara" {{ old('semester', $academicCalendar->semester) == 'Antara' ? 'selected' : '' }}>Antara</option>
                                </select>
                                @error('semester')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $academicCalendar->start_date->format('Y-m-d')) }}" required>
                                @error('start_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tanggal Selesai (Opsional)</label>
                                <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $academicCalendar->end_date ? $academicCalendar->end_date->format('Y-m-d') : '') }}">
                                @error('end_date')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tipe Agenda <span class="text-danger">*</span></label>
                                <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="kegiatan" {{ old('type', $academicCalendar->type) == 'kegiatan' ? 'selected' : '' }}>Kegiatan Akademik</option>
                                    <option value="pendaftaran" {{ old('type', $academicCalendar->type) == 'pendaftaran' ? 'selected' : '' }}>Pendaftaran/Registrasi</option>
                                    <option value="ujian" {{ old('type', $academicCalendar->type) == 'ujian' ? 'selected' : '' }}>Ujian/UTS/UAS</option>
                                    <option value="libur" {{ old('type', $academicCalendar->type) == 'libur' ? 'selected' : '' }}>Libur</option>
                                </select>
                                @error('type')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Warna Label (Opsional)</label>
                                <input type="color" name="color" class="form-control h-auto" value="{{ old('color', $academicCalendar->color ?: '#007bff') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Deskripsi/Keterangan</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $academicCalendar->description) }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Urutan Tampil</label>
                                <input type="number" name="order" class="form-control" value="{{ old('order', $academicCalendar->order) }}">
                            </div>
                            <div class="col-md-6 form-group d-flex align-items-center mt-4">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $academicCalendar->is_active) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">Aktifkan Agenda</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.academic-calendars.index') }}" class="btn btn-secondary mr-2">Batal</a>
                        <button type="submit" class="btn btn-warning px-4 text-white fw-bold">Perbarui Agenda</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
