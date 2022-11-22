@extends('backend.layouts.app')
@section('admin-user-index', 'mm-active')
@section('title', 'Admin User List')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>Admin User List
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container">
            <div class="card">
                <div class="card-title">
                    <div class="card-header">
                        <p>Admin User List</p>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table" id="example">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admin_users as $admin_user)
                                <tr>
                                    <th scope="row">{{ $admin_user->id }}</th>
                                    <td>{{ $admin_user->name }}</td>
                                    <td>{{ $admin_user->email }}</td>
                                    <td>{{ $admin_user->phone }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <script>
        $(document).ready(function() {
            $('#example').DataTable();
        });
    </script>
@endsection
