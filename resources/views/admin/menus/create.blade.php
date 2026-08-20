@extends('adminlte::page')

@section('title', 'Tambah Menu')

@section('content_header')
    <h1><i class="fas fa-plus me-2"></i>Tambah Menu Utama</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <form action="{{ route('admin.menus.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>Nama Menu <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Main Navigation">
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label>Lokasi (Identifier / Slug) <span class="text-danger">*</span></label>
                            <select name="location" class="form-control @error('location') is-invalid @enderror" required>
                                <option value="">-- Pilih Lokasi Menu --</option>
                                <option value="top-menu" {{ old('location') == 'top-menu' ? 'selected' : '' }}>Top Menu (Diatas Header)</option>
                                <option value="main-menu" {{ old('location') == 'main-menu' ? 'selected' : '' }}>Main Menu (Header)</option>
                                <option value="secondary-menu" {{ old('location') == 'secondary-menu' ? 'selected' : '' }}>Secondary Menu (Bawah Main Menu)</option>
                                <option value="footer-menu" {{ old('location') == 'footer-menu' ? 'selected' : '' }}>Footer Menu (Footer)</option>
                            </select>
                            <small class="text-muted">Pilih lokasi di mana menu ini akan ditampilkan.</small>
                            @error('location') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Aktifkan Menu</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Data</button>
                        <a href="{{ route('admin.menus.index') }}" class="btn btn-default float-right">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
