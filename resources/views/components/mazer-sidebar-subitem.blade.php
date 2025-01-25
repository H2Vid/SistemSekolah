@props(['link', 'name'])

<li class="submenu-item">
    <a href="{{ $link }}" wire:navigate>{{ $name }}</a>
</li>
