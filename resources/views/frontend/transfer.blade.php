@extends('frontend.layouts.app')
@section('title', 'Transfer')
@section('content')
    <div class="transfer">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('transferConfirm') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="mb-1">From</label>
                        <p class="mb-1 text-muted">{{ $auth_user->name }}</p>
                        <p class="mb-1 text-muted">{{ $auth_user->phone }}</p>
                    </div>

                    <div class="form-group">
                        <label for="to">To</label>
                        <input type="number"
                            class="form-control @error('to_phone')
                            is-invalid
                        @enderror"
                            name="to_phone" autocomplete="off">
                        @error('to_phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="to">Amount (MMK)</label>
                        <input type="number"
                            class="form-control @error('amount')
                            is-invalid
                        @enderror"
                            name="amount" autocomplete="off">
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="to">Description</label>
                        <textarea name="" id="" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-5 text-white"
                        style="width: 100%">Continue</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- {!! JsValidator::formRequest('App\Http\Requests\TransferFormValidate', '#transfer') !!} --}}

    <script></script>
@endsection
