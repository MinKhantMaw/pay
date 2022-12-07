@extends('frontend.layouts.app')
@section('title', 'Change Password')
@section('content')
    <div class="update_password">
        <div class="card mb-3">
            <div class="card-body pr-0">
                <div class="mb-3">
                    <img src="{{ asset('img/password.png') }}" alt="">
                </div>
                <div class="form-group mb-3">
                    <label for="">Old Password</label>
                    <input type="password" name="old_password" class="form-control" id="">
                </div>
                <div class="form-group mb-3">
                    <label for="">New Password</label>
                    <input type="password" name="new_password" class="form-control" id="">
                </div>
                <div class="form-group mb-3">
                    <label for="">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="">
                </div>
                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-theme btn-block text-white" style="width:100%">Update
                        Password</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script></script>
@endsection
