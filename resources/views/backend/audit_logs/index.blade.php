@extends('backend.layouts.app')
@section('audit-log-index', 'mm-active')
@section('title', 'Audit Logs')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon"><i class="pe-7s-note2 icon-gradient bg-mean-fruit"></i></div>
                <div>Audit Logs</div>
            </div>
        </div>
    </div>
    <div class="content py-3">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="form-inline mb-3">
                    <select name="module" class="form-control mr-2">
                        <option value="">All Modules</option>
                        @foreach ($modules as $module)
                            <option value="{{ $module }}" @selected(request('module') === $module)>{{ $module }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="action" class="form-control mr-2" placeholder="Action" value="{{ request('action') }}">
                    <button class="btn btn-primary">Filter</button>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr class="bg-light">
                            <th>Date</th>
                            <th>Actor</th>
                            <th>Module</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($auditLogs as $log)
                            <tr>
                                <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                                <td>{{ class_basename($log->user_type) }} #{{ $log->user_id }}</td>
                                <td>{{ $log->module }}</td>
                                <td>{{ $log->action }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->ip_address }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $auditLogs->links() }}
            </div>
        </div>
    </div>
@endsection
