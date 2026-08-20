@extends('adminlte::page')

@section('title', 'Edit Pengguna: ' . $user->name)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-user-edit me-2"></i>Edit Pengguna: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card card-outline card-primary shadow-sm">
                <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9 border-right">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                        @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Email Valid <span class="text-danger">*</span></label>
                                    <div class="col-sm-9">
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Ganti Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8" placeholder="Kosongkan jika tidak ingin ganti password">
                                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Ulangi Password Baru</label>
                                    <div class="col-sm-9">
                                        <input type="password" name="password_confirmation" class="form-control" minlength="8" placeholder="Ketik ulang password baru jika mengganti">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group text-center">
                                    <label>Pas Foto</label>
                                    <div class="mt-2 mb-3">
                                        <img src="{{ $user->avatar_url }}" id="preview-avatar" class="img-circle elevation-2" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="avatar" class="custom-file-input" id="avatarInput" accept="image/*">
                                        <label class="custom-file-label" for="avatarInput">Pilih Baru</label>
                                    </div>
                                    <small class="form-text text-muted text-left mt-1">Biarkan kosong jika tidak ganti.</small>
                                    @error('avatar') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="form-group mt-4">
                                    <label>Status Akun <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Nonaktif (Suspend)</option>
                                    </select>
                                    @if($user->id == auth()->id())
                                        <input type="hidden" name="status" value="{{ $user->status }}">
                                        <small class="text-muted">Tidak bisa ubah status diri sendiri.</small>
                                    @endif
                                    @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>

                                @if(!empty($roles))
                                <div class="form-group mt-3">
                                    <label>Peran (Role)</label>
                                    <select name="roles[]" class="form-control @error('roles') is-invalid @enderror" multiple {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                        @foreach($roles as $role)
                                            <option value="{{ $role }}" {{ (collect(old('roles', $userRoles))->contains($role)) ? 'selected':'' }}>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted d-block">Tahan CTRL untuk pilih banyak.</small>
                                    @if($user->id == auth()->id())
                                        @foreach($userRoles as $r)
                                            <input type="hidden" name="roles[]" value="{{ $r }}">
                                        @endforeach
                                    @endif
                                    @error('roles') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-default">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Perbarui Profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $('#avatarInput').change(function(){
        let reader = new FileReader();
        reader.onload = (e) => { 
            $('#preview-avatar').attr('src', e.target.result); 
        }
        if(this.files[0]) {
            reader.readAsDataURL(this.files[0]);
            $(this).next('.custom-file-label').html(this.files[0].name);
        } else {
            $(this).next('.custom-file-label').html('Pilih Baru');
        }
    });
</script>
@stop
