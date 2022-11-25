@extends('backend.layouts.app')
@section('admin-user-index', 'mm-active')
@section('title', 'Edit Admin User ')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Edit User Management
                </div>
            </div>

        </div>
    </div>
    <div class="content pt-3">
        <div class="card">
            <div class="card-body">
                @include('backend.layouts.flag')
                <form action="{{ route('admin.admin-user.update', $admin_user->id) }}" method="POST" id="update">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" value="{{ $admin_user->name }}" class="form-control"
                            id="">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" value="{{ $admin_user->email }}" class="form-control"
                            id="">
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="phone" name="phone" value="{{ $admin_user->phone }}" class="form-control"
                            id="">
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" id="">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-success mr-2" id=""> Update </button>
                        <button type="reset" onclick="history.back()" class="btn btn-danger ">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UpdateAdminUser', '#update') !!}

    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
