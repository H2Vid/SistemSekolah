@props([
    'type' => 'text',
    'class' => 'form-control',
    'currency' => '',
    'aditionalClasses' => '',
    'placeholder' => 'Username',
    'name' => 'username',
    'id' => '',
    'value' => '',
    'livewire' => true,
    'icon' => '',
    'rows' => '',
    'cols' => '',
    'accept' => '',
])
@if($type === 'file')
<input
    type="file"
    class="{{ $class }} @error($name) is-invalid @enderror {{ $aditionalClasses }}"
    placeholder="{{ $placeholder }}"
    accept="application/pdf"
    @if($livewire)
        wire:model="{{ $name }}"
    @else
        name="{{ $name }}"
    @endif
    value="{{ $value }}"
    @if($id)
        id="{{ $id }}"
    @endif
/>
@elseif($type === 'textarea')
    <textarea
        class="{{ $class }} @error($name) is-invalid @enderror {{ $aditionalClasses }}"
        placeholder="{{ $placeholder }}"
        @if($livewire)
            wire:model="{{ $name }}"
        @else
            name="{{ $name }}"
        @endif
        value="{{ $value }}"
        @if($id)
            id="{{ $id }}"
        @endif
        @if($rows)
            rows="{{ $rows }}"
        @endif
        @if($cols)
            cols="{{ $cols }}"
        @endif
    ></textarea>
@elseif($type === 'select-normal')
    <select
        class="{{ $class }} @error($name) is-invalid @enderror {{ $aditionalClasses }}"
        @if($livewire)
            wire:model="{{ $name }}"
        @else
            name="{{ $name }}"
        @endif
        @if($id)
            id="{{ $id }}"
        @endif
    >
        {{ $slot }}
    </select>
@elseif($type === 'select')
    <select
        class="{{ $class }} @error($name) is-invalid @enderror {{ $aditionalClasses }} select2"
        @if($livewire)
            wire:model="{{ $name }}"
        @else
            name="{{ $name }}"
        @endif
        @if($id)
            id="{{ $id }}"
        @endif
    >
        {{ $slot }}
    </select>
@elseif($type === 'hidden')
<input
        type="text"
        @if($currency)
            class="{{ $class }} currency @error($name) is-invalid @enderror {{ $aditionalClasses }} hide-input"
        @else
            class="{{ $class }} @error($name) is-invalid @enderror {{ $aditionalClasses }} hide-input"
        @endif
        placeholder="{{ $placeholder }}"
        @if($livewire)
            wire:model="{{ $name }}"
        @else
            name="{{ $name }}"
        @endif
        value="{{ $value }}"
        @if($id)
            id="{{ $id }}"
        @endif
    />
@else
    <input
        type="{{ $type }}"
        @if($currency)
            class="{{ $class }} currency @error($name) is-invalid @enderror {{ $aditionalClasses }}"
        @else
            class="{{ $class }} @error($name) is-invalid @enderror {{ $aditionalClasses }}"
        @endif
        placeholder="{{ $placeholder }}"
        @if($livewire)
            wire:model="{{ $name }}"
        @else
            name="{{ $name }}"
        @endif
        value="{{ $value }}"
        @if($id)
            id="{{ $id }}"
        @endif
    />
@endif


@if($icon)
    <div class="form-control-icon">
        <i class="{{ $icon }}"></i>
    </div>
@endif
