<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ siteSettings('siteName') }} | Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    {!! HTML::style('//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') !!}
    {!! HTML::style('//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css') !!}
    {!! HTML::style('static/admin/css/main.css') !!}
    <!--[if lt IE 9]>
    {!! HTML::style('https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js') !!}
    {!! HTML::style('https://oss.maxcdn.com/respond/1.4.2/respond.min.js') !!}
    <![endif]-->
</head>
<body class="skin-purple sidebar-mini">
@include('admin.master.notices')
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="{{ url("admin") }}" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>{{ siteSettings('siteName') }}</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

        </nav>
    </header>

    <aside class="main-sidebar">
        @include('admin/master/sidebar')
    </aside>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ $title }} @if(Request::is('admin')) <small>ArtVenue Version {{ config('version.version') }}</small>@endif</h1>
        </section>
        <!-- Main content -->
        <section class="content">

            @yield('content')

        </section>
        <!-- /.content -->
    </div>

    <footer class="main-footer">
        <strong>{{ siteSettings('siteName') }} &copy; {{ date('Y') }}.</strong>
        All rights reserved.
    </footer>
    <div class="control-sidebar-bg"></div>
</div>

{!! HTML::script('static/admin/main.js') !!}
@yield('extra-js')
</body>
</html>