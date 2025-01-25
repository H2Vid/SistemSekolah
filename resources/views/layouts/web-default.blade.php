<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title') {{ $title ?? '' }}</title>
    <meta name="description" content>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/fe') }}/img/logo/favicon.png">
    @include('layouts.web.styles')
    @yield('styles')
    {{ $styles ?? '' }}
</head>

<body>
    <div id="preloader">
        <div class="preloader">
            <span></span>
            <span></span>
        </div>
    </div>
    <button class="scroll-top scroll-to-target" data-target="html">
        <i class="far fa-angle-double-up"></i>
    </button>
    @include('layouts.web.mobile-menu')
    <div class="body-overlay"></div>
    <header>
        @if ($title == "Home")
        <div class="it-header-transparent">
            @include('layouts.web.main-header')
            @include('layouts.web.main-menu')
        </div>
        @else
            @include('layouts.web.main-header')
            @include('layouts.web.main-menu')
        @endif
    </header>
    <main>
        @yield('content')
        {{ $slot ?? '' }}
        @include('layouts.web.main-subcriber')
    </main>
    @include('layouts.web.main-footer')
    @include('layouts.web.scripts')
    @yield('scripts')
    {{ $scripts ?? '' }}
</body>

</html>
