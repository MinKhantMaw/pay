@extends('frontend.layouts.app')
@section('title', 'Translation')
@section('content')
    <div class="transaction">
        <div class="card mb-2">
            <div class="card-body p-2">
                <h6> <i class="fas fa-filter"></i> Filter</h6>
                <div class="row">
                    <div class="col-6">
                        <div class="input-group my-2">
                            <label class="input-group-text">Date</label>
                            <input type="text" class="form-control date" value="{{ request()->date ?? date('Y-m-d') }}">
                        </div>

                    </div>
                    <div class="col-6">
                        <div class="input-group my-2">
                            <label class="input-group-text">Type</label>
                            <select class="form-select type">
                                <option value="">All</option>
                                <option value="1" @if (request()->type == 1) selected @endif>Income</option>
                                <option value="2" @if (request()->type == 2) selected @endif>Expense</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h6> Transactions</h6>
        <div class="card">
            <div class="card-body p-2">

                <div class="infinite-scroll">
                    @foreach ($transactions as $transaction)
                        <a href="{{ url('transactions/' . $transaction->trx_id) }}" class="text-decoration-none">
                            <div class="d-flex justify-content-between">
                                <h6 class="mb-1 text-black">Trx Id : {{ $transaction->trx_id }}</h6>
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
                        </a>
                        <hr>
                    @endforeach
                    <div class="ms-2"> {{ $transactions->links() }}</div>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<div class="text-center"><img  src="/images/loading.gif" alt="Loading..." /></div>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });

            $('.date').daterangepicker({
                "singleDatePicker": true,
                "autoApply": true,
                "locale": {
                    "format": "YYYY-MM-DD",
                },
            });

            $('.date').on('apply.daterangepicker', function(ev, picker) {
                var date = $('.date').val();
                var type = $('.type').val();
                history.pushState(null, '', `?date=${date}&type=${type}`)
                window.location.reload();
            });

            $('.type').change(function() {
                var date = $('.date').val();
                var type = $('.type').val();
                history.pushState(null, '', `?date=${date}&type=${type}`)
                window.location.reload();
            });
        });
    </script>
@endsection
