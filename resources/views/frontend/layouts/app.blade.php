<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    {{-- bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;700&display=swap"
        rel="stylesheet">
    {{-- fontawesome link --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/frontend/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    @yield('extra-css')

</head>

<body>
    <div id="app">
        <div class="header-menu">
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-2 text-center">
                            @if (!request()->is('/'))
                                <a href="#" class="back-btn">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            @endif

                        </div>
                        <div class="col-8 text-center">
                            <h3>@yield('title')</h3>
                        </div>
                        <div class="col-2 text-center">
                            <a href="{{ route('notification') }}"> <i class="fas fa-bell"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    @yield('content')
                </div>
            </div>

        </div>

        <div class="bottom-menu">
            <a href="{{ route('scanpay') }}" class="scan-tab">
                <div class="inside">
                    <i class="fas fa-qrcode"></i>
                </div>
            </a>
            <div class="d-flex justify-content-center">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-3 text-center">
                            <a href="{{ route('home') }}"> <i class="fas fa-home"></i>
                                <p>Home</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{ route('wallet') }}">
                                <i class="fa-sharp fa-solid fa-wallet"></i>
                                <p>Wallet</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{ route('transactions') }}">
                                <i class="fa-solid fa-money-bill-transfer"></i>
                                <p>Transaction</p>
                            </a>
                        </div>
                        <div class="col-3 text-center">
                            <a href="{{ route('profile') }}"> <i class="fas fa-user"></i>
                                <p>Profile</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- bootstrap js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    {{-- sweet alert cdn --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ url('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    {{-- daterangepicker --}}
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>



    <script src="{{ asset('assets/frontend/js/jsscroll.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF_TOKEN': token.content
                        // 'Content-Type: application/json',
                        // 'Accept: application/json'
                    }
                });
            }
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })
            @if (session('create'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('create') }}'
                })
            @endif

            @if (session('update'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('update') }}'
                })
            @endif

            $('.back-btn').on('click', function(e) {
                e.preventDefault();
                window.history.go(-1);
                return false;
            })
        });
    </script>
    @yield('scripts')


</body>

</html>
