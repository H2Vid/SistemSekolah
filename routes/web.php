<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/generate', function(){
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
 });
 
// Route::get('/', App\Livewire\Cms\Dashboard::class)->name('dashboard');
Route::get('/', App\Livewire\Web\Home::class)->name('home');
Route::get('/home', App\Livewire\Web\Home::class)->name('home');
Route::get('/product', App\Livewire\Web\Product::class)->name('product');
Route::get('/product/detail', App\Livewire\Web\ProductDetail::class)->name('product-detail');
Route::get('/contact', App\Livewire\Web\Contact::class)->name('contact');
Route::get('/login', App\Livewire\Web\Login::class)->name('login');

Route::get('/register', App\Livewire\Web\Register::class)->name('register');
Route::get('/register/{string}', App\Livewire\Web\Register::class)->name('register');

Route::post('/login/doLogin', [App\Livewire\Web\Login::class, 'doLogin'])->name('doLogin');
Route::post('/register/store', [App\Livewire\Web\Register::class, 'store'])->name('store');

Route::get('/task', App\Livewire\Web\Task::class)->name('task');
Route::get('/vip', App\Livewire\Web\Vip::class)->name('vip');
Route::get('/dashboard', App\Livewire\Web\Dashboard::class)->name('dashboard');
Route::get('/language/{num}', App\Livewire\Web\Language::class)->name('language');

require_once __DIR__ . '/cms.php';
require_once __DIR__ . '/auth.php';
