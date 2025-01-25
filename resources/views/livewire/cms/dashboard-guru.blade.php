<div>
    <div class="page-content"> 
        <section class="row">
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col col-mobile">
                        <div class="card">
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start col-icon ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9 col-information">
                                        <h6 class="text-muted font-semibold">Total Guru</h6>
                                        <h6 style="font-size: 26px;" class="font-extrabold mb-0">{{ $total_guru }}</h6>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="col col-mobile">
                        <div class="card">
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start col-icon ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9 col-information">
                                        <h6 class="text-muted font-semibold">Total Siswa</h6>
                                        <h6 style="font-size: 26px;" class="font-extrabold mb-0">{{ $total_siswa }}</h6>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>

                    <div class="col col-mobile">
                        <div class="card">
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start col-icon ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-9 col-information">
                                        <h6 class="text-muted font-semibold">Jumlah Kelas Diampu</h6>
                                        <h6 style="font-size: 26px;" class="font-extrabold mb-0">{{ $total_kelas_diampu }}</h6>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="col col-mobile">
                        <div class="card">
                            <div class="card-body px-4">
                                <div class="row">
                                    <div class="col-md-3 col-lg-12 col-xl-12 col-xxl-3 d-flex justify-content-start col-icon ">
                                        <div class="stats-icon purple mb-2">
                                            <i class="iconly-boldShow"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-lg-12 col-xl-12 col-xxl-9 col-information">
                                        <h6 class="text-muted font-semibold">Jumlah Siswa Diampu</h6>
                                        <h6 style="font-size: 26px;" class="font-extrabold mb-0">{{ $total_siswa_diampu }}</h6>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Laporan Absensi Siswa yang tidak hadir</h4>
                            </div>
                            <div class="card-body">
                                <canvas wire:ignore id="myChart" style="height: 550px; width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {!! json_encode($data) !!},
    });
</script>