@extends('frontend.layouts.app')
@section('title', 'Scan & Pay Form')
@section('content')
    <div class="transfer">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('scanAndPayConfirm') }}" method="GET" id="transfer-form">
                    @csrf
                    <input type="hidden" name="hash_value" class="hash_value" value="">
                    <input type="hidden" name="to_phone" class="to_phone" value="{{ $to_account->phone }}">
                    <div class="form-group">
                        <label class="mb-1">From</label>
                        <p class="mb-1 text-muted">{{ $from_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $from_account->phone }}</p>
                    </div>
                    <div class="form-group">
                        <label class="mb-1">To</label>
                        <p class="mb-1 text-muted">{{ $to_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }}</p>
                    </div>

                    <div class="form-group">
                        <label for="to">Amount (MMK)</label>
                        <input type="number" value="{{ old('amount') }}"
                            class="form-control amount @error('amount')
                            is-invalid
                        @enderror"
                            name="amount" autocomplete="off">
                        @error('amount')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="to">Description</label>
                        <textarea name="description" id="" class="form-control">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-5 text-white submit-btn"
                        style="width: 100%">Continue</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- {!! JsValidator::formRequest('App\Http\Requests\TransferFormValidate', '#transfer') !!} --}}

    <script>
        $('document').ready(function() {
            $('.verify-btn').on('click', function() {
                var phone = $('.to_phone').val();
                $.ajax({
                    url: '/to-account-verify?phone=' + phone,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.to_account_info').text('(' + res.data['name'] + ')');
                        } else {
                            $('.to_account_info').text('(' + res.message + ')');
                        }
                    }
                });
            });
            $('.submit-btn').on('click', function(e) {
                e.preventDefault();
                var to_phone = $('.to_phone').val();
                var amount = $('.amount').val();
                var description = $('.description').val();
                $.ajax({
                    url: `/transfer-hash?to_phone=${to_phone}&amount=${amount}&description=${description}`,
                    type: 'GET',
                    success: function(res) {
                        if (res.status == 'success') {
                            $('.hash_value').val(res.data);
                            $('#transfer-form').submit();
                        }
                    }
                });
            });
        });
    </script>
@endsection
