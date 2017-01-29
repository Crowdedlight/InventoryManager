<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Manager</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap-theme.min.css">

    @stack('css')
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">

</head>
<body>
<div id="wrap">
    @if (Auth::user()->FK_eventID != null)
        @include('layout.partials.navigation_event')
    @else
        @include('layout.partials.navigation')
    @endif

    <div class="container content">
        @yield('content')
    </div>
</div>

@include('layout.partials.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script src="{{ URL::asset('js/script.js') }}"></script>

@stack('javascript')

</body>
</html>