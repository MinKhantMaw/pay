@extends('backend.layouts.app')
@section('admin-user-index', 'mm-active')
@section('title', 'Create Admin User ')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Admin User Management
                </div>
            </div>

        </div>
    </div>
    <div class="content pt-3">
        <div class="card">
            <div class="card-body">
                @include('backend.layouts.flag')
                <form action="{{ route('admin.admin-user.store') }}" method="POST" id="create">
                    @csrf
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control" id="">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" id="">
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="phone" name="phone" class="form-control" id="">
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" id="">
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-success mr-2" id=""> Save </button>
                        <button type="reset" onclick="history.back()" class="btn btn-danger ">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\StoreAdminUser', '#create') !!}

    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
