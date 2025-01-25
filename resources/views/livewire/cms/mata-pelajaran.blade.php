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
                                <td>{{ $d->kode_mapel }}</td>
                                <td>{{ $d->tingkat_kelas }}</td>
                                <td>{{ $d->nama }}</td>
                                <td>{{ $d->nilai_kkm }}</td>
                                <td>{{ $d->kategori }}</td>
                                @if($this->akses_update == 1 || $this->akses_delete == 1)
                                <td style="text-align: right; white-space:nowrap;">
                                    <a
                                        title="Predikat"
                                        class="btn btn-primary"
                                        href="/cms/predikat?id={{$d->id}}"
                                    >
                                        <i class="align-middle" data-feather="plus"></i> Predikat
                                    </a>
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
                    <label class="form-label">Kode Mata Pelajaran</label>
                    <x-mazer-input name="form.kode_mapel" placeholder="Kode Mata Pelajaran" />
                    <x-mazer-input-error for="form.kode_mapel" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <x-mazer-input name="form.nama" placeholder="Nama" />
                    <x-mazer-input-error for="form.nama" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Tingkat Kelas</label>
                    <x-mazer-input type="select" name="form.tingkat_kelas" id="tingkat_kelas">
                        <option value="">--Pilih Kelas--</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->tingkat_kelas }}">{{ $k->tingkat_kelas }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.tingkat_kelas" />
                </div>
            </div>
            <div class="col-md-12">
            <div class="col-md-12">
    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <x-mazer-input type="select" name="form.kategori" id="kategori">
            <option value="">--Pilih Kategori Mata Pelajaran--</option>
            @foreach($kategoriOptions as $kategori)
                <option value="{{ $kategori }}" @if($kategori == $this->kategori) selected @endif>{{ $kategori }}</option>
            @endforeach
        </x-mazer-input>
        <x-mazer-input-error for="form.kategori" />
    </div>
</div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Nilai KKM</label>
                    <x-mazer-input name="form.nilai_kkm" placeholder="Nilai KKM" />
                    <x-mazer-input-error for="form.nilai_kkm" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>

