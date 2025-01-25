<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title[0] ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            @if($this->akses_export == 1)
                                <button
                                    style="float: left;"
                                    class="btn btn-primary btn-export"
                                    wire:click="export('xlsx')"
                                >
                                    <i class="align-middle" data-feather="download"></i>
                                    Download
                                </button>
                            @endif
                        </div>
                        <div class="col-md-3">
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
                        <div class="col-md-4 btn-search-table">
                            <x-mazer-search />
                        </div>
                    </div>
                </div>
                <table class="table table-hover table-striped" style="width:100%; margin-top: 30px;">
                    <thead>
                        <tr>
                            <x-mazer-loop-th :$searchBy :$orderBy :$order />
                            @if($this->akses_update == 1 || $this->akses_delete == 1)
                            <th style="width: 8%!important; text-align: center;">
                                Aksi
                            </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($get as $d)
                            <tr>
                                <td>{{ $d->kelas }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->nisn }}</td>
                                @if($this->akses_update == 1 || $this->akses_delete == 1)
                                <td style="text-align: right; white-space:nowrap;">
                                    <a
                                        target="_BLANK"
                                        title="Download"
                                        class="btn btn-primary btn-sm"
                                        href="/cms/rapot?nisn={{ $d->nisn }}"
                                    >
                                        <i class="align-middle" data-feather="download"></i> Download
                                    </a>
                                    <button
                                        title="DETAIL"
                                        class="btn btn-success btn-sm"
                                        wire:click="detail('{{ $d->nisn }}')"
                                        @click="new bootstrap.Modal(document.getElementById('mazer-modal')).show()"
                                    >
                                        <i class="align-middle" data-feather="eye"></i> DETAIL
                                    </button>
                                    <button
                                        title="Ubah"
                                        class="btn btn-warning btn-sm"
                                        wire:click="change('{{ $d->nisn }}')"
                                        @click="new bootstrap.Modal(document.getElementById('change')).show()"
                                    >
                                        <i class="align-middle" data-feather="edit"></i> UBAH
                                    </button>
                                </td>
                                @endif
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

                <div class="float-end">
                    {{ $get->links() }}
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="modal fade" id="mazer-modal" tabindex="-1" aria-labelledby="mazer-modal-access-label" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mazer-modal-label">Data Nilai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Kode Kelas</label>
                                    <input type="text" class="form-control" value="{{ $head_kode_kelas }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" value="{{ $head_nama }}" readonly>
                                </div>
                                <div class="col-md-4">
                                    <label>NISN</label>
                                    <input type="text" class="form-control" value="{{ $head_nisn }}" readonly>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <div>
                                <div class="col-md-12">
                                    <x-mazer-search-detail />
                                    <table class="table table-hover table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <x-mazer-loop-th-detail :$searchByDetail :$orderByDetail :$orderDetail />
                                                <th>Nilai Akhir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($get_detail as $d)
                                                <tr>
                                                    <td>{{ $d->nama }}</td>
                                                    <td>{{ $d->ph1 }}</td>
                                                    <td>{{ $d->ph2 }}</td>
                                                    <td>{{ $d->uts }}</td>
                                                    <td>{{ $d->uas }}</td>
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
                                </div>
                            </div>
                            <div class="float-end">
                                {{ $get_detail->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="modal fade" id="change" tabindex="-1" aria-labelledby="change-label" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <form wire:submit="changeSubmit">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="change-label">Ubah Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Kode Kelas</label>
                                        <input type="text" class="form-control" value="{{ $head_kode_kelas }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" value="{{ $head_nama }}" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label>NISN</label>
                                        <input type="text" class="form-control" value="{{ $head_nisn }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="table-responsive">
                                <div>
                                    <div class="col-md-12">
                                        <x-mazer-search-detail />
                                        <table class="table table-hover table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <x-mazer-loop-th-detail :$searchByDetail :$orderByDetail :$orderDetail />
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($get_detail as $d)
                                                    <tr>
                                                        <td>{{ $d->nama }}</td>
                                                        <td><input type="text" class="form-control" value="{{ $d->ph1 }}"  wire:change="savePh1({{ $d->id }}, $event.target.value)"></td>
                                                        <td><input type="text" class="form-control" value="{{ $d->ph2 }}" wire:change="savePh2({{ $d->id }}, $event.target.value)"></td>
                                                        <td><input type="text" class="form-control" value="{{ $d->uts }}" wire:change="saveUts({{ $d->id }}, $event.target.value)"></td>
                                                        <td><input type="text" class="form-control" value="{{ $d->uas }}" wire:change="saveUas({{ $d->id }}, $event.target.value)"></td>
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
                                    </div>
                                </div>
                                <div class="float-end">
                                    {{ $get_detail->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
            title: "Nilai berhasil ubah. Terimakasih"
        })
    })
</script>