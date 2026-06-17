@extends('backend.layouts.app')
@section('user-index', 'mm-active')
@section('title', 'Create User ')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div> User Management
                </div>
            </div>

        </div>
    </div>
    <div class="content pt-3">
        <div class="card">
            <div class="card-body">
                @include('backend.layouts.flag')
                <form action="{{ route('user.user.store') }}" method="POST" id="create" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="">
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" id="">
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="phone" name="phone" value="{{ old('phone') }}" class="form-control" id="">
                    </div>
                    <div class="form-group">
                        <label for="profile">Profile Image</label>
                        <input type="file" name="profile" class="form-control" id="profile" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status" required>
                            <option value="{{ \App\Enums\UserStatus::Active->value }}"
                                @selected(old('status', \App\Enums\UserStatus::Active->value) === \App\Enums\UserStatus::Active->value)>Active</option>
                            <option value="{{ \App\Enums\UserStatus::InActive->value }}"
                                @selected(old('status') === \App\Enums\UserStatus::InActive->value)>InActive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" name="password" class="form-control" id="password"
                            inputmode="numeric" pattern="[0-9]*" maxlength="15" autocomplete="new-password"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <div class="form-group">
                        <label for="">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            id="password_confirmation" inputmode="numeric" pattern="[0-9]*" maxlength="15"
                            autocomplete="new-password"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
    {!! JsValidator::formRequest('App\Http\Requests\StoreUser', '#create') !!}

    <script>
        $(document).ready(function() {
            $('#password, #password_confirmation').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
@endsection
