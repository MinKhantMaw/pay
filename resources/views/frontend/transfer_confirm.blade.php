@extends('frontend.layouts.app')
@section('title', 'Transfer Confirmation')
@section('content')
    <div class="transfer">
        <div class="card mt-1">
            <div class="card-body">
                <form action="" method="">
                    @csrf
                    <div class="form-group">
                        <label class="mb-0"><strong>From</strong></label>
                        <p class="mb-1 text-muted">{{ $auth_user->name }}</p>
                    </div>

                    <div class="form-group">
                        <label class="mb-0"><strong>Phone</strong></label>
                        <p class="mb-1 text-muted">{{ $to_phone }}</p>
                    </div>

                    <div class="form-group">
                        <label class="mb-0"><strong>Amount (MMK)</strong></label>
                        <p class="mb-1 text-muted">{{ number_format($amount) }}</p>
                    </div>

                    <div class="form-group">
                        <label class="mb-0"><strong>Description</strong></label>
                        <p class="mb-1 text-muted">{{ $description }}</p>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-5 text-white"
                        style="width: 100%">Continue</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script></script>
@endsection
