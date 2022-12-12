@extends('frontend.layouts.app')
@section('title', 'E-Wallet')
@section('content')
    <div class="my-home">
        <div class="row">
            <div class="col-12">
                <div class="profile mb-3">
                    <img src="https://ui-avatars.com/api/?background=5842E3&color=fff&name={{ $user->name }}" class="avatar"
                        alt="">
                    <h6>{{ $user->name }}</h6>
                    <p class="text-muted">{{ $user->wallet ? $user->wallet->amount : 0 }} MMK</p>
                </div>
            </div>
            <div class="col-6">
                <div class="card shortcut-box">
                    <div class="card-body">
                        <img src="{{ asset('img/qr-code-scan.png') }}" alt="">
                        <span>Scan & Pay</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shortcut-box">
                    <div class="card-body">
                        <img src="{{ asset('img/qr-code.png') }}" alt="">
                        <span>Receive QR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
