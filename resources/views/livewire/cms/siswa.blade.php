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
                            <div class="col-md-4">
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
                            <div class="col-md-3">
                                <select wire:model.live="kode_kelas" class="form-control btn-filter">
                                    <option value="">Kode Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->kode_kelas }}">{{ $k->kode_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="col-md-3">
                                <div class="btn-fitur-group">
                                    @if($this->akses_export == 1)
                                        <button
                                            style="float: left; margin-left: 10px;"
                                            class="btn btn-primary btn-export"
                                            wire:click="export('xlsx')"
                                        >
                                            <i class="align-middle" data-feather="download"></i>
                                            Download
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select wire:model.live="kode_kelas" class="form-control btn-filter">
                                    <option value="">Kode Kelas</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->kode_kelas }}">{{ $k->kode_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
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
                            <th style="text-align: center;">No</th>
                            <x-mazer-loop-th :$searchBy :$orderBy :$order />
                            <th style="width: 8%!important; text-align: center;">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                        @endphp
                        @forelse($get as $d)
                            <tr>
                                <td style="text-align: center;">{{ $counter }}</td>
                                <td style="white-space: nowrap;">{{ $d->nama }}</td>
                                <td style="white-space: nowrap;">{{ $d->nisn }}</td>
                                <td style="white-space: nowrap;">{{ $d->nama_semester }}</td>
                                <td style="white-space:nowrap;">{{ $d->jenis_kelamin }}</td>
                                <td>{{ $d->kode_kelas }}</td>
                                <td>{{ $d->no_hp }}</td>
                                <td>{{ $d->tanggal_lahir }}</td>
                                <td style="text-align: right; white-space:nowrap;">
                                    @if(session()->get('role_id') == 1)
                                    <button
                                        title="Change Password"
                                        class="btn btn-success"
                                        wire:click="changePassword('{{ $d->nisn }}')"
                                    >
                                        <i class="align-middle" data-feather="key"></i>
                                    </button>
                                    @endif
                                    <button
                                        title="Detail"
                                        class="btn btn-primary"
                                        wire:click="detail('{{ $d->nisn }}')"
                                        @click="new bootstrap.Modal(document.getElementById('mazer-modal-detail')).show()"
                                    >
                                        <i class="align-middle" data-feather="eye"></i>
                                    </button>
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

                <div class="float-end">
                    {{ $get->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Create / Update Modal --}}
    <x-mazer-modal title="{{ $isUpdate ? 'Ubah' : 'Tambah' }} {{ $title[0] }}">
        <x-mazer-form submit="saveWithUpload">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">NISN</label>
                    <x-mazer-input name="form.nisn" placeholder="NISN" />
                    <x-mazer-input-error for="form.nisn" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <x-mazer-input name="form.nama" placeholder="Nama" />
                    <x-mazer-input-error for="form.nama" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <x-mazer-input type="date" name="form.tanggal_lahir" placeholder="Tanggal Lahir" />
                    <x-mazer-input-error for="form.tanggal_lahir" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <x-mazer-input name="form.tempat_lahir" placeholder="Tempat Lahir" />
                    <x-mazer-input-error for="form.tempat_lahir" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <x-mazer-input type="select" name="form.jenis_kelamin" id="jenis_kelamin">
                        <option value="">--Pilih Jenis Kelamin--</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </x-mazer-input>
                    <x-mazer-input-error for="form.jenis_kelamin" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Agama</label>
                    <x-mazer-input type="select" name="form.agama" id="agama">
                        <option value="">--Pilih Agama--</option>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Budha">Budha</option>
                    </x-mazer-input>
                    <x-mazer-input-error for="form.agama" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">No. HP</label>
                    <x-mazer-input name="form.no_hp" placeholder="No. HP" />
                    <x-mazer-input-error for="form.no_hp" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <x-mazer-input type="email" name="form.email" placeholder="Email" />
                    <x-mazer-input-error for="form.email" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <x-mazer-input type="textarea" name="form.alamat" placeholder="Alamat" />
                    <x-mazer-input-error for="form.alamat" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Nama Wali</label>
                    <x-mazer-input name="form.nama_wali" placeholder="Nama Wali" />
                    <x-mazer-input-error for="form.nama_wali" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">No. Telpon Wali</label>
                    <x-mazer-input name="form.no_telepon_wali" placeholder="No. Telpon Wali" />
                    <x-mazer-input-error for="form.no_telepon_wali" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Angkatan</label>
                    <x-mazer-input name="form.angkatan" placeholder="Angkatan" />
                    <x-mazer-input-error for="form.angkatan" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Kode Kelas</label>
                    <x-mazer-input type="select" name="form.kode_kelas" id="kode_kelas">
                        <option value="">--Pilih Kelas--</option>
                        @foreach($form_kelas as $k)
                            <option value="{{ $k->kode_kelas }}">{{ $k->nama }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.kode_kelas" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Semester</label>
                    <x-mazer-input type="select" name="form.semester_id" id="semester_id">
                        <option value="">--Pilih Semester--</option>
                        @foreach($form_semester as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.semester_id" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <x-mazer-image-preview :$image :form_image="$form->image" prefix_url="siswa" />
                    <x-mazer-input type="file" name="image" />
                    <x-mazer-input-error for="form.image" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <x-mazer-input type="select" name="form.status_siswa">
                        <option value="">Pilih Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </x-mazer-input>
                    <x-mazer-input-error for="form.status_siswa" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>

    <div>
        <div class="modal fade" id="mazer-modal-detail" tabindex="-1" aria-labelledby="mazer-modal-detail-label" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg" style="min-width:1200px!important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mazer-modal-detail-label">Detail Siswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="{{ asset('storage/siswa/' . $this->detail_image) }}" style="width: 100%;">
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">NISN</label>
                                            <input type="text" class="form-control" value="{{ $this->detail_nisn }}" readonly />
                                        </div>
                                        <div class="col-md-12" style="margin-top: 15px;">
                                            <label class="form-label">Nama</label>
                                            <input type="text" class="form-control" value="{{ $this->detail_nama }}" readonly />
                                        </div>
                                        <div class="col-md-6" style="margin-top: 15px;">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="text" class="form-control" value="{{ $this->detail_tanggal_lahir }}" readonly />
                                        </div>
                                        <div class="col-md-6" style="margin-top: 15px;">
                                            <label class="form-label">Tempat Lahir</label>
                                            <input type="text" class="form-control" value="{{ $this->detail_tempat_lahir }}" readonly />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_jenis_kelamin }}" readonly />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Agama</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_agama }}" readonly />
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_no_hp }}" readonly />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_email }}" readonly />
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-12">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_alamat }}" readonly />
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-6">
                                    <label class="form-label">Nama Wali</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_nama_wali }}" readonly />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">No. Telepon Wali</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_no_telepon_wali }}" readonly />
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-6">
                                    <label class="form-label">Angkatan</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_angkatan }}" readonly />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Kode Kelas</label>
                                    <input type="text" class="form-control" value="{{ $this->detail_kode_kelas }}" readonly />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
window.addEventListener('update-password', event => {
    alert('password berhasil diubah');
})
</script>