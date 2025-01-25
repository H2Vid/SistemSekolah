<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <x-mazer-header />
                <table class="table table-hover table-striped" style="width:100%">
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
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->username }}</td>
                                <td>{{ $d->email }}</td>
                                <td>{{ $d->role }}</td>
                                @if($this->akses_update == 1 || $this->akses_delete == 1)
                                <td style="text-align: right;">
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
    <x-mazer-modal title="{{ $isUpdate ? 'Ubah' : 'Tambah' }} {{ $title }}">
        <x-mazer-form submit="save">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Level</label>
                    <x-mazer-input type="select" name="form.role_id" id="role_id">
                        <option value="">--Pilih Level--</option>
                        @foreach($roles as $item)
                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.role_id" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Pusat / Cabang</label>
                    <x-mazer-input type="select" name="form.store_id" id="store_id">
                        <option value="">--Pilih Pusat / Cabang--</option>
                        @foreach($stores->unique('id') as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.store_id" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <x-mazer-input name="form.name" placeholder="Name" />
                    <x-mazer-input-error for="form.name" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <x-mazer-input name="form.username" placeholder="Username" />
                    <x-mazer-input-error for="form.username" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <x-mazer-input type="email" name="form.email" placeholder="Email" />
                    <x-mazer-input-error for="form.email" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <x-mazer-input type="password" name="form.password" placeholder="Password" />
                    <x-mazer-input-error for="form.password" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>
