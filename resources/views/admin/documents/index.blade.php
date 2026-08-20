@extends('adminlte::page')

@section('title', 'Kelola Dokumen')

@section('content')
<div class="container-fluid pt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button class="close" data-dismiss="alert">&times;</button></div>
    @endif

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau deskripsi dokumen..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-control form-control-sm">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <a href="{{ route('admin.documents.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold"><i class="fas fa-file-alt me-2"></i>Arsip Dokumen Program Studi</h3>
            <a href="{{ route('admin.documents.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-upload me-1"></i> Upload Dokumen
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-3" width="50">Tipe</th>
                        <th width="35%">Judul / Nama File</th>
                        <th>Kategori</th>
                        <th>Ukuran</th>
                        <th>Status</th>
                        <th>Diunggah</th>
                        <th width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($documents as $doc)
                    <tr>
                        <td class="pl-3 align-middle text-center text-lg">
                            @php
                                $icon = 'fa-file';
                                $color = 'text-secondary';
                                switch(strtolower($doc->file_type)) {
                                    case 'pdf': $icon = 'fa-file-pdf'; $color = 'text-danger'; break;
                                    case 'doc': case 'docx': $icon = 'fa-file-word'; $color = 'text-primary'; break;
                                    case 'xls': case 'xlsx': $icon = 'fa-file-excel'; $color = 'text-success'; break;
                                    case 'ppt': case 'pptx': $icon = 'fa-file-powerpoint'; $color = 'text-warning'; break;
                                    case 'zip': case 'rar': $icon = 'fa-file-archive'; $color = 'text-muted'; break;
                                    case 'jpg': case 'jpeg': case 'png': $icon = 'fa-file-image'; $color = 'text-info'; break;
                                }
                            @endphp
                            <i class="fas {{ $icon }} {{ $color }}"></i>
                        </td>
                        <td class="align-middle">
                            <strong>{{ $doc->title }}</strong>
                            <br>
                            <span class="text-xs text-muted" title="{{ $doc->file_name }}">{{ \Illuminate\Support\Str::limit($doc->file_name, 40) }}</span>
                        </td>
                        <td class="align-middle">
                            <span class="badge badge-light border">{{ $doc->category?->name ?? 'Tanpa Kategori' }}</span>
                        </td>
                        <td class="align-middle text-sm text-muted">
                            {{ number_format($doc->file_size / 1024 / 1024, 2) }} MB
                        </td>
                        <td class="align-middle">
                            @if($doc->is_public)
                                <span class="badge badge-success">Publik</span>
                            @else
                                <span class="badge badge-warning">Internal</span>
                            @endif
                        </td>
                        <td class="align-middle text-sm">
                            {{ $doc->created_at->format('d M Y') }}
                            <br>
                            <small class="text-muted">oleh {{ $doc->user?->name ?? 'Sistem' }}</small>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-outline-info" title="Download / Lihat"><i class="fas fa-download"></i></a>
                                <a href="{{ route('admin.documents.edit', $doc) }}" class="btn btn-outline-primary" title="Edit Info"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada dokumen yang diunggah.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Menampilkan {{ $documents->firstItem() }}-{{ $documents->lastItem() }} dari {{ $documents->total() }} dokumen</small>
                {{ $documents->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@stop
