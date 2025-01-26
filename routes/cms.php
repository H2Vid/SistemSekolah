<?php

use App\Exports\ExcelExport;
use App\Http\Controllers\Rapot;
use App\Http\Controllers\Jadwal;
use App\Http\Controllers\NilaiGuru;

use Maatwebsite\Excel\Facades\Excel;
use App\Models\Jadwal as JadwalModel;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'cms',
    'as' => 'cms.',
    'middleware' => ['auth', 'role:admin'],
], function() {
    Route::get('/rapot', Rapot::class);
    Route::get('/nilaigurupdf', NilaiGuru::class);
    Route::get('/download-jadwal', Jadwal::class);
    Route::get('/', App\Livewire\Cms\Dashboard::class)->name('dashboard');
    Route::get('', App\Livewire\Cms\Dashboard::class)->name('dashboard');
    Route::get('dashboard', App\Livewire\Cms\Dashboard::class)->name('dashboard');
    Route::get('profile', App\Livewire\Cms\Profile::class)->name('profile');
    Route::post('/profile/simpanProfile', [App\Livewire\Cms\Profile::class, 'simpanProfile'])->name('simpanProfile');

    Route::get('guru', App\Livewire\Cms\Guru::class)->name('guru');
    Route::get('siswa', App\Livewire\Cms\Siswa::class)->name('siswa');
    Route::get('kenaikan', App\Livewire\Cms\NaikSiswa::class)->name('kenaikan');
    Route::get('mata-pelajaran', App\Livewire\Cms\MataPelajaran::class)->name('mata-pelajaran');
    Route::get('predikat', App\Livewire\Cms\MataPelajaranPredikat::class)->name('predikat');
    Route::get('jadwal', App\Livewire\Cms\Jadwal::class)->name('jadwal');
    Route::get('nilai', App\Livewire\Cms\Nilai::class)->name('nilai');
    Route::get('modul', App\Livewire\Cms\Modul::class)->name('modul');
    Route::get('kelas', App\Livewire\Cms\Kelas::class)->name('kelas');
    Route::get('semester', App\Livewire\Cms\Semester::class)->name('semester');
    Route::get('naiksemester', App\Livewire\Cms\NaikSemester::class)->name('naiksemester');
    Route::get('absensi', App\Livewire\Cms\Absensi::class)->name('absensi');
    Route::get('nilai-guru', App\Livewire\Cms\NilaiGuru::class)->name('nilai-guru');
    Route::get('wali-murid', App\Livewire\Cms\WaliMurid::class)->name('wali-murid');

    // investmen
    // Route::get('investmen/category', App\Livewire\Cms\Investmen\Category::class)->name('investmen.category');
    // Route::get('investmen/product', App\Livewire\Cms\Investmen\Product::class)->name('investmen.product');

    // Settings
    Route::get('pengaturan/store', App\Livewire\Cms\Setting\Store::class)->name('pengaturan.store');
    Route::get('pengaturan/menu', App\Livewire\Cms\Setting\Menu::class)->name('pengaturan.menu');
    Route::get('pengaturan/role', App\Livewire\Cms\Setting\Role::class)->name('pengaturan.role');
    Route::get('pengaturan/user', App\Livewire\Cms\Setting\User::class)->name('pengaturan.user');
    Route::get('pengaturan/language', App\Livewire\Cms\Setting\Language::class)->name('pengaturan.language');
    Route::get('pengaturan/penarikan', App\Livewire\Cms\Setting\Withdraw::class)->name('pengaturan.penarikan');
    Route::get('pengaturan/bank', App\Livewire\Cms\Setting\Bank::class)->name('pengaturan.bank');



});


