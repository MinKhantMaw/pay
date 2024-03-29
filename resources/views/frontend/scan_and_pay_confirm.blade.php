@extends('frontend.layouts.app')
@section('title', 'Transfer Confirmation')
@section('content')
    <div class="transfer">
        <div class="card mt-1">
            <div class="card-body">
                @include('backend.layouts.flag')
                <form action="{{ url('scan-and-pay/complete') }}" method="POST" id="form">
                    @csrf
                    <input type="hidden" name="hash_value" value="{{ $hash_value }}">
                    <input type="hidden" name="to_phone" value="{{ $to_account->phone }}">
                    <input type="hidden" name="amount" value="{{ $amount }}">
                    <input type="hidden" name="description" value="{{ $description }}">
                    <div class="form-group">
                        <label class="mb-0"><strong>From</strong></label>
                        <p class="mb-1 text-muted">{{ $from_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $from_account->phone }}</p>
                    </div>

                    <div class="form-group">
                        <label class="mb-0"><strong>To</strong></label>
                        <p class="mb-1 text-muted">{{ $to_account->name }}</p>
                        <p class="mb-1 text-muted">{{ $to_account->phone }}</p>
                    </div>

                    <div class="form-group">
                        <label class="mb-0"><strong>Amount (MMK)</strong></label>
                        <p class="mb-1 text-muted">{{ number_format($amount, 2) }}</p>
                    </div>

                    <div class="form-group">
                        <label class="mb-0"><strong>Description</strong></label>
                        <p class="mb-1 text-muted">{{ $description }}</p>
                    </div>
                    <button type="submit" class="btn btn-theme btn-block mt-5 text-white confirm-btn"
                        style="width: 100%">Confirm</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.confirm-btn').on('click', function(e) {
                e.preventDefault();
                console.log("test");
                Swal.fire({
                    title: 'Please confirm your password',
                    icon: 'info',
                    html: '<input type="password" class="form-control text-center password" />',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        var password = $('.password').val();
                        $.ajax({
                            url: '/transfers/confirm/password-check?password=' + password,
                            type: 'GET',
                            success: function(res) {
                                if (res.status == 'success') {
                                    $('#form').submit();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: res.message,
                                    });
                                }
                            }
                        });
                    }
                })

            });
        });
    </script>
@endsection
