<!DOCTYPE html>
<html lang="zxx">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') {{ $title ?? '' }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/fe') }}/favicon.ico" type="image/x-icon">
    @include('layouts.web.styles')
    @yield('styles')
    {{ $styles ?? '' }}

    <style>
      .register-select{
        height: 60px;
        padding-left: 25px;
        /* padding-right: 50px; */
        padding-top: 5px;
        padding-bottom: 5px;
        color: white;
        margin-bottom: 10px;
        font-size: 14px;
        
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 5px;
        /* background: transparent; */
        /* background-color:linear-gradient(-19deg, #4776e6 10%, #8e54e9) */
        transition: all 0.3s ease-in;

        background-color: #7062E7; 
      }
      .register-textarea{
        height: 130px;
        padding-left: 30px;
        padding-right: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
        color: white;
        margin-bottom: 10px;
        font-size: 14px;
        
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 5px;
        background: transparent;
        color: #fff;
        transition: all 0.3s ease-in;
      }
      .register-textarea::placeholder {
        color: #fff; /* Placeholder text color */
    }
    </style>
  </head>
  <body>
    <div class="notification-alert">
      <div class="notice-list"></div>
    </div>
    <!-- preloader begin-->
    <div class="preloader">
      <img src="{{ asset('assets/fe') }}/images/tenor.gif" alt="">
    </div>
    <!-- preloader end -->
    <div class="mobile-navbar-wrapper">
      <!-- header begin -->
      @include('layouts.web.main-header')
      @yield('content')
      {{ $slot ?? '' }}
       <!-- footer begin -->
      @include('layouts.web.main-footer')
      <!-- footer end -->
    </div>

    <!-- mobile navbar wrapper end -->
    <div class="d-xl-none d-lg-none d-block">
      <div class="mobile-navigation-bar">
        <ul>
          <li style="color: white;">
            <a href="#0"><img src="{{ asset('assets/fe') }}/images/profile.svg" alt=""></a> Beranda
          </li>
          <li style="color: white;">
            <a href="#0"><img src="{{ asset('assets/fe') }}/images/profile.svg" alt=""></a> Profile
          </li>
          <li style="color: white;">
            <a href="#0"><img src="{{ asset('assets/fe') }}/images/money-transfering.svg" alt=""></a> Tugas
          </li>
          <li style="color: white;">
            <a href="#0"><img src="{{ asset('assets/fe') }}/images/money-transfering.svg" alt=""></a> VIP
          </li>
          <li style="color: white;"><a href="#header"><img src="{{ asset('assets/fe') }}/images/arrow.svg" alt=""></a></li>
        </ul>
      </div>
    </div>
    <div class="d-xl-block d-lg-block d-none">
      <div class="back-to-top-btn">
        <a href="#"><img src="{{ asset('assets/fe') }}/images/arrow.svg" alt=""></a>
      </div>
    </div>
    @include('layouts.web.scripts')
    @yield('scripts')
    {{ $scripts ?? '' }}
    
  </body>
</html>