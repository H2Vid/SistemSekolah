<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title[0] ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <div>
                    <div class="row mb-3">
                        @if($this->akses_insert == 1)
                            <div class="col-md-3">
                                <div class="btn-fitur-group" style="float: left;">
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
                                <select wire:model.live="kode_mapel" class="form-control btn-filter" wire:change="changeMataPelajaran($event.target.value)">
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
                        @else
                            <div class="col-md-3">
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
                                <select wire:model.live="kode_mapel" class="form-control btn-filter" wire:change="changeMataPelajaran($event.target.value)">
                                    <option value="">Mata Pelajaran</option>
                                    @foreach($mata_pelajaran as $k)
                                        <option value="{{ $k->kode_mapel }}">{{ $k->nama }}</option>
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
                        @endif
                        
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
                                <td>{{ $d->mapel }}</td>
                                <td>{{ $d->kelas }}</td>
                                <td>{{ $d->nama_file }}</td>
                                <td>{{ $d->tanggal_upload }}</td>
                                <td style="white-space:nowrap;"><a target="_BLANK" href="{{ asset('storage/modul/' . $d->file_upload) }}">Lihat file</a></td>
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
        <x-mazer-form submit="saveWithUpload">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Nama Modul</label>
                    <x-mazer-input name="form.nama_file" placeholder="Nama Modul" />
                    <x-mazer-input-error for="form.nama_file" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <x-mazer-input type="select" name="form.id_jadwal" id="id_jadwal">
                        <option value="">--Pilih Mata Pelajaran--</option>
                        @foreach($list_jadwal as $lj)
                            <option value="{{ $lj->id }}">{{ $lj->nama }} - {{ $lj->kelas }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.jenis_kelamin" />
                </div>
            </div>
            <div class="col-md-12">
            <div class="mb-3">
    <label class="form-label">File</label>
    <x-mazer-input type="file" name="file_upload" />
    <x-mazer-input-error for="form.file_upload" />
    <!-- Menambahkan pesan maksimal ukuran file -->
    <small class="text-muted">Upload file maksimal 2MB. File yang diterima: PDF, DOC, DOCX, PPT, PPTX.</small>
</div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>

