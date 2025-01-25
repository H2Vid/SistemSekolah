<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title[0] ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <x-mazer-header />
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
                                <td>{{ $d->kode_kelas }}</td>
                                <td>{{ $d->kode_mapel }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->nip }}</td>
                                <td>{{ $d->jam_mulai }}</td>
                                <td>{{ $d->jam_selesai }}</td>
                                <td>{{ $d->hari }}</td>
                                <td>{{ $d->aktif }}</td>
                                <td>{{ $d->tahun_ajaran }}</td>
                                @if($this->akses_update == 1 || $this->akses_delete == 1)
                                <td style="text-align: right; white-space:nowrap;">
                                    @if($this->akses_update == 1)
                                        <button
                                            title="Ubah"
                                            class="btn btn-warning"
                                            wire:click="edit({{ $d->id }})"
                                            @click="new bootstrap.Modal(document.getElementById('mazer-modal')).show()"
                                        >
                                            <i class="align-middle" data-feather="edit"></i>
                                        </button>
                                    @endif
                                    @if($this->akses_delete == 1)
                                    <button
                                        title="Hapus"
                                        class="btn btn-danger"
                                        wire:click="confirmDelete({{ $d->id }})"
                                    >
                                        <i class="align-middle" data-feather="trash"></i>
                                    </button>
                                    @endif
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

    {{-- Create / Update Modal --}}
    <x-mazer-modal title="{{ $isUpdate ? 'Ubah' : 'Tambah' }} {{ $title[0] }}">
        <x-mazer-form submit="save">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Kode Kelas</label>
                    <x-mazer-input type="select" name="form.kode_kelas" id="kode_kelas">
                        <option value="">--Pilih Kelas--</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->kode_kelas }}">{{ $k->kode_kelas }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.kode_kelas" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Kode Mata Pelajaran</label>
                    <x-mazer-input type="select" name="form.kode_mapel" id="kode_mapel">
                        <option value="">--Pilih Kode Mata Pelajaran--</option>
                        @foreach($mata_pelajaran as $k)
                            <option value="{{ $k->kode_mapel }}">{{ $k->kode_mapel }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.kode_mapel" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <x-mazer-input type="select" name="form.semester_id" id="semester_id">
                        <option value="">--Pilih Semester--</option>
                        @foreach($semester as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.semester_id" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Guru</label>
                    <x-mazer-input type="select" name="form.nip" id="nip">
                        <option value="">--Pilih Guru--</option>
                        @foreach($guru as $k)
                            <option value="{{ $k->nip }}">{{ $k->nama }} - {{ $k->nip }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.nip" />
                </div>
            </div>

            <div class="col-md-12">
    <div class="mb-3">
        <label class="form-label">Tahun Ajaran</label>
        <x-mazer-input type="select" name="form.tahun_ajaran" id="tahun_ajaran">
            <option value="">--Pilih Tahun Ajaran--</option>
            <option value="2024/2025">2024/2025</option>
            <option value="2025/2026">2025/2026</option>
            <option value="2026/2027">2026/2027</option>
            <option value="2028/2029">2028/2029</option>
            <option value="2029/2030">2029/2030</option>
        </x-mazer-input>
        <x-mazer-input-error for="form.hari" />
    </div>
</div>

            <div class="col-md-6">
    <div class="mb-3">
        <label class="form-label">Jam Mulai</label>
        <x-mazer-input type="select" name="form.jam_mulai" id="jam_mulai">
            <option value="">--Pilih Jam Mulai--</option>
            <option value="07:00">07:00</option>
            <option value="07:45">07:45</option>
            <option value="08:30">08:30</option>
            <option value="09:15">09:15</option>
            <option value="10:10">10:10</option>
            <option value="10:55">10:55</option>
            <option value="12:10">12:10</option>
            <option value="12:55">12:55</option>
            <option value="13:00">13:00</option>
            <option value="13:45">13:45</option>
            <option value="14:30">14:30</option>
        </x-mazer-input>
        <x-mazer-input-error for="form.jam_mulai" />
    </div>
</div>

<div class="col-md-6">
    <div class="mb-3">
        <label class="form-label">Jam Selesai</label>
        <x-mazer-input type="select" name="form.jam_selesai" id="jam_selesai">
            <option value="">--Pilih Jam Selesai--</option>
            <option value="07:45">07:45</option>
            <option value="08:30">08:30</option>
            <option value="09:15">09:15</option>
            <option value="10:00">10:00</option>
            <option value="10:55">10:55</option>
            <option value="11:40">11:40</option>
            <option value="12:55">12:55</option>
            <option value="13:40">13:40</option>
            <option value="13:45">13:45</option>
            <option value="14:30">14:30</option>
            <option value="15:00">15:00</option>
        </x-mazer-input>
        <x-mazer-input-error for="form.jam_selesai" />
    </div>
</div>
            <div class="col-md-12">
    <div class="mb-3">
        <label class="form-label">Hari</label>
        <x-mazer-input type="select" name="form.hari" id="hari">
            <option value="">--Pilih Hari--</option>
            <option value="Senin">Senin</option>
            <option value="Selasa">Selasa</option>
            <option value="Rabu">Rabu</option>
            <option value="Kamis">Kamis</option>
            <option value="Jumat">Jumat</option>
        </x-mazer-input>
        <x-mazer-input-error for="form.hari" />
    </div>
</div>

            <div class="col-md-12">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <x-mazer-input type="select" name="form.aktif">
                        <option value="">Pilih Status</option>
                        <option value="Ya">Ya</option>
                        <option value="Tidak">Tidak</option>
                    </x-mazer-input>
                    <x-mazer-input-error for="form.aktif" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>

