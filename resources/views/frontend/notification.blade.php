@extends('frontend.layouts.app')
@section('title', 'Notifications')
@section('content')
    <div class="notification">
        <h6> Notifications</h6>
        <div class="card">
            <div class="card-body p-2 ">
                <div class="infinite-scroll">
                    @foreach ($notifications as $notification)
                        <a href="{{ url('notification/' . $notification->id) }}" class="text-decoration-none">
                            <div class="card mb-2">
                                <div class="card-body p-2 text-dark">
                                    <h6 class=""><i
                                            class="fas fa-bell @if (is_null($notification->read_at)) text-danger @endif"></i>{{ Illuminate\Support\Str::limit($notification->data['title'], 40) }}
                                    </h6>
                                    <p class="mb-1">
                                        {{ Illuminate\Support\Str::limit($notification->data['message'], 50) }}
                                    </p>
                                    <p class="mb-1 text-muted">
                                        {{ Carbon\Carbon::parse($notification->created_at)->format('Y-m-d h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        <hr>
                    @endforeach
                    <div class="ms-2"> {{ $notifications->links() }}</div>
                </div>

            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('ul.pagination').hide();
        $(function() {
            $('.infinite-scroll').jscroll({
                autoTrigger: true,
                loadingHtml: '<div class="text-center"><img  src="/images/loading.gif" alt="Loading..." /></div>',
                padding: 0,
                nextSelector: '.pagination li.active + li a',
                contentSelector: 'div.infinite-scroll',
                callback: function() {
                    $('ul.pagination').remove();
                }
            });

            // $('.date').daterangepicker({
            //     "singleDatePicker": true,
            //     "autoApply": true,
            //     "locale": {
            //         "format": "YYYY-MM-DD",
            //     },
            // });

            // $('.date').on('apply.daterangepicker', function(ev, picker) {
            //     var date = $('.date').val();
            //     var type = $('.type').val();
            //     history.pushState(null, '', `?date=${date}&type=${type}`)
            //     window.location.reload();
            // });
            //
            // $('.type').change(function() {
            //     var date = $('.date').val();
            //     var type = $('.type').val();
            //     history.pushState(null, '', `?date=${date}&type=${type}`)
            //     window.location.reload();
            // });
        });
    </script>
@endsection
