@props([
    'image' => null,
    'form_image' => null,
    'prefix_url' => '',
    'width' => null,
    'height' => null,
])

@php
    $styles = 'style="';

    if ($width) $styles .= 'width: ' . $width . ';';

    if ($height) $styles .= 'height: ' . $height . ';';

    $styles .= '"';
@endphp

<div class="mb-3">
    @if($image instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)
        <img src="{{ $image->temporaryUrl() }}" alt="logo" class="img-fluid" {!! $styles !!}>
    @elseif($form_image !== null)
        <img src="{{ asset('storage/' . $prefix_url . '/' . $form_image) }}" alt="logo" class="img-fluid" {!! $styles !!}>
    @endif
</div>
