@extends('backend.layouts.app')
@section('user-index', 'mm-active')
@section('title', ' User List')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div> User Management
                </div>
            </div>

        </div>
    </div>
    <div class="pt-3">
        <a href="{{ route('user.user.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i>
            create
            new user</a>
    </div>
    <div class="content py-3">
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="user">
                            <thead>
                                <tr class="bg-light">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>IP</th>
                                    <th>User Agent</th>
                                    <th>Action</th>
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
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                processing: true,
                serverSide: true,
                ajax: '{{ route('user.getDatatable') }}',
                columns: [{
                        data: 'name',
                        name: 'name',
                        sortable: false,
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                    },
                    {
                        data: 'ip',
                        name: 'ip',
                    },
                    {
                        data: 'user_agent',
                        name: 'user_agent',
                    },
                    {
                        data: 'action',
                        name: 'action',
                    },
                ],
                columnDefs: [{
                        targets: [0, 1, 2, 3, 4],
                        sortable: false,
                    },

                ]
            });

            $(document).on('click', '.delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure, you want to delete?',
                    showCancelButton: true,
                    confirmButtonText: `Confirm`,
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/user/user/' + id,
                            type: 'DELETE',
                            success: function() {
                                table.ajax.reload();
                            }
                        });
                    }
                })
            });
        });
    </script>
@endsection
