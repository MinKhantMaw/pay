@extends('backend.layouts.app')
@section('wallet-index', 'mm-active')
@section('title', ' Wallet Account List')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-wallet icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div> Wallet Account Management
                </div>
            </div>

        </div>
    </div>
    <div class="pt-3">
        <a href="{{ route('wallet.addAmount') }}" class="btn btn-outline-success"><i class="fas fa-plus"></i> Add Amount</a>
        <a href="{{ route('wallet.reduceAmount') }}" class="btn btn-outline-danger"><i class="fas fa-minus-circle"></i>
            Reduce Amount</a>
    </div>
    <div class="content py-3">
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="user">
                            <thead>
                                <tr class="bg-light">
                                    <th>Account Number</th>
                                    <th>Account Person</th>
                                    <th>Amount (MMK)</th>
                                    <th>Created at</th>
                                    <th>Updated at</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#user').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('wallet.getDatatable') }}',
                columns: [{
                        data: 'account_number',
                        name: 'account_number',

                    },
                    {
                        data: 'account_person',
                        name: 'account_person',
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                    },
                    {
                        data: 'updated_at',
                        name: 'updated_at',
                    },

                ],
                columnDefs: [{
                        targets: [0, 1, 2, 3, 4],
                        sortable: false,
                    },

                ],
            });
        });
    </script>
@endsection
