@extends('backend.layouts.app')
@section('user-index', 'mm-active')
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
                <form action="{{ route('user.user.update', $user->id) }}" method="POST" id="update" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="form-control"
                            id="">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="form-control"
                            id="">
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="phone" name="phone" value="{{ $user->phone }}" class="form-control"
                            id="">
                    </div>
                    <div class="form-group">
                        <label for="profile">Profile Image</label>
                        <div class="mb-2">
                            <img src="{{ $user->profile ? asset('storage/' . $user->profile) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff' }}"
                                alt="{{ $user->name }}" class="rounded-circle" width="72" height="72"
                                style="object-fit: cover;">
                        </div>
                        <input type="file" name="profile" class="form-control" id="profile" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status" required>
                            <option value="{{ \App\Enums\UserStatus::Active->value }}"
                                @selected(old('status', $user->status?->value) === \App\Enums\UserStatus::Active->value)>Active</option>
                            <option value="{{ \App\Enums\UserStatus::InActive->value }}"
                                @selected(old('status', $user->status?->value) === \App\Enums\UserStatus::InActive->value)>InActive</option>
                        </select>
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
    {!! JsValidator::formRequest('App\Http\Requests\UpdateUser', '#update') !!}

    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
