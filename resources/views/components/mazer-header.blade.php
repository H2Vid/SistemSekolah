@props(['isCreate' => true, 'isSearch' => true])

<div>
    <div class="row mb-3">
        
        @if($isCreate)
            <div class="col-md-{{ $isSearch ? '7' : '12' }}">
                @if($this->akses_insert == 1)
                    <x-mazer-create-btn />
                @endif
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
        @if($isSearch)
            <div class="col-md-{{ $isCreate ? '4' : '10' }} btn-search-table">
                <x-mazer-search />
            </div>
        @endif
        
        
        {{ $slot->isEmpty() ? '' : $slot }}
    </div>
</div>
