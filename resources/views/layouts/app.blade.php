<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIAKAD | SMK Negeri 16 Jakarta</title>



    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png">



    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- date range picker -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{ $styles ?? '' }}
    <style>
        @media only screen and (max-width: 600px) {
            .col-mobile{
                width: 100%!important;
                flex: 100%!important;
            }

            .col-icon{
                width: 30%!important;
            }
            
            .col-information{
                width: 70%!important;
                margin-left: -20px;
                margin-top: -2px;
            }

            .btn-search-table{
                width: 70%!important;
            }

            .page-show{
                width: 30%!important;
            }

            .btn-tambah-table{
                margin-top: 15px;
                width: 48%;
                font-size: 12px;
                margin-bottom: 15px;
            }

            .btn-export{
                margin-top: 15px;
                width: 48%;
                font-size: 12px;
                margin-bottom: 15px;
            }

            .btn-filter{
                margin-bottom: 15px;
                width: 100%!important;
            }
            .icon-profile{
                float: right;
                margin-top: -50px;
            }

            .btn-fitur-group{
                width: 100%;
            }
        }

        @media (min-width:601px)  { 
            .btn-fitur-group{
                float: left;
            }
            .footer-information{
                position:absolute;
                bottom:0;
                height:50px;
            }

            .btn-tambah-table{
                float: right;
            }

            .icon-profile{
                float: right;
            }
        }


        

        .table th{
            white-space:nowrap;
            background-color: #0075B7;
            color: white;
        }

        .stats-icon.purple{
            background-color: #0075B7;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered{
            display: block;
            width: 100%;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #607080;
            -webkit-appearance: none;
            appearance: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #dce7f1;
            border-radius: 0.25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .hide-input{
            display: none!important;
        }

        .sidebar-wrapper .sidebar-header img{
            height: 100%!important;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
        }
        .user-name {
    font-size: 16px;
    font-weight: bold;
}
        /* Style for the user info div */
        #userInfo {
            position: absolute;
            top: 30px; /* Adjust this value to position it properly */
            /* left: 200px; */
            right: 75px;
            transform: translateX(-50%);
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
            z-index: 1; /* Ensure it appears above the image */
            display: none;
        }

        /* Style for the links inside the user info div */
        #userInfo ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        #userInfo ul li {
            margin-bottom: 5px;
        }

        #userInfo ul li a {
            text-decoration: none;
            color: #333;
        }

        #userInfo ul li a:hover {
            color: #007bff;
        }

        .avatar.avatar-xl .avatar-content, .avatar.avatar-xl img{
            width: 45px;
            height: 45px;
        }
    </style>
</head>

<body>
    <script src="{{ asset('assets/static/js/initTheme.js') }}" data-navigate-once="true"></script>
    <div id="app">
        @include('layouts.partials.sidebar')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none" id="sidebar-toggle">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div style="margin-top: -5px;"></div>
            <div class="page-title">
                <div class="row">
                    <div class="col-12 col-md-9 order-md-1 order-last">
                        <nav aria-label="breadcrumb" class="breadcrumb-header" style="margin-top: 20px;">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}/cms/dashboard">Dashboard</a></li>
                                @if($title[0] != "Dashboard")
                                <li class="breadcrumb-item active" aria-current="page">{{ $title[0] }}</li>
                                @endif
                            </ol>
                        </nav>
                    </div>
                    <div class="col-12 col-md-3 order-md-2 order-first">
                    <div class="d-flex align-items-center justify-content-end">
        <!-- Tampilkan nama pengguna di sebelah kiri ikon profil -->
        <div class="user-name" style="margin-right: 10px;">
        <a href="{{ url('/') }}/cms/profile">{{ Auth::user()->name }}</a>
        </div>
                        <div class="d-flex align-items-center icon-profile" style="float: right;">
                            <div class="avatar avatar-xl" id="avatarDiv">
                                <img src="{{ asset('storage/'.$title[1]) }}">
                            </div>
                            <div id="userInfo" style="display: none;">
                                <ul>
                                    <li><a href="#" id="btn-logout-2">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="margin-top: 20px;"></div>
            {{ $slot ?? '' }}

            @persist('footer')
                @if($title[0] != "Profile")
                    @include('layouts.partials.footer')
                @endif
            @endpersist
        </div>
    </div>
    <x-javascript />
    {{ $scripts ?? '' }}
    <script>
        // Get the image and the user info div
        var avatarImage = document.getElementById('avatarDiv');
        var userInfo = document.getElementById('userInfo');

        // Add click event listener to the image
        avatarImage.addEventListener('click', function() {
            // Toggle the visibility of the user info div
            if (userInfo.style.display === 'none') {
                userInfo.style.display = 'block';
            } else {
                userInfo.style.display = 'none';
            }
        });
    </script>
</body>

</html>
