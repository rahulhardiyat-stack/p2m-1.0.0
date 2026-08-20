@extends('adminlte::page')

@section('title', __('admin.categories'))

@section('content')
<div class="container-fluid pt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button class="close" data-dismiss="alert">&times;</button></div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">{{ session('error') }}<button class="close" data-dismiss="alert">&times;</button></div>
    @endif

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('admin.search_categories') }}" value="{{ request('search') }}">
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold"><i class="fas fa-tags me-2"></i>{{ __('admin.categories') }}</h3>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> {{ __('admin.add_category') }}
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-3" width="30%">{{ __('admin.name') }}</th>
                        <th>{{ __('admin.slug') }}</th>
                        <th>{{ __('admin.parent_category') }}</th>
                        <th>{{ __('admin.category_color') }}</th>
                        <th>{{ __('admin.category_icon') }}</th>
                        <th class="text-center">{{ __('admin.articles_count') }}</th>
                        <th width="120">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="pl-3">
                            <strong>{{ $category->name }}</strong>
                        </td>
                        <td><small>{{ $category->slug }}</small></td>
                        <td><small>{{ $category->parent?->name ?? '-' }}</small></td>
                        <td>
                            <span class="badge" style="background-color: {{ $category->color }}; color: #fff;">
                                {{ $category->color }}
                            </span>
                        </td>
                        <td><i class="{{ $category->icon ?? 'fas fa-tag' }}"></i></td>
                        <td class="text-center">
                            <a href="{{ route('admin.posts.index', ['category_id' => $category->id]) }}" class="text-decoration-none">
                                <span class="badge badge-info px-2 py-1" style="font-size: 0.9em;">{{ $category->posts_count }}</span>
                            </a>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-primary" title="{{ __('admin.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="{{ __('admin.delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">{{ __('admin.no_categories') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ __('admin.showing') }} {{ $categories->firstItem() }}-{{ $categories->lastItem() }} {{ __('admin.of') }} {{ $categories->total() }}</small>
                {{ $categories->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@stop
