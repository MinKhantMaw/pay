@extends('frontend.layouts.app')
@section('title', 'Transfer')
@section('content')
    <div class="transfer">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('transferConfirm') }}" method="GET">
                    @csrf
                    <div class="form-group">
                        <label class="mb-1">From</label>
                        <p class="mb-1 text-muted">{{ $auth_user->name }}</p>
                        <p class="mb-1 text-muted">{{ $auth_user->phone }}</p>
                    </div>

                    <div class="form-group">


                    </div>

                    <div class="form-group">
                        <label for="to">To <span class="text-success to_account_info"></span></label>
                        <div class="input-group">
                            <input type="number" value="{{ old('to_phone') }}"
                                class="form-control rounded to_phone @error('to_phone')
                            is-invalid
                        @enderror"
                                name="to_phone" autocomplete="off">
                            <div class="input-group-append">
                                <span class="input-group-text btn btn-theme verify-btn" style="display: block"
                                    id="basic-addon2"><i class="fas fa-check-circle" style="color: white;"></i></span>
                            </div>
                        </div>
                        @error('to_phone')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="to">Amount (MMK)</label>
                        <input type="number" value="{{ old('amount') }}"
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
                        <textarea name="" id="" class="form-control">{{ old('message') }}</textarea>
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
        });
    </script>
@endsection
