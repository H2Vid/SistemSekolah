<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\Kelas;
use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Session;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // ...
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Facades\View::composer(['layouts.partials.sidebar'], function(View $view) {
            $menus = [];
            if(Auth::check()) {
                $menus = Menu::join('role_applications', 'role_applications.menu_id', '=', 'menus.id')
                            ->with('children')
                            ->orderBy('menus.ordering')
                            ->whereNull('menus.parent_id')
                            ->where('menus.on', 'cms')
                            ->where('role_applications.role_id', session()->get('role_id'))
                            ->select('menus.*')
                            ->get();
                $check = Kelas::where('nip', session()->get('store_username'))->count();
            }
            // dd($menus[5]->children);
            $view->with(['menus' => $menus, 'check' => $check]);
            // $view->with('menus', $menus);
            // $view->with('check', $check);
        });

        Facades\View::composer(['layouts.web.main-menu'], function(View $view) {
            $menus = Menu::with('children')->orderBy('ordering')->whereNull('parent_id')->where('on', 'web')->get();

            $view->with('menus', $menus);
        });
    }
}
