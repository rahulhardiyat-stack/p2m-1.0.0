@extends('adminlte::page')

@section('title', __('admin.events'))

@section('content')
<div class="container-fluid pt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button class="close" data-dismiss="alert">&times;</button></div>
    @endif

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('admin.search_events') }}" value="{{ request('search') }}">
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
                    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold"><i class="fas fa-calendar-alt me-2"></i>{{ __('admin.events') }}</h3>
            <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> {{ __('admin.add_event') }}
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-3" width="30%">{{ __('admin.event_title') }}</th>
                        <th>{{ __('admin.event_start') }}</th>
                        <th>{{ __('admin.event_location') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th>{{ __('admin.post_author') }}</th>
                        <th width="120">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($events as $event)
                    <tr>
                        <td class="pl-3">
                            <div class="d-flex align-items-center gap-2">
                                @if($event->featured_image)
                                    <img src="{{ $event->featured_image_url }}" style="width:40px;height:40px;object-fit:cover;border-radius:4px" alt="">
                                @endif
                                <div>
                                    <strong>{{ \Illuminate\Support\Str::limit($event->title, 50) }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $event->category ?? '-' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($event->start_date)
                            <small>
                                {{ $event->start_date->format('d/m/Y H:i') }}<br>
                                {{ $event->end_date ? $event->end_date->format('d/m/Y H:i') : '-' }}
                            </small>
                            @endif
                        </td>
                        <td>
                            <small>{{ \Illuminate\Support\Str::limit($event->location, 30) }}</small>
                            @if($event->online_url)<div class="badge badge-info mt-1"><i class="fas fa-globe"></i> Online</div>@endif
                        </td>
                        <td>
                            @if($event->is_published)
                                <span class="badge badge-success">{{ __('admin.post_published') }}</span>
                            @else
                                <span class="badge badge-secondary">{{ __('admin.post_draft') }}</span>
                            @endif
                        </td>
                        <td><small>{{ $event->user->name }}</small></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-outline-primary" title="{{ __('admin.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="{{ __('admin.delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_events') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ __('admin.showing') }} {{ $events->firstItem() }}-{{ $events->lastItem() }} {{ __('admin.of') }} {{ $events->total() }}</small>
                {{ $events->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@stop
