<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title[0] ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div>
                    <div class="row mb-3">
                        <div class="col-md-2">
                            @if($this->akses_export == 1)
                                <a
                                    style="float: left;"
                                    class="btn btn-primary btn-export"
                                    href="/cms/rapot?nisn={{session()->get('store_username')}}"
                                    target="_BLANK"
                                >
                                    <i class="align-middle" data-feather="download"></i>
                                    Rapot
                                </a>
                            @endif
                        </div>
                        <div class="col-md-2">
                            <select wire:model.live="kode_mapel" class="form-control btn-filter">
                                <option value="">Mata Pelajaran</option>
                                @foreach($mata_pelajaran as $k)
                                    <option value="{{ $k->kode_mapel }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select wire:model.live="semester_id" class="form-control btn-filter">
                                <option value="">Semester</option>
                                @foreach($semester as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-1 page-show">
                            <select class="form-control" wire:model.live="paginate" name="paginate" id="paginate">
                                <option value="10">Tampilkan</option>
                                <option value="10" selected>10</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                        <div class="col-md-5 btn-search-table">
                            <x-mazer-search />
                        </div>
                    </div>
                </div>
                <table class="table table-hover table-striped" style="width:100%; margin-top: 30px;">
                    <thead>
                        <tr>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>PH1</th>
                            <th>PH2</th>
                            <th>UTS</th>
                            <th>UAS</th>
                            <th>Nilai Keterampilan</th> <!-- Kolom Nilai Keterampilan -->
                            <th>Sikap</th> <!-- Kolom Sikap -->
                            <th>Kebersihan</th> <!-- Kolom Kebersihan -->
                            <th>Kedisiplinan</th> <!-- Kolom Kedisiplinan -->
                            <th>Nilai Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($get as $d)
                            <tr>
                                <td>{{ $d->mapel }}</td>
                                <td>{{ $d->kelas }}</td>
                                <td>{{ $d->ph1 }}</td>
                                <td>{{ $d->ph2 }}</td>
                                <td>{{ $d->uts }}</td>
                                <td>{{ $d->uas }}</td>
                                <td>{{ $d->nilai_keterampilan }}</td> <!-- Nilai Keterampilan -->
                                <td>{{ $d->sikap }}</td> <!-- Sikap -->
                                <td>{{ $d->kebersihan }}</td> <!-- Kebersihan -->
                                <td>{{ $d->kedisiplinan }}</td>
                                <td>{{ ($d->ph1 * 0.3) + ($d->ph2 * 0.3) + ($d->uts * 0.2) + ($d->uas * 0.2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100" class="text-center">
                                    No Data Found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                 <div class="table-description text-muted mt-1">
                <p style="margin-bottom: 0;">
                    *Keterangan:<br>
                    PH  : Penilaian Harian<br>
                    UTS : Ujian Tengah Semester<br>
                    UAS : Ujian Akhir Semester<br> <br>
                </p>
            </div>

                <div class="float-end">
                    {{ $get->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    window.addEventListener('notification-failed', event => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'warning',
            title: "Nilai sudah ada. Terimakasih"
        })
    })

    window.addEventListener('notification-success', event => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: "Nilai berhasil ditambahkan. Terimakasih"
        })
    })
</script>