@extends('frontend.layouts.app')
@section('title', 'Translation')
@section('content')
    <div class="transaction">

        <div class="card">
            <div class="card-body p-2">
                @foreach ($transactions as $transaction)
                    <div class="d-flex justify-content-between">
                        <h6 class="mb-1">{{ $transaction->trx_id }}</h6>
                        <p
                            class="mb-1 @if ($transaction->type == 1) text-success @elseif($transaction->type == 2) text-danger @endif">
                            {{ $transaction->amount }}
                            <small>MMK</small>
                        </p>
                    </div>
                    <p class="mb-1 text-muted">
                        @if ($transaction->type == 1)
                            From
                        @elseif($transaction->type == 2)
                            To
                        @endif
                        {{ $transaction->source ? $transaction->source->name : '' }}
                    </p>
                    <p class="mb-1 text-muted">{{ $transaction->created_at }}</p>
                    <hr>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script></script>
@endsection
