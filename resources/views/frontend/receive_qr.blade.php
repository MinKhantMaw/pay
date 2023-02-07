@extends('frontend.layouts.app')
@section('title', 'Receive QR')
@section('content')
    <div class="receive-qr">
        <div class="card my-card">
            <div class="card-body">
                <p class="text-center mb-0"> Scan to pay me</p>
                <div class="text-center">
                    <img src="data:image/png;base64, {!! base64_encode(
                        QrCode::format('png')->size(250)->generate($authUser->phone),
                    ) !!} ">
                </div>
                <p class="text-center">{{ $authUser->name }}</p>
                <p class="text-center">{{ $authUser->phone }}</p>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script></script>
@endsection
