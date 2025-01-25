<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title[0] ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="btn-fitur-group">
                                @if($this->akses_export == 1)
                                    <button
                                        style="float: right; margin-left: 10px;"
                                        class="btn btn-primary btn-export"
                                        wire:click="export('xlsx')"
                                    >
                                        <i class="align-middle" data-feather="download"></i>
                                        Download
                                    </button>
                                @endif
                                <button
                                    class="btn btn-success btn-tambah-table"
                                    wire:loading.attr="disabled"
                                    wire:target="create"
                                    wire:click="create"
                                    @click="new bootstrap.Modal(document.getElementById('mazer-modal')).show()"
                                >
                                    <i class="align-middle" data-feather="plus-circle"></i>
                                    Tambah
                                </button>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select wire:model.live="kode_mapel" class="form-control btn-filter">
                                <option value="">Mata Pelajaran</option>
                                @foreach($mata_pelajaran as $k)
                                    <option value="{{ $k->kode_mapel }}">{{ $k->nama }} - {{ $k->kode_mapel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select wire:model.live="kode_kelas" class="form-control btn-filter">
                                <option value="">Kelas</option>
                                @foreach($kelas as $k)
                                    <option value="{{ $k->kode_kelas }}">{{ $k->kode_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select  class="form-control btn-filter">
                                <option value="">Tahun Ajaran</option>
                                    <option >2024/2025</option>
                                    <option >2023/2024</option>
                                    <option >2022/2023</option>
                                    <option >2021/2024</option>
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
                          <th>Nilai Akhir</th>
                            <th style="width: 8%!important; text-align: center;">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($get as $d)
                            <tr>
                                <td>{{ $d->mapel }}</td>
                                <td>{{ $d->kelas }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->nama_semester }}</td>
                                <td>{{ $d->ph1 }}</td>
                                <td>{{ $d->ph2 }}</td>
                                <td>{{ $d->uts }}</td>
                                <td>{{ $d->uas }}</td>
                                <td>{{ $d->nilai_keterampilan }}</td> <!-- Kolom Nilai Keterampilan -->
                                <td>{{ $d->sikap }}</td> <!-- Kolom Sikap -->
                                <td>{{ $d->kebersihan }}</td> <!-- Kolom Kebersihan -->
                                <td>{{ $d->kedisiplinan }}</td> <!-- Kolom Kedisiplinan -->
                                <td>{{ ($d->ph1 * 0.3) + ($d->ph2 * 0.3) + ($d->uts * 0.2) + ($d->uas * 0.2) }}</td>
                                <td style="text-align: right; white-space:nowrap;">
                                    <button
                                        title="Ubah"
                                        class="btn btn-warning"
                                        wire:click="change({{ $d->id }})"
                                        @click="new bootstrap.Modal(document.getElementById('change')).show()"
                                    >
                                        <i class="align-middle" data-feather="edit"></i> Ubah Nilai
                                    </button>
                                </td>
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

    {{-- Create / Update Modal --}}
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
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Mata Pelajaran</label>
                                        <input type="hidden" class="form-control" wire:model="change_id" name="change_id" readonly>
                                        <input class="form-control" wire:model="change_kode_mapel" name="change_kode_mapel" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Kelas</label>
                                        <input class="form-control" wire:model="change_kode_kelas" name="change_kode_kelas" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Semester</label>
                                        <input class="form-control" wire:model="change_semester" name="change_semester" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">NISN</label>
                                        <input class="form-control" wire:model="change_nisn" name="change_nisn" readonly>
                                    </div>
                                </div>
                                <div class="row">


                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Siswa</label>
                                        <input class="form-control" wire:model="change_nama" name="change_nama" readonly>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">PH1</label>
                                        <input class="form-control" wire:model="change_ph1" name="change_ph1">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">PH2</label>
                                        <input class="form-control" wire:model="change_ph2" name="change_ph2">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">UTS</label>
                                        <input class="form-control" wire:model="change_uts" name="change_uts">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label">UAS</label>
                                        <input class="form-control" wire:model="change_uas" name="change_uas">
                                    </div>
                                </div>
                                <div class="col-md-3">
    <div class="mb-3">
        <label class="form-label">Nilai Keterampilan</label>
        <input class="form-control" wire:model="change_nilai_keterampilan" name="change_nilai_keterampilan">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-3">
        <label class="form-label">Sikap</label>
        <input class="form-control" wire:model="change_sikap" name="change_sikap">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-3">
        <label class="form-label">Kebersihan</label>
        <input class="form-control" wire:model="change_kebersihan" name="change_kebersihan">
    </div>
</div>
<div class="col-md-3">
    <div class="mb-3">
        <label class="form-label">Kedisiplinan</label>
        <input class="form-control" wire:model="change_kedisiplinan" name="change_kedisiplinan">
    </div>
</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="float-end">
                                            <button
                                                class="btn btn-warning"
                                                wire:loading.attr="disabled"
                                                wire:target="changeSubmit"
                                                type="reset">
                                                <i class="align-middle" data-feather="refresh-ccw"></i>
                                                Reset
                                            </button>
                                        <button
                                            class="btn btn-success"
                                            id="form-add"
                                            wire:loading.attr="disabled"
                                            wire:target="changeSubmit"
                                            type="submit">
                                            <i class="align-middle" data-feather="save"></i>
                                            Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-mazer-modal title="{{ $isUpdate ? 'Ubah' : 'Tambah' }} {{ $title[0] }}">
        <x-mazer-form submit="simpan">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Kode Mata Pelajaran</label>
                    <select wire:model="value_kode_mapel" name="value_kode_mapel" wire:change="changeMataPelajaran($event.target.value)" class="form-control">
                        <option value="">--Pilih Kode Mata Pelajaran--</option>
                        @foreach($mata_pelajaran as $k)
                            <option value="{{ $k->kode_mapel }}">{{ $k->nama }} - {{ $k->kode_mapel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Kode Kelas</label>
                    <select wire:model="value_kode_kelas" name="value_kode_kelas" wire:change="changeKelas($event.target.value)" class="form-control">
                        <option value="">--Pilih Kelas--</option>
                        @foreach($input_kelas as $m)
                            <option value="{{ $m->kode_kelas }}">{{ $m->kode_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <select wire:model="value_semester_id" name="value_semester_id" wire:change="changeSemester($event.target.value)" class="form-control">
                        <option value="">--Pilih Semester--</option>
                        @foreach($semester as $m)
                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-md-12">
                <div class="table-responsive">
                    <div>
                        <div class="col-md-12">
                            <table class="table table-hover table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        <th>PH1</th>
                                        <th>PH2</th>
                                        <th>UTS</th>
                                        <th>UAS</th>
                                        <th>Keterampilan</th>
                                        <th>Sikap</th>
                                        <th>Kedisiplinan</th>
                                        <th>Kebersihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @forelse($list_siswa as $d)
                                    <tr>
                                    <td>{{ $counter }}</td>
                                    <td>{{ $d->nama }}</td>
                                    <td>
                                        <input type="text" class="form-control" name="ph1.{{ $d->id }}" wire:model="ph1.{{ $d->id }}" style="width: 50px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="ph2.{{ $d->id }}" wire:model="ph2.{{ $d->id }}" style="width: 50px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="uts.{{ $d->id }}" wire:model="uts.{{ $d->id }}" style="width: 50px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="uas.{{ $d->id }}" wire:model="uas.{{ $d->id }}" style="width: 50px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="nilai_keterampilan.{{ $d->id }}" wire:model="nilai_keterampilan.{{ $d->id }}" style="width: 50px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="sikap.{{ $d->id }}" wire:model="sikap.{{ $d->id }}" style="width: 50px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="kedisiplinan.{{ $d->id }}" wire:model="kedisiplinan.{{ $d->id }}" style="width: 50px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="uas.{{ $d->id }}" wire:model="kebersihan.{{ $d->id }}" style="width: 50px;">
                                    </td>
                                </tr>
                                        @php
                                            $counter++;
                                        @endphp
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
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
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
            title: "Data nilai untuk semester ini sudah ada. Silakan periksa kembali."
        })
    })

    window.addEventListener('notification-error', event => {
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
            title: "Harap periksa kembali inputan anda"
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
    window.addEventListener('close-modal', event => {
    const closeButton = document.querySelector('#change .btn-close');
    if (closeButton) {
        closeButton.click(); // Klik tombol close untuk menutup modal
    }
});

</script>