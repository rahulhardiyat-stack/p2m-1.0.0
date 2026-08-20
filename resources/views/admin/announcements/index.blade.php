@extends('adminlte::page')

@section('title', __('admin.announcements'))

@section('content')
<div class="container-fluid pt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button class="close" data-dismiss="alert">&times;</button></div>
    @endif

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('admin.search_announcements') }}" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">{{ __('admin.status') }}</option>
                        <option value="published" {{ request('status')=='published'?'selected':'' }}>{{ __('admin.post_published') }}</option>
                        <option value="draft" {{ request('status')=='draft'?'selected':'' }}>{{ __('admin.post_draft') }}</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold"><i class="fas fa-bullhorn me-2"></i>{{ __('admin.announcements') }}</h3>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> {{ __('admin.add_announcement') }}
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-3" width="35%">{{ __('admin.title') }}</th>
                        <th>Priority</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>{{ __('admin.announcement_date') }}</th>
                        <th>Attachment</th>
                        <th width="120">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($announcements as $announcement)
                    <tr>
                        <td class="pl-3">
                            <strong>{{ \Illuminate\Support\Str::limit($announcement->title, 60) }}</strong>
                            <br>
                            <small class="text-muted">{{ $announcement->user->name }}</small>
                        </td>
                        <td>
                            @php
                                $badgeColor = match($announcement->priority) {
                                    'low' => 'secondary',
                                    'normal' => 'info',
                                    'high' => 'warning',
                                    'urgent' => 'danger',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge badge-{{ $badgeColor }}">{{ \Illuminate\Support\Str::ucfirst($announcement->priority) }}</span>
                        </td>
                        <td>
                            @if($announcement->is_published)
                                <span class="badge badge-success">{{ __('admin.post_published') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('admin.post_draft') }}</span>
                            @endif
                        </td>
                        <td>
                            <small>
                                @if($announcement->expire_date)
                                    {{ $announcement->expire_date->format('d/m/Y') }}
                                @else
                                    -
                                @endif
                            </small>
                        </td>
                        <td>
                            @if($announcement->attachment)
                                <a href="{{ asset('storage/' . $announcement->attachment) }}" target="_blank" class="btn btn-xs btn-outline-info"><i class="fas fa-paperclip"></i></a>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-outline-primary" title="{{ __('admin.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="{{ __('admin.delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_announcements') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ __('admin.showing') }} {{ $announcements->firstItem() }}-{{ $announcements->lastItem() }} {{ __('admin.of') }} {{ $announcements->total() }}</small>
                {{ $announcements->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@stop
