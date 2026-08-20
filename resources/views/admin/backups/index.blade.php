@extends('adminlte::page')

@section('title', 'Manajemen Backup')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0"><i class="fas fa-database mr-2 text-primary"></i>Manajemen Backup</h1>
            <small class="text-muted">Kelola file backup database dan aplikasi</small>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.backups.clean') }}" method="POST" onsubmit="return confirm('Hapus backup lama sesuai konfigurasi?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-warning btn-sm mr-2">
                    <i class="fas fa-broom mr-1"></i> Bersihkan Lama
                </button>
            </form>
            <form action="{{ route('admin.backups.create') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary btn-sm" id="btnCreateBackup">
                    <i class="fas fa-plus-circle mr-1"></i> Buat Backup Baru
                </button>
            </form>
        </div>
    </div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
    </div>
@endif

{{-- Stats Row --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#4f8fff,#7c3aed);">
                    <i class="fas fa-hdd text-white fa-lg"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Backup</div>
                    <div class="font-weight-bold" style="font-size:1.4rem;">{{ count($backups) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#10b981,#059669);">
                    <i class="fas fa-server text-white fa-lg"></i>
                </div>
                <div>
                    <div class="text-muted small">Disk Total</div>
                    <div class="font-weight-bold" style="font-size:1.4rem;">{{ $diskTotal }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width:52px;height:52px;background:linear-gradient(135deg,#f59e0b,#d97706);">
                    <i class="fas fa-tachometer-alt text-white fa-lg"></i>
                </div>
                <div>
                    <div class="text-muted small">Disk Tersisa</div>
                    <div class="font-weight-bold" style="font-size:1.4rem;">{{ $diskFree }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Backup List & Schedule Grid --}}
<div class="row">
    <div class="col-md-8">
        {{-- Backup List --}}
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header d-flex align-items-center" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                <i class="fas fa-archive text-primary mr-2"></i>
                <h5 class="mb-0 font-weight-bold">Daftar File Backup</h5>
            </div>
            <div class="card-body p-0">
                @if(count($backups) > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Tanggal Dibuat</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $i => $backup)
                            <tr>
                                <td class="text-muted">{{ $i + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-archive text-warning mr-2" style="font-size:1.2rem;"></i>
                                        <div>
                                            <div class="font-weight-600 text-break" style="max-width:380px;font-size:0.85rem;">{{ $backup['name'] }}</div>
                                            @if($i === 0)
                                                <span class="badge badge-success badge-sm">Terbaru</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">{{ $backup['size'] }}</span>
                                </td>
                                <td>
                                    <div class="text-sm">{{ $backup['date'] }}</div>
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.backups.download', $backup['name']) }}"
                                       class="btn btn-sm btn-info mr-1" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('admin.backups.destroy', $backup['name']) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Hapus file backup ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-database fa-4x text-muted opacity-25 mb-3 d-block"></i>
                    <p class="text-muted mb-3">Belum ada file backup tersedia.</p>
                    <form action="{{ route('admin.backups.create') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Backup Pertama
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        {{-- Schedule Settings --}}
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-header d-flex align-items-center" style="border-bottom: 1px solid rgba(0,0,0,0.06);">
                <i class="fas fa-clock text-primary mr-2"></i>
                <h5 class="mb-0 font-weight-bold">Jadwal Otomatis</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.backups.schedule') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    @php
                        $enabled = \App\Models\Setting::get('backup_enabled') === true;
                        $frequency = \App\Models\Setting::get('backup_frequency') ?: 'daily';
                    @endphp

                    <div class="form-group mb-4">
                        <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                            <input type="checkbox" class="custom-control-input" id="backup_enabled" name="backup_enabled" {{ $enabled ? 'checked' : '' }}>
                            <label class="custom-control-label" for="backup_enabled">Aktifkan Backup Otomatis</label>
                        </div>
                        <small class="text-muted d-block mt-2">Jika aktif, sistem akan melakukan backup secara otomatis sesuai frekuensi yang dipilih.</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="backup_frequency" class="small font-weight-bold text-uppercase text-muted">Frekuensi Backup</label>
                        <select name="backup_frequency" id="backup_frequency" class="form-control custom-select">
                            <option value="daily" {{ $frequency == 'daily' ? 'selected' : '' }}>Setiap Hari (00:00)</option>
                            <option value="weekly" {{ $frequency == 'weekly' ? 'selected' : '' }}>Setiap Minggu (Senin)</option>
                            <option value="monthly" {{ $frequency == 'monthly' ? 'selected' : '' }}>Setiap Bulan (Tgl 1)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save mr-1"></i> Simpan Pengaturan
                    </button>
                </form>

                <hr class="my-4">
                
                <div class="alert alert-info border-0 mb-0" style="background-color: rgba(79, 143, 255, 0.08); color: #2c5282;">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <span class="small">Pastikan server Anda menjalankan <strong>Laravel Scheduler</strong> (Cron Job) untuk mengaktifkan fitur ini.</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Info Card --}}
<div class="card border-0 shadow-sm mt-3" style="border-radius:14px; border-left: 4px solid #4f8fff !important;">
    <div class="card-body py-3">
        <h6 class="font-weight-bold mb-2"><i class="fas fa-info-circle text-primary mr-2"></i>Informasi Backup</h6>
        <ul class="mb-0 text-muted small">
            <li>Backup disimpan di <code>storage/app/Laravel/</code></li>
            <li>Backup mencakup seluruh database MySQL dan file aplikasi penting</li>
            <li>Gunakan <strong>Bersihkan Lama</strong> untuk menghapus backup sesuai kebijakan retensi</li>
            <li>Jadwal backup otomatis dapat dikonfigurasi di <code>config/backup.php</code></li>
        </ul>
    </div>
</div>

@stop

@section('js')
<script>
    document.getElementById('btnCreateBackup')?.closest('form').addEventListener('submit', function(e) {
        const btn = document.getElementById('btnCreateBackup');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Membuat Backup...';
        btn.disabled = true;
    });
</script>
@stop
