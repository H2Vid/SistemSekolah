<div>
    <h1 class="h3 mb-3">
        {{ $title ?? '' }}
    </h1>

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
                            <th style="width: 20%!important; text-align: right;">
                                Aksi
                            </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($get as $d)
                            <tr>
                                <td>{{ $d->name }}</td>
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
                                    <button
                                        class="btn btn-primary"
                                        wire:click="detail({{ $d->id }})"
                                        @click="new bootstrap.Modal(document.getElementById('mazer-modal-access')).show()"
                                    >
                                        <i class="align-middle" data-feather="list"></i>
                                    </button>
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
                    <label class="form-label">Name</label>
                    <x-mazer-input name="form.name" placeholder="Name" />
                    <x-mazer-input-error for="form.name" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>


    <div>
        <div class="modal fade" id="mazer-modal-access" tabindex="-1" aria-labelledby="mazer-modal-access-label" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg" style="min-width:1200px!important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="mazer-modal-access-label">Role Access Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label" style="text-align: right; float: right; padding-top: 5px;">Role: </label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" placeholder="Name" value="{{ $this->val_role_name }}" readonly />
                                </div>
                            </div>
                            <hr>
                            <table class="table table-hover table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <x-mazer-loop-th-access :$searchByMenu :$orderByMenu :$orderMenu />
                                        <th style="width: 8%;">Menu</th>
                                        <th style="width: 8%;">Insert</th>
                                        <th style="width: 8%;">Read</th>
                                        <th style="width: 8%;">Update</th>
                                        <th style="width: 8%;">Delete</th>
                                        <th style="width: 8%;">Export</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($get_menu as $d)
                                        <tr>
                                            <td>{{ $d->name }}</td>
                                            @if ($d->roleApplications->isEmpty())
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_menu" wire:change="saveMenu({{ $d->id }}, $event.target.checked)" name="status_menu" id="status_menu">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_insert" wire:change="saveStatus(0, {{ $d->id }}, $event.target.checked)" name="status_insert" id="status_insert">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_read" wire:change="saveStatus(0, {{ $d->id }}, $event.target.checked)" name="status_read" id="status_read">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_update" wire:change="saveStatus(0, {{ $d->id }}, $event.target.checked)" name="status_update" id="status_update">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_delete" wire:change="saveStatus(0, {{ $d->id }}, $event.target.checked)" name="status_delete" id="status_delete">
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_export" wire:change="status_export(0, {{ $d->id }}, $event.target.checked)" name="status_export" id="status_export">
                                                    </div>
                                                </td>
                                            @else
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_menu" wire:change="updateMenu({{ $d->id }}, {{ $d->roleApplications[0]['id'] }}, $event.target.checked)" name="status_menu" id="status_menu" {{ $d->roleApplications[0]['menu_id'] > 0 ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_insert" wire:change="updateStatusInsert({{ $d->roleApplications[0]['id'] }}, $event.target.checked)" name="status_insert" id="status_insert" {{ $d->roleApplications[0]['status_insert'] > 0 ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_read" wire:change="updateStatusRead({{ $d->roleApplications[0]['id'] }}, $event.target.checked)" name="status_read" id="status_read" {{ $d->roleApplications[0]['status_read'] > 0 ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_update" wire:change="updateStatusUpdate({{ $d->roleApplications[0]['id'] }}, $event.target.checked)" name="status_update" id="status_update" {{ $d->roleApplications[0]['status_update'] > 0 ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_delete" wire:change="updateStatusDelete({{ $d->roleApplications[0]['id'] }}, $event.target.checked)" name="status_delete" id="status_delete" {{ $d->roleApplications[0]['status_delete'] > 0 ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <input type="checkbox" class="form-check-input" wire:model="status_export" wire:change="updateStatusExport({{ $d->roleApplications[0]['id'] }}, $event.target.checked)" name="status_export" id="status_export" {{ $d->roleApplications[0]['status_export'] > 0 ? 'checked' : '' }}>
                                                    </div>
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
                                {{ $get_menu->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    window.addEventListener('notification', event => {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'warning',
            title: "Harap isi menu terlebih dahulu"
        })
    })
</script>