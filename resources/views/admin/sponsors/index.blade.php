@extends('adminlte::page')
@section('title', 'Manajemen Sponsor')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-hand-holding-heart mr-2 text-primary"></i>Manajemen Sponsor</h1>
    <a href="{{ route('admin.sponsors.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus mr-1"></i> Tambah Sponsor
    </a>
</div>
@stop

@section('content')
<div class="row">
    <!-- Sidebar Groups (Settings sidebar logic) -->
    <div class="col-md-3">
        @include('admin.settings._sidebar', ['group' => 'sponsor'])
    </div>

    <div class="col-md-9">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                <form method="GET" class="form-inline">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control" placeholder="Cari sponsor..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
                <small class="text-muted">Total: {{ $sponsors->total() }} sponsor</small>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th width="60" class="text-center">No</th>
                                <th>Logo</th>
                                <th>Nama Sponsor</th>
                                <th>Website</th>
                                <th class="text-center" width="80">Urutan</th>
                                <th class="text-center" width="100">Status</th>
                                <th class="text-center" width="130">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sponsors as $i => $sponsor)
                            <tr>
                                <td class="text-center align-middle">{{ $sponsors->firstItem() + $i }}</td>
                                <td class="align-middle">
                                    @if($sponsor->logo)
                                        <img src="{{ asset('storage/'.$sponsor->logo) }}" alt="{{ $sponsor->name }}" style="height:48px; max-width:100px; object-fit:contain; border-radius:6px; background:#f8f9fa; padding:4px;">
                                    @else
                                        <span class="badge badge-secondary">No Logo</span>
                                    @endif
                                </td>
                                <td class="align-middle font-weight-bold">{{ $sponsor->name }}</td>
                                <td class="align-middle">
                                    @if($sponsor->url)
                                        <a href="{{ $sponsor->url }}" target="_blank" class="text-primary small">{{ Str::limit($sponsor->url, 40) }} <i class="fas fa-external-link-alt fa-xs"></i></a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">{{ $sponsor->order }}</td>
                                <td class="text-center align-middle">
                                    <form action="{{ route('admin.sponsors.toggle-status', $sponsor) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $sponsor->is_active ? 'btn-success' : 'btn-secondary' }}" title="Toggle Status">
                                            {{ $sponsor->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center align-middle">
                                    <a href="{{ route('admin.sponsors.edit', $sponsor) }}" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.sponsors.destroy', $sponsor) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus sponsor ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted"><i class="fas fa-hand-holding-heart fa-3x mb-3 d-block opacity-50"></i>Belum ada sponsor</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($sponsors->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $sponsors->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@stop
