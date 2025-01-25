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
                                <td>{{ $d->nip }}</td>
                                <td>{{ $d->kode_kelas }}</td>
                                <td>{{ $d->tingkat_kelas }}</td>
                                <td>{{ $d->nama }}</td>
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
                    <label class="form-label">Guru</label>
                    <x-mazer-input type="select" name="form.nip" id="nip">
                        <option value="">--Pilih Guru--</option>
                        @foreach($guru as $g)
                            <option value="{{ $g->nip }}">{{ $g->nip }} - {{ $g->nama }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.nip" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Kode Kelas</label>
                    <x-mazer-input name="form.kode_kelas" placeholder="Kode Kelas" />
                    <x-mazer-input-error for="form.kode_kelas" />
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
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>

                    </x-mazer-input>
                    <x-mazer-input-error for="form.tingkat_kelas" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>

