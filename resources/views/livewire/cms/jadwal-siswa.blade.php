<style>
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .card {
        position: relative!important;
        width: 360px!important;
        height: 250px!important;
        border-radius: 14px!important;
        z-index: 1111!important;
        overflow: hidden!important;
        display: flex!important;
        flex-direction: column!important;
        align-items: center!important;
        justify-content: center!important;
        box-shadow: 20px 20px 60px #bebebe!important;
        margin-bottom: 20px!important; /* Ensure spacing between rows if items wrap */
        /* font-color: black; */
    }

    .bg {
        position: absolute;
        top: 5px;
        left: 5px;
        width: 95%;
        height: 240px;
        z-index: 2;
        background: rgba(255, 255, 255, .95);
        backdrop-filter: blur(24px);
        border-radius: 10px;
        overflow: hidden;
        outline: 2px solid white;
    }

    .blob {
        position: absolute;
        z-index: 1;
        top: 50%;
        left: 50%;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background-color: #ff0000;
        opacity: 1;
        filter: blur(12px);
        animation: blob-bounce 5s infinite ease;
    }
    
    li{
        color: black;
    }

    h5{
        color: black;
    }

    @keyframes blob-bounce {
        0% {
            transform: translate(-100%, -100%) translate3d(0, 0, 0);
        }

        25% {
            transform: translate(-100%, -100%) translate3d(100%, 0, 0);
        }

        50% {
            transform: translate(-100%, -100%) translate3d(100%, 100%, 0);
        }

        75% {
            transform: translate(-100%, -100%) translate3d(0, 100%, 0);
        }

        100% {
            transform: translate(-100%, -100%) translate3d(0, 0, 0);
        }
    }

    @media (min-width: 768px) {
        .card-container {
            justify-content: space-between;
        }
    }

    @media (min-width: 1024px) {
        .card-container {
            gap: 30px;
        }

        .card {
            flex-basis: calc(33.33% - 20px); /* Three columns */
        }
    }
</style>
<div>
    <a
        style="width: 20%; margin-bottom: 20px;"
        class="btn btn-primary btn-export"
        href="/cms/download-jadwal"
    >
        <i class="align-middle" data-feather="download"></i>
        Download Jadwal
    </a>
    <div class="card-container">
        <div class="card">
            <div class="bg">
                <h5 style="text-align: center; margin-top: 10px; color: black;">Senin</h5>
                <ul>
                    @foreach($jadwal_pelajaran_senin as $q)
                        <li>{{ $q->nama }} : {{ \Carbon\Carbon::parse($q->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($q->jam_selesai)->format('H.i') }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="blob"></div>
        </div>
        <div class="card">
            <div class="bg">
                <h5 style="text-align: center; margin-top: 10px; color: black;">Selasa</h5>
                <ul>
                    @foreach($jadwal_pelajaran_selasa as $q)
                        <li>{{ $q->nama }} : {{ \Carbon\Carbon::parse($q->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($q->jam_selesai)->format('H.i') }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="blob"></div>
        </div>
        <div class="card">
            <div class="bg">
                <h5 style="text-align: center; margin-top: 10px; color: black;">Rabu</h5>
                <ul>
                    @foreach($jadwal_pelajaran_rabu as $q)
                        <li>{{ $q->nama }} : {{ \Carbon\Carbon::parse($q->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($q->jam_selesai)->format('H.i') }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="blob"></div>
        </div>
        <div class="card">
            <div class="bg">
                <h5 style="text-align: center; margin-top: 10px; color: black;">Kamis</h5>
                <ul>
                    @foreach($jadwal_pelajaran_kamis as $q)
                        <li>{{ $q->nama }} : {{ \Carbon\Carbon::parse($q->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($q->jam_selesai)->format('H.i') }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="blob"></div>
        </div>
        <div class="card">
            <div class="bg">
                <h5 style="text-align: center; margin-top: 10px; color: black;">Jumat</h5>
                <ul>
                    @foreach($jadwal_pelajaran_jumat as $q)
                        <li>{{ $q->nama }} : {{ \Carbon\Carbon::parse($q->jam_mulai)->format('H.i') }} - {{ \Carbon\Carbon::parse($q->jam_selesai)->format('H.i') }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="blob"></div>
        </div>
    </div>
</div>