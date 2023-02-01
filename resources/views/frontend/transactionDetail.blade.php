@extends('frontend.layouts.app')
@section('title', 'Translation Detail')
@section('content')
    <div class="transaction-detail">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/checked.png') }}" alt="">
                </div>
                @if (session('transfer_success'))
                    <div class="alert alert-success text-center fade show" role="alert">
                        {{ session('transfer_success') }}

                    </div>
                @endif
                @if ($transactionDetail->type == 1)
                    <h5 class="text-center text-success"> {{ number_format($transactionDetail->amount) }} MMK</h5>
                @elseif($transactionDetail->type == 2)
                    <h5 class="text-center text-danger"> {{ number_format($transactionDetail->amount) }} MMK</h5>
                @endif

                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Trx ID</p>
                    <p class="mb-0">{{ $transactionDetail->trx_id }}</p>
                </div>
                <hr>

                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Reference Number</p>
                    <p class="mb-0">{{ $transactionDetail->ref_no }}</p>
                </div>
                <hr>

                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Type</p>
                    <p class="mb-0">
                        @if ($transactionDetail->type == 1)
                            <span class="badge badge-pill badge-success text-success">Success</span>
                        @elseif($transactionDetail->type == 2)
                            <span class="badge badge-pill badge-danger text-danger">expnese</span>
                        @endif
                    </p>
                </div>
                <hr>

                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Amount</p>
                    <p class="mb-0">{{ number_format($transactionDetail->amount) }} MMK</p>
                </div>
                <hr>

                <div class="d-flex justify-content-between">
                    <p class="mb-0 text-muted">Transfer Date</p>
                    <p class="mb-0">{{ $transactionDetail->created_at }} </p>
                </div>
                <hr>

                <div class="d-flex justify-content-between">
                    {{-- <p class="mb-0 text-muted"></p> --}}
                    <p class="mb-0">
                        @if ($transactionDetail->type == 1)
                            From
                        @elseif($transactionDetail->type == 2)
                            To
                        @endif
                    </p>
                    <p class="mb-0">
                        {{ $transactionDetail->source ? $transactionDetail->source->name : '' }}
                    </p>
                </div>
                <hr>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script></script>
@endsection
