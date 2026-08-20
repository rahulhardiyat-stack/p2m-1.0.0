@extends('adminlte::page')

@section('title', __('admin.contact_messages'))

@section('content')
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-envelope me-2"></i>{{ __('admin.contact_messages') }}</h3>
                    
                    <div class="card-tools">
                        <form action="{{ route('admin.contact-messages.index') }}" method="GET" class="form-inline">
                            <div class="input-group input-group-sm mr-2" style="width: 150px;">
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">{{ __('admin.status') }}</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>{{ __('admin.unread') }}</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Read</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="{{ __('admin.search') }}..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.message_from') }}</th>
                                <th>{{ __('admin.message_subject') }}</th>
                                <th>{{ __('admin.message_date') }}</th>
                                <th width="15%" class="text-center">{{ __('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($messages as $index => $msg)
                                <tr class="{{ !$msg->is_read ? 'font-weight-bold bg-light' : '' }}">
                                    <td class="text-center">{{ $messages->firstItem() + $index }}</td>
                                    <td>
                                        @if(!$msg->is_read)
                                            <span class="badge badge-warning"><i class="fas fa-envelope"></i> {{ __('admin.unread') }}</span>
                                        @else
                                            <span class="badge badge-success"><i class="fas fa-envelope-open"></i> Read</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $msg->name }}<br>
                                        <small class="text-primary">{{ $msg->email }}</small>
                                    </td>
                                    <td>{{ Str::limit($msg->subject, 40) }}</td>
                                    <td>{{ $msg->created_at ? $msg->created_at->format('d M Y H:i') : '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.contact-messages.show', $msg) }}" class="btn btn-info btn-sm" title="{{ __('admin.reply_message') }}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('admin.contact-messages.destroy', $msg) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-right" title="{{ __('admin.delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">{{ __('admin.no_messages') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer clearfix">
                    <div class="float-left">
                        {{ __('admin.showing') }} {{ $messages->firstItem() ?? 0 }} - {{ $messages->lastItem() ?? 0 }} {{ __('admin.of') }} {{ $messages->total() }}
                    </div>
                    <div class="float-right">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    <style>
        .table td, .table th { vertical-align: middle; }
    </style>
@stop
