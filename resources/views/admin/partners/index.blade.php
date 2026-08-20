@extends('adminlte::page')
@section('title', 'Mitra Kerjasama')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-handshake mr-2"></i>Mitra Kerjasama</h1>
    <a href="{{ route('admin.partners.create') }}" class="btn btn-primary">
        <i class="fas fa-plus mr-1"></i> Tambah Mitra
    </a>
</div>
@stop

@section('content')
<div class="card shadow-sm border-0" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
        <form method="GET" class="form-inline">
            <div class="input-group input-group-sm">
                <input type="text" name="search" class="form-control" placeholder="Cari mitra..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </form>
        <small class="text-muted">Total: {{ $partners->total() }} mitra</small>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th width="60" class="text-center">No</th>
                    <th>Logo</th>
                    <th>Nama Mitra</th>
                    <th>Website</th>
                    <th class="text-center" width="80">Urutan</th>
                    <th class="text-center" width="100">Status</th>
                    <th class="text-center" width="130">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($partners as $i => $partner)
                <tr>
                    <td class="text-center align-middle">{{ $partners->firstItem() + $i }}</td>
                    <td class="align-middle">
                        @if($partner->logo)
                            <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" style="height:48px; max-width:100px; object-fit:contain; border-radius:6px; background:#f8f9fa; padding:4px;">
                        @else
                            <span class="badge badge-secondary">No Logo</span>
                        @endif
                    </td>
                    <td class="align-middle font-weight-bold">{{ $partner->name }}</td>
                    <td class="align-middle">
                        @if($partner->website_url)
                            <a href="{{ $partner->website_url }}" target="_blank" class="text-primary small">{{ Str::limit($partner->website_url, 40) }} <i class="fas fa-external-link-alt fa-xs"></i></a>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-center align-middle">{{ $partner->order }}</td>
                    <td class="text-center align-middle">
                        <form action="{{ route('admin.partners.toggle-status', $partner) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $partner->is_active ? 'btn-success' : 'btn-secondary' }}" title="Toggle Status">
                                {{ $partner->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="text-center align-middle">
                        <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.partners.destroy', $partner) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus mitra ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fas fa-handshake fa-3x mb-3 d-block opacity-50"></i>Belum ada mitra</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($partners->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $partners->withQueryString()->links() }}
    </div>
    @endif
</div>
@stop
