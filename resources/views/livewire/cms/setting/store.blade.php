<div style="margin-top: -20px;">
    <div class="card" style="margin-bottom: 1rem!important;">
        <div class="card-header">
            <h5 class="card-title">{{ $title ?? '' }}</h5>
        </div>
        <div class="card-body">
            <form wire:submit="saveWithUpload">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <x-mazer-input name="form.name" placeholder="Nama toko" />
                                    <x-mazer-input-error for="form.name" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Nomor Handphone</label>
                                    <x-mazer-input name="form.phone" placeholder="Nomor HP" />
                                    <x-mazer-input-error for="form.phone" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">E-mail</label>
                                    <x-mazer-input name="form.email" placeholder="Email toko / pribadi" />
                                    <x-mazer-input-error for="form.email" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Tipe (Pusat / Cabang)</label>
                                    <x-mazer-input type="select-normal" name="form.type">
                                        <option value="">--Pilih Tipe--</option>
                                        <option value="1">Pusat</option>
                                        <option value="2">Cabang</option>
                                    </x-mazer-input>
                                    <x-mazer-input-error for="form.type" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <x-mazer-input type="textarea" name="form.address" placeholder="Alamat toko" />
                            <x-mazer-input-error for="form.address" />
                        </div>
                        <br>
                        <hr>
                        <button
                            style="width: 100%!important;"
                            class="btn btn-success"
                            wire:loading.attr="disabled"
                            wire:target="saveWithUpload"
                            type="submit">
                            <i class="align-middle" data-feather="save"></i>
                            Simpan
                        </button>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <x-mazer-image-preview :$image :form_image="$form->image" prefix_url="store" height="290px;" width="100%" />
                            <x-mazer-input type="file" name="image" />
                            <x-mazer-input-error for="form.image" />
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="">
                <div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <h5 class="card-title">Data Cabang</h5>
                        </div>
                        <div class="col-md-6">
                            <x-mazer-search />
                        </div>
                        <div class="col-md-2">
                            <x-mazer-create-btn width="100%;"/>
                        </div>
                    </div>
                </div>
                <table class="table table-hover table-striped" style="width:100%;">
                    <thead>
                        <tr>
                            <x-mazer-loop-th :$searchBy :$orderBy :$order />
                            <th style="width: 8%!important; text-align: center;">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($get as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->phone }}</td>
                                <td>{{ $d->email }}</td>
                                <td>{{ $d->address }}</td>
                                <td style="text-align: right;">
                                    <button
                                        title="Ubah"
                                        class="btn btn-warning"
                                        wire:click="editCabang({{ $d->id }})"
                                        @click="new bootstrap.Modal(document.getElementById('mazer-modal')).show()"
                                    >
                                        <i class="align-middle" data-feather="edit"></i>
                                    </button>
                                    <button
                                        title="Hapus"
                                        class="btn btn-danger"
                                        wire:click="confirmDelete({{ $d->id }})"
                                    >
                                        <i class="align-middle" data-feather="trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100" class="text-center">
                                    Data tidak ditemukan
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
    <x-mazer-modal title="{{ $isUpdate ? 'Ubah' : 'Tambah' }} {{ $title }}">
        <form wire:submit="saveCabang">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Toko Cabang</label>
                        <x-mazer-input name="formCabang.name" placeholder="Nama Toko Cabang" />
                        <x-mazer-input-error for="formCabang.name" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Nomor HP</label>
                        <x-mazer-input name="formCabang.phone" placeholder="Nomor Handphone" />
                        <x-mazer-input-error for="formCabang.phone" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <x-mazer-input name="formCabang.email" placeholder="Email" />
                        <x-mazer-input-error for="formCabang.email" />
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <x-mazer-input type="textarea" name="formCabang.address" placeholder="Alamat toko" />
                        <x-mazer-input-error for="formCabang.address" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="float-end">
                        <button
                            class="btn btn-success"
                            id="form-add"
                            wire:loading.attr="disabled"
                            wire:target="saveCabang"
                            type="submit">
                            <i class="align-middle" data-feather="save"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </x-mazer-modal>
</div>
