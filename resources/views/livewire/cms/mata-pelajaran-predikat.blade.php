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
                                <td>{{ $d->predikat }}</td>
                                <td>{{ $d->keterangan }}</td>
                                <td>{{ $d->nilai_min }}</td>
                                <td>{{ $d->nilai_max }}</td>
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
        <x-mazer-form submit="savePredikat({{ $id_mapel }})">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Predikat </label>
                    <x-mazer-input name="form.predikat" placeholder="Predikat" />
                    <x-mazer-input-error for="form.predikat" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <x-mazer-input name="form.keterangan" placeholder="Keterangan" />
                    <x-mazer-input-error for="form.keterangan" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Min. Nilai</label>
                    <x-mazer-input name="form.nilai_min" placeholder="Min. Nilai" />
                    <x-mazer-input-error for="form.nilai_min" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Max. Nilai</label>
                    <x-mazer-input name="form.nilai_max" placeholder="Max. Nilai" />
                    <x-mazer-input-error for="form.nilai_max" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>

