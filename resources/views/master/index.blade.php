<!doctype html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', e($title)) - {{ siteSettings('siteName') }}</title>
    @yield('meta', '<meta name="description" content="'.siteSettings('description').'">')
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
    <!--[if IE 8]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link href='//fonts.googleapis.com/css?family=Open+Sans:300italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    {!! HTML::style('static/css/main.css') !!}
    {!! HTML::style('static/css/style.min.css') !!}
    @yield('style')
</head>
<body>
@include('master/notices')
@include('master/navbar')
@yield('above-container')
<div class="container">
    <div class="row">
        @yield('custom')
        @if(Request::segment(1) != 'upload')
            <div class="col-md-9">
                @yield('content')
            </div>
        @section('sidebar')
            <div class="col-md-3">
                @include('gallery/sidebar')
            </div>
        @show
        @endif
    </div>
</div>
@yield('pagination')
@include('master/footer')
@yield('gmaps')
{!! HTML::script('static/js/main.js') !!}
{!! HTML::script('static/js/custom.min.js') !!}
@yield('extrafooter')
</body>
</html>