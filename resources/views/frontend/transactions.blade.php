@extends('frontend.layouts.app')
@section('title', 'Translation')
@section('content')
    <div class="transaction">
        <div class="card mb-2">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-6">
                        {{-- <div class="input-group my-2">
                            <label class="input-group-text">Type</label>
                            <select class="form-select">
                                <option value="">All</option>
                                <option value="1">Income</option>
                                <option value="2">Expense</option>
                            </select>
                        </div> --}}
                    </div>
                    <div class="col-6">
                        <div class="input-group my-2">
                            <label class="input-group-text">Type</label>
                            <select class="form-select type">
                                <option value="">All</option>
                                <option value="1">Income</option>
                                <option value="2">Expense</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            $('.type').change(function() {
                var type = $('.type').val();
                console.log(type);
            });
        });
    </script>
@endsection
