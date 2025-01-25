@props([
    'icon' => '',
    'link',
    'name',
    'check',
])

@php
    $routeName = Request::route()->getName();
    $names = explode(' ', $name);

    // Check active or not
    $active = false;

    foreach ($names as $v) {
        $active = str_contains($routeName, strtolower($v));
        if($active) break;
    }

    $classes = $active ? 'sidebar-item active' : 'sidebar-item';
@endphp

@if($name != 'Daftar Predikat' && $name != 'Pengaturan' && $name != 'Daftar Tugas')
    @if($name == 'Wali Kelas' && $check == 0)

    @else
        <li class="{{ $classes }} {{ $slot->isEmpty() ? '' : 'has-sub' }}" x-data="{ open: false }">
            <a href="{{ $slot->isEmpty() ? $link : '#'}}" class='sidebar-link' {!! $slot->isEmpty() ? 'wire:navigate' : '@click="open = !open"' !!}>
                <i class="{{ $icon }}"></i>
                <span style="margin-left: 0.5rem!important;">{{ $name }}</span>
            </a>
            @if (!$slot->isEmpty())
                <ul class="submenu {{ $active ? 'active submenu-open' : '' }}" :class="open ? 'submenu-open' : 'submenu-closed'">
                    {{ $slot ?? '' }}
                </ul>
            @endif
        </li>
    @endif
        
@endif

