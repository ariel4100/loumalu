<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    @if($metas->firstWhere('url', request()->path()))
        @php($meta = $metas->firstWhere('url', request()->path()))
        <title>{{ env('APP_NAME') }} {{ $meta->title ? ' - '.@$meta->title : '' }}</title>
        <meta name="description" content="{!! @$meta->description !!}" />
        <meta name="keywords" content="{{ @$meta->keyword }}" />
    @else
        <title>{{ env('APP_NAME') }} {{ @$title ? ' - '.@$title : '' }}</title>
    @endif
    {{--        @foreach($metas ?? [] as $m)--}}
    {{--            @php(strtolower(@$m['section']) == 'inicio' ? @$m['section'] = '/' : '')--}}
    {{--            @if(request()->path() == strtolower(@$m['section']))--}}
    {{--                --}}{{--            @dd(request()->path())--}}
    {{--                <meta name="description" content="{!! @$m['description'] !!}" />--}}
    {{--                <meta name="keywords" content="{{ @$m['keyword'] }}" />--}}
    {{--            @endif--}}
    {{--        @endforeach--}}
    <meta property="og:image" content="{{ asset($favicon ?? '') }}">
    <link rel="icon" href="{{ asset($favicon ?? '') }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Font awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('style/app.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">
    <link rel="stylesheet" href="{{ asset('style/page.css') }}">
    <!--------------SLICK------------------->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css"/>

    <!-- Scripts -->
    @routes
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="font-sans antialiased">
@inertia

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>

<script>
    $(document).ready(function() {
        new WOW().init();

    });
</script>
</body>
</html>
