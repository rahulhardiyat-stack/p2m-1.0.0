@extends('adminlte::page')

@section('title', __('admin.faqs'))

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ __('admin.faqs') }}</h1>
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> {{ __('admin.add_faq') }}
        </a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-header border-0 pb-0">
                    <form action="{{ route('admin.faqs.index') }}" method="GET" class="form-inline">
                        <div class="input-group input-group-sm w-100" style="max-width: 300px;">
                            <input type="text" name="search" class="form-control" placeholder="{{ __('admin.search_faqs') }}" value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('admin.faq_question') }}</th>
                                <th>{{ __('admin.status') }}</th>
                                <th>{{ __('admin.faq_order') }}</th>
                                <th>{{ __('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($faqs as $faq)
                                <tr>
                                    <td>{{ $loop->iteration + $faqs->firstItem() - 1 }}</td>
                                    <td style="white-space: normal; min-width: 300px;">{{ $faq->question }}</td>
                                    <td>
                                        @if($faq->is_active)
                                            <span class="badge badge-success">{{ __('admin.active') }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ __('admin.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $faq->order }}</td>
                                    <td>
                                        <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-info" title="{{ __('admin.edit') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('admin.confirm_delete') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="{{ __('admin.delete') }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mb-2" style="font-size: 24px;"></i><br>
                                        {{ __('admin.no_faqs') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($faqs->hasPages())
                    <div class="card-footer border-0">
                        {{ $faqs->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
