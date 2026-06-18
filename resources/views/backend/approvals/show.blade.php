@extends('backend.layouts.app')
@section('approval-index', 'mm-active')
@section('title', 'Approval Detail')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon"><i class="pe-7s-check icon-gradient bg-mean-fruit"></i></div>
                <div>Approval Detail</div>
            </div>
        </div>
    </div>
    <div class="content py-3">
        <div class="card">
            <div class="card-body">
                @include('backend.layouts.flag')
                <table class="table table-bordered">
                    <tr><th>ID</th><td>{{ $approval->id }}</td></tr>
                    <tr><th>Action</th><td>{{ str_replace('_', ' ', $approval->action) }}</td></tr>
                    <tr><th>Status</th><td><span class="badge {{ $approval->status->badgeClass() }}">{{ $approval->status->value }}</span></td></tr>
                    <tr><th>User</th><td>{{ $approval->user?->name }} {{ $approval->user?->phone ? '('.$approval->user?->phone.')' : '' }}</td></tr>
                    <tr><th>Amount</th><td>{{ $approval->amount ? number_format($approval->amount, 2) : '-' }}</td></tr>
                    <tr><th>Description</th><td>{{ $approval->description ?? '-' }}</td></tr>
                    <tr><th>Requested By</th><td>{{ $approval->requester?->name ?? '-' }}</td></tr>
                    <tr><th>Approved By</th><td>{{ $approval->approver?->name ?? '-' }}</td></tr>
                    <tr><th>Approved At</th><td>{{ $approval->approved_at?->format('Y-m-d H:i') ?? '-' }}</td></tr>
                    <tr><th>Rejected By</th><td>{{ $approval->rejecter?->name ?? '-' }}</td></tr>
                    <tr><th>Rejected At</th><td>{{ $approval->rejected_at?->format('Y-m-d H:i') ?? '-' }}</td></tr>
                    <tr><th>Reject Reason</th><td>{{ $approval->reject_reason ?? '-' }}</td></tr>
                </table>

                @if ($approval->isPending())
                    @if (auth('admin_user')->user()->can('wallet.adjust_balance') || auth('admin_user')->user()->can('cashin.approve') || auth('admin_user')->user()->can('cashout.approve') || auth('admin_user')->user()->can('transaction.refund') || auth('admin_user')->user()->can('transaction.reverse'))
                        <form action="{{ route('admin.approvals.approve', $approval) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                    @endif
                    @if (auth('admin_user')->user()->can('wallet.adjust_balance') || auth('admin_user')->user()->can('cashin.reject') || auth('admin_user')->user()->can('cashout.reject') || auth('admin_user')->user()->can('transaction.refund') || auth('admin_user')->user()->can('transaction.reverse'))
                        <form action="{{ route('admin.approvals.reject', $approval) }}" method="POST" class="mt-3">
                            @csrf
                            <div class="form-group">
                                <label>Reject Reason</label>
                                <textarea name="reject_reason" class="form-control">{{ old('reject_reason') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-danger">Reject</button>
                            <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary">Back</a>
                        </form>
                    @endif
                @else
                    <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary">Back</a>
                @endif
            </div>
        </div>
    </div>
@endsection
