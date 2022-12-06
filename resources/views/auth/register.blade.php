@extends('frontend.layouts.app_without_nav')
@section('title', 'Register')
@section('content')
    <div class="container">
        <div class="row justify-content-center align-items-center" style="height:100vh;">
            <div class="col-md-4">
                <div class="card auth-form">
                    {{-- <div class="card-header">{{ __('Register') }}</div> --}}
                    <div class="card-body">
                        <h3 class="text-center">Register</h3>
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-group my-3">
                                <label for="">Name</label>
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" id=""
                                    value="{{ old('name') }}" autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group my-3">
                                <label for="">Email</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror" id=""
                                    value="{{ old('email') }}" autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group my-3">
                                <label for="">Phone</label>
                                <input type="phone" name="phone"
                                    class="form-control @error('phone') is-invalid @enderror" id=""
                                    value="{{ old('phone') }}" autocomplete="phone" autofocus>
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group my-3">
                                <label for="">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" id="">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group my-3">
                                <label for="">Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    id="">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group my-3 ">
                                <button type="submit" class="btn btn-primary btn-theme"
                                    style="width:100%">Register</button>
                                <a href="{{ route('login') }}" class="text-decoration-none">Already have an
                                    account?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
