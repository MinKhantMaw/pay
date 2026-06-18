@extends('backend.layouts.app')
@section('permission-index', 'mm-active')
@section('title', 'Permission Management')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading">
                <div class="page-title-icon"><i class="pe-7s-lock icon-gradient bg-mean-fruit"></i></div>
                <div>Permission Management</div>
            </div>
        </div>
    </div>
    <div class="content py-3">
        <div class="card">
            <div class="card-body">
                @foreach ($permissions as $group => $items)
                    <h6 class="text-capitalize mt-2">{{ $group }}</h6>
                    @foreach ($items as $permission)
                        <span class="badge badge-secondary mb-2">{{ $permission->name }}</span>
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
@endsection
