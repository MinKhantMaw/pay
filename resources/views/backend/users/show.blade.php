@extends('backend.layouts.app')
@section('user-index', 'mm-active')
@section('title', 'User Detail')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-users icon-gradient bg-mean-fruit">
                    </i>
                </div>
                <div>User Detail</div>
            </div>
        </div>
    </div>

    <div class="content pt-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        <img src="{{ $user->profile ? asset('storage/' . $user->profile) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0D8ABC&color=fff' }}"
                            alt="{{ $user->name }}" class="rounded-circle img-fluid" style="width: 140px; height: 140px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <th width="180">Name</th>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $user->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $user->status?->badgeClass() ?? 'badge-danger' }}">
                                            {{ $user->status?->value ?? 'InActive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Wallet Balance</th>
                                    <td>{{ number_format((float) optional($user->wallet)->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>Last Login</th>
                                    <td>{{ $user->login_at ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('user.user.edit', $user->id) }}" class="btn btn-warning mr-2">Edit</a>
                    <a href="{{ route('user.user.index') }}" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
