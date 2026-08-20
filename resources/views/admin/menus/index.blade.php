@extends('adminlte::page')

@section('title', 'Manajemen Menu')

@section('content')
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-bars me-2"></i>Manajemen Menu</h3>
                    <a href="{{ route('admin.menus.create') }}" class="btn btn-primary btn-sm ml-auto">
                        <i class="fas fa-plus me-1"></i> Tambah Menu
                    </a>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>Nama Menu</th>
                                <th>Lokasi (Slug)</th>
                                <th class="text-center">Jumlah Item</th>
                                <th class="text-center">Status</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($menus as $index => $menu)
                                <tr>
                                    <td class="text-center">{{ $menus->firstItem() + $index }}</td>
                                    <td class="font-weight-bold">{{ $menu->name }}</td>
                                    <td><code>{{ $menu->location }}</code></td>
                                    <td class="text-center"><span class="badge badge-info">{{ $menu->items_count }} Item</span></td>
                                    <td class="text-center">
                                        @if($menu->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.menus.show', $menu) }}" class="btn btn-warning btn-sm" title="Builder Menu Items">
                                                <i class="fas fa-sitemap mt-1"></i> Builder
                                            </a>
                                            <a href="{{ route('admin.menus.edit', $menu) }}" class="btn btn-outline-primary btn-sm" title="Edit Properties">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini beserta isi itemnya secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada menu yang ditambahkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer clearfix">
                    {{ $menus->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
