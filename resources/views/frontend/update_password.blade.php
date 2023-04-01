@extends('frontend.layouts.app')
@section('title', 'Change Password')
@section('content')
    <div class="update_password">
        <div class="card mb-3">
            <div class="card-body pr-0">
                <div class="mb-3">
                    <img src="{{ asset('img/password.png') }}" alt="">
                </div>
                {{-- @include('backend.layouts.flag') --}}
                <form action="{{ route('updatePasswordStore') }}" method="POST" id="update">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="">Old Password</label>
                        <input type="password" name="old_password"
                            class="form-control @error('old_password')
                            is-invalid
                        @enderror"
                            id="updated">
                        @error('old_password')
                            <span class="invalid-feeback" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="">New Password</label>
                        <input type="password" name="new_password"
                            class="form-control @error('new_password')
                            is-invalid
                        @enderror"
                            id="">
                        @error('new_password')
                            <span class="invalid-feeback" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    {{-- <div class="form-group mb-3">
                        <label for="">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" id="">
                    </div> --}}
                    <div class="form-group mb-3">
                        <button type="submit" class="btn btn-theme btn-block text-white" style="width:100%">Update
                            Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {!! JsValidator::formRequest('App\Http\Requests\UpdatePassword', '#updated') !!}
    <script></script>
@endsection
