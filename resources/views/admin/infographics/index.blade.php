@extends('adminlte::page')

@section('title', 'Info Grafis Tahun Ajaran')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1><i class="fas fa-chart-pie me-2"></i>Info Grafis Tahun Ajaran</h1>
        </div>
        <div class="col-sm-6 text-right">
            <a href="{{ route('admin.infographics.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Tambah Data Baru
            </a>
        </div>
    </div>
@stop

@section('content')
<div class="container-fluid pt-3">
    <div class="row mb-3">
        <div class="col-12">
            <div class="card shadow-sm mb-0">
                <div class="card-body py-3 border-left border-primary" style="border-width: 4px !important;">
                    <h3 class="card-title m-0 font-weight-bold text-primary"><i class="fas fa-chart-pie me-2"></i>Info Grafis: Tahun Ajaran</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Groups -->
        <div class="col-md-3">
            @include('admin.settings._sidebar')
        </div>

        <!-- Table Content -->
        <div class="col-md-9">
            <div class="card card-primary card-outline shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold">Daftar Info Grafis</h3>
                    <div class="card-tools">
                         <a href="{{ route('admin.infographics.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus me-1"></i> Tambah Baru
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Judul Data</th>
                                    <th>Status Aktif</th>
                                    <th>Statistik</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($infographics as $item)
                                    <tr>
                                        <td class="font-weight-bold">{{ $item->title }}</td>
                                        <td>
                                            @if($item->is_active)
                                                <span class="badge badge-success"><i class="fas fa-check-circle me-1"></i> AKTIF</span>
                                            @else
                                                <form action="{{ route('admin.infographics.activate', $item) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-outline-success">Aktifkan</button>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->stats && count($item->stats) > 0)
                                                <div class="small">
                                                    @foreach($item->stats as $stat)
                                                        <span class="badge badge-light border mr-1">{{ $stat['label'] }}: {{ $stat['value'] }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('admin.infographics.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.infographics.edit', $item) }}" class="btn btn-xs btn-info">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="submit" class="btn btn-xs btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Belum ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($infographics->hasPages())
                    <div class="card-footer">
                        {{ $infographics->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@stop
