@extends('backend.layouts.app')
@section('role-index', 'mm-active')
@section('title', 'Role Management')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon"><i class="pe-7s-key icon-gradient bg-mean-fruit"></i></div>
                <div>Role Management</div>
            </div>
        </div>
    </div>

    <div class="content py-3">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        @include('backend.layouts.flag')
                        <form action="{{ route('admin.roles.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group">
                                <label>Permissions</label>
                                @foreach ($permissions as $group => $items)
                                    <p class="mb-1 mt-2 font-weight-bold text-capitalize">{{ $group }}</p>
                                    @foreach ($items as $permission)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="custom-control-input" id="permission-{{ $permission->id }}">
                                            <label class="custom-control-label" for="permission-{{ $permission->id }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-light">
                                    <th>Name</th>
                                    <th>Permissions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @foreach ($role->permissions as $permission)
                                                <span class="badge badge-secondary">{{ $permission->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="text-warning"><i class="fas fa-edit"></i></a>
                                            @if ($role->name !== 'Super Admin')
                                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-link text-danger p-0"><i class="fas fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
