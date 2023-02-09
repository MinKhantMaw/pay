@extends('frontend.layouts.app')
@section('title', 'Scan & Pay ')
@section('content')
    <div class="scan-and-pay">
        <div class="card my-card">
            <div class="card-body text-center">
                <div class="text-center">
                    <img src="{{ asset('img/scanpay.png') }}" alt="" style="width: 220px">
                </div>
                <p class="mb-3">Click button,put QR code in the frame and pay</p>
                <button class="btn btn-theme text-white" data-bs-toggle="modal" data-bs-target="#scanModal">Scan</button>

                <!-- Scam Modal -->
                <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="scanModalLabel">Scan & Pay</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <video id="scanner"></video>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm"
                                    data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/frontend/js/qr-scanner.umd.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var videoElem = document.getElementById('scanner')
            const qrScanner = new QrScanner(
                videoElem,
                result => console.log('decoded qr code:', result), {
                    /* your options or returnDetailedScanResult: true if you're not specifying any other options */
                },
            );
        })
    </script>
@endsection
