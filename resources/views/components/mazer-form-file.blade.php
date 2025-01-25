@props([
    'submit',
    'withReset' => true,
])

<div {{ $attributes->merge(['class' => 'container mt-2']) }}>
    <form wire:submit="{{ $submit }}" enctype="multipart/form-data">
        <div class="container">
            <div class="row">
                {{ $slot }}
            </div>
        </div>

        @if (isset($actions))
            <div class="row">
                {{ $actions }}
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="float-end">
                        @if($withReset)
                            <button
                                class="btn btn-warning"
                                wire:loading.attr="disabled"
                                wire:target="{{ $submit }}"
                                type="reset">
                                <i class="align-middle" data-feather="refresh-ccw"></i>
                                Reset
                            </button>
                        @endif
                        <button
                            class="btn btn-success"
                            id="form-add"
                            wire:loading.attr="disabled"
                            wire:target="{{ $submit }}"
                            type="submit">
                            <i class="align-middle" data-feather="save"></i>
                            Simpan
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </form>
</div>
