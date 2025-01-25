<x-mazer-sidebar :href="route('cms.dashboard')" :logo="asset('assets/compiled/svg/LogoSMKN16.png')" :title="config('app.name')">
    @foreach($menus as $menu)
        @if($menu->type !== 'header')
            <x-mazer-sidebar-item
                :icon="$menu->icon"
                :link="\Illuminate\Support\Facades\Route::has($menu->route) ? route($menu->route) : '#'"
                :name="$menu->name"
                :check="$check"
            >
            @if(count($menu->children) > 0)
                @foreach($menu->children as $child)
                    <x-mazer-sidebar-subitem
                        :link="\Illuminate\Support\Facades\Route::has($child->route) ? route($child->route) : '#'"
                        :name="$child->name"
                    />
                @endforeach
            @endif
            </x-mazer-sidebar-item>
        @else
            <x-mazer-sidebar-title :name="$menu->name" />
        @endif
    @endforeach
    <hr>
    <div class="dropdown">
    <button class="btn dropdown-toggle" type="button" id="dropdownAkun" data-bs-toggle="dropdown" aria-expanded="false" style="color: #25396f;">
        <i class="align-middle" data-feather="user"></i> Akun
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownAkun">
        <li>
            <a class="dropdown-item" href="#" id="btn-profil">
                <i class="align-middle" data-feather="user"></i> {{ Auth::user()->name }}
            </a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <a class="dropdown-item" href="#" id="btn-logout">
                <i class="align-middle" data-feather="log-out"></i> Keluar Aplikasi
            </a>
        </li>
    </ul>
</div>

</x-mazer-sidebar>
