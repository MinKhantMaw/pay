@extends('backend.layouts.app')
@section('role-index', 'mm-active')
@section('title', 'Edit Role')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon"><i class="pe-7s-key icon-gradient bg-mean-fruit"></i></div>
                <div>Edit Role</div>
            </div>
        </div>
    </div>

    <div class="content py-3">
        <div class="card">
            <div class="card-body">
                @include('backend.layouts.flag')
                <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}">
                    </div>
                    <div class="form-group">
                        <label>Permissions</label>
                        @foreach ($permissions as $group => $items)
                            <p class="mb-1 mt-2 font-weight-bold text-capitalize">{{ $group }}</p>
                            @foreach ($items as $permission)
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="custom-control-input" id="permission-{{ $permission->id }}" @checked($role->hasPermissionTo($permission->name))>
                                    <label class="custom-control-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
