@props(['isCreate' => true, 'isSearch' => true])

<div>
    <div class="row mb-3">
        @if($isSearch)
            <div class="col-md-{{ $isCreate ? '6' : '12' }}">
                <x-mazer-search />
            </div>
        @endif
        @if($isCreate)
            <div class="col-md-{{ $isSearch ? '6' : '12' }}">
                @if($this->akses_export == 1)
                    <div x-data>
                        <div class="float-end">
                            <button
                                class="btn btn-success"
                                wire:click="export('xlsx')"
                            >
                                <i class="align-middle" data-feather="download"></i>
                                Excel
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        {{ $slot->isEmpty() ? '' : $slot }}
    </div>
</div>
