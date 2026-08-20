@extends('adminlte::page')

@section('title', __('admin.users'))

@section('content')
<div class="container-fluid pt-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title font-weight-bold"><i class="fas fa-users me-2"></i>{{ __('admin.users') }}</h3>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm ml-auto mr-3">
                        <i class="fas fa-plus me-1"></i> {{ __('admin.add_user') }}
                    </a>
                    
                    <div class="card-tools">
                        <form action="{{ route('admin.users.index') }}" method="GET" class="form-inline">
                            <div class="input-group input-group-sm mr-2" style="width: 150px;">
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="">{{ __('admin.status') }}</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.active') }}</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.inactive') }}</option>
                                </select>
                            </div>
                            <div class="input-group input-group-sm" style="width: 200px;">
                                <input type="text" name="search" class="form-control float-right" placeholder="{{ __('admin.search_users') }}" value="{{ request('search') }}">
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
                                <th width="10%" class="text-center">Avatar</th>
                                <th>{{ __('admin.user_name') }}</th>
                                <th>{{ __('admin.user_email') }}</th>
                                <th class="text-center">{{ __('admin.user_role') }}</th>
                                <th class="text-center">{{ __('admin.status') }}</th>
                                <th width="15%" class="text-center">{{ __('admin.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $users->firstItem() + $index }}</td>
                                    <td class="text-center">
                                        <img src="{{ $user->avatar_url }}" alt="Avatar" class="img-circle border" style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="font-weight-bold">{{ $user->name }}</td>
                                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                                    <td class="text-center">
                                        @if(class_exists(\Spatie\Permission\Models\Role::class) && $user->roles->count() > 0)
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-primary">{{ $role->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted small">Standard User</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($user->status === 'active')
                                            <span class="badge badge-success">{{ __('admin.active') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('admin.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm" title="{{ __('admin.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id() && auth()->user()->canImpersonate() && $user->canBeImpersonated())
                                            <a href="{{ route('admin.impersonate', $user->id) }}" class="btn btn-outline-success btn-sm" title="Impersonate">
                                                <i class="fas fa-user-secret"></i>
                                            </a>
                                            @endif
                                            @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('admin.confirm_delete') }}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="{{ __('admin.delete') }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @else
                                            <button type="button" class="btn btn-outline-secondary btn-sm" disabled><i class="fas fa-trash"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-users-slash fa-3x text-muted mb-2"></i>
                                        <p class="mb-0 text-muted">{{ __('admin.no_users') }}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="card-footer clearfix">
                    <div class="float-left pt-2">
                        {{ __('admin.showing') }} {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} {{ __('admin.of') }} {{ $users->total() }}
                    </div>
                    <div class="float-right">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
