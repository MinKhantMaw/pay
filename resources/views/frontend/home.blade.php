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
                <div class="card shortcut-box mb-3" style="margin-left: 8px">
                    <div class="card-body p-2">
                        <img src="{{ asset('img/qr-code-scan.png') }}" alt="">
                        <span>Scan & Pay</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card shortcut-box mb-3" style="margin-right: 8px">
                    <div class="card-body p-2 mr-1">
                        <img src="{{ asset('img/qr-code.png') }}" alt="">
                        <span>Receive QR</span>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card mb-3 function-box m-2">
                    <div class="card-body pr-0">
                        <a href="{{ route('transfer') }}" class="d-flex justify-content-between update-password">
                            <span> <img src="{{ asset('img/money-transfer.png') }}" alt=""> Transfer</span>
                            <span class="mr-3">
                                <i class="fas fa-angle-right"></i>
                            </span>
                        </a>
                        <hr>
                        <a href="{{ route('wallet') }}" class="d-flex justify-content-between logout">
                            <span> <img src="{{ asset('img/wallet.png') }}" alt=""> Wallet</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                        <hr>
                        <a href="{{ route('transactions') }}" class="d-flex justify-content-between logout">
                            <span> <img src="{{ asset('img/money-transfer (1).png') }}" alt=""> Transaction</span>
                            <span class="mr-3"><i class="fas fa-angle-right"></i></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
