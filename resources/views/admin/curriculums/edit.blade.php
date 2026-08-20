@extends('adminlte::page')

@section('title', 'Edit Mata Kuliah')

@section('content_header')
    <h1><i class="fas fa-edit me-2"></i>Edit Mata Kuliah</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <div class="card">
                <form action="{{ route('admin.curriculums.update', $curriculum) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label>Kode Mata Kuliah <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $curriculum->code) }}" required placeholder="Contoh: IF1234">
                                @error('code')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-8 form-group">
                                <label>Nama Mata Kuliah <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $curriculum->name) }}" required placeholder="Contoh: Algoritma dan Pemrograman">
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label>Semester <span class="text-danger">*</span></label>
                                <input type="number" name="semester" class="form-control @error('semester') is-invalid @enderror" value="{{ old('semester', $curriculum->semester) }}" min="1" max="14" required>
                                @error('semester')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label>SKS <span class="text-danger">*</span></label>
                                <input type="number" name="credits" class="form-control @error('credits') is-invalid @enderror" value="{{ old('credits', $curriculum->credits) }}" min="0" max="10" required>
                                @error('credits')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Sifat Mata Kuliah <span class="text-danger">*</span></label>
                                <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                                    <option value="wajib" {{ old('type', $curriculum->type) == 'wajib' ? 'selected' : '' }}>Wajib</option>
                                    <option value="pilihan" {{ old('type', $curriculum->type) == 'pilihan' ? 'selected' : '' }}>Pilihan</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Konsentrasi (Opsional)</label>
                                <input type="text" name="concentration" class="form-control @error('concentration') is-invalid @enderror" value="{{ old('concentration', $curriculum->concentration) }}" placeholder="Contoh: Rekayasa Perangkat Lunak">
                                @error('concentration')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="form-group border-top pt-3 mt-3">
                            <label>Deskripsi Singkat Mata Kuliah (Opsional)</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Capaian pembelajaran mata kuliah ini adalah...">{{ old('description', $curriculum->description) }}</textarea>
                            @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="row bg-light border p-3 rounded mt-3">
                            <div class="col-md-7">
                                <div class="form-group mb-0">
                                    <label>File RPS / Silabus</label>
                                    @if($curriculum->rps_file)
                                        <div class="mb-2">
                                            <a href="{{ asset('storage/' . $curriculum->rps_file) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-pdf"></i> Lihat File RPS Saat Ini</a>
                                        </div>
                                    @endif
                                    <div class="custom-file mt-2">
                                        <input type="file" name="rps_file" class="custom-file-input @error('rps_file') is-invalid @enderror" id="customFile" accept=".pdf,.doc,.docx">
                                        <label class="custom-file-label" for="customFile">Pilih dokumen baru (Opsional)</label>
                                    </div>
                                    @error('rps_file')<span class="text-danger d-block text-sm mt-1">{{ $message }}</span>@enderror
                                    <small class="text-muted d-block mt-1">Biarkan kosong jika tidak mengubah file.</small>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group mb-0 mt-3 mt-md-0">
                                    <label>Status</label>
                                    <div class="custom-control custom-switch mt-1">
                                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $curriculum->is_active) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="is_active">Aktif (Tampilkan di web)</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.curriculums.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card card-outline card-info">
                <div class="card-header"><h3 class="card-title">Petunjuk</h3></div>
                <div class="card-body text-sm text-muted">
                    <p>Silakan sesuaikan informasi mata kuliah di form ini.</p>
                    <p>Mengunggah file <strong>RPS (Rencana Pembelajaran Semester)</strong> baru akan menimpa file yang lama.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        bsCustomFileInput.init();
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
@stop
