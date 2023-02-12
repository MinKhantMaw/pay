@extends('frontend.layouts.app')
@section('title', 'Notification Detail')
@section('content')
    <div class="notification-detail">
        <div class="card">
            <div class="card-body text-center">
                <div class="text-center">
                    <img src="{{ asset('img/notifiaction.png') }}" alt="" style="width: 220px">
                </div>
                <h5 class="text-center">{{ $notification->data['title'] }}</h5>
                <p class="text-center text-muted mb-1">{{ $notification->data['message'] }}</p>
                <p class="text-center mb-3">{{ Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i A') }}</p>
                <a href="{{ $notification->data['web_link'] }}" class="btn btn-sm btn-theme text-white">Containue</a>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script></script>
@endsection
