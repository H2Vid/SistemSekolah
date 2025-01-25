@props(['for'])

<div class="invalid-feedback">
    <i class="bx bx-radio-circle"></i>
    @error($for)
        {{ $message }}
    @enderror
    @if(session()->has($for))
        {{ session($for) }}
    @endif
</div>
