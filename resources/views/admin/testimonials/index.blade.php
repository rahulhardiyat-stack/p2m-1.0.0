@extends('adminlte::page')

@section('title', __('admin.testimonials'))

@section('content')
<div class="container-fluid pt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">{{ session('success') }}<button class="close" data-dismiss="alert">&times;</button></div>
    @endif

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('admin.search') }}..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">{{ __('admin.status') }}</option>
                        <option value="approved" {{ request('status')=='approved'?'selected':'' }}>Approved</option>
                        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Pending</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times"></i></a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title font-weight-bold"><i class="fas fa-comments me-2"></i>{{ __('admin.testimonials') }}</h3>
            <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> {{ __('admin.add_testimonial') }}
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="pl-3" width="60">{{ __('admin.testimonial_photo') }}</th>
                        <th width="25%">{{ __('admin.testimonial_name') }}</th>
                        <th>{{ __('admin.testimonial_content') }}</th>
                        <th>{{ __('admin.testimonial_rating') }}</th>
                        <th>{{ __('admin.status') }}</th>
                        <th width="120">{{ __('admin.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($testimonials as $testimonial)
                    <tr>
                        <td class="pl-3 align-middle">
                            @if($testimonial->photo)
                                <img src="{{ asset('storage/' . $testimonial->photo) }}" class="img-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 20px;">
                                    {{ substr($testimonial->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td class="align-middle">
                            <strong>{{ $testimonial->name }}</strong>
                            <br>
                            <small class="text-muted">
                                {{ $testimonial->position ?? '-' }} @if($testimonial->company) @ {{ $testimonial->company }} @endif
                                <br>
                                @if($testimonial->batch_year) {{ $testimonial->batch_year }} @endif
                            </small>
                        </td>
                        <td class="align-middle"><small>{{ \Illuminate\Support\Str::limit($testimonial->content, 80) }}</small></td>
                        <td class="align-middle text-warning text-sm">
                            @for($i=1; $i<=5; $i++)
                                <i class="fas fa-star {{ $i <= $testimonial->rating ? '' : 'text-black-50' }}"></i>
                            @endfor
                        </td>
                        <td class="align-middle">
                            <form action="{{ route('admin.testimonials.toggle-status', $testimonial) }}" method="POST" style="display:inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="badge badge-{{ $testimonial->is_approved ? 'success' : 'secondary' }} border-0 cursor-pointer">
                                    {{ $testimonial->is_approved ? __('admin.active') : __('admin.inactive') }}
                                </button>
                            </form>
                        </td>
                        <td class="align-middle">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}" class="btn btn-outline-primary" title="{{ __('admin.edit') }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.testimonials.destroy', $testimonial) }}" method="POST" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="{{ __('admin.delete') }}"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">{{ __('admin.no_testimonials') }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">{{ __('admin.showing') }} {{ $testimonials->firstItem() }}-{{ $testimonials->lastItem() }} {{ __('admin.of') }} {{ $testimonials->total() }}</small>
                {{ $testimonials->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@stop
