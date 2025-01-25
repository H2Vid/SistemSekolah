<div>
    
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title[0] ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" style="width:100%;margin-top: -10px;">
                <x-mazer-header />
                <table class="table table-hover table-striped" style="width:100%; margin-top: 30px;">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No</th>
                            <x-mazer-loop-th :$searchBy :$orderBy :$order />
                            <th style="width: 11%!important; text-align: center;">
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
                                <td style="white-space:nowrap;">{{ $d->nama }}</td>
                                <td style="white-space:nowrap;">{{ $d->nip }}</td>
                                <td>{{ $d->jenis_kelamin }}</td>
                                <td>{{ $d->tanggal_lahir }}</td>
                                <td>{{ $d->no_hp }}</td>
                                <td>{{ $d->email }}</td>
                                <!-- <td><a target="_BLANK" href="{{ asset('storage/guru/' . $d->image) }}">Lihat foto</a></td> -->
                                <td style="text-align: right; white-space:nowrap;">
                                    <button
                                        title="Change Password"
                                        class="btn btn-success"
                                        wire:click="changePassword('{{ $d->nip }}')"
                                    >
                                        <i class="align-middle" data-feather="key"></i>
                                    </button>
                                    <button
                                        title="Detail"
                                        class="btn btn-primary"
                                        wire:click="detail('{{ $d->nip }}')"
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
                    <label class="form-label">NIP</label>
                    <x-mazer-input name="form.nip" placeholder="NIP" />
                    <x-mazer-input-error for="form.nip" />
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
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Foto</label>
                    <x-mazer-image-preview :$image :form_image="$form->image" prefix_url="guru" />
                    <x-mazer-input type="file" name="image" />
                    <x-mazer-input-error for="form.image" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <x-mazer-input type="select" name="form.status_guru">
                        <option value="">Pilih Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </x-mazer-input>
                    <x-mazer-input-error for="form.status_guru" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>

    <div>
        <div class="modal fade" id="mazer-modal-detail" tabindex="-1" aria-labelledby="mazer-modal-detail-label" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mazer-modal-detail-label">Detail Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3">
                                    <img src="{{ asset('storage/guru/' . $this->detail_image) }}" style="width: 100%;">
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">NIP</label>
                                            <input type="text" class="form-control" value="{{ $this->detail_nip }}" readonly />
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