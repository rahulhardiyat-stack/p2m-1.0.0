@extends('adminlte::page')

@section('title', 'Edit Testimoni')

@section('content_header')
    <h1><i class="fas fa-edit me-2"></i>Edit Testimoni</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $testimonial->name) }}" required>
                                @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Tahun Angkatan (Opsional)</label>
                                <input type="text" name="batch_year" class="form-control @error('batch_year') is-invalid @enderror" value="{{ old('batch_year', $testimonial->batch_year) }}" placeholder="Contoh: 2018">
                                @error('batch_year')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Pekerjaan / Jabatan (Opsional)</label>
                                <input type="text" name="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $testimonial->position) }}" placeholder="Contoh: Software Engineer, Founder">
                                @error('position')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Nama Instansi / Perusahaan (Opsional)</label>
                                <input type="text" name="company" class="form-control @error('company') is-invalid @enderror" value="{{ old('company', $testimonial->company) }}" placeholder="Contoh: PT. ABC Makmur">
                                @error('company')<span class="invalid-feedback">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header border-0 pb-0">
                                <h6 class="font-weight-bold mb-0">Isi Testimoni</h6>
                            </div>
                            <div class="card-body pt-3">
                                <div class="form-group mb-0">
                                    <label>Isi Testimoni <span class="text-danger">*</span></label>
                                    <textarea name="content" class="form-control @error('content') is-invalid @enderror" rows="4" required>{{ old('content', $testimonial->content) }}</textarea>
                                    @error('content')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-5 form-group">
                                <label>Rating Bintang <span class="text-danger">*</span></label>
                                <select name="rating" class="form-control @error('rating') is-invalid @enderror" required>
                                    <option value="5" {{ old('rating', $testimonial->rating) == 5 ? 'selected' : '' }}>5 Bintang (Sangat Baik)</option>
                                    <option value="4" {{ old('rating', $testimonial->rating) == 4 ? 'selected' : '' }}>4 Bintang (Baik)</option>
                                    <option value="3" {{ old('rating', $testimonial->rating) == 3 ? 'selected' : '' }}>3 Bintang (Cukup)</option>
                                    <option value="2" {{ old('rating', $testimonial->rating) == 2 ? 'selected' : '' }}>2 Bintang (Kurang)</option>
                                    <option value="1" {{ old('rating', $testimonial->rating) == 1 ? 'selected' : '' }}>1 Bintang (Sangat Kurang)</option>
                                </select>
                            </div>
                            <div class="col-md-7 form-group">
                                <label>Foto Profil (Opsional)</label>
                                @if($testimonial->photo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $testimonial->photo) }}" class="img-thumbnail rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                @endif
                                <div class="custom-file">
                                    <input type="file" name="photo" class="custom-file-input @error('photo') is-invalid @enderror" id="customFile" accept="image/*">
                                    <label class="custom-file-label" for="customFile">Pilih foto proporsi 1:1</label>
                                </div>
                                @error('photo')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                                <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto. Maks: 2MB.</small>
                            </div>
                        </div>

                        <div class="form-group border-top pt-3 mt-2">
                            <label>Status Publikasi</label>
                            <div class="custom-control custom-switch mt-1">
                                <input type="checkbox" class="custom-control-input" id="is_approved" name="is_approved" value="1" {{ old('is_approved', $testimonial->is_approved) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_approved">Tampilkan di Publik (Disetujui)</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
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
