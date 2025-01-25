<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">{{ $title ?? '' }} Data</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <x-mazer-search />
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="view_parent_only" class="form-control">
                            <option value="">Pilih Parent Only</option>
                            <option value="0">False</option>
                            <option value="1">True</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select wire:model.live="on" class="form-control">
                            <option value="">Pilih Menu On</option>
                            <option value="cms">CMS</option>
                            <option value="web">Web</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <x-mazer-create-btn />
                    </div>
                </div>
            </div>
                <table class="table table-hover table-striped" style="width:100%" id="table">
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
                                <td>{{ $d->parent_name }}</td>
                                <td>{{ $d->name }}</td>
                                <td>{{ $d->on }}</td>
                                <td>{{ $d->type }}</td>
                                <td>{{ $d->icon }}</td>
                                <td>{{ $d->route }}</td>
                                <td>{{ $d->ordering }}</td>
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
    <x-mazer-modal title="{{ $isUpdate ? 'Update' : 'Create' }} {{ $title }}">
        <x-mazer-form submit="save">
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Parent</label>
                    <x-mazer-input type="select" name="form.parent_id">
                        <option value="">--Select Parent--</option>
                        @foreach($parents as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                        @endforeach
                    </x-mazer-input>
                    <x-mazer-input-error for="form.parent_id" />
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
                    <label class="form-label">On</label>
                    <x-mazer-input type="select" name="form.on">
                        <option value="">--Select Type--</option>
                        <option value="cms">Cms</option>
                        <option value="web">Web</option>
                    </x-mazer-input>
                    <x-mazer-input-error for="form.on" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Type</label>
                    <x-mazer-input type="select" name="form.type">
                        <option value="">--Select Type--</option>
                        <option value="item">Item</option>
                        <option value="header">Header</option>
                    </x-mazer-input>
                    <x-mazer-input-error for="form.type" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Icon</label>
                    <x-mazer-input name="form.icon" placeholder="Icon" />
                    <x-mazer-input-error for="form.icon" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Route</label>
                    <x-mazer-input name="form.route" placeholder="Route" />
                    <x-mazer-input-error for="form.route" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label">Ordering</label>
                    <x-mazer-input type="number" name="form.ordering" placeholder="Ordering" />
                    <x-mazer-input-error for="form.ordering" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>
