<div x-data>
    <div class="btn-fitur-group" style="">
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
