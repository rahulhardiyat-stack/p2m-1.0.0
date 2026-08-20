@extends('adminlte::page')

@section('title', __('admin.add_category'))

@section('content_header')
    <h1><i class="fas fa-plus me-2"></i>{{ __('admin.add_category') }}</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label>{{ __('admin.category_name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>{{ __('admin.parent_category') }}</label>
                            <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                                <option value="">{{ __('admin.no_parent') }}</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="form-group">
                            <label>{{ __('admin.description') }}</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                            @error('description')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('admin.category_color') }}</label>
                                    <input type="color" name="color" class="form-control @error('color') is-invalid @enderror" value="{{ old('color', '#007bff') }}">
                                    @error('color')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('admin.category_icon') }}</label>
                                    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror" placeholder="fas fa-tag" value="{{ old('icon') }}">
                                    @error('icon')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Order</label>
                                    <input type="number" name="order" class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}">
                                    @error('order')<span class="invalid-feedback">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">{{ __('admin.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('admin.save_category') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
