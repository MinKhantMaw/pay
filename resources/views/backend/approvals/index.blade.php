@extends('backend.layouts.app')
@section('approval-index', 'mm-active')
@section('title', 'Approval List')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon"><i class="pe-7s-check icon-gradient bg-mean-fruit"></i></div>
                <div>Approval List</div>
            </div>
        </div>
    </div>
    <div class="content py-3">
        <div class="card">
            <div class="card-body">
                @include('backend.layouts.flag')
                <form method="GET" class="form-inline mb-3">
                    <select name="status" class="form-control mr-2">
                        <option value="">All Status</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->value }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary">Filter</button>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-light">
                            <th>ID</th>
                            <th>Action</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Requested By</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($approvals as $approval)
                            <tr>
                                <td>{{ $approval->id }}</td>
                                <td>{{ str_replace('_', ' ', $approval->action) }}</td>
                                <td>{{ $approval->user?->name ?? '-' }}</td>
                                <td>{{ $approval->amount ? number_format($approval->amount, 2) : '-' }}</td>
                                <td><span class="badge {{ $approval->status->badgeClass() }}">{{ $approval->status->value }}</span></td>
                                <td>{{ $approval->requester?->name ?? '-' }}</td>
                                <td>{{ $approval->created_at?->format('Y-m-d H:i') }}</td>
                                <td><a href="{{ route('admin.approvals.show', $approval) }}" class="text-info"><i class="fas fa-eye"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $approvals->links() }}
            </div>
        </div>
    </div>
@endsection
