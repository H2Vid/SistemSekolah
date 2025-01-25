<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">Data {{ $title ?? '' }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <x-mazer-header />
                <table class="table table-hover table-striped" style="width:100%;">
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
                                <td>{{ number_format($d->min_nominal, 0, ',', '.') }}</td>
                                <td>{{ number_format($d->max_nominal, 0, ',', '.') }}</td>
                                <td>{{ $d->percentase }}</td>
                                <td>
                                    @if($d->status == 1)
                                        Aktif
                                    @else
                                        Tidak Aktif
                                    @endif
                                </td>
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
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Minimal Nominal</label>
                    <x-mazer-input name="form.min_nominal" placeholder="Minimal Nominal" currency="true" />
                    <x-mazer-input-error for="form.min_nominal" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Maksimal Nominal</label>
                    <x-mazer-input name="form.max_nominal" placeholder="Maksimal Nominal" currency="true" />
                    <x-mazer-input-error for="form.max_nominal" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Persentase</label>
                    <x-mazer-input name="form.percentase" placeholder="Persentase"/>
                    <x-mazer-input-error for="form.percentase" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <x-mazer-input type="select" name="form.status">
                        <option value="">Pilih Status</option>
                        <option value="1">Aktif</option>
                        <option value="2">Tidak Aktif</option>
                    </x-mazer-input>
                    <x-mazer-input-error for="form.status" />
                </div>
            </div>
        </x-mazer-form>
    </x-mazer-modal>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        document.getElementsByClassName('currency')[0].addEventListener('input', function(e) {
            let cursorPosition = this.selectionStart;
            let sanitizedValue = parseInt(this.value.replace(/[^,\d]/g, ''));
            let originalLength = this.value.length;

            if (isNaN(sanitizedValue)) {
                this.value = "";
            } else {
                this.value = sanitizedValue.toLocaleString('id-ID', {
                    currency: 'IDR',
                    style: 'currency',
                    minimumFractionDigits: 0
                });
                cursorPosition = this.value.length - originalLength + cursorPosition;
                this.setSelectionRange(cursorPosition, cursorPosition);
            }
        });
        document.getElementsByClassName('currency')[1].addEventListener('input', function(e) {
            let cursorPosition = this.selectionStart;
            let sanitizedValue = parseInt(this.value.replace(/[^,\d]/g, ''));
            let originalLength = this.value.length;

            if (isNaN(sanitizedValue)) {
                this.value = "";
            } else {
                this.value = sanitizedValue.toLocaleString('id-ID', {
                    currency: 'IDR',
                    style: 'currency',
                    minimumFractionDigits: 0
                });
                cursorPosition = this.value.length - originalLength + cursorPosition;
                this.setSelectionRange(cursorPosition, cursorPosition);
            }
        });
    })
</script>